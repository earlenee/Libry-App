<?php
// file koneksi ke database dengan dukungan Environment Variables (untuk Vercel)
$host = getenv('DB_HOST') ?: "localhost";
$user = getenv('DB_USER') ?: "root";
$pass = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : "";
$db   = getenv('DB_NAME') ?: "libry_db";
$port = getenv('DB_PORT') ?: 3306;

$conn = new mysqli($host, $user, $pass, $db, $port);

// cek koneksi biar ga error
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>