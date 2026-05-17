<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar" id="main-sidebar">
  <script>
    if (localStorage.getItem('sidebarState') === 'closed') {
      document.getElementById('main-sidebar').classList.add('closed');
      // matiin transisi biar ga kedip pas pertama load
      document.getElementById('main-sidebar').style.transition = 'none';
      setTimeout(() => document.getElementById('main-sidebar').style.transition = '', 50);
    }
  </script>
  <div class="sidebar-logo" onclick="window.location.href='../index.php'">
    <span class="sidebar-logo-text">LI<img src="../asset/logo.png" alt="logo" />RY</span>
  </div>
  <nav class="sidebar-nav">
    <a class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>" onclick="window.location.href='dashboard.php'">
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" /><polyline points="9 22 9 12 15 12 15 22" /></svg>Home
    </a>
    <a class="<?php echo ($current_page == 'shop.php') ? 'active' : ''; ?>" onclick="window.location.href='shop.php'">
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="3" width="5" height="8" rx="1" /><rect x="9" y="3" width="5" height="5" rx="1" /><rect x="2" y="13" width="5" height="8" rx="1" /><rect x="9" y="10" width="5" height="11" rx="1" /><rect x="16" y="3" width="6" height="18" rx="1" /></svg>Category
    </a>
    <a class="<?php echo ($current_page == 'favourite.php') ? 'active' : ''; ?>" onclick="window.location.href='favourite.php'">
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" /></svg>Favourite
    </a>
    <a class="<?php echo ($current_page == 'cart.php') ? 'active' : ''; ?>" onclick="window.location.href='cart.php'">
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1" /><circle cx="20" cy="21" r="1" /><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6" /></svg>Cart
    </a>
    <a class="sidebar-contact" onclick="window.location.href='logout.php'">
      <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4" /><polyline points="16 17 21 12 16 7" /><line x1="21" y1="12" x2="9" y2="12" /></svg>Logout
    </a>
  </nav>
</aside>
