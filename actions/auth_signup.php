<?php
// proses registrasi user baru
session_start();
require '../config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'] ?? '';

    if ($password !== $password_confirm) {
        echo "<script>alert('Registration failed: Passwords do not match!'); window.history.back();</script>";
        exit;
    }

    // hash password pake bcrypt biar aman
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // cek dulu username/email udah kepake atau belum
    $cek_query = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt_cek = $conn->prepare($cek_query);
    $stmt_cek->bind_param("ss", $username, $email);
    $stmt_cek->execute();
    $result_cek = $stmt_cek->get_result();

    if ($result_cek->num_rows > 0) {
        echo "<script>alert('Registration failed: Username or Email already exists!'); window.history.back();</script>";
    } else {
        // masukin data user baru ke tabel users
        $query = "INSERT INTO users (name, username, email, password, role) VALUES (?, ?, ?, ?, 'member')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $name, $username, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful! Please log in.'); window.location.href='../page/login.php';</script>";
        } else {
            echo "<script>alert('A technical error occurred. Please try again.'); window.history.back();</script>";
        }
    }
}
?>