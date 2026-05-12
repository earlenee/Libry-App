<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }
if (!isset($_SESSION['purchased'])) { $_SESSION['purchased'] = []; }

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'remove_cart') {
        $book_id = intval($_POST['book_id']);
        if (($key = array_search($book_id, $_SESSION['cart'])) !== false) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }
        header("Location: cart.php");
        exit;
    }
}

// Fetch books from DB
$books = [];
$total_price = 0;
if (!empty($_SESSION['cart']) && file_exists('../config/koneksi.php')) {
    require_once '../config/koneksi.php';
    if (isset($conn) && !$conn->connect_error) {
        $ids = implode(',', array_map('intval', $_SESSION['cart']));
        $result = $conn->query("SELECT * FROM books WHERE id IN ($ids)");
        if ($result) {
            while($row = $result->fetch_assoc()) {
                $books[] = $row;
                $total_price += $row['price'] ?? 0;
            }
        }
    }
}

// Membership Tier Logic
$owned_books = count($_SESSION['purchased'] ?? []);
$discount_rate = 0;
$tax_rate = 0.10;

if ($owned_books >= 20) {
    $discount_rate = 0.25;
    $tax_rate = 0;
} elseif ($owned_books >= 5) {
    $discount_rate = 0.10;
}

$discount_amount = $total_price * $discount_rate;
$subtotal_after_discount = $total_price - $discount_amount;
$tax_amount = $subtotal_after_discount * $tax_rate;
$grand_total = $subtotal_after_discount + $tax_amount;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cart - LIBRY</title>
  <link rel="stylesheet" href="../styles.css">
  <style>
    #page-cart { display: flex !important; } .page:not(#page-cart) { display: none !important; }
    
    /* Cart Custom Styles */
    .cart-layout {
      display: flex;
      gap: 32px;
      margin-top: 24px;
      flex-wrap: wrap;
    }
    .cart-items {
      flex: 1;
      min-width: 300px;
      background: white;
      border-radius: 16px;
      padding: 24px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }
    .cart-summary {
      width: 320px;
      background: white;
      border-radius: 16px;
      padding: 24px;
      height: fit-content;
      box-shadow: 0 4px 12px rgba(0,0,0,0.03);
      position: sticky;
      top: 24px;
    }
    .cart-item-row {
      display: flex;
      gap: 20px;
      padding: 20px 0;
      border-bottom: 1px solid rgba(0,0,0,0.06);
    }
    .cart-item-row:last-child {
      border-bottom: none;
    }
    .cart-item-img {
      width: 80px;
      height: 110px;
      object-fit: contain;
      background: #f7f5f0;
      border-radius: 8px;
      padding: 8px;
    }
    .cart-item-details {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    .cart-item-title {
      font-family: 'Inter', sans-serif;
      font-weight: 700;
      font-size: 16px;
      color: var(--dark);
      margin-bottom: 6px;
    }
    .cart-item-cat {
      font-size: 13px;
      color: var(--text-muted);
      margin-bottom: 12px;
    }
    .cart-item-actions {
      display: flex;
      align-items: center;
      gap: 16px;
    }
    .qty-btn {
      background: #e8e3db;
      border: none;
      width: 28px;
      height: 28px;
      border-radius: 50%;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
    }
    .remove-btn {
      color: var(--rust);
      background: none;
      border: none;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
    }
    .summary-title {
      font-family: 'Hammersmith One', sans-serif;
      font-size: 20px;
      margin-bottom: 24px;
    }
    .summary-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 16px;
      font-size: 14px;
      color: var(--text-muted);
    }
    .summary-row.total {
      font-weight: 700;
      color: var(--dark);
      font-size: 18px;
      border-top: 1px solid rgba(0,0,0,0.1);
      padding-top: 16px;
      margin-top: 8px;
    }
    .btn-checkout {
      width: 100%;
      background: var(--navy);
      color: white;
      border: none;
      border-radius: 999px;
      padding: 14px;
      font-size: 15px;
      font-weight: 600;
      font-family: "Hammersmith One", sans-serif;
      cursor: pointer;
      margin-top: 24px;
      transition: background 0.2s;
    }
    .btn-checkout:hover {
      background: #1e3655;
    }
    .empty-cart {
      text-align: center;
      padding: 40px 20px;
    }
    @media (max-width: 768px) {
      .cart-summary {
        width: 100%;
        position: static;
      }
    }
  </style>
</head>
<body>
  <div class="page sidebar-layout active" id="page-cart">
    <?php include '../components/sidebar.php'; ?>
    <main class="main-content">
      <?php include '../components/top_navbar.php'; ?>

      <div class="welcome-banner">
        <h1 class="dashboard-title" style="font-family: 'Hammersmith One', sans-serif; font-size: 32px; color: var(--dark); margin-bottom: 8px;">Your Cart</h1>
        <p class="welcome-subtext">Review the books you want to purchase.</p>
      </div>

      <div class="cart-layout">
        <div class="cart-items">
          <?php if (!empty($books)): ?>
            <?php foreach($books as $book): ?>
            <div class="cart-item-row">
              <img src="../asset/<?php echo htmlspecialchars($book['cover_image'] ?? 'logo.png'); ?>" alt="Book Cover" class="cart-item-img" onerror="this.src='../asset/logo.png'">
              <div class="cart-item-details">
                <div class="cart-item-title"><?php echo htmlspecialchars($book['title']); ?></div>
                <div class="cart-item-cat">Category ID: <?php echo htmlspecialchars($book['category_id']); ?></div>
                <div class="cart-item-actions">
                  <div style="display: flex; align-items: center; gap: 8px;">
                    <button class="qty-btn" disabled>-</button>
                    <span style="font-weight: 600; width: 20px; text-align: center;">1</span>
                    <button class="qty-btn" disabled>+</button>
                  </div>
                  <form method="POST" style="margin: 0;">
                    <input type="hidden" name="action" value="remove_cart">
                    <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                    <button type="submit" class="remove-btn">Remove</button>
                  </form>
                </div>
              </div>
              <div style="font-weight: 700; color: var(--navy);">Rp. <?php echo number_format($book['price'] ?? 0, 0, ',', '.'); ?></div>
            </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="empty-cart">
              <svg width="64" height="64" fill="none" stroke="var(--text-muted)" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
              <h2 style="font-family: 'Hammersmith One', sans-serif; font-size: 24px; color: var(--dark); margin-bottom: 8px;">Your cart is empty</h2>
              <p style="color: var(--text-muted); font-family: 'Inter', sans-serif; margin-bottom: 24px;">Looks like you haven't added any books to your cart yet.</p>
              <button class="btn-checkout" style="width: auto; padding: 12px 32px;" onclick="window.location.href='shop.php'">Start Shopping</button>
            </div>
          <?php endif; ?>
        </div>

        <div class="cart-summary">
          <h3 class="summary-title">Order Summary</h3>
          <div class="summary-row">
            <span>Subtotal (<?php echo count($books); ?> items)</span>
            <span>Rp. <?php echo number_format($total_price, 0, ',', '.'); ?></span>
          </div>
          <?php if ($discount_rate > 0): ?>
          <div class="summary-row" style="color: #2b8a3e;">
            <span>Membership Discount (<?php echo $discount_rate * 100; ?>%)</span>
            <span>- Rp. <?php echo number_format($discount_amount, 0, ',', '.'); ?></span>
          </div>
          <?php endif; ?>
          <div class="summary-row">
            <span>Tax (<?php echo $tax_rate * 100; ?>%)</span>
            <span>Rp. <?php echo number_format($tax_amount, 0, ',', '.'); ?></span>
          </div>
          <div class="summary-row total">
            <span>Total</span>
            <span>Rp. <?php echo number_format($grand_total, 0, ',', '.'); ?></span>
          </div>
          <button type="button" class="btn-checkout" onclick="window.location.href='checkout.php'" <?php echo empty($books) ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : ''; ?>>Checkout securely</button>
        </div>
      </div>

    </main>
  </div>
</body>
</html>