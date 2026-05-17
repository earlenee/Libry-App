<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// cek cart kosong atau engga
$cart_empty = true;
$books = [];
$total_price = 0;
$owned_books = 0;

if (file_exists('../config/koneksi.php')) {
    require_once '../config/koneksi.php';
    if (isset($conn) && !$conn->connect_error) {
        $c_stmt = $conn->prepare("SELECT book_id FROM cart WHERE user_id = ?");
        $c_stmt->bind_param("i", $user_id);
        $c_stmt->execute();
        $c_res = $c_stmt->get_result();
        if ($c_res && $c_res->num_rows > 0) {
            $cart_empty = false;
        }

        if ($cart_empty) {
            header("Location: cart.php");
            exit;
        }

        // proses checkout
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay_now'])) {
            // ambil judul buku dulu buat notifikasi
            $titles_stmt = $conn->prepare("SELECT books.title FROM cart JOIN books ON cart.book_id = books.id WHERE cart.user_id = ?");
            $titles_stmt->bind_param("i", $user_id);
            $titles_stmt->execute();
            $titles_res = $titles_stmt->get_result();
            $bought_titles = [];
            while ($t = $titles_res->fetch_assoc()) {
                $bought_titles[] = $t['title'];
            }

            // pindahin item dari cart ke tabel purchases
            $stmt_cart = $conn->prepare("SELECT book_id FROM cart WHERE user_id = ?");
            $stmt_cart->bind_param("i", $user_id);
            $stmt_cart->execute();
            $cart_items = $stmt_cart->get_result();
            $ins_purch = $conn->prepare("INSERT IGNORE INTO purchases (user_id, book_id) VALUES (?, ?)");
            while ($row = $cart_items->fetch_assoc()) {
                $b_id = $row['book_id'];
                $ins_purch->bind_param("ii", $user_id, $b_id);
                $ins_purch->execute();
            }
            // kosongin cart setelah checkout
            $del = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
            $del->bind_param("i", $user_id);
            $del->execute();

            // tambahin notif pembelian ke session
            if (!isset($_SESSION['notifications'])) $_SESSION['notifications'] = [];
            $book_list = implode(', ', $bought_titles);
            $_SESSION['notifications'][] = [
                'icon' => 'cart',
                'color' => '#1e3655',
                'title' => 'Purchase Successful!',
                'message' => 'You have purchased: ' . $book_list . '. Books are now available in My Library.',
                'time' => 'Just now'
            ];

            header("Location: profile.php?checkout_success=1");
            exit;
        }

        // ambil data buku dari cart buat ringkasan
        $stmt_books = $conn->prepare("SELECT books.* FROM cart JOIN books ON cart.book_id = books.id WHERE cart.user_id = ?");
        $stmt_books->bind_param("i", $user_id);
        $stmt_books->execute();
        $result = $stmt_books->get_result();
        if ($result) {
            while($row = $result->fetch_assoc()) {
                $books[] = $row;
                $total_price += $row['price'] ?? 0;
            }
        }
        
        // Fetch owned books count for membership logic
        $own_stmt = $conn->prepare("SELECT COUNT(*) as count FROM purchases WHERE user_id = ?");
        $own_stmt->bind_param("i", $user_id);
        $own_stmt->execute();
        $own_res = $own_stmt->get_result();
        if ($own_res && $own_res->num_rows > 0) {
            $owned_books = $own_res->fetch_assoc()['count'];
        }
    }
} else {
    die("Database connection failed.");
}

// logika diskon membership
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout - LIBRY Secure Pay</title>
  <link rel="stylesheet" href="../styles.css">
  <style>
    body {
      background: #f7f5f0;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      font-family: 'Inter', sans-serif;
    }
    .checkout-container {
      background: white;
      width: 100%;
      max-width: 900px;
      border-radius: 24px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.08);
      display: flex;
      overflow: hidden;
    }
    .checkout-left {
      flex: 1;
      padding: 40px;
      background: #ffffff;
    }
    .checkout-right {
      width: 360px;
      background: #fcfbf9;
      padding: 40px;
      border-left: 1px solid rgba(0,0,0,0.05);
    }
    .checkout-title {
      font-family: 'Hammersmith One', sans-serif;
      font-size: 28px;
      color: var(--dark);
      margin-bottom: 8px;
    }
    .checkout-subtitle {
      font-size: 14px;
      color: var(--text-muted);
      margin-bottom: 32px;
    }
    .payment-methods {
      display: flex;
      flex-direction: column;
      gap: 12px;
      margin-bottom: 32px;
    }
    .payment-method {
      border: 1px solid rgba(0,0,0,0.1);
      border-radius: 12px;
      padding: 16px;
      display: flex;
      align-items: center;
      gap: 16px;
      cursor: pointer;
      transition: all 0.2s;
    }
    .payment-method:hover {
      border-color: var(--navy);
      background: rgba(30, 54, 85, 0.02);
    }
    .payment-method.active {
      border-color: var(--navy);
      background: rgba(30, 54, 85, 0.05);
      border-width: 2px;
    }
    .payment-icon {
      width: 40px;
      height: 28px;
      background: #eee;
      border-radius: 4px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 12px;
      color: var(--dark);
    }
    .payment-name {
      font-weight: 600;
      color: var(--dark);
      flex: 1;
    }
    .payment-radio {
      width: 20px;
      height: 20px;
      border-radius: 50%;
      border: 2px solid rgba(0,0,0,0.2);
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .payment-method.active .payment-radio {
      border-color: var(--navy);
    }
    .payment-method.active .payment-radio::after {
      content: '';
      width: 10px;
      height: 10px;
      background: var(--navy);
      border-radius: 50%;
    }
    
    .card-details {
      display: none;
      flex-direction: column;
      gap: 16px;
      margin-top: 16px;
    }
    
    .input-group {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }
    .input-group label {
      font-size: 13px;
      font-weight: 600;
      color: var(--dark);
    }
    .input-group input {
      padding: 14px 16px;
      border: 1px solid rgba(0,0,0,0.1);
      border-radius: 8px;
      font-family: 'Inter', sans-serif;
      font-size: 15px;
      outline: none;
      transition: border-color 0.2s;
    }
    .input-group input:focus {
      border-color: var(--navy);
    }
    
    .order-item {
      display: flex;
      justify-content: space-between;
      margin-bottom: 16px;
      font-size: 14px;
    }
    .order-item-title {
      color: var(--dark);
      font-weight: 600;
      max-width: 200px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .order-item-price {
      color: var(--text-muted);
    }
    .divider {
      height: 1px;
      background: rgba(0,0,0,0.1);
      margin: 24px 0;
    }
    .summary-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 12px;
      font-size: 14px;
      color: var(--text-muted);
    }
    .summary-row.total {
      font-size: 20px;
      font-weight: 700;
      color: var(--dark);
      margin-top: 24px;
    }
    .btn-pay {
      width: 100%;
      background: var(--navy);
      color: white;
      border: none;
      border-radius: 999px;
      padding: 16px;
      font-size: 16px;
      font-weight: 600;
      font-family: "Hammersmith One", sans-serif;
      cursor: pointer;
      margin-top: 32px;
      transition: all 0.2s;
      position: relative;
      overflow: hidden;
    }
    .btn-pay:hover {
      background: #1e3655;
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(30, 54, 85, 0.2);
    }
    
    .loading-overlay {
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background: var(--navy);
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.3s;
    }
    .btn-pay.loading .loading-overlay {
      opacity: 1;
    }
    
    .spinner {
      width: 24px; height: 24px;
      border: 3px solid rgba(255,255,255,0.3);
      border-radius: 50%;
      border-top-color: white;
      animation: spin 1s linear infinite;
    }
    
    @keyframes spin { 100% { transform: rotate(360deg); } }
    
    .back-link {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      color: var(--text-muted);
      text-decoration: none;
      font-size: 14px;
      font-weight: 600;
      margin-bottom: 24px;
      transition: color 0.2s;
    }
    .back-link:hover {
      color: var(--dark);
    }
    
    @media (max-width: 768px) {
      .checkout-container {
        flex-direction: column;
        border-radius: 0;
        min-height: 100vh;
      }
      .checkout-right {
        width: 100%;
        border-left: none;
        border-top: 1px solid rgba(0,0,0,0.05);
      }
    }
  </style>
</head>
<body>

  <div class="checkout-container">
    <div class="checkout-left">
      <a href="cart.php" class="back-link">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        Back to Cart
      </a>
      
      <h1 class="checkout-title">Payment Details</h1>
      <p class="checkout-subtitle">Complete your purchase securely.</p>
      
      <form id="paymentForm" method="POST">
        <div class="payment-methods">
          <!-- Credit Card -->
          <div>
            <div class="payment-method active" onclick="selectMethod(this)">
              <div class="payment-icon" style="background: #1a1f36; color: white;">CC</div>
              <div class="payment-name">Credit Card</div>
              <div class="payment-radio"></div>
            </div>
            <div class="card-details" style="display: flex;">
              <div class="input-group">
                <label>Cardholder Name</label>
                <input type="text" placeholder="John Doe" value="<?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : ''; ?>" required>
              </div>
              <div class="input-group">
                <label>Card Number</label>
                <input type="text" placeholder="0000 0000 0000 0000" required maxlength="19" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\d{4})(?=\d)/g, '$1 ')">
              </div>
              <div style="display: flex; gap: 16px;">
                <div class="input-group" style="flex: 1;">
                  <label>Expiry Date</label>
                  <input type="text" placeholder="MM/YY" required maxlength="5" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\d{2})(?=\d)/g, '$1/')">
                </div>
                <div class="input-group" style="flex: 1;">
                  <label>CVV</label>
                  <input type="text" placeholder="123" required maxlength="3" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
              </div>
            </div>
          </div>
          
          <!-- Virtual Account -->
          <div>
            <div class="payment-method" onclick="selectMethod(this)">
              <div class="payment-icon" style="background: #e6f2ff; color: #0066cc;">VA</div>
              <div class="payment-name">Bank Virtual Account</div>
              <div class="payment-radio"></div>
            </div>
          </div>
          
          <!-- E-Wallet -->
          <div>
            <div class="payment-method" onclick="selectMethod(this)">
              <div class="payment-icon" style="background: #e6ffe6; color: #009900;">EW</div>
              <div class="payment-name">E-Wallet (GoPay/OVO)</div>
              <div class="payment-radio"></div>
            </div>
          </div>
        </div>
        
        <input type="hidden" name="pay_now" value="1">
      </form>
    </div>
    
    <div class="checkout-right">
      <h3 style="font-family: 'Hammersmith One', sans-serif; font-size: 20px; color: var(--dark); margin-bottom: 24px;">Order Summary</h3>
      
      <div style="max-height: 200px; overflow-y: auto; margin-bottom: 24px;">
        <?php foreach($books as $book): ?>
        <div class="order-item">
          <div class="order-item-title"><?php echo htmlspecialchars($book['title']); ?></div>
          <div class="order-item-price">Rp. <?php echo number_format($book['price'] ?? 0, 0, ',', '.'); ?></div>
        </div>
        <?php endforeach; ?>
      </div>
      
      <div class="divider"></div>
      
      <div class="summary-row">
        <span>Subtotal</span>
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
      
      <button class="btn-pay" onclick="processPayment()">
        Pay Rp. <?php echo number_format($grand_total, 0, ',', '.'); ?>
        <div class="loading-overlay">
          <div class="spinner"></div>
        </div>
      </button>
      
      <div style="text-align: center; margin-top: 16px; font-size: 12px; color: var(--text-muted); display: flex; align-items: center; justify-content: center; gap: 6px;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0110 0v4"></path></svg>
        Payments are secure and encrypted
      </div>
    </div>
  </div>

  <script>
    function selectMethod(el) {
      document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('active'));
      el.classList.add('active');
      // tampilin form detail kalo metode pembayaran dipilih
      document.querySelectorAll('.card-details').forEach(d => d.style.display = 'none');
      const details = el.nextElementSibling;
      if (details && details.classList.contains('card-details')) {
        details.style.display = 'flex';
      }
    }
    
    function processPayment() {
      // validasi input kartu kredit
      const ccActive = document.querySelectorAll('.payment-method')[0].classList.contains('active');
      if (ccActive) {
        const inputs = document.querySelectorAll('.card-details input');
        let valid = true;
        inputs.forEach(i => { if (!i.value) valid = false; });
        if (!valid) {
          alert('Please fill in all credit card details.');
          return;
        }
      }
      
      const btn = document.querySelector('.btn-pay');
      btn.classList.add('loading');
      
      // simulasi loading sebelum submit
      setTimeout(() => {
        document.getElementById('paymentForm').submit();
      }, 2000);
    }
  </script>
</body>
</html>
