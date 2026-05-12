<?php
// Tentukan judul halaman berdasarkan nama file
$page_title = "Home";
$current_file = basename($_SERVER['PHP_SELF']);
if ($current_file == 'shop.php') $page_title = "Category";
else if ($current_file == 'favourite.php') $page_title = "Favourite";
else if ($current_file == 'cart.php') $page_title = "Cart";
?>
<!-- NAVBAR ATAS (Termasuk Search dan Notif) -->
<nav class="top-navbar">
  <div class="nav-left">
    <div class="menu-toggle" onclick="toggleSidebar()">
      <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16" /></svg>
    </div>
    <span class="nav-title"><?php echo $page_title; ?></span>
  </div>
  <div class="nav-right">
    <!-- Fitur Search -->
    <div class="search-dash">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8" /><path d="m21 21-4.35-4.35" /></svg>
      <input type="text" id="dash-search" placeholder="Search books..." onkeyup="typeof searchBooks === 'function' ? searchBooks(this.value) : null" />
    </div>
    <!-- Fitur Notifikasi -->
    <div class="nav-notif" style="position: relative;" onclick="toggleNotifDropdown(event)">
      <button class="nav-icon-btn">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>
        <span style="position: absolute; top: 4px; right: 4px; width: 8px; height: 8px; background: var(--rust); border-radius: 50%;"></span>
      </button>
      <div id="notif-dropdown" class="profile-dropdown" style="width: 320px; padding: 0; overflow: hidden; right: -10px;">
        <div style="padding: 16px; border-bottom: 1px solid rgba(0,0,0,0.05); font-family: 'Hammersmith One', sans-serif; font-size: 16px; color: var(--dark); display: flex; justify-content: space-between; align-items: center;">
          Notifications
          <span style="font-family: 'Inter', sans-serif; font-size: 12px; color: var(--navy); cursor: pointer; font-weight: 600;">Mark all read</span>
        </div>
        <div style="max-height: 300px; overflow-y: auto;">
          <div style="padding: 16px; border-bottom: 1px solid rgba(0,0,0,0.05); display: flex; gap: 12px; align-items: flex-start; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='rgba(0,0,0,0.02)'" onmouseout="this.style.background='transparent'">
            <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(43, 138, 62, 0.1); color: #2b8a3e; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
              <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </div>
            <div>
              <div style="font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 600; color: var(--dark); margin-bottom: 4px;">Welcome to Libry!</div>
              <div style="font-family: 'Inter', sans-serif; font-size: 13px; color: var(--text-muted); line-height: 1.4;">Explore thousands of books and build your ultimate library today.</div>
              <div style="font-size: 11px; color: var(--text-muted); margin-top: 6px;">Just now</div>
            </div>
          </div>
          <div style="padding: 16px; display: flex; gap: 12px; align-items: flex-start; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='rgba(0,0,0,0.02)'" onmouseout="this.style.background='transparent'">
            <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(245, 159, 0, 0.1); color: #f59f00; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
              <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
              <div style="font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 600; color: var(--dark); margin-bottom: 4px;">Complete your profile</div>
              <div style="font-family: 'Inter', sans-serif; font-size: 13px; color: var(--text-muted); line-height: 1.4;">Add a profile picture and update your personal information to get started.</div>
              <div style="font-size: 11px; color: var(--text-muted); margin-top: 6px;">1 hour ago</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Profil Nav -->
    <div class="nav-profile" style="position: relative;" onclick="toggleProfileDropdown(event)">
      <div class="avatar" style="cursor: pointer;"><?php echo isset($_SESSION['name']) ? strtoupper(substr($_SESSION['name'], 0, 2)) : 'US'; ?></div>
      <div id="profile-dropdown" class="profile-dropdown">
        <a href="profile.php">
          <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          My Profile
        </a>
        <a href="logout.php" style="color: var(--rust);">
          <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4" /><polyline points="16 17 21 12 16 7" /><line x1="21" y1="12" x2="9" y2="12" /></svg>
          Logout
        </a>
      </div>
    </div>
  </div>
</nav>

<script>
function toggleSidebar() {
  const sidebar = document.querySelector('.sidebar');
  if (sidebar) {
    sidebar.classList.toggle('closed');
    if (sidebar.classList.contains('closed')) {
      localStorage.setItem('sidebarState', 'closed');
    } else {
      localStorage.setItem('sidebarState', 'open');
    }
  }
}

function toggleProfileDropdown(e) {
  e.stopPropagation();
  const dropdown = document.getElementById('profile-dropdown');
  const notif = document.getElementById('notif-dropdown');
  if(notif && notif.classList.contains('show')) notif.classList.remove('show');
  dropdown.classList.toggle('show');
}

function toggleNotifDropdown(e) {
  e.stopPropagation();
  const dropdown = document.getElementById('profile-dropdown');
  const notif = document.getElementById('notif-dropdown');
  if(dropdown && dropdown.classList.contains('show')) dropdown.classList.remove('show');
  notif.classList.toggle('show');
}

// Close dropdown when clicking outside
window.addEventListener('click', function() {
  const dropdown = document.getElementById('profile-dropdown');
  if (dropdown && dropdown.classList.contains('show')) {
    dropdown.classList.remove('show');
  }
  const notif = document.getElementById('notif-dropdown');
  if (notif && notif.classList.contains('show')) {
    notif.classList.remove('show');
  }
});

// Load profile picture from localStorage on all pages
document.addEventListener('DOMContentLoaded', () => {
  const savedPic = localStorage.getItem('profilePic');
  if (savedPic) {
    const navAvatar = document.querySelector('.nav-profile .avatar');
    if (navAvatar) {
      navAvatar.style.backgroundImage = `url(${savedPic})`;
      navAvatar.style.backgroundSize = 'cover';
      navAvatar.style.backgroundPosition = 'center';
      navAvatar.style.color = 'transparent';
    }
  }
});
</script>
