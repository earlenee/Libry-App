<?php
session_start();
if(isset($_SESSION['user_id'])) {
    header("Location: page/dashboard.php");
    exit;
}
require 'config/koneksi.php';
$books = [];
if (isset($conn) && !$conn->connect_error) {
    $res = $conn->query("SELECT books.*, categories.name as cat_name FROM books LEFT JOIN categories ON books.category_id = categories.id ORDER BY id DESC LIMIT 4");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $books[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LIBRY - Library Website</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <!-- HALAMAN BERANDA (LANDING) -->
  <div class="page active" id="page-landing" style="display: block;">
    <nav class="nav-landing">
      <img class="logo-img" src="asset/logo.png" alt="Libry logo" onclick="window.location.href='index.php'" style="cursor:pointer;"/>
      <div class="nav-links">
        <a href="page/login.php">Home</a>
        <a href="page/login.php">Category</a>
        <a href="page/about.php">About Us</a>
      </div>
      <div class="nav-right">
        <button class="btn-login" onclick="window.location.href='page/login.php'">Login</button>
      </div>
    </nav>

    <div class="hero">
      <div class="hero-left">
        <div class="hero-logo-big">
          LI<span class="mark-wrap"><img src="asset/logo.png" alt="B mark" /></span>RY
        </div>
        <button class="btn-member" onclick="document.getElementById('explore-section').scrollIntoView({behavior: 'smooth'})">
          Explore Books <span class="btn-arrow" style="font-family: Arial, sans-serif; font-size: 20px;">→</span>
        </button>
        <p class="hero-desc">This library website provides organized learning materials from various subjects for easy and quick access.</p>
      </div>
      <div class="hero-right">
        <img src="asset/dashboardawal.png" alt="Book categories" onclick="document.getElementById('explore-section').scrollIntoView({behavior: 'smooth'})" style="cursor:pointer;" />
      </div>
    </div>
  </div>
  
  <!-- FEATURES SECTION -->
  <div style="padding: 100px 5%; background: #fdfdfd; text-align: center; width: 100%; box-sizing: border-box;">
    <h2 style="font-family: 'Playfair Display', serif; font-size: 36px; color: var(--navy); margin-bottom: 16px;">Why Choose LIBRY?</h2>
    <p style="color: var(--text-muted); font-size: 18px; max-width: 600px; margin: 0 auto 60px;">We offer more than just reading. We offer an experience designed for true book lovers.</p>
    
    <div style="display: flex; gap: 40px; justify-content: center; flex-wrap: wrap; width: 100%;">
      <!-- Feature 1 -->
      <div style="flex: 1; min-width: 280px; max-width: 400px; padding: 40px 30px; background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='none'">
        <div style="width: 64px; height: 64px; background: rgba(45, 74, 107, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; color: var(--navy);">
          <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
        </div>
        <h3 style="font-family: 'Hammersmith One', sans-serif; font-size: 22px; color: var(--dark); margin-bottom: 12px;">Vast Collection</h3>
        <p style="color: var(--text-muted); line-height: 1.6; font-size: 15px;">Access thousands of books ranging from academic literature to best-selling novels, updated regularly.</p>
      </div>
      <!-- Feature 2 -->
      <div style="flex: 1; min-width: 280px; max-width: 400px; padding: 40px 30px; background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='none'">
        <div style="width: 64px; height: 64px; background: rgba(184, 74, 46, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; color: var(--rust);">
          <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5z"></path><path d="M2 17l10 5 10-5"></path><path d="M2 12l10 5 10-5"></path></svg>
        </div>
        <h3 style="font-family: 'Hammersmith One', sans-serif; font-size: 22px; color: var(--dark); margin-bottom: 12px;">Premium UI</h3>
        <p style="color: var(--text-muted); line-height: 1.6; font-size: 15px;">Enjoy a clean, distraction-free reading environment designed with modern aesthetics and comfort in mind.</p>
      </div>
      <!-- Feature 3 -->
      <div style="flex: 1; min-width: 280px; max-width: 400px; padding: 40px 30px; background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='none'">
        <div style="width: 64px; height: 64px; background: rgba(212, 168, 67, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; color: var(--gold);">
          <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
        </div>
        <h3 style="font-family: 'Hammersmith One', sans-serif; font-size: 22px; color: var(--dark); margin-bottom: 12px;">Loyalty Rewards</h3>
        <p style="color: var(--text-muted); line-height: 1.6; font-size: 15px;">Earn points as you read and upgrade your membership tier to unlock exclusive discounts and badges.</p>
      </div>
    </div>
  </div>

  <!-- EXPLORE SECTION -->
  <div id="explore-section" style="padding: 100px 5%; background: var(--cream); text-align: center; width: 100%; box-sizing: border-box;">
    <h2 style="font-family: 'Playfair Display', serif; font-size: 40px; color: var(--navy); margin-bottom: 16px;">Discover Our Collection</h2>
    <p style="color: var(--text-muted); font-size: 18px; max-width: 600px; margin: 0 auto 60px;">Explore a curated selection of books from various categories before you join.</p>
    
    <div style="display: flex; gap: 40px; justify-content: center; flex-wrap: wrap; margin-bottom: 60px; width: 100%;">
      <?php foreach($books as $book): ?>
      <div style="background: white; border-radius: 20px; padding: 30px; width: 300px; flex: 1; min-width: 250px; max-width: 320px; text-align: left; transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); cursor: pointer; box-shadow: 0 4px 12px rgba(0,0,0,0.04);" onmouseover="this.style.transform='translateY(-8px) scale(1.02)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.08)';" onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.04)'" onclick="window.location.href='page/login.php'">
        <img src="asset/<?php echo htmlspecialchars($book['cover_image'] ?? 'logo.png'); ?>" alt="Cover" style="width: 100%; height: 380px; object-fit: cover; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 8px 24px rgba(0,0,0,0.12);" onerror="this.src='asset/logo.png'">
        <div style="font-family: 'Inter', sans-serif; font-size: 13px; color: var(--rust); font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px;"><?php echo htmlspecialchars($book['cat_name'] ?? 'General'); ?></div>
        <div style="font-family: 'Hammersmith One', sans-serif; font-size: 20px; color: var(--dark); line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?php echo htmlspecialchars($book['title']); ?></div>
      </div>
      <?php endforeach; ?>
    </div>
    
    <button class="btn-login" style="background: var(--navy); color: white; padding: 20px 60px; font-size: 18px; border-radius: 999px; font-family: 'Hammersmith One', sans-serif; transition: transform 0.2s, background 0.2s; border: none; cursor: pointer;" onmouseover="this.style.transform='scale(1.05)'; this.style.background='#1e3655';" onmouseout="this.style.transform='none'; this.style.background='var(--navy)';" onclick="window.location.href='page/register.php'">Join Now to Read</button>
  </div>

  <!-- STATS / CTA SECTION -->
  <div style="background: var(--navy); color: white; padding: 120px 5%; text-align: center; position: relative; overflow: hidden; width: 100%; box-sizing: border-box;">
    <!-- Abstract Background Elements -->
    <div style="position: absolute; top: -50%; left: -10%; width: 500px; height: 500px; background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0) 70%); border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -50%; right: -10%; width: 600px; height: 600px; background: radial-gradient(circle, rgba(212, 168, 67, 0.08) 0%, rgba(255,255,255,0) 70%); border-radius: 50%;"></div>
    
    <div style="position: relative; z-index: 1; max-width: 900px; margin: 0 auto;">
      <h2 style="font-family: 'Playfair Display', serif; font-size: 56px; margin-bottom: 24px; line-height: 1.1;">Ready to Transform Your Reading Experience?</h2>
      <p style="font-size: 20px; color: rgba(255,255,255,0.8); margin-bottom: 60px; line-height: 1.6;">Join thousands of readers who have already made LIBRY their daily companion. Explore knowledge without boundaries.</p>
      
      <div style="display: flex; gap: 60px; justify-content: center; flex-wrap: wrap;">
        <div style="min-width: 150px; margin-bottom: 20px;">
          <div style="font-family: 'Hammersmith One', sans-serif; font-size: 56px; color: var(--gold); margin-bottom: 12px;">10k+</div>
          <div style="font-size: 16px; text-transform: uppercase; letter-spacing: 2px; color: rgba(255,255,255,0.7); font-weight: 600;">Active Readers</div>
        </div>
        <div style="min-width: 150px; margin-bottom: 20px;">
          <div style="font-family: 'Hammersmith One', sans-serif; font-size: 56px; color: var(--gold); margin-bottom: 12px;">500+</div>
          <div style="font-size: 16px; text-transform: uppercase; letter-spacing: 2px; color: rgba(255,255,255,0.7); font-weight: 600;">Premium Books</div>
        </div>
        <div style="min-width: 150px; margin-bottom: 20px;">
          <div style="font-family: 'Hammersmith One', sans-serif; font-size: 56px; color: var(--gold); margin-bottom: 12px;">4.9</div>
          <div style="font-size: 16px; text-transform: uppercase; letter-spacing: 2px; color: rgba(255,255,255,0.7); font-weight: 600;">User Rating</div>
        </div>
      </div>
    </div>
  </div>

  <!-- FOOTER -->
  <footer style="background: #1a2533; color: white; padding: 100px 5% 40px; font-family: 'Inter', sans-serif; width: 100%; box-sizing: border-box;">
    <div style="width: 100%; display: flex; flex-wrap: wrap; gap: 80px; justify-content: space-between; margin-bottom: 80px;">
      <div style="flex: 2; min-width: 300px;">
        <div style="font-family: 'Hammersmith One', sans-serif; font-size: 36px; display: flex; align-items: center; margin-bottom: 24px;">
          LI<img src="asset/logo.png" alt="Libry logo" style="height: 40px; margin: 0 4px; filter: brightness(0) invert(1);" />RY
        </div>
        <p style="color: rgba(255,255,255,0.6); line-height: 1.8; font-size: 16px; max-width: 400px;">Providing organized learning materials from various subjects for easy and quick access. Read anytime, anywhere.</p>
      </div>
      
      <div style="flex: 1; min-width: 150px;">
        <h4 style="font-size: 18px; font-weight: 700; margin-bottom: 28px; color: white;">Platform</h4>
        <div style="display: flex; flex-direction: column; gap: 20px;">
          <a href="page/login.php" style="color: rgba(255,255,255,0.6); text-decoration: none; font-size: 16px; transition: color 0.2s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">Browse Collection</a>
          <a href="page/about.php" style="color: rgba(255,255,255,0.6); text-decoration: none; font-size: 16px; transition: color 0.2s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">Membership Tiers</a>
          <a href="page/about.php" style="color: rgba(255,255,255,0.6); text-decoration: none; font-size: 16px; transition: color 0.2s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">About Us</a>
        </div>
      </div>
      
      <div style="flex: 1; min-width: 150px;">
        <h4 style="font-size: 18px; font-weight: 700; margin-bottom: 28px; color: white;">Support</h4>
        <div style="display: flex; flex-direction: column; gap: 20px;">
          <a href="#" style="color: rgba(255,255,255,0.6); text-decoration: none; font-size: 16px; transition: color 0.2s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">Help Center</a>
          <a href="#" style="color: rgba(255,255,255,0.6); text-decoration: none; font-size: 16px; transition: color 0.2s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">Terms of Service</a>
          <a href="#" style="color: rgba(255,255,255,0.6); text-decoration: none; font-size: 16px; transition: color 0.2s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">Privacy Policy</a>
        </div>
      </div>
    </div>
    
    <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 40px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; width: 100%;">
      <div style="color: rgba(255,255,255,0.5); font-size: 15px;">© 2026 LIBRY Platform. All rights reserved.</div>
      <div style="display: flex; gap: 20px;">
        <!-- Social Icons -->
        <a href="#" style="color: rgba(255,255,255,0.5); transition: color 0.2s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.5)'"><svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
        <a href="#" style="color: rgba(255,255,255,0.5); transition: color 0.2s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.5)'"><svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
      </div>
    </div>
  </footer>
</body>
</html>
