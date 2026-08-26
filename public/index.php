<?php
session_start();
// Front Controller (Router)
// -------------------------

// Define base path for includes
define('BASE_PATH', dirname(__DIR__));

// Simple Router - get clean path without query string
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Strip the subfolder base from the URI so routing works cleanly
$script_base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
if ($script_base !== '' && strpos($request_uri, $script_base) === 0) {
    $request_uri = substr($request_uri, strlen($script_base));
}

// Ensure clean uri always starts with /
$request_uri = '/' . ltrim($request_uri, '/');

// Routing logic using if/elseif (required for regex routes)
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
        $color = $_POST['color'] ?? '';
        $size = $_POST['size'] ?? '';
        $quantity = (int)($_POST['quantity'] ?? 1);
        
        if ($product_id) {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            $cart_key = $product_id . '_' . $color . '_' . $size;
            if (isset($_SESSION['cart'][$cart_key])) {
                $_SESSION['cart'][$cart_key]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$cart_key] = [
                    'product_id' => $product_id,
                    'color' => $color,
                    'size' => $size,
                    'quantity' => $quantity
                ];
            }
        }
    }
    header("Location: /pj2/public/cart");
    exit;

} elseif ($request_uri === '/cart/update') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cart_key = $_POST['cart_key'] ?? '';
        $action = $_POST['action'] ?? '';
        
        if ($cart_key && isset($_SESSION['cart'][$cart_key])) {
            if ($action === 'increase') {
                $_SESSION['cart'][$cart_key]['quantity']++;
            } elseif ($action === 'decrease' && $_SESSION['cart'][$cart_key]['quantity'] > 1) {
                $_SESSION['cart'][$cart_key]['quantity']--;
            }
        }
    }
    header("Location: /pj2/public/cart");
    exit;

} elseif ($request_uri === '/cart/remove') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cart_key = $_POST['cart_key'] ?? '';
        if ($cart_key && isset($_SESSION['cart'][$cart_key])) {
            unset($_SESSION['cart'][$cart_key]);
        }
    }
    header("Location: /pj2/public/cart");
    exit;

} elseif ($request_uri === '/cart') {
    require BASE_PATH . '/src/views/checkout/cart.php';

} elseif ($request_uri === '/checkout') {
    require BASE_PATH . '/src/views/checkout/checkout.php';

} elseif ($request_uri === '/login') {
    require BASE_PATH . '/src/views/auth/login.php';

} elseif ($request_uri === '/register') {
    require BASE_PATH . '/src/views/auth/register.php';

} elseif ($request_uri === '/logout') {
    session_destroy();
    header("Location: /pj2/public/");
    exit;

} else {
    http_response_code(404);
    require BASE_PATH . '/src/views/404.php';
}
?>
