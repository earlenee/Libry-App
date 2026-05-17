<?php
// endpoint search buku global, dipanggil pake ajax
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error']);
    exit;
}

require_once '../config/koneksi.php';

$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if (strlen($query) < 1) {
    echo json_encode([]);
    exit;
}

// cari buku berdasarkan judul, author, atau kategori
$search = '%' . $query . '%';
$stmt = $conn->prepare("SELECT b.id, b.title, b.author, b.cover_image, b.price, COALESCE(c.name, 'General') as category FROM books b LEFT JOIN categories c ON b.category_id = c.id WHERE b.title LIKE ? OR b.author LIKE ? OR c.name LIKE ? ORDER BY b.title ASC LIMIT 8");
$stmt->bind_param("sss", $search, $search, $search);
$stmt->execute();
$result = $stmt->get_result();

$books = [];
while ($row = $result->fetch_assoc()) {
    // cek udah dibeli belum
    $own = $conn->prepare("SELECT id FROM purchases WHERE user_id = ? AND book_id = ?");
    $own->bind_param("ii", $_SESSION['user_id'], $row['id']);
    $own->execute();
    $row['owned'] = $own->get_result()->num_rows > 0;
    $books[] = $row;
}

echo json_encode($books);
?>
