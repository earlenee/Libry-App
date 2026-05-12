<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$book = null;

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
            }
        }
    }
}

// Simulated book content (Lorem Ipsum formatted as chapters)
$dummy_content = "
<h2>Chapter 1: The Beginning</h2>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
<p>Curabitur pretium tincidunt lacus. Nulla gravida orci a odio. Nullam varius, turpis et commodo pharetra, est eros bibendum elit, nec luctus magna felis sollicitudin mauris. Integer in mauris eu nibh euismod gravida. Duis ac tellus et risus vulputate vehicula. Donec lobortis risus a elit. Etiam tempor. Ut ullamcorper, ligula eu tempor congue, eros est euismod turpis, id tincidunt sapien risus a quam. Maecenas fermentum consequat mi. Donec fermentum. Pellentesque malesuada nulla a mi.</p>

<h2>Chapter 2: The Journey</h2>
<p>Duis sapien sem, aliquet nec, commodo eget, consequat quis, neque. Aliquam faucibus, elit ut dictum aliquet, felis nisl adipiscing sapien, sed malesuada diam lacus eget erat. Cras mollis scelerisque nunc. Nullam arcu. Aliquam consequat. Curabitur augue lorem, dapibus quis, laoreet et, pretium ac, nisi. Aenean magna nisl, mollis quis, molestie eu, feugiat in, orci. In hac habitasse platea dictumst.</p>
<p>Fusce convallis, mauris imperdiet gravida bibendum, nisl turpis suscipit mauris, sed placerat ipsum urna sed risus. In convallis tellus a mauris. Curabitur non elit ut libero tristique sodales. Mauris a lacus. Donec mattis semper leo. In hac habitasse platea dictumst. Vivamus facilisis diam vel magna. Mauris tincidunt sem sed arcu. Nunc posuere.</p>

<h2>Chapter 3: The Resolution</h2>
<p>Pellentesque ipsum. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nunc tristique, ante at tristique vulputate, odio turpis iaculis lorem, a volutpat lacus magna nec urna. Aenean iaculis, tellus at iaculis congue, quam mauris bibendum mauris, condimentum porta dolor neque sed sapien. Fusce et metus. Fusce neque sem, iaculis varius, egestas interdum, vestibulum at, tellus.</p>
<p>Morbi mattis. Vestibulum condimentum velit aliquet odio. Ut magna metus, egestas at, egestas eu, fermentum eu, justo. Donec nec velit eget risus facilisis feugiat. Nunc pretium urna id ipsum. Vivamus et elit. Proin dictum est et ante. Aenean sed sapien quis nulla semper feugiat. Integer ut mauris. Donec interdum hendrerit arcu. Phasellus semper pulvinar odio. Nam dictum pellentesque eros. Sed varius mauris ac enim. Sed viverra.</p>
";

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
      height: 4px;
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
      <div style="position: relative;">
        <button class="btn-reader-action" title="Bookmark" onclick="addBookmark(event)">
          <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
        </button>
        <!-- Bookmark Colors Dropdown -->
        <div id="bookmark-colors-dropdown" style="display: none; position: absolute; top: 100%; right: 0; background: white; padding: 12px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); width: 150px; margin-top: 8px; z-index: 1000;">
          <div style="font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 600; color: gray; margin-bottom: 8px;">Pilih Warna Stabilo</div>
          <div style="display: flex; gap: 8px; justify-content: space-around;">
             <div onclick="applyBookmark('#fff033')" style="width: 24px; height: 24px; background: #fff033; border-radius: 50%; cursor: pointer; border: 1px solid #ddd;" title="Kuning"></div>
             <div onclick="applyBookmark('#7df37d')" style="width: 24px; height: 24px; background: #7df37d; border-radius: 50%; cursor: pointer; border: 1px solid #ddd;" title="Hijau"></div>
             <div onclick="applyBookmark('#7dcbf3')" style="width: 24px; height: 24px; background: #7dcbf3; border-radius: 50%; cursor: pointer; border: 1px solid #ddd;" title="Biru"></div>
             <div onclick="applyBookmark('#ff96ca')" style="width: 24px; height: 24px; background: #ff96ca; border-radius: 50%; cursor: pointer; border: 1px solid #ddd;" title="Pink"></div>
          </div>
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
        <?php echo $dummy_content; ?>
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
    
    // Update reading progress bar and save
    window.addEventListener('scroll', () => {
      const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
      const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
      const scrolled = height > 0 ? (winScroll / height) * 100 : 0;
      document.getElementById('progressBar').style.width = scrolled + '%';
      
      if (bookId > 0 && scrolled > 0) {
        localStorage.setItem('libry_progress_' + bookId, Math.round(scrolled));
      }
      
      // Done reading logic
      const doneIndicator = document.getElementById('doneReadingIndicator');
      if (doneIndicator) {
        if (scrolled >= 99) {
          doneIndicator.style.display = 'block';
        }
      }
    });

    // Auto-hide topbar on scroll down, show on scroll up
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

    // Settings Toggle
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
      
      const notifArea = document.getElementById('toast-notification') || createToastContainer();
      
      if (!text) {
        showToast("Pemberitahuan: Tidak ada text yang diblock. Silakan block text terlebih dahulu untuk membuat bookmark!");
        return;
      }
      
      // Jika ada teks diblock, tampilkan pemilih warna
      document.getElementById('reader-settings-dropdown').style.display = 'none';
      const colorDropdown = document.getElementById('bookmark-colors-dropdown');
      colorDropdown.style.display = colorDropdown.style.display === 'none' ? 'block' : 'none';
    }

    function applyBookmark(color) {
      const selection = window.getSelection();
      if (!selection.rangeCount) return;
      const range = selection.getRangeAt(0);
      const span = document.createElement('span');
      span.style.backgroundColor = color;
      span.style.color = '#000';
      span.className = 'highlighted-bookmark';
      
      try {
        range.surroundContents(span);
        selection.removeAllRanges();
        showToast("Berhasil! Teks telah dibookmark dengan stabilo.");
      } catch (e) {
        showToast("Pemberitahuan: Harap pilih rentang text dalam satu paragraf saja.");
      }
      document.getElementById('bookmark-colors-dropdown').style.display = 'none';
    }

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

    // Font Size Logic
    let currentFontSize = 20;
    function changeFontSize(delta) {
      currentFontSize += delta;
      if(currentFontSize < 14) currentFontSize = 14;
      if(currentFontSize > 32) currentFontSize = 32;
      document.getElementById('readerBody').style.fontSize = currentFontSize + 'px';
      localStorage.setItem('libry_fontsize', currentFontSize);
    }

    // Theme Logic
    function setTheme(theme) {
      document.body.className = '';
      if(theme !== 'light') document.body.classList.add('theme-' + theme);
      localStorage.setItem('libry_theme', theme);
    }

    // On Load Restore Settings and Progress
    document.addEventListener('DOMContentLoaded', () => {
      // Restore Font Size
      const savedFont = localStorage.getItem('libry_fontsize');
      if(savedFont) {
        currentFontSize = parseInt(savedFont);
        document.getElementById('readerBody').style.fontSize = currentFontSize + 'px';
      }
      // Restore Theme
      const savedTheme = localStorage.getItem('libry_theme');
      if(savedTheme) setTheme(savedTheme);

      // Restore Progress
      if (bookId > 0) {
        const savedProgress = localStorage.getItem('libry_progress_' + bookId);
        if (savedProgress && savedProgress > 0) {
          // Delay scrolling slightly to ensure DOM is fully rendered
          setTimeout(() => {
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            window.scrollTo({
              top: (savedProgress / 100) * height,
              behavior: 'smooth'
            });
          }, 300);
        }
      }
    });
  </script>
</body>
</html>
