<?php
// admin/auth.php - Session and Authentication Security for Admin Panel

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_admin_logged_in() {
    return isset($_SESSION['admin_user_id']) && !empty($_SESSION['admin_user_id']);
}

function require_admin_auth() {
    if (!is_admin_logged_in()) {
        header("Location: /admin/login.php");
        exit;
    }
}

/**
 * Custom file uploader helper
 */
function upload_file($file_key, $upload_dir, $allowed_exts = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'mp3']) {
    if (!isset($_FILES[$file_key]) || $_FILES[$file_key]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $file = $_FILES[$file_key];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed_exts)) {
        return false;
    }

    $new_filename = md5(uniqid(microtime(), true)) . '.' . $ext;
    $target_path = $upload_dir . '/' . $new_filename;

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        return $new_filename;
    }

    return false;
}
