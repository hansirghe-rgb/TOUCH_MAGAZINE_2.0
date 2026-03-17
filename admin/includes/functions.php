<?php
function upload_secure_file($file_array, $target_dir, $allowed_types) {
    if (!isset($file_array) || $file_array['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'No valid file uploaded. Error code: ' . ($file_array['error'] ?? 'None')];
    }
    
    $file_type = mime_content_type($file_array['tmp_name']);
    if (!in_array($file_type, $allowed_types)) {
        return ['success' => false, 'error' => 'Invalid file type. Allowed: ' . implode(", ", $allowed_types)];
    }

    // Ensure target dir exists
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    
    // Clean filename
    $filename = basename($file_array["name"]);
    $filename = preg_replace("/[^a-zA-Z0-9.-]/", "_", $filename);
    $new_filename = time() . '_' . $filename; // Prefix timestamp
    $target_file = rtrim($target_dir, '/') . '/' . $new_filename;
    
    if (move_uploaded_file($file_array["tmp_name"], $target_file)) {
        return ['success' => true, 'path' => $target_file]; // store relative path securely
    } else {
        return ['success' => false, 'error' => 'Failed to move uploaded file.'];
    }
}
?>
