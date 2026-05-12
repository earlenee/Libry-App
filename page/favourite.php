<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['favorites'])) { $_SESSION['favorites'] = []; }

// Handle AJAX remove favorite
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'remove_favorite') {
    $book_id = intval($_POST['book_id']);
    if (($key = array_search($book_id, $_SESSION['favorites'])) !== false) {
        unset($_SESSION['favorites'][$key]);
        $_SESSION['favorites'] = array_values($_SESSION['favorites']);
    }
    exit;
}

$books = [];
$favorites = $_SESSION['favorites'];

if (!empty($favorites) && file_exists('../config/koneksi.php')) {
    require_once '../config/koneksi.php';
    if (isset($conn) && !$conn->connect_error) {
        $ids = implode(',', array_map('intval', $favorites));
        $result = $conn->query("SELECT * FROM books WHERE id IN ($ids)");
        if ($result) {
            while($row = $result->fetch_assoc()) {
                $books[] = $row;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Favourite - LIBRY</title>
  <link rel="stylesheet" href="../styles.css">
  <style>
    #page-favourite { display: flex !important; } .page:not(#page-favourite) { display: none !important; }
    
    .btn-remove-fav {
      background: rgba(184, 74, 46, 0.1);
      border: none;
      color: var(--rust);
      cursor: pointer;
      position: relative;
      z-index: 10;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s;
    }
    
    .btn-remove-fav:hover {
      background: var(--rust);
      color: white;
      transform: scale(1.1);
    }
  </style>
</head>
<body>
  <div class="page sidebar-layout active" id="page-favourite">
    <?php include '../components/sidebar.php'; ?>
    <main class="main-content">
      <?php include '../components/top_navbar.php'; ?>

      <div class="welcome-banner" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
          <h1 class="dashboard-title" style="font-family: 'Hammersmith One', sans-serif; font-size: 32px; color: var(--dark); margin-bottom: 8px;">Your Favourites</h1>
          <p class="welcome-subtext">Quick access to the books you love the most.</p>
        </div>
        <div style="background: rgba(184, 74, 46, 0.1); padding: 12px; border-radius: 50%; color: var(--rust);">
          <svg width="32" height="32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
        </div>
      </div>

      <div class="category-grid" id="favoritesGrid">
        <?php if (!empty($books)): ?>
            <?php foreach($books as $book): 
                $is_bought = (isset($_SESSION['purchased']) && in_array($book['id'], $_SESSION['purchased'])) || ($book['id'] % 2 == 0); // Temporary logic matching profile
                $target_url = $is_bought ? "read.php?id=" . $book['id'] : "detail.php?id=" . $book['id'];
            ?>
            <div class="cat-card" style="cursor: pointer;" onclick="window.location.href='<?php echo $target_url; ?>'">
              <div class="cat-card-img-wrap">
                <img src="../asset/<?php echo htmlspecialchars($book['cover_image'] ?? ''); ?>" alt="Book Cover" onerror="this.src='../asset/logo.png'">
              </div>
              <div class="cat-card-info" style="display: flex; justify-content: space-between; align-items: center; gap: 12px;">
                <div style="flex: 1; min-width: 0;">
                  <div class="cat-card-title" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo htmlspecialchars($book['title']); ?></div>
                  <div class="cat-card-cat" style="color: <?php echo $is_bought ? '#2b8a3e' : 'var(--text-muted)'; ?>; font-weight: <?php echo $is_bought ? '600' : 'normal'; ?>;">
                    <?php echo $is_bought ? '✓ Purchased' : 'Category ID: ' . htmlspecialchars($book['category_id']); ?>
                  </div>
                </div>
                <button class="btn-remove-fav" style="flex-shrink: 0;" onclick="event.stopPropagation(); removeFavorite(this, <?php echo $book['id']; ?>)">
                  <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                </button>
              </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1 / -1; background: white; padding: 40px; border-radius: 16px; text-align: center; border: 1px solid rgba(0,0,0,0.05);">
              <p style="color: var(--text-muted); font-family: 'Inter', sans-serif; margin-bottom: 16px;">You don't have any favorite books yet.</p>
              <button class="btn-profile-primary" style="display: inline-block; padding: 10px 24px; background: var(--navy); color: white; border: none; border-radius: 99px; cursor: pointer; font-family: 'Inter', sans-serif; font-weight: 600;" onclick="window.location.href='shop.php'">Browse Books</button>
            </div>
        <?php endif; ?>
      </div>
    </main>
  </div>

  <script>
    function removeFavorite(btn, bookId) {
      // Remove visually
      const card = btn.closest('.cat-card');
      card.style.transform = 'scale(0.9)';
      card.style.opacity = '0';
      
      setTimeout(() => {
        card.remove();
        checkEmptyFavorites();
      }, 300);
      
      // Make AJAX call to update session
      const formData = new FormData();
      formData.append('action', 'remove_favorite');
      formData.append('book_id', bookId);

      fetch('favourite.php', {
          method: 'POST',
          body: formData
      }).then(response => {
          console.log('Successfully removed from favorites');
      }).catch(error => console.error('Error:', error));
    }
    
    function checkEmptyFavorites() {
      const grid = document.getElementById('favoritesGrid');
      const cards = grid.querySelectorAll('.cat-card');
      
      if (cards.length === 0) {
        grid.innerHTML = `
          <div style="grid-column: 1 / -1; background: white; padding: 40px; border-radius: 16px; text-align: center; border: 1px solid rgba(0,0,0,0.05);">
            <p style="color: var(--text-muted); font-family: 'Inter', sans-serif; margin-bottom: 16px;">You don't have any favorite books yet.</p>
            <button class="btn-profile-primary" style="display: inline-block; padding: 10px 24px; background: var(--navy); color: white; border: none; border-radius: 99px; cursor: pointer; font-family: 'Inter', sans-serif; font-weight: 600;" onclick="window.location.href='shop.php'">Browse Books</button>
          </div>
        `;
      }
    }
  </script>
</body>
</html>