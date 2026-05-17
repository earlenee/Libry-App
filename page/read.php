<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$book = null;
$user_book_data = null;
$book_content = "";

if (file_exists('../config/koneksi.php')) {
    require_once '../config/koneksi.php';
    if (isset($conn) && !$conn->connect_error) {
        $stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $book_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result && $result->num_rows > 0) {
                $book = $result->fetch_assoc();
                $book_content = $book['content'] ?? "Content coming soon...";
            }
        }
        
        $stmt_ubd = $conn->prepare("SELECT * FROM user_book_data WHERE user_id = ? AND book_id = ?");
        if ($stmt_ubd) {
            $stmt_ubd->bind_param("ii", $user_id, $book_id);
            $stmt_ubd->execute();
            $res_ubd = $stmt_ubd->get_result();
            if ($res_ubd && $res_ubd->num_rows > 0) {
                $user_book_data = $res_ubd->fetch_assoc();
                // kalo ada html yg udah di-highlight, pake itu aja
                if (!empty($user_book_data['highlighted_html'])) {
                    $book_content = $user_book_data['highlighted_html'];
                }
            }
        }
    }
}

// ambil tema & ukuran font dari session
$theme = isset($_SESSION['theme']) ? $_SESSION['theme'] : 'light';
$font_size = isset($_SESSION['font_size']) ? intval($_SESSION['font_size']) : 20;
$saved_progress = isset($user_book_data['progress_percent']) ? intval($user_book_data['progress_percent']) : 0;


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $book ? htmlspecialchars($book['title']) : 'Reading Book'; ?> - LIBRY</title>
  <link rel="stylesheet" href="../styles.css">
  <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
  <style>
    :root {
      --reader-bg: #f4f1ea;
      --reader-text: #333333;
      --reader-bar: rgba(244, 241, 234, 0.95);
      --reader-title: var(--dark);
    }
    
    body { 
      background: var(--reader-bg); 
      color: var(--reader-text);
      margin: 0;
      padding: 0;
      transition: background 0.3s, color 0.3s;
    }
    
    body.theme-sepia {
      --reader-bg: #f4ece1;
      --reader-text: #5b4636;
      --reader-bar: rgba(244, 236, 225, 0.95);
      --reader-title: #3e2723;
    }

    body.theme-dark {
      --reader-bg: #121212;
      --reader-text: #e0e0e0;
      --reader-bar: rgba(18, 18, 18, 0.95);
      --reader-title: #ffffff;
    }
    
    /* Hide default sidebar/navbar for distraction-free reading */
    .page.sidebar-layout { display: none !important; }
    
    /* E-Reader Top Bar */
    .reader-topbar {
      position: fixed;
      top: 0; left: 0; right: 0;
      height: 64px;
      background: var(--reader-bar);
      backdrop-filter: blur(10px);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 24px;
      border-bottom: 1px solid rgba(128,128,128,0.1);
      z-index: 1000;
      transition: transform 0.3s, background 0.3s;
    }
    
    .reader-topbar.hidden {
      transform: translateY(-100%);
    }
    
    .reader-back {
      display: flex;
      align-items: center;
      gap: 8px;
      color: var(--reader-title);
      text-decoration: none;
      font-family: 'Inter', sans-serif;
      font-weight: 600;
      font-size: 14px;
      cursor: pointer;
      background: none;
      border: none;
      padding: 8px 12px;
      border-radius: 8px;
      transition: background 0.2s;
    }
    
    .reader-back:hover {
      background: rgba(128,128,128,0.1);
    }
    
    .reader-title-bar {
      font-family: 'Hammersmith One', sans-serif;
      font-size: 16px;
      color: var(--reader-title);
      text-align: center;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 50%;
    }
    
    .reader-actions {
      display: flex;
      gap: 12px;
    }
    
    .btn-reader-action {
      background: none;
      border: none;
      color: var(--reader-title);
      cursor: pointer;
      padding: 8px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.2s;
    }
    
    .btn-reader-action:hover {
      background: rgba(128,128,128,0.1);
    }
    
    /* E-Reader Content */
    .reader-container {
      max-width: 720px;
      margin: 100px auto 60px;
      padding: 0 24px;
    }
    
    .reader-header {
      text-align: center;
      margin-bottom: 60px;
    }
    
    .reader-cover {
      width: 160px;
      border-radius: 8px;
      box-shadow: 0 12px 24px rgba(0,0,0,0.15);
      margin-bottom: 30px;
    }
    
    .reader-book-title {
      font-family: 'Playfair Display', serif;
      font-size: 42px;
      font-weight: 800;
      color: var(--reader-title);
      line-height: 1.2;
      margin-bottom: 12px;
    }
    
    .reader-author {
      font-family: 'Inter', sans-serif;
      font-size: 16px;
      color: gray;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    
    .reader-body {
      font-family: 'Lora', serif;
      font-size: 20px;
      line-height: 1.8;
      color: var(--reader-text);
      transition: font-size 0.2s;
    }
    
    .reader-body h2 {
      font-family: 'Playfair Display', serif;
      font-size: 1.4em;
      font-weight: 700;
      margin-top: 60px;
      margin-bottom: 24px;
      text-align: center;
      color: var(--reader-title);
    }
    
    .reader-body p {
      margin-bottom: 24px;
      text-indent: 32px;
      text-align: justify;
    }
    
    /* Progress Bar */
    .reading-progress-container {
      position: fixed;
      bottom: 0; left: 0; right: 0;
      height: 12px;
      background: rgba(128,128,128,0.2);
      z-index: 1000;
    }
    
    .reading-progress-bar {
      height: 100%;
      background: var(--rust);
      width: 0%;
      transition: width 0.1s;
    }
    
    @media (max-width: 768px) {
      .reader-book-title { font-size: 32px; }
      .reader-body { font-size: 18px; line-height: 1.7; }
      .reader-title-bar { display: none; }
    }
  </style>
</head>
<body>

  <!-- Top Bar -->
  <div class="reader-topbar" id="readerTopbar">
    <button class="reader-back" onclick="window.location.href='dashboard.php'">
      <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
      Back
    </button>
    <div class="reader-title-bar">
      <?php echo $book ? htmlspecialchars($book['title']) : 'Unknown Book'; ?>
    </div>
    <div class="reader-actions">
      <div style="position: relative;">
        <button class="btn-reader-action" title="Settings" onclick="toggleReaderSettings(event)">
          <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"></path></svg>
        </button>
        <!-- Settings Dropdown -->
        <div id="reader-settings-dropdown" style="display: none; position: absolute; top: 100%; right: 0; background: white; padding: 16px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); width: 200px; margin-top: 8px;">
          <div style="font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 600; color: gray; margin-bottom: 8px;">Font Size</div>
          <div style="display: flex; gap: 8px; margin-bottom: 16px;">
            <button onclick="changeFontSize(-2)" style="flex: 1; padding: 8px; background: rgba(0,0,0,0.05); border: none; border-radius: 8px; cursor: pointer; font-size: 16px; color: black;">A-</button>
            <button onclick="changeFontSize(2)" style="flex: 1; padding: 8px; background: rgba(0,0,0,0.05); border: none; border-radius: 8px; cursor: pointer; font-size: 18px; font-weight: bold; color: black;">A+</button>
          </div>
          <div style="font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 600; color: gray; margin-bottom: 8px;">Theme</div>
          <div style="display: flex; gap: 8px;">
            <div onclick="setTheme('light')" style="flex: 1; height: 32px; background: #ffffff; border: 1px solid #ddd; border-radius: 8px; cursor: pointer;" title="Light"></div>
            <div onclick="setTheme('sepia')" style="flex: 1; height: 32px; background: #f4ece1; border: 1px solid #ddd; border-radius: 8px; cursor: pointer;" title="Sepia"></div>
            <div onclick="setTheme('dark')" style="flex: 1; height: 32px; background: #1a1a1a; border: 1px solid #444; border-radius: 8px; cursor: pointer;" title="Dark"></div>
          </div>
        </div>
      </div>
      <div style="position: relative;">
        <button class="btn-reader-action" title="Bookmark" onclick="addBookmark(event)" onmousedown="event.preventDefault()">
          <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
        </button>
        <!-- Bookmark Colors Dropdown -->
        <div id="bookmark-colors-dropdown" style="display: none; position: absolute; top: 100%; right: 0; background: white; padding: 12px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); width: 180px; margin-top: 8px; z-index: 1000;">
          <div style="font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 600; color: gray; margin-bottom: 8px;">Select Highlight Color</div>
          <div style="display: flex; gap: 8px; justify-content: space-around; margin-bottom: 12px;">
             <div onclick="applyBookmark('#fff033')" onmousedown="event.preventDefault()" style="width: 24px; height: 24px; background: #fff033; border-radius: 50%; cursor: pointer; border: 1px solid #ddd;" title="Yellow"></div>
             <div onclick="applyBookmark('#7df37d')" onmousedown="event.preventDefault()" style="width: 24px; height: 24px; background: #7df37d; border-radius: 50%; cursor: pointer; border: 1px solid #ddd;" title="Green"></div>
             <div onclick="applyBookmark('#7dcbf3')" onmousedown="event.preventDefault()" style="width: 24px; height: 24px; background: #7dcbf3; border-radius: 50%; cursor: pointer; border: 1px solid #ddd;" title="Blue"></div>
             <div onclick="applyBookmark('#ff96ca')" onmousedown="event.preventDefault()" style="width: 24px; height: 24px; background: #ff96ca; border-radius: 50%; cursor: pointer; border: 1px solid #ddd;" title="Pink"></div>
          </div>
          <button onclick="applyBookmark('remove')" onmousedown="event.preventDefault()" style="width: 100%; padding: 6px 0; border: 1px solid #ff4d4d; background: #fff0f0; color: #ff4d4d; border-radius: 6px; cursor: pointer; font-family: 'Inter', sans-serif; font-size: 12px; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 4px;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            Remove Bookmark
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="reader-container">
    <?php if ($book): ?>
      <div class="reader-header">
        <img src="../asset/<?php echo htmlspecialchars($book['cover_image'] ?? ''); ?>" alt="Cover" class="reader-cover" onerror="this.src='../asset/logo.png'">
        <h1 class="reader-book-title"><?php echo htmlspecialchars($book['title']); ?></h1>
        <div class="reader-author">By <?php echo htmlspecialchars($book['author'] ?? 'Unknown Author'); ?></div>
      </div>
      
      <div class="reader-body" id="readerBody">
        <?php echo !empty($book_content) ? $book_content : '<p style="text-align:center; color:#999; margin-top:40px;">No content available for this book.</p>'; ?>
      </div>
      
      <!-- Done Reading Indicator -->
      <div id="doneReadingIndicator" style="display: none; margin-top: 80px; text-align: center; padding: 40px; background: rgba(43, 138, 62, 0.05); border: 1px solid rgba(43, 138, 62, 0.2); border-radius: 16px;">
        <div style="width: 64px; height: 64px; background: #2b8a3e; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
          <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"></path></svg>
        </div>
        <h3 style="font-family: 'Hammersmith One', sans-serif; font-size: 24px; color: #2b8a3e; margin-bottom: 8px;">Done Reading</h3>
        <p style="font-family: 'Inter', sans-serif; color: var(--text-muted); margin-bottom: 24px;">You have successfully finished reading this book. Great job!</p>
        <button class="btn-reader-action" style="margin: 0 auto; background: var(--navy); color: white; padding: 12px 32px; font-weight: 600;" onclick="window.location.href='dashboard.php'">Return to Dashboard</button>
      </div>

    <?php else: ?>
      <div class="reader-header">
        <h1 class="reader-book-title">Book Not Found</h1>
        <p style="font-family: 'Inter', sans-serif; color: var(--text-muted);">The book you are trying to read does not exist or has been removed.</p>
        <button class="reader-back" style="margin: 20px auto; background: var(--navy); color: white;" onclick="window.location.href='dashboard.php'">Go to Dashboard</button>
      </div>
    <?php endif; ?>
  </div>
  
  <div class="reading-progress-container">
    <div class="reading-progress-bar" id="progressBar"></div>
  </div>

  <!-- Floating Back Button -->
  <button onclick="window.location.href='dashboard.php'" style="position: fixed; bottom: 32px; left: 32px; width: 56px; height: 56px; border-radius: 50%; background: var(--navy); color: white; border: none; box-shadow: 0 8px 24px rgba(30, 54, 85, 0.3); display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 1000; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'" title="Back to Dashboard">
    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg>
  </button>

  <script>
    const bookId = <?php echo $book_id; ?>;
    
    let scrollTimeout;
    let currentProgress = <?php echo $saved_progress; ?>;
    
    function calculateProgress() {
      const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
      if (height <= 0) {
        // konten muat di layar tanpa scroll = udah 100% dibaca
        return 100;
      }
      const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
      return Math.min(Math.round((winScroll / height) * 100), 100);
    }
    
    function saveProgress(progress, force) {
      if (bookId > 0 && (force || progress > currentProgress)) {
        currentProgress = Math.max(currentProgress, progress);
        fetch('../actions/sync_book_data.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            book_id: bookId,
            progress: progress
          })
        });
      }
    }
    
    // update progress bar pas scroll & simpan ke db
    window.addEventListener('scroll', () => {
      const scrolled = calculateProgress();
      document.getElementById('progressBar').style.width = scrolled + '%';
      
      if (bookId > 0 && scrolled > 0) {
        // pake debounce biar ga spam request ke server
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => saveProgress(scrolled), 1000);
      }
      
      // cek udah selesai baca belum
      const doneIndicator = document.getElementById('doneReadingIndicator');
      if (doneIndicator) {
        if (scrolled >= 99) {
          doneIndicator.style.display = 'block';
        }
      }
    });
    
    // simpen progress pas user mau ninggalin halaman
    window.addEventListener('beforeunload', () => {
      const scrolled = calculateProgress();
      if (bookId > 0 && scrolled > currentProgress) {
        // pake sendBeacon biar datanya tetep kekirim walaupun halaman ditutup
        const data = JSON.stringify({ book_id: bookId, progress: scrolled });
        const blob = new Blob([data], { type: 'application/json' });
        navigator.sendBeacon('../actions/sync_book_data.php', blob);
      }
    });
    
    // langsung simpen progress pas halaman dibuka
    document.addEventListener('DOMContentLoaded', () => {
      const scrolled = calculateProgress();
      document.getElementById('progressBar').style.width = scrolled + '%';
      
      // biar continue reading langsung kebaca di dashboard
      if (bookId > 0) {
        saveProgress(Math.max(scrolled, 1), true);
      }
      
      if (scrolled >= 100) {
        const doneIndicator = document.getElementById('doneReadingIndicator');
        if (doneIndicator) doneIndicator.style.display = 'block';
      }
    });

    // auto sembunyiin topbar pas scroll kebawah, muncul lagi pas scroll keatas
    let lastScrollTop = 0;
    const topbar = document.getElementById('readerTopbar');
    
    window.addEventListener('scroll', () => {
      let st = window.pageYOffset || document.documentElement.scrollTop;
      if (st > lastScrollTop && st > 100) {
        topbar.classList.add('hidden');
      } else {
        topbar.classList.remove('hidden');
      }
      lastScrollTop = st <= 0 ? 0 : st;
    }, false);

    // toggle panel pengaturan
    function toggleReaderSettings(e) {
      e.stopPropagation();
      document.getElementById('bookmark-colors-dropdown').style.display = 'none';
      const dropdown = document.getElementById('reader-settings-dropdown');
      dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
    }

    function addBookmark(e) {
      e.stopPropagation();
      const selection = window.getSelection();
      const text = selection.toString().trim();
      
      let existingHighlight = false;
      if (selection.rangeCount > 0) {
          let node = selection.getRangeAt(0).commonAncestorContainer;
          while (node && node.id !== 'readerBody') {
              if (node.nodeType === 1 && node.classList.contains('highlighted-bookmark')) {
                  existingHighlight = true;
                  break;
              }
              node = node.parentNode;
          }
      }
      
      if (!text && !existingHighlight) {
        showToast("Notice: No text selected. Please select text or click on an existing bookmark!");
        return;
      }
      
      // tampilin dropdown pilihan warna
      document.getElementById('reader-settings-dropdown').style.display = 'none';
      const colorDropdown = document.getElementById('bookmark-colors-dropdown');
      colorDropdown.style.display = colorDropdown.style.display === 'none' ? 'block' : 'none';
    }

    function applyBookmark(color) {
      const selection = window.getSelection();
      if (!selection.rangeCount) return;
      
      const range = selection.getRangeAt(0);
      
      // cek ada highlight yang dipilih ga
      let existingHighlight = null;
      let node = range.commonAncestorContainer;
      while (node && node.id !== 'readerBody') {
          if (node.nodeType === 1 && node.classList.contains('highlighted-bookmark')) {
              existingHighlight = node;
              break;
          }
          node = node.parentNode;
      }
      
      if (!existingHighlight) {
          // cek kalo selection nutupin highlight yang udah ada
          const frag = range.cloneContents();
          if (frag.querySelectorAll('.highlighted-bookmark').length > 0) {
              showToast("Notice: Please remove existing bookmarks within your selection first.");
              document.getElementById('bookmark-colors-dropdown').style.display = 'none';
              return;
          }
      }

      if (existingHighlight) {
         if (color === 'remove') {
             const parent = existingHighlight.parentNode;
             while (existingHighlight.firstChild) {
                 parent.insertBefore(existingHighlight.firstChild, existingHighlight);
             }
             parent.removeChild(existingHighlight);
             parent.normalize(); 
             showToast("Bookmark removed successfully.");
         } else {
             existingHighlight.style.backgroundColor = color;
             showToast("Bookmark color changed successfully.");
         }
      } else {
         if (color === 'remove') {
             showToast("No bookmark selected to remove.");
             document.getElementById('bookmark-colors-dropdown').style.display = 'none';
             return;
         }
         
         const span = document.createElement('span');
         span.style.backgroundColor = color;
         span.style.color = '#000';
         span.className = 'highlighted-bookmark';
         
         try {
           range.surroundContents(span);
           selection.removeAllRanges();
           showToast("Success! Text has been highlighted.");
         } catch (e) {
           showToast("Notice: Please select text within a single paragraph without overlapping other elements.");
           document.getElementById('bookmark-colors-dropdown').style.display = 'none';
           return;
         }
      }
      
      updateProgressMarkers();
      
      // simpen perubahan highlight ke database
      const htmlContent = document.getElementById('readerBody').innerHTML;
      fetch('../actions/sync_book_data.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          book_id: bookId,
          highlighted_html: htmlContent
        })
      });
      
      document.getElementById('bookmark-colors-dropdown').style.display = 'none';
    }

    function updateProgressMarkers() {
      const container = document.querySelector('.reading-progress-container');
      if (!container) return;
      
      // hapus marker lama dulu
      container.querySelectorAll('.progress-marker').forEach(el => el.remove());
      
      const totalScrollHeight = document.documentElement.scrollHeight;
      const screenWidth = window.innerWidth;
      const marks = document.querySelectorAll('.highlighted-bookmark');
      
      const usedPixels = [];
      const markerWidth = 40;
      
      marks.forEach(mark => {
        const rect = mark.getBoundingClientRect();
        const absoluteY = window.pageYOffset + rect.top;
        
        // hitung posisi marker di progress bar
        let targetPx = (absoluteY / totalScrollHeight) * screenWidth;
        
        // biar marker yg berdekatan nyambung jadi satu
        usedPixels.sort((a,b) => a - b);
        for (let p of usedPixels) {
          if (targetPx > p - markerWidth && targetPx < p + markerWidth) {
             targetPx = p + markerWidth - 0.5;
          }
        }
        usedPixels.push(targetPx);
        
        const marker = document.createElement('div');
        marker.className = 'progress-marker';
        marker.style.position = 'absolute';
        marker.style.left = targetPx + 'px';
        marker.style.top = '0';
        marker.style.width = markerWidth + 'px';
        marker.style.height = '100%';
        marker.style.backgroundColor = mark.style.backgroundColor || '#ffd255';
        marker.style.zIndex = '1001';
        marker.style.cursor = 'pointer';
        marker.title = 'Highlighted Text';
        marker.style.borderRadius = '0px';
        // biar lebih keliatan terang
        marker.style.boxShadow = '0 0 12px ' + (mark.style.backgroundColor || '#ffd255');
        marker.style.opacity = '1'; 
        marker.style.transition = 'all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
        marker.style.transformOrigin = 'bottom';
        
        marker.onmouseover = () => {
           marker.style.transform = 'scaleY(2.5)';
        };
        marker.onmouseout = () => {
           marker.style.transform = 'scaleY(1)';
        };
        
        // klik marker = scroll ke posisi bookmark
        marker.addEventListener('click', (e) => {
          e.stopPropagation();
          window.scrollTo({
            top: absoluteY - 100,
            behavior: 'smooth'
          });
        });
        
        container.appendChild(marker);
      });
    }

    window.addEventListener('resize', () => {
      clearTimeout(window.resizeTimeout);
      window.resizeTimeout = setTimeout(updateProgressMarkers, 200);
    });

    function createToastContainer() {
      const div = document.createElement('div');
      div.id = 'toast-notification';
      div.style.position = 'fixed';
      div.style.bottom = '100px';
      div.style.left = '50%';
      div.style.transform = 'translateX(-50%)';
      div.style.background = 'var(--navy)';
      div.style.color = '#fff';
      div.style.padding = '12px 24px';
      div.style.borderRadius = '30px';
      div.style.fontFamily = "'Inter', sans-serif";
      div.style.fontSize = '14px';
      div.style.boxShadow = '0 10px 30px rgba(0,0,0,0.2)';
      div.style.zIndex = '9999';
      div.style.opacity = '0';
      div.style.pointerEvents = 'none';
      div.style.transition = 'opacity 0.3s, transform 0.3s';
      document.body.appendChild(div);
      return div;
    }

    let toastTimeout;
    function showToast(message) {
      const toast = document.getElementById('toast-notification') || createToastContainer();
      toast.innerText = message;
      toast.style.opacity = '1';
      toast.style.transform = 'translate(-50%, -10px)';
      clearTimeout(toastTimeout);
      toastTimeout = setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(-50%)';
      }, 3000);
    }

    window.addEventListener('click', () => {
      const dropdown = document.getElementById('reader-settings-dropdown');
      if (dropdown) dropdown.style.display = 'none';
      const colorDropdown = document.getElementById('bookmark-colors-dropdown');
      if (colorDropdown) colorDropdown.style.display = 'none';
    });

    // logika ukuran font
    let currentFontSize = <?php echo $font_size; ?>;
    document.getElementById('readerBody').style.fontSize = currentFontSize + 'px';
    
    function changeFontSize(delta) {
      currentFontSize += delta;
      if(currentFontSize < 14) currentFontSize = 14;
      if(currentFontSize > 32) currentFontSize = 32;
      document.getElementById('readerBody').style.fontSize = currentFontSize + 'px';
      
      fetch('../actions/sync_settings.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ font_size: currentFontSize })
      });
    }

    // logika ganti tema
    const currentTheme = '<?php echo $theme; ?>';
    if(currentTheme !== 'light') document.body.classList.add('theme-' + currentTheme);
    
    function setTheme(theme) {
      document.body.className = '';
      if(theme !== 'light') document.body.classList.add('theme-' + theme);
      
      fetch('../actions/sync_settings.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ theme: theme })
      });
    }

    // pas load halaman, restore posisi baca & marker
    document.addEventListener('DOMContentLoaded', () => {
      // gambar marker bookmark yang udah ada
      setTimeout(updateProgressMarkers, 500);

      const savedProgress = <?php echo $saved_progress; ?>;
      if (bookId > 0 && savedProgress > 0) {
        // scroll ke posisi terakhir baca
        setTimeout(() => {
          const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
          window.scrollTo({
            top: (savedProgress / 100) * height,
            behavior: 'smooth'
          });
        }, 300);
      }
    });
  </script>
</body>
</html>
