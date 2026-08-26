<?php
session_start();

// Define base path for includes
define('BASE_PATH', dirname(__DIR__));

// Load app config (BASE_URL etc.)
require_once BASE_PATH . '/src/config/app.php';

// =====================================================
// Router — get clean path without query string
// =====================================================
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$script_base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
if ($script_base !== '' && strpos($request_uri, $script_base) === 0) {
    $request_uri = substr($request_uri, strlen($script_base));
}
$request_uri = '/' . ltrim($request_uri, '/');

// =====================================================
// STOREFRONT ROUTES
// =====================================================

if ($request_uri === '/' || $request_uri === '/home') {
    require BASE_PATH . '/src/views/home.php';

} elseif ($request_uri === '/products') {
    require BASE_PATH . '/src/views/products/plp.php';

} elseif (preg_match('/^\/product\/([a-z0-9\-]+)$/', $request_uri, $matches)) {
    $product_slug = $matches[1];
    require BASE_PATH . '/src/views/products/pdp.php';

} elseif ($request_uri === '/cart/add') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $product_id = $_POST['product_id'] ?? '';
        $color      = $_POST['color'] ?? '';
        $size       = $_POST['size'] ?? '';
        $quantity   = (int)($_POST['quantity'] ?? 1);

        if ($product_id) {
            if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
            $cart_key = $product_id . '_' . $color . '_' . $size;
            if (isset($_SESSION['cart'][$cart_key])) {
                $_SESSION['cart'][$cart_key]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$cart_key] = [
                    'product_id' => $product_id,
                    'color'      => $color,
                    'size'       => $size,
                    'quantity'   => $quantity,
                ];
            }
        }
    }
    header("Location: " . BASE_URL . "/cart");
    exit;

} elseif ($request_uri === '/cart/update') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cart_key = $_POST['cart_key'] ?? '';
        $action   = $_POST['action'] ?? '';
        if ($cart_key && isset($_SESSION['cart'][$cart_key])) {
            if ($action === 'increase') {
                $_SESSION['cart'][$cart_key]['quantity']++;
            } elseif ($action === 'decrease' && $_SESSION['cart'][$cart_key]['quantity'] > 1) {
                $_SESSION['cart'][$cart_key]['quantity']--;
            }
        }
    }
    header("Location: " . BASE_URL . "/cart");
    exit;

} elseif ($request_uri === '/cart/remove') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cart_key = $_POST['cart_key'] ?? '';
        if ($cart_key && isset($_SESSION['cart'][$cart_key])) {
            unset($_SESSION['cart'][$cart_key]);
        }
    }
    header("Location: " . BASE_URL . "/cart");
    exit;

} elseif ($request_uri === '/cart') {
    require BASE_PATH . '/src/views/checkout/cart.php';

} elseif ($request_uri === '/checkout/success') {
    require BASE_PATH . '/src/views/checkout/success.php';

} elseif ($request_uri === '/checkout') {
    require BASE_PATH . '/src/views/checkout/checkout.php';

} elseif ($request_uri === '/login') {
    require BASE_PATH . '/src/views/auth/login.php';

} elseif ($request_uri === '/register') {
    require BASE_PATH . '/src/views/auth/register.php';

} elseif ($request_uri === '/logout') {
    session_destroy();
    header("Location: " . BASE_URL . "/");
    exit;

// =====================================================
// ADMIN ROUTES
// =====================================================

} elseif ($request_uri === '/admin' || $request_uri === '/admin/') {
    require BASE_PATH . '/src/views/admin/dashboard.php';

} elseif ($request_uri === '/admin/products') {
    require BASE_PATH . '/src/views/admin/products/index.php';

} elseif ($request_uri === '/admin/products/create') {
    require BASE_PATH . '/src/views/admin/products/create.php';

} elseif (preg_match('/^\/admin\/products\/(\d+)\/edit$/', $request_uri)) {
    require BASE_PATH . '/src/views/admin/products/edit.php';

} elseif ($request_uri === '/admin/categories') {
    require BASE_PATH . '/src/views/admin/categories/index.php';

} elseif ($request_uri === '/admin/orders') {
    require BASE_PATH . '/src/views/admin/orders/index.php';

} elseif (preg_match('/^\/admin\/orders\/(\d+)$/', $request_uri)) {
    require BASE_PATH . '/src/views/admin/orders/detail.php';

} elseif ($request_uri === '/admin/users') {
    require BASE_PATH . '/src/views/admin/users/index.php';

// =====================================================
// 404
// =====================================================
} else {
    http_response_code(404);
    require BASE_PATH . '/src/views/404.php';
}
?>
