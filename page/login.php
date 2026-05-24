<?php
session_start();
// kalo udah login langsung redirect ke dashboard
if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - LIBRY</title>
  <link rel="stylesheet" href="../styles.css">
  <style>
    body {
      margin: 0;
      font-family: 'Inter', sans-serif;
      background: #f7f5f0;
      display: flex;
      min-height: 100vh;
    }
    
    .auth-container {
      display: flex;
      width: 100%;
      min-height: 100vh;
      flex-direction: row-reverse;
    }
    
    .auth-left {
      flex: 1;
      background: var(--navy);
      color: white;
      padding: 60px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      position: relative;
      overflow: hidden;
    }
    .auth-left::before {
      content: '';
      position: absolute;
      top: -20%; right: -20%;
      width: 600px; height: 600px;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
      border-radius: 50%;
    }
    .auth-left-content {
      max-width: 480px;
      margin: auto 0;
      z-index: 1;
    }
    .auth-quote {
      font-family: 'Playfair Display', serif;
      font-size: 40px;
      line-height: 1.3;
      margin-bottom: 24px;
    }
    .auth-author {
      font-size: 16px;
      color: rgba(255,255,255,0.7);
    }
    
    .auth-right {
      width: 500px;
      background: white;
      padding: 60px 80px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      position: relative;
    }
    .btn-back {
      position: absolute;
      top: 40px; right: 40px;
      background: none;
      border: none;
      font-size: 14px;
      font-weight: 600;
      color: var(--text-muted);
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: color 0.2s;
    }
    .btn-back:hover { color: var(--dark); }
    
    .auth-logo {
      font-family: 'Hammersmith One', sans-serif;
      font-size: 28px;
      color: var(--navy);
      display: flex;
      align-items: center;
      margin-bottom: 40px;
      cursor: pointer;
      text-decoration: none;
    }
    .auth-logo img { height: 32px; margin: 0 4px; transform: translateY(-4px); }
    
    .auth-title {
      font-family: 'Hammersmith One', sans-serif;
      font-size: 36px;
      color: var(--dark);
      margin-bottom: 8px;
      margin-top: 0;
    }
    .auth-subtitle {
      color: var(--text-muted);
      margin-bottom: 40px;
      font-size: 15px;
    }
    
    .form-group { margin-bottom: 24px; }
    .form-group label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      color: var(--dark);
      margin-bottom: 8px;
    }
    .input-wrapper {
      position: relative;
      display: flex;
      align-items: center;
    }
    .form-group input {
      width: 100%;
      padding: 14px 16px;
      border: 1px solid rgba(0,0,0,0.1);
      border-radius: 12px;
      font-family: 'Inter', sans-serif;
      font-size: 15px;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
      box-sizing: border-box;
      background: #fdfcfb;
    }
    .form-group input:focus {
      border-color: var(--navy);
      box-shadow: 0 0 0 4px rgba(30, 54, 85, 0.05);
      background: white;
    }
    .eye-btn {
      position: absolute;
      right: 16px;
      background: none;
      border: none;
      cursor: pointer;
      color: var(--text-muted);
      padding: 4px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .eye-btn:hover { color: var(--dark); }
    
    .helper-text {
      font-size: 12px;
      color: var(--text-muted);
      margin-top: 8px;
      display: block;
    }
    
    .btn-submit {
      width: 100%;
      padding: 16px;
      background: var(--navy);
      color: white;
      border: none;
      border-radius: 999px;
      font-family: 'Hammersmith One', sans-serif;
      font-size: 16px;
      cursor: pointer;
      margin-top: 16px;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(30, 54, 85, 0.2);
    }
    
    .auth-footer {
      margin-top: 32px;
      text-align: center;
      font-size: 14px;
      color: var(--text-muted);
    }
    .auth-footer a {
      color: var(--rust);
      font-weight: 600;
      text-decoration: none;
    }
    .auth-footer a:hover { text-decoration: underline; }
    
    @media (max-width: 900px) {
      .auth-left { display: none; }
      .auth-right { width: 100%; padding: 40px 24px; }
      .auth-container { justify-content: center; }
    }
  </style>
</head>
<body>

  <div class="auth-container">
    <div class="auth-left">
      <div>
        <a href="../index.php" class="auth-logo" style="color: white;">
          LI<img src="../asset/logo.png" alt="logo" style="filter: brightness(0) invert(1);" />RY
        </a>
      </div>
      <div class="auth-left-content">
        <div class="auth-quote">"A room without books is like a body without a soul."</div>
        <div class="auth-author">— Marcus Tullius Cicero</div>
      </div>
      <div style="font-size: 12px; color: rgba(255,255,255,0.5);">© 2026 LIBRY Platform. All rights reserved.</div>
    </div>
    
    <div class="auth-right">
      <button class="btn-back" onclick="window.location.href='../index.php'">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        Back to Home
      </button>
      
      <h1 class="auth-title">Welcome back</h1>
      <p class="auth-subtitle">Please enter your details to sign in.</p>
      
      <form action="../actions/auth_signin.php" method="POST">
        <div class="form-group">
          <label>Username</label>
          <input type="text" name="username" placeholder="Enter your username" required autofocus />
        </div>
        
        <div class="form-group">
          <label>Password</label>
          <div class="input-wrapper">
            <input type="password" id="password" name="password" placeholder="Enter your password" minlength="8" required />
            <button type="button" class="eye-btn" onclick="togglePassword('password', 'eyeIcon')">
              <svg id="eyeIcon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            </button>
          </div>
          <span class="helper-text">Minimum 8 characters required.</span>
        </div>
        
        <button type="submit" class="btn-submit">Sign In</button>
      </form>
      
      <div class="auth-footer">
        Don't have an account? <a href="register.php">Create Member</a>
      </div>
    </div>
  </div>

  <script>
    function togglePassword(inputId, iconId) {
      const input = document.getElementById(inputId);
      const icon = document.getElementById(iconId);
      if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
      } else {
        input.type = 'password';
        icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
      }
    }
  </script>
</body>
</html>