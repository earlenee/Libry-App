<?php
// Router untuk Vercel Serverless PHP (Legacy Multi-page App)
$uri = $_SERVER['REQUEST_URI'];

// Pisahkan query parameter agar mendapatkan path file saja
$path = parse_url($uri, PHP_URL_PATH);
$path = ltrim($path, '/');

// Jika root atau index.php, panggil index.php di root
if ($path === '' || $path === 'index.php') {
    require __DIR__ . '/../index.php';
    exit;
}

// Tentukan lokasi file asli di folder parent (root project)
$file = __DIR__ . '/../' . $path;

// Jika file PHP tersebut ada, jalankan file tersebut
if (file_exists($file) && is_file($file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
    require $file;
    exit;
}

// Jika tidak ditemukan, kembalikan 404
http_response_code(404);
echo "<h2>404 Not Found</h2>";
echo "<p>Halaman yang Anda cari tidak ditemukan di server Vercel.</p>";
exit;
?>
