<?php
// proses login user
session_start();
require '../config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // cari user di database pake prepared statement
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // cocokkin password
        if (password_verify($password, $user['password'])) {
            // simpen data user ke session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['profile_pic'] = $user['profile_pic'] ?? '';
            $_SESSION['theme'] = $user['theme'] ?? 'light';
            $_SESSION['font_size'] = $user['font_size'] ?? 20;

            // hitung streak harian buat fitur reading streak
            $today = date('Y-m-d');
            $last_active = $user['last_active_date'];
            $streak = isset($user['streak_count']) ? intval($user['streak_count']) : 0;
            
            if ($last_active != $today) {
                if ($last_active == date('Y-m-d', strtotime('-1 day'))) {
                    $streak++;
                } else {
                    $streak = 1;
                }
                
                $update_stmt = $conn->prepare("UPDATE users SET streak_count = ?, last_active_date = ? WHERE id = ?");
                $update_stmt->bind_param("isi", $streak, $today, $user['id']);
                $update_stmt->execute();
            }
            
            $_SESSION['streak_count'] = $streak;

            // notifikasi welcome pas login
            $_SESSION['notifications'] = [];
            $_SESSION['notifications'][] = [
                'icon' => 'check',
                'color' => '#2b8a3e',
                'title' => 'Welcome, ' . $user['name'] . '!',
                'message' => 'You have successfully logged in. Enjoy reading and exploring the Libry book collection.',
                'time' => 'Just now'
            ];

            echo "<script>alert('Login successful! Welcome ".$user['name']."'); window.location.href='../page/dashboard.php';</script>";
        } else {
            echo "<script>alert('The password you entered is incorrect!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Username not found! Please register first.'); window.history.back();</script>";
    }
}
?>