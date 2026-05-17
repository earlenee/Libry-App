<?php
// simpan pengaturan tema & ukuran font ke database
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

require_once '../config/koneksi.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['theme']) || isset($data['font_size'])) {
    $user_id = $_SESSION['user_id'];
    
    $theme = isset($data['theme']) ? $data['theme'] : (isset($_SESSION['theme']) ? $_SESSION['theme'] : 'light');
    $font_size = isset($data['font_size']) ? intval($data['font_size']) : (isset($_SESSION['font_size']) ? $_SESSION['font_size'] : 20);

    $stmt = $conn->prepare("UPDATE users SET theme = ?, font_size = ? WHERE id = ?");
    $stmt->bind_param("sii", $theme, $font_size, $user_id);
    
    if ($stmt->execute()) {
        // update juga di session biar langsung berubah
        $_SESSION['theme'] = $theme;
        $_SESSION['font_size'] = $font_size;
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update settings']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
}
?>
