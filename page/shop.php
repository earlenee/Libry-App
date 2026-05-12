<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ensure the connection script exists before requiring
$books = [];
$categories = [];
$cat_map = [];

if (file_exists('../config/koneksi.php')) {
    require_once '../config/koneksi.php';
    if (isset($conn) && !$conn->connect_error) {
        $cat_res = $conn->query("SELECT * FROM categories");
        if ($cat_res) {
            while($c = $cat_res->fetch_assoc()) {
                $categories[] = $c;
                $cat_map[$c['id']] = $c['name'];
            }
        }

        $result = $conn->query("SELECT * FROM books");
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
  <title>Category - LIBRY</title>
  <link rel="stylesheet" href="../styles.css">
  <style>#page-shop { display: flex !important; } .page:not(#page-shop) { display: none !important; }</style>
</head>
<body>
  <div class="page sidebar-layout active" id="page-shop">
    <?php include '../components/sidebar.php'; ?>
    <main class="main-content">
      <?php include '../components/top_navbar.php'; ?>

      <div class="welcome-banner">
        <h1 class="dashboard-title" style="font-family: 'Hammersmith One', sans-serif; font-size: 32px; color: var(--dark); margin-bottom: 8px;">Explore Categories</h1>
        <p class="welcome-subtext">Find books from various subjects and expand your knowledge.</p>
      </div>

      <div class="filter-pills" id="shop-pills" style="position: relative; display: inline-flex; background: rgba(0,0,0,0.04); padding: 6px; border-radius: 99px; gap: 4px; margin-bottom: 40px; overflow-x: auto; scrollbar-width: none;">
        <div class="pill-indicator"></div>
        <button class="pill active" onclick="filterCategory('All', this)">All Books</button>
        <?php foreach($categories as $cat): ?>
            <button class="pill" onclick="filterCategory('<?php echo htmlspecialchars($cat['name']); ?>', this)"><?php echo htmlspecialchars($cat['name']); ?></button>
        <?php endforeach; ?>
        <?php if(empty($categories)): ?>
            <button class="pill" onclick="filterCategory('General Knowledge', this)">General Knowledge</button>
        <?php endif; ?>
      </div>

      <div class="category-grid">
        <?php if (!empty($books)): ?>
            <?php foreach($books as $book): 
                $cat_name = $cat_map[$book['category_id']] ?? 'General Knowledge';
            ?>
            <div class="cat-card" data-category="<?php echo htmlspecialchars($cat_name); ?>" data-title="<?php echo htmlspecialchars($book['title']); ?>" onclick="window.location.href='detail.php?id=<?php echo $book['id']; ?>'">
              <div class="cat-card-img-wrap">
                <img src="../asset/<?php echo htmlspecialchars($book['cover_image'] ?? ''); ?>" alt="Book Cover" onerror="this.src='../asset/logo.png'">
              </div>
              <div class="cat-card-info">
                <div class="cat-card-title"><?php echo htmlspecialchars($book['title']); ?></div>
                <div class="cat-card-cat"><?php echo htmlspecialchars($cat_name); ?></div>
              </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Placeholder if no database connection or empty -->
            <?php for($i=1; $i<=8; $i++): ?>
            <div class="cat-card" data-category="General Knowledge" data-title="Interesting Book Title <?php echo $i; ?>" onclick="alert('Book clicked!')">
              <div class="cat-card-img-wrap">
                <img src="../asset/logo.png" alt="Book Cover" style="opacity: 0.5;">
              </div>
              <div class="cat-card-info">
                <div class="cat-card-title">Interesting Book Title <?php echo $i; ?></div>
                <div class="cat-card-cat">General Knowledge</div>
              </div>
            </div>
            <?php endfor; ?>
        <?php endif; ?>
      </div>

      <!-- EMPTY STATE -->
      <div id="shop-empty-state" style="display: none; background: white; padding: 60px 20px; border-radius: 16px; text-align: center; border: 1px solid rgba(0,0,0,0.05); margin-top: 20px;">
        <svg width="64" height="64" fill="none" stroke="var(--text-muted)" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom: 16px; opacity: 0.5;"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        <h2 style="font-family: 'Hammersmith One', sans-serif; font-size: 24px; color: var(--dark); margin-bottom: 8px;">No books found</h2>
        <p style="color: var(--text-muted); font-family: 'Inter', sans-serif;">Try adjusting your search or filter to find what you're looking for.</p>
      </div>

    </main>
  </div>

  <script>
    function filterCategory(catName, btnElement) {
        document.querySelectorAll('.pill').forEach(btn => btn.classList.remove('active'));
        if(btnElement) {
            btnElement.classList.add('active');
            
            // Animasi memantul pada indikator kategori
            const indicator = document.querySelector('.pill-indicator');
            if (indicator) {
                const offset = btnElement.offsetLeft - 6; 
                indicator.style.width = btnElement.offsetWidth + 'px';
                indicator.style.transform = `translateX(${offset}px)`;
            }
        }

        const cards = document.querySelectorAll('.cat-card');
        
        cards.forEach(card => {
            card.style.transition = 'all 0.3s ease';
            card.style.opacity = '0';
            card.style.transform = 'translateY(15px) scale(0.95)';
        });

        setTimeout(() => {
            let delayIndex = 0;
            let hasVisible = false;
            cards.forEach(card => {
                if (catName === 'All' || card.getAttribute('data-category') === catName) {
                    card.style.display = 'flex'; 
                    hasVisible = true;
                    card.offsetHeight; // trigger reflow
                    
                    setTimeout(() => {
                        card.style.transition = 'all 0.5s cubic-bezier(0.34, 1.25, 0.64, 1)';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0) scale(1)';
                    }, delayIndex * 40);
                    delayIndex++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            const emptyState = document.getElementById('shop-empty-state');
            if (emptyState) {
                emptyState.style.display = hasVisible ? 'none' : 'block';
            }
        }, 300);
    }

    function searchBooks(val) {
        const query = val.toLowerCase();
        const cards = document.querySelectorAll('.cat-card');
        
        cards.forEach(card => {
            card.style.transition = 'all 0.3s ease';
            card.style.opacity = '0';
            card.style.transform = 'translateY(15px) scale(0.95)';
        });

        setTimeout(() => {
            let delayIndex = 0;
            let hasVisible = false;
            cards.forEach(card => {
                const title = card.getAttribute('data-title').toLowerCase();
                if (title.includes(query)) {
                    card.style.display = 'flex';
                    hasVisible = true;
                    card.offsetHeight; // trigger reflow
                    setTimeout(() => {
                        card.style.transition = 'all 0.5s cubic-bezier(0.34, 1.25, 0.64, 1)';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0) scale(1)';
                    }, delayIndex * 20);
                    delayIndex++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            const emptyState = document.getElementById('shop-empty-state');
            if (emptyState) {
                emptyState.style.display = hasVisible ? 'none' : 'block';
            }
        }, 300);
    }

    window.addEventListener('DOMContentLoaded', () => {
        const activeBtn = document.querySelector('.pill.active');
        if(activeBtn) {
            const indicator = document.querySelector('.pill-indicator');
            if (indicator) {
                indicator.style.width = activeBtn.offsetWidth + 'px';
                indicator.style.transform = `translateX(${activeBtn.offsetLeft - 6}px)`;
            }
        }
    });
  </script>
</body>
</html>