<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }
if (!isset($_SESSION['favorites'])) { $_SESSION['favorites'] = []; }
if (!isset($_SESSION['purchased'])) { $_SESSION['purchased'] = []; }

$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add_cart') {
            if (!in_array($book_id, $_SESSION['cart'])) {
                $_SESSION['cart'][] = $book_id;
            }
            header("Location: detail.php?id=" . $book_id . "&cart_added=1");
            exit;
        } elseif ($_POST['action'] === 'toggle_favorite') {
            if (($key = array_search($book_id, $_SESSION['favorites'])) !== false) {
                unset($_SESSION['favorites'][$key]);
                // Reindex array
                $_SESSION['favorites'] = array_values($_SESSION['favorites']);
            } else {
                $_SESSION['favorites'][] = $book_id;
            }
            header("Location: detail.php?id=" . $book_id);
            exit;
        }
    }
}

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
            $stmt->close();
        }
    }
}

// Fallback mock data if DB fails or book not found
if (!$book) {
    $book = [
        'id' => $book_id,
        'title' => 'Sejarah Indonesia SMA/MA/SMK Kelas 10',
        'author' => 'Samsul Farid',
        'cover_image' => 'logo.png', // Fallback image
        'price' => 75000,
        'publisher' => 'Yrama Widya',
        'publication_date' => '2016-08-11',
        'description' => 'Buku Sejarah Indonesia kelas X Kurikulum 2013 bertujuan membantu siswa tidak hanya menghafal, tetapi juga memahami, menulis, dan menganalisis peristiwa sejarah, serta mengaitkannya dengan konteks lokal, nasional, dan global. Dengan pendekatan regresif, siswa diajak mengamati kondisi sosial-budaya dan warisan sejarah saat ini. Siswa juga diharapkan mencari sumber lain dan mengambil nilai-nilai sejarah untuk menumbuhkan rasa cinta tanah air dan nasionalisme.'
    ];
}

$price_formatted = "Rp. " . number_format($book['price'] ?? 0, 0, ',', '.');
$publisher = $book['publisher'] ?? 'Yrama Widya';
$pub_date = isset($book['publication_date']) ? date('d M Y', strtotime($book['publication_date'])) : '11 Agu 2016';
$description = $book['description'] ?? 'No description available for this book.';

$in_cart = in_array($book_id, $_SESSION['cart']);
$is_favorite = in_array($book_id, $_SESSION['favorites']);
$is_purchased = in_array($book_id, $_SESSION['purchased']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book Detail - LIBRY</title>
  <link rel="stylesheet" href="../styles.css">
  <style>
    #page-detail { display: flex !important; } .page:not(#page-detail) { display: none !important; }
    .toast-success {
      position: fixed;
      top: 24px;
      right: 24px;
      background: #2b8a3e;
      color: white;
      padding: 16px 24px;
      border-radius: 12px;
      font-family: 'Inter', sans-serif;
      font-weight: 600;
      font-size: 14px;
      box-shadow: 0 10px 30px rgba(43, 138, 62, 0.3);
      transform: translateY(-100px);
      opacity: 0;
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      z-index: 10000;
    }
    .toast-success.show {
      transform: translateY(0);
      opacity: 1;
    }
    .reviews-section {
      margin-top: 40px;
    }
    .review-card {
      background: rgba(0,0,0,0.02);
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 16px;
    }
    .review-header {
      display: flex;
      justify-content: space-between;
      margin-bottom: 12px;
      align-items: center;
    }
    .reviewer-name {
      font-family: 'Inter', sans-serif;
      font-weight: 700;
      color: var(--dark);
    }
    .review-stars {
      color: #f59f00;
    }
    .review-text {
      color: var(--text-muted);
      line-height: 1.6;
    }
  </style>
</head>
<body>
  <?php if(isset($_GET['cart_added']) && $_GET['cart_added'] == '1'): ?>
  <div class="toast-success" id="successToast">Book added to cart!</div>
  <?php endif; ?>

  <div class="page sidebar-layout active" id="page-detail">
    <?php include '../components/sidebar.php'; ?>
    <main class="main-content">
      <?php include '../components/top_navbar.php'; ?>

      <div class="book-detail-container" style="margin-top: 40px; margin-bottom: 60px;">
        <div class="bd-top">
          <div class="bd-cover-box">
            <img src="../asset/<?php echo htmlspecialchars($book['cover_image']); ?>" alt="Book Cover" onerror="this.src='../asset/logo.png'">
          </div>
          <div class="bd-info-box">
            <h1 id="detail-title"><?php echo htmlspecialchars($book['title']); ?></h1>
            <div id="detail-price">
              <?php echo $is_purchased ? '<span style="color: #2b8a3e; font-size: 18px;">✓ You own this book</span>' : $price_formatted; ?>
            </div>
            
            <div class="bd-actions" style="display: flex; gap: 16px; align-items: center;">
              <?php if ($is_purchased): ?>
                <button type="button" class="btn-checkout" style="background: var(--navy); width: 100%;" onclick="window.location.href='read.php?id=<?php echo $book_id; ?>'">Read Now</button>
              <?php else: ?>
                <form method="POST" style="margin: 0;">
                  <input type="hidden" name="action" value="add_cart">
                  <?php if ($in_cart): ?>
                    <button type="button" class="btn-checkout" style="background: #2b8a3e;" onclick="window.location.href='cart.php'">View in Cart</button>
                  <?php else: ?>
                    <button type="submit" class="btn-checkout">Add to Cart</button>
                  <?php endif; ?>
                </form>
              <?php endif; ?>

              <form method="POST" style="margin: 0;">
                <input type="hidden" name="action" value="toggle_favorite">
                <button type="submit" class="btn-add-cart" style="background: <?php echo $is_favorite ? 'rgba(184, 74, 46, 0.1)' : 'var(--dark)'; ?>; color: <?php echo $is_favorite ? 'var(--rust)' : 'white'; ?>; border: <?php echo $is_favorite ? '1px solid var(--rust)' : 'none'; ?>;">
                  <?php if ($is_favorite): ?>
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                  <?php else: ?>
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" /></svg>
                  <?php endif; ?>
                </button>
              </form>
            </div>

            <div class="bd-meta">
              <div class="meta-item">
                <h4>Penerbit</h4>
                <p><?php echo htmlspecialchars($publisher); ?></p>
              </div>
              <div class="meta-item">
                <h4>Tanggal Terbit</h4>
                <p><?php echo htmlspecialchars($pub_date); ?></p>
              </div>
            </div>
          </div>
        </div>

        <div class="bd-bottom">
          <div class="bd-desc">
            <h3>Description</h3>
            <p><?php echo nl2br(htmlspecialchars($description)); ?></p>
          </div>
          <div class="bd-divider-bar"></div>
          
          <div class="reviews-section">
            <h3 style="font-family: 'Hammersmith One', sans-serif; font-size: 24px; color: var(--dark); margin-bottom: 24px;">Reader Reviews</h3>
            
            <div class="review-card">
              <div class="review-header">
                <div class="reviewer-name">Alex Johnson</div>
                <div class="review-stars">★★★★★</div>
              </div>
              <div class="review-text">An incredibly insightful and engaging read! The author presents complex ideas in a way that is highly accessible without losing any of the nuance. Highly recommended for anyone interested in this topic.</div>
            </div>

            <div class="review-card">
              <div class="review-header">
                <div class="reviewer-name">Sarah Williams</div>
                <div class="review-stars">★★★★☆</div>
              </div>
              <div class="review-text">Very well written and informative. I learned a lot from the historical context provided. The only reason it's not 5 stars is because the pacing in the middle chapters felt a bit slow, but overall a great addition to my library.</div>
            </div>
            
            <button class="btn-profile-secondary" style="width: 100%; margin-top: 16px; border: 1px dashed rgba(0,0,0,0.2); background: transparent;" onclick="alert('Review submission coming soon!')">Write a Review</button>
          </div>
        </div>
      </div>

    </main>
  </div>
  
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const toast = document.getElementById('successToast');
      if (toast) {
        setTimeout(() => {
          toast.classList.add('show');
          setTimeout(() => {
            toast.classList.remove('show');
          }, 3000);
        }, 100);
      }
    });
  </script>
</body>
</html>
