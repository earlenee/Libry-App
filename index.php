<?php
session_start();
if(isset($_SESSION['user_id'])) {
    header("Location: page/dashboard.php");
    exit;
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
        <button class="btn-member" onclick="window.location.href='page/register.php'">
          Member Number <span class="btn-arrow">?</span>
        </button>
        <p class="hero-desc">This library website provides organized learning materials from various subjects for easy and quick access.</p>
      </div>
      <div class="hero-right">
        <img src="asset/dashboardawal.png" alt="Book categories" onclick="window.location.href='page/login.php'" style="cursor:pointer;" />
      </div>
    </div>
  </div>
</body>
</html>
