<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require '../config/koneksi.php';
$query_cat = mysqli_query($conn, "SELECT * FROM categories");

// Get last purchased book for Continue Reading
$last_book = null;
if (isset($_SESSION['purchased']) && count($_SESSION['purchased']) > 0) {
    $last_book_id = end($_SESSION['purchased']); // get the last item
    $q_last = mysqli_query($conn, "SELECT * FROM books WHERE id = " . intval($last_book_id));
    if ($q_last && mysqli_num_rows($q_last) > 0) {
        $last_book = mysqli_fetch_assoc($q_last);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - LIBRY</title>
  <link rel="stylesheet" href="../styles.css">
  <style>
    #page-dashboard { display: flex !important; } .page:not(#page-dashboard) { display: none !important; }
    .continue-reading-banner {
      background: var(--navy);
      border-radius: 16px;
      padding: 24px;
      margin-bottom: 40px;
      display: flex;
      gap: 24px;
      color: white;
      align-items: center;
      cursor: pointer;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .continue-reading-banner:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 24px rgba(0,0,0,0.1);
    }
    @media (max-width: 768px) {
      .continue-reading-banner {
        flex-direction: column;
        text-align: center;
      }
    }
  </style>
</head>
<body>
  <div class="page sidebar-layout active" id="page-dashboard">
    <?php include '../components/sidebar.php'; ?>
    <main class="main-content">
      <?php include '../components/top_navbar.php'; ?>

      <div class="welcome-banner">
        <div style="display: flex; align-items: center; gap: 10px;">
          <span style="font-family: 'Inter', sans-serif; font-size: 28px; font-weight: 600; color: var(--dark);">Welcome to the</span>
          <h1 class="dashboard-title" style="font-family: 'Hammersmith One', sans-serif; font-size: 40px; font-weight: 900; line-height: 1; letter-spacing: -1px; color: var(--dark); display: flex; align-items: center; margin: 0;">
            LI
            <span style="display: inline-flex; align-items: center; height: 40px; margin: 0 -15px;">
              <img src="../asset/logo.png" style="height: 72px; width: auto; object-fit: contain; transform: translateY(-14px);" alt="" />
            </span>
            RY !
          </h1>
        </div>
        <p class="welcome-subtext">Discover and read your favorite books easily, <b><?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'User'; ?></b>.</p>
      </div>
      <div class="divider" style="margin: 20px auto 40px; width: 70%; height: 1px; background: rgba(0,0,0,0.1);"></div>

      <?php if ($last_book): ?>
      <!-- CONTINUE READING BANNER -->
      <div class="continue-reading-banner" onclick="window.location.href='read.php?id=<?php echo $last_book['id']; ?>'">
        <img src="../asset/<?php echo htmlspecialchars($last_book['cover_image'] ?? 'logo.png'); ?>" style="height: 120px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.2);" onerror="this.src='../asset/logo.png'">
        <div style="flex: 1;">
          <span style="font-family: 'Inter', sans-serif; font-size: 12px; text-transform: uppercase; letter-spacing: 1.5px; color: rgba(255,255,255,0.7); font-weight: 700;">Continue Reading</span>
          <h2 style="font-family: 'Hammersmith One', sans-serif; font-size: 28px; margin: 8px 0; color: white;"><?php echo htmlspecialchars($last_book['title']); ?></h2>
          <div style="width: 100%; max-width: 400px; height: 6px; background: rgba(255,255,255,0.2); border-radius: 3px; margin-top: 16px; overflow: hidden;">
            <div id="dashboardProgressBar" style="width: 0%; height: 100%; background: var(--rust); border-radius: 3px; transition: width 0.8s cubic-bezier(0.34, 1.25, 0.64, 1);"></div>
          </div>
          <span id="dashboardProgressText" style="font-family: 'Inter', sans-serif; font-size: 13px; color: rgba(255,255,255,0.7); margin-top: 8px; display: block;">0% completed</span>
        </div>
        <div style="background: rgba(255,255,255,0.1); width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
          <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </div>
      </div>
      <?php endif; ?>



      <!-- RECENTLY READ (Populated by JS) -->
      <div class="category-section" id="recently-read-section" style="display: none;">
        <h2 class="category-title" style="font-family: 'Hammersmith One', sans-serif; font-size: 24px; color: var(--dark); border-bottom: 2px solid rgba(0,0,0,0.05); padding-bottom: 12px; margin-bottom: 24px;">Recently Read</h2>
        <div class="category-grid" id="recently-read-grid"></div>
      </div>

      <!-- MY LIBRARY (Purchased Books) -->
      <div class="category-section" id="my-library">
        <h2 class="category-title" style="font-family: 'Hammersmith One', sans-serif; font-size: 24px; color: var(--dark); border-bottom: 2px solid rgba(0,0,0,0.05); padding-bottom: 12px; margin-bottom: 24px;">My Library</h2>
        
        <?php
          $purchased = isset($_SESSION['purchased']) ? $_SESSION['purchased'] : [];
          $purchased_json = [];
          if (!empty($purchased)) {
              $ids = implode(',', array_map('intval', $purchased));
              $q_lib = mysqli_query($conn, "SELECT books.*, categories.name as cat_name FROM books LEFT JOIN categories ON books.category_id = categories.id WHERE books.id IN ($ids)");
              
              if ($q_lib && mysqli_num_rows($q_lib) > 0) {
                  echo '<div class="category-grid" id="dashboard-books">';
                  while ($book = mysqli_fetch_assoc($q_lib)) {
                      $purchased_json[$book['id']] = $book;
        ?>
                    <div class="cat-card book-card" data-title="<?php echo htmlspecialchars($book['title']); ?>" onclick="window.location.href='read.php?id=<?php echo $book['id']; ?>'">
                      <div class="cat-card-img-wrap">
                        <img src="../asset/<?php echo htmlspecialchars($book['cover_image'] ?? ''); ?>" alt="Book Cover" onerror="this.src='../asset/logo.png'">
                      </div>
                      <div class="cat-card-info" style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="flex: 1; min-width: 0;">
                          <div class="cat-card-title" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo htmlspecialchars($book['title']); ?></div>
                          <div class="cat-card-cat" style="color: #2b8a3e; font-weight: 600;">✓ Ready to read</div>
                        </div>
                      </div>
                    </div>
        <?php
                  }
                  echo '</div>';
              }
          } else {
        ?>
          <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 16px; border: 1px solid rgba(0,0,0,0.05);">
            <svg width="48" height="48" fill="none" stroke="var(--text-muted)" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            <h3 style="font-family: 'Hammersmith One', sans-serif; font-size: 20px; color: var(--dark); margin-bottom: 8px;">Your library is empty</h3>
            <p style="font-family: 'Inter', sans-serif; color: var(--text-muted); margin-bottom: 24px;">You haven't purchased any books yet. Discover new knowledge today.</p>
            <button class="btn-profile-primary" style="padding: 12px 32px; background: var(--navy); color: white; border: none; border-radius: 999px; cursor: pointer; font-weight: 600;" onclick="window.location.href='shop.php'">Browse Catalog</button>
          </div>
        <?php
          }
        ?>
      </div>
    </main>
  </div>
  
  <script>
    const purchasedBooks = <?php echo json_encode($purchased_json); ?>;
    
    function searchBooks(val) {
        const query = val.toLowerCase();
        const cards = document.querySelectorAll('#my-library .book-card');
        
        cards.forEach(card => {
            const title = card.getAttribute('data-title').toLowerCase();
            if (title.includes(query)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
      // 1. Continue Reading Progress
      <?php if ($last_book): ?>
      const lastBookId = <?php echo $last_book['id']; ?>;
      const savedProgress = localStorage.getItem('libry_progress_' + lastBookId);
      if (savedProgress) {
        setTimeout(() => {
          document.getElementById('dashboardProgressBar').style.width = savedProgress + '%';
          document.getElementById('dashboardProgressText').innerText = savedProgress + '% completed';
        }, 300);
      }
      <?php endif; ?>

      // 2. Populate Recently Read
      if (purchasedBooks && Object.keys(purchasedBooks).length > 0) {
        let recentlyReadList = [];
        for (let bookId in purchasedBooks) {
          const prog = localStorage.getItem('libry_progress_' + bookId);
          if (prog && parseInt(prog) > 0) {
            recentlyReadList.push({
               ...purchasedBooks[bookId],
               progress: parseInt(prog)
            });
          }
        }
        
        // Sort by id descending just as a mock for recency, or we can just show them
        if (recentlyReadList.length > 0) {
          const rrSection = document.getElementById('recently-read-section');
          const rrGrid = document.getElementById('recently-read-grid');
          rrSection.style.display = 'block';
          
          let html = '';
          recentlyReadList.slice(0, 4).forEach(book => {
            html += `
              <div class="cat-card book-card" onclick="window.location.href='read.php?id=${book.id}'">
                <div class="cat-card-img-wrap" style="position:relative;">
                  <img src="../asset/${book.cover_image || 'logo.png'}" onerror="this.src='../asset/logo.png'">
                  <div style="position:absolute; bottom:0; left:0; right:0; height:4px; background:rgba(0,0,0,0.5);">
                    <div style="height:100%; background:var(--rust); width:${book.progress}%;"></div>
                  </div>
                </div>
                <div class="cat-card-info" style="display: flex; justify-content: space-between; align-items: center;">
                  <div style="flex: 1; min-width: 0;">
                    <div class="cat-card-title" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${book.title}</div>
                    <div class="cat-card-cat" style="color: var(--text-muted); font-size:12px;">${book.progress}% Read</div>
                  </div>
                </div>
              </div>
            `;
          });
          rrGrid.innerHTML = html;
        }
      }
    });
  </script>
</body>
</html>

