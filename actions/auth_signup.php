<?php
session_start();
require '../config/koneksi.php'; // Panggil file koneksi database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data yang dikirimkan dari form Sign Up
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'] ?? '';

    if ($password !== $password_confirm) {
        echo "<script>alert('Gagal Registrasi: Password tidak cocok (mismatch)!'); window.history.back();</script>";
        exit;
    }

    // Enkripsi password menggunakan BCRYPT untuk keamanan
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Cek apakah username atau email sudah ada di database
    $cek_query = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt_cek = $conn->prepare($cek_query);
    $stmt_cek->bind_param("ss", $username, $email);
    $stmt_cek->execute();
    $result_cek = $stmt_cek->get_result();

    if ($result_cek->num_rows > 0) {
        echo "<script>alert('Gagal Registrasi: Username atau Email sudah terdaftar!'); window.history.back();</script>";
    } else {
        // Jika belum ada, masukkan data user baru ke database
        $query = "INSERT INTO users (name, username, email, password, role) VALUES (?, ?, ?, ?, 'member')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $name, $username, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "<script>alert('Registrasi berhasil! Silakan Log In.'); window.location.href='../page/login.php';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan teknis. Coba lagi.'); window.history.back();</script>";
        }
    }
}
?>