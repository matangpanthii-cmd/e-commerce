<?php
/**
 * Admin Guard Middleware
 * Include at the top of every admin view/page.
 * Redirects non-admin users back to login.
 */
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['redirect_after_login'] = BASE_URL . '/admin';
    }
    header("Location: " . BASE_URL . "/login");
    exit;
}
?>
