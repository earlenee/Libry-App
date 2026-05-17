<?php
// hapus sesi terus redirect ke halaman awal
session_start();
session_destroy();
echo "<script>alert('You have been successfully logged out!'); window.location.href='../index.php';</script>";
exit;
?>