<?php
$host = "localhost";
$user = "root";       // Username default MySQL di Laragon/XAMPP
$pass = "";           // Password default biasanya kosong
$db   = "libry_db";   // Nama database yang akan kita buat

// Membuat koneksi ke database
$conn = new mysqli($host, $user, $pass, $db);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>