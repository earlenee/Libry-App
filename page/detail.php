<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (file_exists('../config/koneksi.php')) {
    require_once '../config/koneksi.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($conn)) {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add_cart') {
            // cek udah ada di cart belum
            $check = $conn->prepare("SELECT id FROM cart WHERE user_id = ? AND book_id = ?");
            $check->bind_param("ii", $user_id, $book_id);
            $check->execute();
            $check_res = $check->get_result();
            if ($check_res && $check_res->num_rows == 0) {
                $ins = $conn->prepare("INSERT INTO cart (user_id, book_id) VALUES (?, ?)");
                $ins->bind_param("ii", $user_id, $book_id);
                $ins->execute();
            }
            header("Location: detail.php?id=" . $book_id . "&cart_added=1");
            exit;
        } elseif ($_POST['action'] === 'toggle_favorite') {
            $check = $conn->prepare("SELECT id FROM favourites WHERE user_id = ? AND book_id = ?");
            $check->bind_param("ii", $user_id, $book_id);
            $check->execute();
            $check_res = $check->get_result();
            if ($check_res && $check_res->num_rows > 0) {
                $del = $conn->prepare("DELETE FROM favourites WHERE user_id = ? AND book_id = ?");
                $del->bind_param("ii", $user_id, $book_id);
                $del->execute();
            } else {
                $ins = $conn->prepare("INSERT INTO favourites (user_id, book_id) VALUES (?, ?)");
                $ins->bind_param("ii", $user_id, $book_id);
                $ins->execute();
            }
            header("Location: detail.php?id=" . $book_id);
            exit;
        }
    }
}

$book = null;

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

if (!$book) {
    $book = [
        'id' => $book_id,
        'title' => 'Book Not Found',
        'author' => 'Unknown Author',
        'cover_image' => 'logo.png',
        'price' => 0,
        'publisher' => 'Unknown Publisher',
        'publication_date' => null,
        'description' => 'This book could not be found in the database.'
    ];
}

$price_formatted = "Rp. " . number_format($book['price'] ?? 0, 0, ',', '.');
$publisher = $book['publisher'] ?? 'N/A';
$pub_date = !empty($book['publication_date']) ? date('d M Y', strtotime($book['publication_date'])) : 'N/A';
$description = $book['description'] ?? 'No description available for this book.';

$in_cart = false;
$is_favorite = false;
$is_purchased = false;

if (isset($conn)) {
    $c_stmt = $conn->prepare("SELECT id FROM cart WHERE user_id = ? AND book_id = ?");
    $c_stmt->bind_param("ii", $user_id, $book_id);
    $c_stmt->execute();
    if ($c_stmt->get_result()->num_rows > 0) $in_cart = true;
    
    $f_stmt = $conn->prepare("SELECT id FROM favourites WHERE user_id = ? AND book_id = ?");
    $f_stmt->bind_param("ii", $user_id, $book_id);
    $f_stmt->execute();
    if ($f_stmt->get_result()->num_rows > 0) $is_favorite = true;
    
    $p_stmt = $conn->prepare("SELECT id FROM purchases WHERE user_id = ? AND book_id = ?");
    $p_stmt->bind_param("ii", $user_id, $book_id);
    $p_stmt->execute();
    if ($p_stmt->get_result()->num_rows > 0) $is_purchased = true;
}
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
                <h4>Publisher</h4>
                <p><?php echo htmlspecialchars($publisher); ?></p>
              </div>
              <div class="meta-item">
                <h4>Publication Date</h4>
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
            
            <div style="text-align: center; padding: 40px 20px;">
              <svg width="48" height="48" fill="none" stroke="var(--text-muted)" stroke-width="1.5" viewBox="0 0 24 24" style="opacity: 0.3; margin-bottom: 12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
              <p style="font-family: 'Inter', sans-serif; font-size: 14px; color: var(--text-muted); margin: 0;">No reviews yet. Be the first to share your thoughts!</p>
            </div>
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
