<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['purchased'])) { $_SESSION['purchased'] = []; }

// Handle Edit Profile Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_profile') {
    $_SESSION['name'] = $_POST['name'];
    $_SESSION['email'] = $_POST['email'];
    header("Location: profile.php?updated=1");
    exit;
}

$user_name = isset($_SESSION['name']) ? $_SESSION['name'] : 'User';
$user_email = isset($_SESSION['email']) ? $_SESSION['email'] : 'Update your email in Edit Profile';
$initials = strtoupper(substr($user_name, 0, 2));

// Fetch purchased books
$purchased_books = [];
if (!empty($_SESSION['purchased']) && file_exists('../config/koneksi.php')) {
    require_once '../config/koneksi.php';
    if (isset($conn) && !$conn->connect_error) {
        $ids = implode(',', array_map('intval', $_SESSION['purchased']));
        $result = $conn->query("SELECT * FROM books WHERE id IN ($ids)");
        if ($result) {
            while($row = $result->fetch_assoc()) {
                $purchased_books[] = $row;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile - LIBRY</title>
  <link rel="stylesheet" href="../styles.css">
  <style>
    #page-profile { display: flex !important; } .page:not(#page-profile) { display: none !important; }
    
    .profile-page-content {
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 100%;
    }
    
    .purchased-section { 
      margin-top: 20px; 
      width: 100%; 
      max-width: 900px; 
    }
    
    .purchased-title { 
      font-family: 'Hammersmith One', sans-serif; 
      font-size: 24px; 
      color: var(--dark); 
      margin-bottom: 24px; 
      text-align: left; 
    }
    
    /* Edit Profile Modal specific styles */
    .edit-profile-form { 
      display: flex; 
      flex-direction: column; 
      gap: 16px; 
      width: 100%; 
    }
    
    .edit-profile-form input { 
      padding: 14px 16px; 
      border: 1px solid rgba(0,0,0,0.1); 
      background: white;
      border-radius: 8px; 
      font-family: 'Inter', sans-serif; 
      font-size: 15px; 
      outline: none;
      transition: border-color 0.2s;
    }
    
    .edit-profile-form input:focus {
      border-color: var(--dark);
    }
    
    .edit-profile-form button { 
      margin-top: 8px; 
    }
    
    .modal-content-profile { 
      flex-direction: column; 
      gap: 20px; 
      align-items: stretch; 
      max-width: 450px; 
      padding: 40px; 
    }
    
    .modal-content-profile h2 { 
      font-family: 'Hammersmith One', sans-serif; 
      font-size: 28px; 
      color: var(--dark); 
      margin-bottom: 4px; 
      text-align: center;
    }
    
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

    /* Stats container */
    .stats-container {
      display: flex;
      gap: 20px;
      margin-top: 32px;
      margin-bottom: 24px;
      width: 100%;
      max-width: 900px;
      flex-wrap: wrap;
    }
    .stat-card {
      flex: 1;
      min-width: 200px;
      background: white;
      border-radius: 16px;
      padding: 24px;
      text-align: center;
      border: 1px solid rgba(0,0,0,0.05);
      box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }
    .stat-number {
      font-family: 'Hammersmith One', sans-serif;
      font-size: 36px;
      color: var(--navy);
      margin-bottom: 8px;
    }
    .stat-label {
      font-family: 'Inter', sans-serif;
      font-size: 14px;
      color: var(--text-muted);
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    /* Profile Avatar Editor */
    .avatar-wrapper {
      position: relative;
      margin: 0 auto 16px;
      width: fit-content;
    }
    .profile-avatar-large {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      background-color: var(--rust);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 48px;
      font-weight: bold;
      box-shadow: 0 8px 24px rgba(184, 74, 46, 0.3);
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }
    .avatar-edit-btn {
      position: absolute;
      bottom: 0;
      right: 0;
      background: var(--navy);
      color: white;
      border: 3px solid white;
      border-radius: 50%;
      width: 36px;
      height: 36px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
      transition: transform 0.2s;
    }
    .avatar-edit-btn:hover {
      transform: scale(1.1);
    }
  </style>
</head>
<body>
  <?php if(isset($_GET['updated']) && $_GET['updated'] == '1'): ?>
  <div class="toast-success" id="successToast">Profile updated successfully!</div>
  <?php endif; ?>

  <?php if(isset($_GET['checkout_success']) && $_GET['checkout_success'] == '1'): ?>
  <div class="toast-success" id="successToast" style="background: var(--navy);">Payment successful! Books added to your library.</div>
  <?php endif; ?>

  <div class="page sidebar-layout active" id="page-profile">
    <?php include '../components/sidebar.php'; ?>
    <main class="main-content">
      <?php include '../components/top_navbar.php'; ?>

      <div class="profile-page-content">
        <div class="welcome-banner" style="text-align: center; width: 100%;">
          <h1 class="dashboard-title" style="font-family: 'Hammersmith One', sans-serif; font-size: 32px; color: var(--dark); margin-bottom: 8px;">My Profile</h1>
          <p class="welcome-subtext">Manage your personal information and reading activity.</p>
        </div>

        <div class="profile-container" style="width: 100%; max-width: 900px; text-align: center; padding: 40px; background: white; border-radius: 16px; border: 1px solid rgba(0,0,0,0.05);">
          
          <div class="avatar-wrapper">
            <div class="profile-avatar-large" id="profileAvatarLarge">
              <span id="profileAvatarInitials"><?php echo $initials; ?></span>
            </div>
            <input type="file" id="profileImageInput" style="display: none;" accept="image/*" onchange="handleImageUpload(event)">
            <div class="avatar-edit-btn" onclick="document.getElementById('profileImageInput').click()" title="Change Profile Picture">
              <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
            </div>
          </div>
          
          <div class="profile-name" style="font-size: 24px;"><?php echo htmlspecialchars($user_name); ?></div>
          <div class="profile-email" style="color: var(--text-muted); margin-bottom: 12px;"><?php echo htmlspecialchars($user_email); ?></div>
          <?php
            $owned_books = count($_SESSION['purchased'] ?? []);
            if ($owned_books >= 20) {
                echo '<div style="display:inline-block; padding:6px 16px; background:rgba(255,215,0,0.15); color:#b8860b; border-radius:999px; font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:1px; border:1px solid rgba(255,215,0,0.3);">Gold Member</div>';
            } elseif ($owned_books >= 5) {
                echo '<div style="display:inline-block; padding:6px 16px; background:rgba(192,192,192,0.2); color:#666; border-radius:999px; font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:1px; border:1px solid rgba(192,192,192,0.4);">Silver Member</div>';
            } else {
                echo '<div style="display:inline-block; padding:6px 16px; background:rgba(0,0,0,0.05); color:var(--text-muted); border-radius:999px; font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:1px; border:1px solid rgba(0,0,0,0.1);">Regular Member</div>';
            }
          ?>
          
          <div class="profile-actions" style="margin-top: 24px;">
            <button class="btn-profile-primary" onclick="openEditProfile()">Edit Profile</button>
            <button class="btn-profile-secondary" onclick="alert('Feature coming soon: Change Password')">Change Password</button>
          </div>
        </div>

        <div class="stats-container">
          <div class="stat-card">
            <div class="stat-number"><?php echo count($purchased_books); ?></div>
            <div class="stat-label">Books Owned</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">3</div>
            <div class="stat-label">Day Streak</div>
          </div>
          <div class="stat-card">
            <div class="stat-number" style="color: var(--rust);">Gold</div>
            <div class="stat-label">Member Status</div>
          </div>
        </div>

        <div class="purchased-section">
          <h2 class="purchased-title">My Purchased Books</h2>
          <div class="category-grid" style="padding-top: 10px;">
            <?php if (!empty($purchased_books)): ?>
                <?php foreach($purchased_books as $book): ?>
                <div class="cat-card" onclick="window.location.href='read.php?id=<?php echo $book['id']; ?>'">
                  <div class="cat-card-img-wrap">
                    <img src="../asset/<?php echo htmlspecialchars($book['cover_image'] ?? ''); ?>" alt="Book Cover" onerror="this.src='../asset/logo.png'">
                  </div>
                  <div class="cat-card-info">
                    <div class="cat-card-title"><?php echo htmlspecialchars($book['title']); ?></div>
                    <div class="cat-card-cat" style="color: #2b8a3e; font-weight: 600;">✓ Purchased</div>
                  </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="grid-column: 1 / -1; background: white; padding: 40px; border-radius: 16px; text-align: center; border: 1px solid rgba(0,0,0,0.05);">
                  <p style="color: var(--text-muted); font-family: 'Inter', sans-serif; margin-bottom: 16px;">You haven't purchased any books yet.</p>
                  <button class="btn-profile-primary" style="display: inline-block; padding: 10px 24px;" onclick="window.location.href='shop.php'">Explore Books</button>
                </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </main>
  </div>

  <!-- Edit Profile Modal -->
  <div class="modal-overlay" id="editProfileModal">
    <div class="modal-content modal-content-profile">
      <button class="modal-close" type="button" onclick="closeEditProfile()">&times;</button>
      <h2>Edit Profile</h2>
      <form class="edit-profile-form" method="POST" action="profile.php">
        <input type="hidden" name="action" value="edit_profile">
        <input type="text" name="name" value="<?php echo htmlspecialchars($user_name); ?>" required placeholder="Full Name">
        <input type="email" name="email" value="<?php echo htmlspecialchars($user_email); ?>" required placeholder="Email Address">
        <button type="submit" class="btn-profile-primary">Save Changes</button>
      </form>
    </div>
  </div>

  <script>
    function openEditProfile() {
      const modal = document.getElementById('editProfileModal');
      modal.classList.add('active');
    }
    
    function closeEditProfile() {
      const modal = document.getElementById('editProfileModal');
      modal.classList.remove('active');
    }

    // Close modal on outside click
    window.onclick = function(event) {
      const modal = document.getElementById('editProfileModal');
      if (event.target == modal) {
        closeEditProfile();
      }
    }

    function handleImageUpload(event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const dataUrl = e.target.result;
          localStorage.setItem('profilePic', dataUrl);
          applyProfilePic(dataUrl);
          
          // Show toast
          const toast = document.getElementById('successToast');
          if (toast) {
            toast.textContent = "Profile picture updated!";
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
          }
        }
        reader.readAsDataURL(file);
      }
    }

    function applyProfilePic(dataUrl) {
      const avatarLarge = document.getElementById('profileAvatarLarge');
      const avatarInitials = document.getElementById('profileAvatarInitials');
      
      if (avatarLarge && avatarInitials) {
        avatarLarge.style.backgroundImage = `url(${dataUrl})`;
        avatarInitials.style.display = 'none';
      }
      
      // Update top navbar avatar if it exists
      const navAvatar = document.querySelector('.nav-profile .avatar');
      if (navAvatar) {
        navAvatar.style.backgroundImage = `url(${dataUrl})`;
        navAvatar.style.backgroundSize = 'cover';
        navAvatar.style.backgroundPosition = 'center';
        navAvatar.style.color = 'transparent';
      }
    }

    // Handle toast success and load profile pic
    document.addEventListener('DOMContentLoaded', () => {
      // Load saved profile pic
      const savedPic = localStorage.getItem('profilePic');
      if (savedPic) {
        applyProfilePic(savedPic);
      }

      const toast = document.getElementById('successToast');
      if (toast && toast.textContent.trim() !== '') {
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
