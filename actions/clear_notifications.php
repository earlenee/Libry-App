<?php
// hapus notifikasi dari session (pake ajax)
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['clear_all']) && $data['clear_all'] === true) {
    // hapus semua notif
    $_SESSION['notifications'] = [];
    echo json_encode(['status' => 'success']);
} elseif (isset($data['remove_index'])) {
    // hapus notif tertentu berdasarkan index
    $index = intval($data['remove_index']);
    if (isset($_SESSION['notifications'][$index])) {
        array_splice($_SESSION['notifications'], $index, 1);
    }
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
