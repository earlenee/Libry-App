<?php
// upload foto profil user
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

require_once '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
    $user_id = $_SESSION['user_id'];
    $file = $_FILES['profile_image'];
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['status' => 'error', 'message' => 'File upload error.']);
        exit;
    }
    
    // validasi tipe file yg boleh diupload
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $file_info = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($file_info, $file['tmp_name']);
    finfo_close($file_info);
    
    if (!in_array($mime_type, $allowed_types)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid file type. Only JPG, PNG, GIF, and WebP are allowed.']);
        exit;
    }
    
    // bikin nama file unik biar ga bentrok
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    if (empty($extension)) {
        $parts = explode('/', $mime_type);
        $extension = $parts[1] ?? 'jpg';
    }
    $filename = 'avatar_' . $user_id . '_' . time() . '.' . $extension;
    $upload_dir = '../asset/avatars/';
    
    // buat folder kalo belum ada
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $destination = $upload_dir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        $db_path = 'avatars/' . $filename;
        
        // update path foto di database
        $stmt = $conn->prepare("UPDATE users SET profile_pic = ? WHERE id = ?");
        $stmt->bind_param("si", $db_path, $user_id);
        
        if ($stmt->execute()) {
            $_SESSION['profile_pic'] = $db_path;
            echo json_encode([
                'status' => 'success', 
                'message' => 'Profile picture updated successfully.',
                'path' => $db_path
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database update failed.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
