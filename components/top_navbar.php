<?php
// tentuin judul halaman dari nama file
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
    <!-- Search Global -->
    <div class="search-dash" style="position: relative;">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8" /><path d="m21 21-4.35-4.35" /></svg>
      <input type="text" id="dash-search" placeholder="Search books..." autocomplete="off" oninput="globalSearch(this.value)" onfocus="globalSearch(this.value)" />
      <div id="search-dropdown" style="display:none; position:absolute; top:calc(100% + 8px); left:0; right:0; min-width:360px; background:white; border-radius:14px; box-shadow:0 12px 40px rgba(0,0,0,0.18); z-index:9999; overflow:hidden; max-height:420px; overflow-y:auto;">
        <div id="search-results" style="padding:6px 0;"></div>
      </div>
    </div>
<?php
  // Hapus notifikasi 'Complete your profile' secara total agar tidak pernah muncul lagi
  if (isset($_SESSION['notifications'])) {
      $_SESSION['notifications'] = array_filter($_SESSION['notifications'], function($notif) {
          return strtolower($notif['title']) !== 'complete your profile';
      });
      $_SESSION['notifications'] = array_values($_SESSION['notifications']);
  }
  $notifications = isset($_SESSION['notifications']) ? $_SESSION['notifications'] : [];
  $notif_count = count($notifications);
?>
    <!-- Fitur Notifikasi -->
    <div class="nav-notif" style="position: relative;" onclick="toggleNotifDropdown(event)">
      <button class="nav-icon-btn">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>
        <?php if ($notif_count > 0): ?>
        <span id="notif-badge" style="position: absolute; top: 2px; right: 2px; min-width: 18px; height: 18px; background: var(--rust); border-radius: 50%; font-family: 'Inter', sans-serif; font-size: 10px; font-weight: 700; color: white; display: flex; align-items: center; justify-content: center; padding: 0 4px;"><?php echo $notif_count; ?></span>
        <?php endif; ?>
      </button>
      <div id="notif-dropdown" class="profile-dropdown" style="width: 340px; padding: 0; overflow: hidden; right: -10px;">
        <div style="padding: 16px; border-bottom: 1px solid rgba(0,0,0,0.05); font-family: 'Hammersmith One', sans-serif; font-size: 16px; color: var(--dark); display: flex; justify-content: space-between; align-items: center;">
          Notifications
          <?php if ($notif_count > 0): ?>
          <span onclick="event.stopPropagation(); clearAllNotifications()" style="font-family: 'Inter', sans-serif; font-size: 12px; color: var(--rust); cursor: pointer; font-weight: 600;">Clear all</span>
          <?php endif; ?>
        </div>
        <div style="max-height: 350px; overflow-y: auto;" id="notif-list">
          <?php if ($notif_count > 0): ?>
            <?php foreach($notifications as $i => $notif): ?>
            <div class="notif-item" data-index="<?php echo $i; ?>" style="padding: 14px 16px; border-bottom: 1px solid rgba(0,0,0,0.04); display: flex; gap: 12px; align-items: flex-start; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='rgba(0,0,0,0.02)'" onmouseout="this.style.background='transparent'">
              <div style="width: 36px; height: 36px; border-radius: 50%; background: <?php echo htmlspecialchars($notif['color']); ?>20; color: <?php echo htmlspecialchars($notif['color']); ?>; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <?php if ($notif['icon'] === 'check'): ?>
                  <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                <?php elseif ($notif['icon'] === 'cart'): ?>
                  <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/></svg>
                <?php else: ?>
                  <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <?php endif; ?>
              </div>
              <div style="flex: 1; min-width: 0;">
                <div style="font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 600; color: var(--dark); margin-bottom: 4px;"><?php echo htmlspecialchars($notif['title']); ?></div>
                <div style="font-family: 'Inter', sans-serif; font-size: 13px; color: var(--text-muted); line-height: 1.4; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;"><?php echo htmlspecialchars($notif['message']); ?></div>
                <div style="font-size: 11px; color: var(--text-muted); margin-top: 6px;"><?php echo htmlspecialchars($notif['time']); ?></div>
              </div>
              <button onclick="event.stopPropagation(); removeNotification(<?php echo $i; ?>, this)" style="background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px; opacity: 0.5; transition: opacity 0.2s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.5'" title="Remove">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
              </button>
            </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div style="padding: 40px 20px; text-align: center;">
              <svg width="40" height="40" fill="none" stroke="var(--text-muted)" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom: 12px; opacity: 0.4;"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>
              <p style="font-family: 'Inter', sans-serif; font-size: 13px; color: var(--text-muted); margin: 0;">No notifications</p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <!-- Profil Nav -->
    <div class="nav-profile" style="position: relative;" onclick="toggleProfileDropdown(event)">
      <?php
        $nav_initials = isset($_SESSION['name']) ? strtoupper(substr($_SESSION['name'], 0, 2)) : 'US';
        $nav_pic = isset($_SESSION['profile_pic']) && !empty($_SESSION['profile_pic']) ? '../asset/' . $_SESSION['profile_pic'] : '';
      ?>
      <div class="avatar" style="cursor: pointer; <?php if($nav_pic) echo "background-image: url('$nav_pic'); background-size: cover; background-position: center; color: transparent;"; ?>"><?php echo $nav_initials; ?></div>
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

// tutup dropdown kalo klik di luar
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

function clearAllNotifications() {
  fetch('../actions/clear_notifications.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ clear_all: true })
  }).then(res => res.json()).then(data => {
    if (data.status === 'success') {
      const list = document.getElementById('notif-list');
      list.innerHTML = '<div style="padding: 40px 20px; text-align: center;">' +
        '<svg width="40" height="40" fill="none" stroke="var(--text-muted)" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom: 12px; opacity: 0.4;"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>' +
        '<p style="font-family: Inter, sans-serif; font-size: 13px; color: var(--text-muted); margin: 0;">No notifications</p></div>';
      const badge = document.getElementById('notif-badge');
      if (badge) badge.remove();
    }
  });
}

function removeNotification(index, btn) {
  fetch('../actions/clear_notifications.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ remove_index: index })
  }).then(res => res.json()).then(data => {
    if (data.status === 'success') {
      const item = btn.closest('.notif-item');
      item.style.transition = 'all 0.3s ease';
      item.style.opacity = '0';
      item.style.transform = 'translateX(20px)';
      setTimeout(() => {
        item.remove();
        const remaining = document.querySelectorAll('.notif-item');
        if (remaining.length === 0) {
          const list = document.getElementById('notif-list');
          list.innerHTML = '<div style="padding: 40px 20px; text-align: center;">' +
            '<svg width="40" height="40" fill="none" stroke="var(--text-muted)" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom: 12px; opacity: 0.4;"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>' +
            '<p style="font-family: Inter, sans-serif; font-size: 13px; color: var(--text-muted); margin: 0;">No notifications</p></div>';
          const badge = document.getElementById('notif-badge');
          if (badge) badge.remove();
        } else {
          const badge = document.getElementById('notif-badge');
          if (badge) badge.textContent = remaining.length;
        }
      }, 300);
    }
  });
}
// search global - cari buku dari database
let searchTimeout;
function globalSearch(query) {
  const dropdown = document.getElementById('search-dropdown');
  const results = document.getElementById('search-results');
  
  query = query.trim();
  if (query.length < 1) {
    dropdown.style.display = 'none';
    return;
  }
  
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    fetch('../actions/search_books.php?q=' + encodeURIComponent(query))
      .then(r => r.json())
      .then(books => {
        if (books.length === 0) {
          results.innerHTML = `
            <div style="padding:32px 20px; text-align:center;">
              <svg width="36" height="36" fill="none" stroke="#bbb" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:10px;opacity:0.5;"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
              <p style="font-family:'Inter',sans-serif; font-size:13px; color:#999; margin:0;">No books found for "<b>${query}</b>"</p>
            </div>`;
          dropdown.style.display = 'block';
          return;
        }
        
        let html = '';
        books.forEach(book => {
          const coverUrl = book.cover_image ? '../asset/' + book.cover_image : '../asset/logo.png';
          const priceText = book.price > 0 ? 'Rp. ' + parseInt(book.price).toLocaleString('id-ID') : 'Free';
          const ownedBadge = book.owned 
            ? '<span style="font-size:10px;padding:2px 8px;background:#2b8a3e20;color:#2b8a3e;border-radius:99px;font-weight:700;">Owned</span>' 
            : '<span style="font-size:10px;padding:2px 8px;background:var(--rust-light,#f5e6e0);color:var(--rust);border-radius:99px;font-weight:700;">' + priceText + '</span>';
          
          html += `
            <a href="detail.php?id=${book.id}" style="display:flex; gap:12px; padding:10px 16px; text-decoration:none; color:inherit; transition:background 0.15s; align-items:center;" 
               onmouseover="this.style.background='rgba(0,0,0,0.03)'" onmouseout="this.style.background='transparent'">
              <img src="${coverUrl}" onerror="this.src='../asset/logo.png'" style="width:40px; height:56px; object-fit:cover; border-radius:6px; box-shadow:0 2px 8px rgba(0,0,0,0.1); flex-shrink:0;" />
              <div style="flex:1; min-width:0;">
                <div style="font-family:'Inter',sans-serif; font-size:14px; font-weight:600; color:var(--dark); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${book.title}</div>
                <div style="font-family:'Inter',sans-serif; font-size:12px; color:#888; margin-top:2px;">${book.author} · ${book.category}</div>
              </div>
              <div style="flex-shrink:0;">${ownedBadge}</div>
            </a>`;
        });
        
        // tambahin link "View All" kalo hasil banyak
        if (books.length >= 8) {
          html += `
            <div style="padding:10px 16px; text-align:center; border-top:1px solid rgba(0,0,0,0.06);">
              <a href="shop.php" style="font-family:'Inter',sans-serif; font-size:13px; color:var(--rust); font-weight:600; text-decoration:none;">Browse all books →</a>
            </div>`;
        }
        
        results.innerHTML = html;
        dropdown.style.display = 'block';
      })
      .catch(() => {
        dropdown.style.display = 'none';
      });
  }, 250);
}

// tutup search dropdown kalo klik di luar
document.addEventListener('click', function(e) {
  const searchContainer = document.querySelector('.search-dash');
  const dropdown = document.getElementById('search-dropdown');
  if (dropdown && searchContainer && !searchContainer.contains(e.target)) {
    dropdown.style.display = 'none';
  }
});

// tutup search dropdown pas pencet Escape
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    const dropdown = document.getElementById('search-dropdown');
    if (dropdown) dropdown.style.display = 'none';
    document.getElementById('dash-search').blur();
  }
});

</script>
