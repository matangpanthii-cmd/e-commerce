<?php
ob_start();
require_once BASE_PATH . '/src/models/Product.php';
require_once BASE_PATH . '/src/models/Order.php';

// ---- Auth Guard ----
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = BASE_URL . '/checkout';
    header("Location: " . BASE_URL . "/login");
    exit;
}

// ---- Cart Check ----
if (empty($_SESSION['cart'])) {
    header("Location: " . BASE_URL . "/cart");
    exit;
}

$productModel = new Product();
$orderModel = new Order();

// Build cart items with real prices
$cart_items = [];
$subtotal = 0;
foreach ($_SESSION['cart'] as $cart_key => $item) {
    $p = $productModel->getProductById($item['product_id']);
    if (!$p)
        continue;
    $price = (float) $p['price'];
    $line = $price * $item['quantity'];
    $subtotal += $line;
    $cart_items[] = [
        'cart_key' => $cart_key,
        'product_id' => $item['product_id'],
        'name' => $p['name'],
        'slug' => $p['slug'],
        'color' => $item['color'],
        'size' => $item['size'],
        'quantity' => $item['quantity'],
        'price' => $price,
        'line_total' => $line,
        'image' => $p['images'][0]['image_url'] ?? '',
    ];
}
$tax = $subtotal * 0.08;
$total = $subtotal + $tax;

$errors = [];
$form = [];

// ---- Handle POST — Place Order ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form['shipping_name'] = trim($_POST['shipping_name'] ?? '');
    $form['shipping_address'] = trim($_POST['shipping_address'] ?? '');
    $form['shipping_phone'] = trim($_POST['shipping_phone'] ?? '');

    if (empty($form['shipping_name']))
        $errors[] = 'Full name is required.';
    if (empty($form['shipping_address']))
        $errors[] = 'Address is required.';
    if (empty($form['shipping_phone']))
        $errors[] = 'Phone number is required.';

    if (empty($errors)) {
        // Create order
        $order_id = $orderModel->createOrder(
            $_SESSION['user_id'],
            $total,
            $form['shipping_name'],
            $form['shipping_address'],
            $form['shipping_phone']
        );

        if ($order_id) {
            // Insert order items
            $items_data = [];
            foreach ($cart_items as $ci) {
                $items_data[] = [
                    'product_id' => $ci['product_id'],
                    'variant_id' => null,
                    'quantity' => $ci['quantity'],
                    'price' => $ci['price'],
                ];
            }
            $orderModel->createOrderItems($order_id, $items_data);

            // Clear cart
            unset($_SESSION['cart']);

            // Redirect to success
            header("Location: " . BASE_URL . "/checkout/success?order_id=" . $order_id);
            exit;
        } else {
            $errors[] = 'Could not place order. Please try again.';
        }
    }
}
?>

<div class="bg-PRAIRAVEE-cream border-b border-gray-200">
    <div class="container mx-auto px-6 lg:px-12 py-8">
        <h1 class="text-3xl font-bold tracking-widest uppercase">Checkout</h1>
    </div>
</div>

<div class="container mx-auto px-6 lg:px-12 py-12">

    <?php if (!empty($errors)): ?>
        <div class="mb-8 bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-md text-sm">
            <ul class="list-disc pl-4 space-y-1">
                <?php foreach ($errors as $e): ?>
                    <li>
                        <?php echo htmlspecialchars($e); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/checkout" id="checkout-form">
        <div class="flex flex-col lg:flex-row gap-12">

            <!-- Left: Shipping Form -->
            <div class="w-full lg:w-3/5">
                <h2 class="text-xl font-bold mb-6 uppercase tracking-wider">Shipping Information</h2>

                <div class="bg-white border border-gray-100 rounded-sm p-8 space-y-6">
                    <div>
                        <label for="shipping_name" class="block text-sm font-medium text-gray-700 mb-1">Full
                            Name</label>
                        <input type="text" id="shipping_name" name="shipping_name" required
                            value="<?php echo htmlspecialchars($form['shipping_name'] ?? $_SESSION['user_name'] ?? ''); ?>"
                            class="w-full px-4 py-3 border border-gray-300 rounded-sm text-sm focus:outline-none focus:ring-2 focus:ring-PRAIRAVEE-green transition">
                    </div>
                    <div>
                        <label for="shipping_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone
                            Number</label>
                        <input type="tel" id="shipping_phone" name="shipping_phone" required
                            value="<?php echo htmlspecialchars($form['shipping_phone'] ?? ''); ?>"
                            placeholder="e.g. 081-234-5678"
                            class="w-full px-4 py-3 border border-gray-300 rounded-sm text-sm focus:outline-none focus:ring-2 focus:ring-PRAIRAVEE-green transition">
                    </div>
                    <div>
                        <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-1">Shipping
                            Address</label>
                        <textarea id="shipping_address" name="shipping_address" required rows="4"
                            placeholder="Street address, City, Province, Postal Code"
                            class="w-full px-4 py-3 border border-gray-300 rounded-sm text-sm focus:outline-none focus:ring-2 focus:ring-PRAIRAVEE-green transition resize-none"><?php echo htmlspecialchars($form['shipping_address'] ?? ''); ?></textarea>
                    </div>
                </div>

                <!-- Mock Payment Section -->
                <h2 class="text-xl font-bold mt-10 mb-6 uppercase tracking-wider">Payment</h2>
                <div class="bg-white border border-gray-100 rounded-sm p-8">
                    <div
                        class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-md text-sm mb-6 flex items-start space-x-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 mt-0.5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span><strong>Demo Mode:</strong> This is a simulated payment. Click "Place Order" to confirm
                            without real charges.</span>
                    </div>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Card Number (Demo)</label>
                            <input type="text" value="4242 4242 4242 4242" disabled
                                class="w-full px-4 py-3 border border-gray-200 rounded-sm text-sm bg-gray-50 text-gray-400 cursor-not-allowed">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Expiry (Demo)</label>
                                <input type="text" value="12/28" disabled
                                    class="w-full px-4 py-3 border border-gray-200 rounded-sm text-sm bg-gray-50 text-gray-400 cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">CVV (Demo)</label>
                                <input type="text" value="123" disabled
                                    class="w-full px-4 py-3 border border-gray-200 rounded-sm text-sm bg-gray-50 text-gray-400 cursor-not-allowed">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Order Summary -->
            <div class="w-full lg:w-2/5">
                <h2 class="text-xl font-bold mb-6 uppercase tracking-wider">Order Summary</h2>
                <div class="bg-gray-50 border border-gray-100 rounded-sm p-6 sticky top-24">
                    <div class="space-y-4 mb-6">
                        <?php foreach ($cart_items as $ci): ?>
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-20 bg-gray-200 flex-shrink-0 overflow-hidden rounded-sm">
                                    <?php if ($ci['image']): ?>
                                        <img src="<?php echo htmlspecialchars($ci['image']); ?>"
                                            class="w-full h-full object-cover" alt="">
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium truncate">
                                        <?php echo htmlspecialchars($ci['name']); ?>
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        <?php
                                        $parts = [];
                                        if ($ci['color'])
                                            $parts[] = ucfirst($ci['color']);
                                        if ($ci['size'])
                                            $parts[] = 'Size ' . $ci['size'];
                                        echo implode(', ', $parts);
                                        ?>
                                    </p>
                                    <p class="text-xs text-gray-500">Qty:
                                        <?php echo $ci['quantity']; ?>
                                    </p>
                                </div>
                                <div class="text-sm font-medium flex-shrink-0">
                                    $
                                    <?php echo number_format($ci['line_total'], 2); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="border-t border-gray-200 pt-4 space-y-3 text-sm mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span>$
                                <?php echo number_format($subtotal, 2); ?>
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span class="text-green-600 font-medium">Free</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax (8%)</span>
                            <span>$
                                <?php echo number_format($tax, 2); ?>
                            </span>
                        </div>
                    </div>
                    <div class="border-t border-gray-200 pt-4 mb-8">
                        <div class="flex justify-between items-center text-xl font-bold">
                            <span>Total</span>
                            <span>$
                                <?php echo number_format($total, 2); ?>
                            </span>
                        </div>
                    </div>

                    <button type="submit" id="place-order-btn"
                        class="w-full bg-PRAIRAVEE-green text-white py-4 font-semibold uppercase tracking-wider text-sm hover:bg-opacity-90 transition shadow-md">
                        Place Order — $
                        <?php echo number_format($total, 2); ?>
                    </button>
                    <p class="text-xs text-gray-400 text-center mt-4">By placing your order, you agree to our Terms of
                        Service.</p>
                </div>
            </div>

        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require BASE_PATH . '/src/views/layouts/main.php';
?>
SESSION['redirect_after_login'] = BASE_URL . '';
header("Location: " . BASE_URL . "/login");
exit;
}

// ---- Cart Check ----
if (empty($_SESSION['cart'])) {
header("Location: " . BASE_URL . "/cart");
exit;
}

$productModel = new Product();
$orderModel = new Order();

// Build cart items with real prices
$cart_items = [];
$subtotal = 0;
foreach ($_SESSION['cart'] as $cart_key => $item) {
$p = $productModel->getProductById($item['product_id']);
if (!$p) continue;
$price = (float)$p['price'];
$line = $price * $item['quantity'];
$subtotal += $line;
$cart_items[] = [
'cart_key' => $cart_key,
'product_id' => $item['product_id'],
'name' => $p['name'],
'slug' => $p['slug'],
'color' => $item['color'],
'size' => $item['size'],
'quantity' => $item['quantity'],
'price' => $price,
'line_total' => $line,
'image' => $p['images'][0]['image_url'] ?? '',
];
}
$tax = $subtotal * 0.08;
$total = $subtotal + $tax;

$errors = [];
$form = [];

// ---- Handle POST — Place Order ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$form['shipping_name'] = trim($_POST['shipping_name'] ?? '');
$form['shipping_address'] = trim($_POST['shipping_address'] ?? '');
$form['shipping_phone'] = trim($_POST['shipping_phone'] ?? '');

if (empty($form['shipping_name'])) $errors[] = 'Full name is required.';
if (empty($form['shipping_address'])) $errors[] = 'Address is required.';
if (empty($form['shipping_phone'])) $errors[] = 'Phone number is required.';

if (empty($errors)) {
// Create order
$order_id = $orderModel->createOrder(
$_SESSION['user_id'],
$total,
$form['shipping_name'],
$form['shipping_address'],
$form['shipping_phone']
);

if ($order_id) {
// Insert order items
$items_data = [];
foreach ($cart_items as $ci) {
$items_data[] = [
'product_id' => $ci['product_id'],
'variant_id' => null,
'quantity' => $ci['quantity'],
'price' => $ci['price'],
];
}
$orderModel->createOrderItems($order_id, $items_data);

// Clear cart
unset($_SESSION['cart']);

// Redirect to success
header("Location: " . BASE_URL . "/checkout/success?order_id=" . $order_id);
exit;
} else {
$errors[] = 'Could not place order. Please try again.';
}
}
}
?>

<div class="bg-PRAIRAVEE-cream border-b border-gray-200">
    <div class="container mx-auto px-6 lg:px-12 py-8">
        <h1 class="text-3xl font-bold tracking-widest uppercase">Checkout</h1>
    </div>
</div>

<div class="container mx-auto px-6 lg:px-12 py-12">

    <?php if (!empty($errors)): ?>
        <div class="mb-8 bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-md text-sm">
            <ul class="list-disc pl-4 space-y-1">
                <?php foreach ($errors as $e): ?>
                    <li>
                        <?php echo htmlspecialchars($e); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/checkout" id="checkout-form">
        <div class="flex flex-col lg:flex-row gap-12">

            <!-- Left: Shipping Form -->
            <div class="w-full lg:w-3/5">
                <h2 class="text-xl font-bold mb-6 uppercase tracking-wider">Shipping Information</h2>

                <div class="bg-white border border-gray-100 rounded-sm p-8 space-y-6">
                    <div>
                        <label for="shipping_name" class="block text-sm font-medium text-gray-700 mb-1">Full
                            Name</label>
                        <input type="text" id="shipping_name" name="shipping_name" required
                            value="<?php echo htmlspecialchars($form['shipping_name'] ?? $_SESSION['user_name'] ?? ''); ?>"
                            class="w-full px-4 py-3 border border-gray-300 rounded-sm text-sm focus:outline-none focus:ring-2 focus:ring-PRAIRAVEE-green transition">
                    </div>
                    <div>
                        <label for="shipping_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone
                            Number</label>
                        <input type="tel" id="shipping_phone" name="shipping_phone" required
                            value="<?php echo htmlspecialchars($form['shipping_phone'] ?? ''); ?>"
                            placeholder="e.g. 081-234-5678"
                            class="w-full px-4 py-3 border border-gray-300 rounded-sm text-sm focus:outline-none focus:ring-2 focus:ring-PRAIRAVEE-green transition">
                    </div>
                    <div>
                        <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-1">Shipping
                            Address</label>
                        <textarea id="shipping_address" name="shipping_address" required rows="4"
                            placeholder="Street address, City, Province, Postal Code"
                            class="w-full px-4 py-3 border border-gray-300 rounded-sm text-sm focus:outline-none focus:ring-2 focus:ring-PRAIRAVEE-green transition resize-none"><?php echo htmlspecialchars($form['shipping_address'] ?? ''); ?></textarea>
                    </div>
                </div>

                <!-- Mock Payment Section -->
                <h2 class="text-xl font-bold mt-10 mb-6 uppercase tracking-wider">Payment</h2>
                <div class="bg-white border border-gray-100 rounded-sm p-8">
                    <div
                        class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-md text-sm mb-6 flex items-start space-x-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 mt-0.5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span><strong>Demo Mode:</strong> This is a simulated payment. Click "Place Order" to confirm
                            without real charges.</span>
                    </div>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Card Number (Demo)</label>
                            <input type="text" value="4242 4242 4242 4242" disabled
                                class="w-full px-4 py-3 border border-gray-200 rounded-sm text-sm bg-gray-50 text-gray-400 cursor-not-allowed">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Expiry (Demo)</label>
                                <input type="text" value="12/28" disabled
                                    class="w-full px-4 py-3 border border-gray-200 rounded-sm text-sm bg-gray-50 text-gray-400 cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">CVV (Demo)</label>
                                <input type="text" value="123" disabled
                                    class="w-full px-4 py-3 border border-gray-200 rounded-sm text-sm bg-gray-50 text-gray-400 cursor-not-allowed">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Order Summary -->
            <div class="w-full lg:w-2/5">
                <h2 class="text-xl font-bold mb-6 uppercase tracking-wider">Order Summary</h2>
                <div class="bg-gray-50 border border-gray-100 rounded-sm p-6 sticky top-24">
                    <div class="space-y-4 mb-6">
                        <?php foreach ($cart_items as $ci): ?>
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-20 bg-gray-200 flex-shrink-0 overflow-hidden rounded-sm">
                                    <?php if ($ci['image']): ?>
                                        <img src="<?php echo htmlspecialchars($ci['image']); ?>"
                                            class="w-full h-full object-cover" alt="">
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium truncate">
                                        <?php echo htmlspecialchars($ci['name']); ?>
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        <?php
                                        $parts = [];
                                        if ($ci['color'])
                                            $parts[] = ucfirst($ci['color']);
                                        if ($ci['size'])
                                            $parts[] = 'Size ' . $ci['size'];
                                        echo implode(', ', $parts);
                                        ?>
                                    </p>
                                    <p class="text-xs text-gray-500">Qty:
                                        <?php echo $ci['quantity']; ?>
                                    </p>
                                </div>
                                <div class="text-sm font-medium flex-shrink-0">
                                    $
                                    <?php echo number_format($ci['line_total'], 2); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="border-t border-gray-200 pt-4 space-y-3 text-sm mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span>$
                                <?php echo number_format($subtotal, 2); ?>
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span class="text-green-600 font-medium">Free</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax (8%)</span>
                            <span>$
                                <?php echo number_format($tax, 2); ?>
                            </span>
                        </div>
                    </div>
                    <div class="border-t border-gray-200 pt-4 mb-8">
                        <div class="flex justify-between items-center text-xl font-bold">
                            <span>Total</span>
                            <span>$
                                <?php echo number_format($total, 2); ?>
                            </span>
                        </div>
                    </div>

                    <button type="submit" id="place-order-btn"
                        class="w-full bg-PRAIRAVEE-green text-white py-4 font-semibold uppercase tracking-wider text-sm hover:bg-opacity-90 transition shadow-md">
                        Place Order — $
                        <?php echo number_format($total, 2); ?>
                    </button>
                    <p class="text-xs text-gray-400 text-center mt-4">By placing your order, you agree to our Terms of
                        Service.</p>
                </div>
            </div>

        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require BASE_PATH . '/src/views/layouts/main.php';
?>