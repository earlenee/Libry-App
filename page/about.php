<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us - LIBRY</title>
  <link rel="stylesheet" href="../styles.css">
  <style>
    body {
      background: #f7f5f0;
      margin: 0;
      font-family: 'Inter', sans-serif;
      color: var(--dark);
    }
    
    .about-nav {
      position: fixed;
      top: 0; left: 0; right: 0;
      height: 80px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 40px;
      background: rgba(247, 245, 240, 0.9);
      backdrop-filter: blur(10px);
      z-index: 100;
      border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    .about-nav-links a {
      margin-left: 32px;
      text-decoration: none;
      color: var(--dark);
      font-weight: 600;
      transition: color 0.2s;
    }
    .about-nav-links a:hover { color: var(--navy); }
    
    .hero-section {
      padding: 160px 24px 80px;
      text-align: center;
      max-width: 800px;
      margin: 0 auto;
    }
    .hero-title {
      font-family: 'Playfair Display', serif;
      font-size: 56px;
      font-weight: 800;
      line-height: 1.1;
      margin-bottom: 24px;
      color: var(--navy);
    }
    .hero-subtitle {
      font-size: 18px;
      line-height: 1.6;
      color: var(--text-muted);
      margin-bottom: 40px;
    }
    
    .membership-section {
      padding: 80px 24px;
      background: white;
      text-align: center;
    }
    .membership-title {
      font-family: 'Hammersmith One', sans-serif;
      font-size: 36px;
      margin-bottom: 16px;
    }
    .membership-desc {
      font-size: 16px;
      color: var(--text-muted);
      max-width: 600px;
      margin: 0 auto 60px;
    }
    
    .tiers-container {
      display: flex;
      justify-content: center;
      gap: 32px;
      max-width: 1000px;
      margin: 0 auto;
      flex-wrap: wrap;
    }
    
    .tier-card {
      background: #fdfdfd;
      border: 1px solid rgba(0,0,0,0.08);
      border-radius: 24px;
      padding: 40px;
      flex: 1;
      min-width: 300px;
      position: relative;
      overflow: hidden;
      transition: transform 0.3s, box-shadow 0.3s;
      text-align: left;
    }
    .tier-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 20px 40px rgba(0,0,0,0.06);
    }
    
    .tier-silver {
      border-top: 6px solid #C0C0C0;
    }
    .tier-gold {
      border-top: 6px solid #FFD700;
      background: #fffdf5;
    }
    
    .tier-badge {
      display: inline-block;
      padding: 8px 16px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 24px;
    }
    .badge-silver { background: rgba(192,192,192,0.2); color: #666; }
    .badge-gold { background: rgba(255,215,0,0.2); color: #b8860b; }
    
    .tier-name {
      font-family: 'Hammersmith One', sans-serif;
      font-size: 32px;
      margin-bottom: 8px;
    }
    .tier-req {
      font-size: 15px;
      font-weight: 600;
      color: var(--rust);
      margin-bottom: 24px;
    }
    
    .tier-features {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    .tier-features li {
      margin-bottom: 16px;
      display: flex;
      align-items: flex-start;
      gap: 12px;
      font-size: 15px;
      color: var(--text-muted);
    }
    
    .cta-section {
      padding: 100px 24px;
      text-align: center;
      background: var(--navy);
      color: white;
    }
    .cta-btn {
      display: inline-block;
      padding: 16px 40px;
      background: white;
      color: var(--navy);
      font-family: 'Hammersmith One', sans-serif;
      font-size: 18px;
      text-decoration: none;
      border-radius: 999px;
      margin-top: 32px;
      transition: transform 0.2s;
    }
    .cta-btn:hover {
      transform: scale(1.05);
    }
  </style>
</head>
<body>

  <nav class="about-nav">
    <img src="../asset/logo.png" alt="LIBRY" style="height: 48px; cursor: pointer;" onclick="window.location.href='../index.php'">
    <div class="about-nav-links">
      <a href="../index.php">Home</a>
      <a href="login.php">Category</a>
      <a href="login.php" style="padding: 10px 24px; background: var(--navy); color: white; border-radius: 999px;">Login</a>
    </div>
  </nav>

  <section class="hero-section">
    <h1 class="hero-title">Empowering Your Reading Journey</h1>
    <p class="hero-subtitle">At LIBRY, we believe that every book opens a new world. We are dedicated to providing a seamless, premium digital library experience where organizing, reading, and discovering books feels effortless and beautiful.</p>
  </section>

  <section class="membership-section">
    <h2 class="membership-title">Libry Membership Tiers</h2>
    <p class="membership-desc">Read more, earn more. Our loyalty program rewards your passion for reading with exclusive perks, discounts, and badges.</p>
    
    <div class="tiers-container">
      
      <!-- Silver Tier -->
      <div class="tier-card tier-silver">
        <div class="tier-badge badge-silver">Silver Member</div>
        <h3 class="tier-name">Silver</h3>
        <div class="tier-req">Unlock by purchasing 5 books</div>
        <ul class="tier-features">
          <li>
            <svg width="20" height="20" fill="none" stroke="#2b8a3e" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
            Special Silver profile badge
          </li>
          <li>
            <svg width="20" height="20" fill="none" stroke="#2b8a3e" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
            10% discount on all future book purchases
          </li>
          <li>
            <svg width="20" height="20" fill="none" stroke="#2b8a3e" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
            Early access to new arrivals
          </li>
        </ul>
      </div>

      <!-- Gold Tier -->
      <div class="tier-card tier-gold">
        <div class="tier-badge badge-gold">Gold Member</div>
        <h3 class="tier-name">Gold</h3>
        <div class="tier-req">Unlock by purchasing 20 books</div>
        <ul class="tier-features">
          <li>
            <svg width="20" height="20" fill="none" stroke="#b8860b" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
            Exclusive Gold profile badge
          </li>
          <li>
            <svg width="20" height="20" fill="none" stroke="#b8860b" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
            25% discount on all future book purchases
          </li>
          <li>
            <svg width="20" height="20" fill="none" stroke="#b8860b" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
            Zero tax on checkout
          </li>
          <li>
            <svg width="20" height="20" fill="none" stroke="#b8860b" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
            Priority customer support
          </li>
        </ul>
      </div>

    </div>
  </section>

  <section class="cta-section">
    <h2 style="font-family: 'Playfair Display', serif; font-size: 40px; margin-bottom: 16px;">Start building your library today</h2>
    <p style="color: rgba(255,255,255,0.8); font-size: 18px;">Join thousands of readers and upgrade your reading experience.</p>
    <a href="register.php" class="cta-btn">Become a Member</a>
  </section>

</body>
</html>
