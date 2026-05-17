<?php
// file koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "libry_db";

$conn = new mysqli($host, $user, $pass, $db);

// cek koneksi biar ga error
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>