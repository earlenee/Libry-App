<?php
// simpan progress baca & highlight ke database
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

require_once '../config/koneksi.php';

$raw_input = file_get_contents('php://input');
$data = json_decode($raw_input, true);

if (isset($data['book_id'])) {
    $user_id = $_SESSION['user_id'];
    $book_id = intval($data['book_id']);
    
    $progress = isset($data['progress']) ? intval($data['progress']) : null;
    $html = isset($data['highlighted_html']) ? $data['highlighted_html'] : null;

    // cek udah ada datanya belum di tabel user_book_data
    $stmt = $conn->prepare("SELECT id, progress_percent, highlighted_html FROM user_book_data WHERE user_id = ? AND book_id = ?");
    $stmt->bind_param("ii", $user_id, $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // update yg udah ada, progress cuma bisa naik ga bisa turun
        $row = $result->fetch_assoc();
        $new_progress = $progress !== null ? max($progress, $row['progress_percent']) : $row['progress_percent'];
        $new_html = $html !== null ? $html : $row['highlighted_html'];

        // paksa updated_at = NOW() biar urutan "terakhir dibaca" bener
        $update_stmt = $conn->prepare("UPDATE user_book_data SET progress_percent = ?, highlighted_html = ?, updated_at = NOW() WHERE id = ?");
        $update_stmt->bind_param("isi", $new_progress, $new_html, $row['id']);
        if ($update_stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update data']);
        }
    } else {
        // buat record baru kalo belum ada
        $new_progress = $progress !== null ? $progress : 0;
        $insert_stmt = $conn->prepare("INSERT INTO user_book_data (user_id, book_id, progress_percent, highlighted_html, updated_at) VALUES (?, ?, ?, ?, NOW())");
        $insert_stmt->bind_param("iiis", $user_id, $book_id, $new_progress, $html);
        if ($insert_stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to insert data']);
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
}
?>
