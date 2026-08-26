<?php
/**
 * Upload Helper — Handles product image uploads to public/uploads/
 */

define('UPLOAD_DIR', BASE_PATH . '/public/uploads/');
define('UPLOAD_URL_BASE', BASE_URL . '/uploads/');

/**
 * Handles a single image file upload.
 * Returns the public URL string on success, or false on failure.
 * Sets $error message on failure.
 */
function handleImageUpload(array $file, string &$error): string|false
{
    $allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $max_size      = 5 * 1024 * 1024; // 5 MB

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error = 'Upload error (code: ' . $file['error'] . ')';
        return false;
    }

    if ($file['size'] > $max_size) {
        $error = 'File is too large. Maximum size is 5 MB.';
        return false;
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, $allowed_types)) {
        $error = 'Invalid file type. Only JPG, PNG, WebP, and GIF are allowed.';
        return false;
    }

    // Create directory if it doesn't exist
    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }

    // Generate unique filename
    $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('product_', true) . '.' . strtolower($ext);
    $dest     = UPLOAD_DIR . $filename;

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        $error = 'Failed to save the uploaded file. Check directory permissions.';
        return false;
    }

    return UPLOAD_URL_BASE . $filename;
}

/**
 * Deletes a local uploaded file by its URL path.
 */
function deleteUploadedFile(string $url_path): void
{
    if (strpos($url_path, UPLOAD_URL_BASE) === 0) {
        $filename  = basename($url_path);
        $full_path = UPLOAD_DIR . $filename;
        if (file_exists($full_path)) {
            unlink($full_path);
        }
    }
}
?>
