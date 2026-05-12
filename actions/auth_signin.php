<?php
session_start();
require '../config/koneksi.php'; // Panggil file koneksi

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Mencari user berdasarkan username (atau bisa juga dikembangkan mencocokkan email)
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika user ditemukan di database
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Cek apakah password cocok dengan password hased di database
        if (password_verify($password, $user['password'])) {
            // Berhasil login: Simpan data penting ke sesi (Session) server
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];

            // Pindahkan halaman (refresh) kembali ke page/dashboard.php
            echo "<script>alert('Login Berhasil! Selamat datang ".$user['name']."'); window.location.href='../page/dashboard.php';</script>";
        } else {
            echo "<script>alert('Password yang Anda masukkan salah!'); window.history.back();</script>";
        }
    } else {
        // Jika username tidak ditemukan
        echo "<script>alert('Username tidak ditemukan! Silakan daftar terlebih dahulu.'); window.history.back();</script>";
    }
}
?>