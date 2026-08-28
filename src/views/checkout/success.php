<?php
ob_start();
require_once BASE_PATH . '/src/models/Order.php';

// Guard: must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "/login");
    exit;
}

$order_id = (int)($_GET['order_id'] ?? 0);
$order = null;
if ($order_id) {
    $orderModel = new Order();
    $order = $orderModel->getOrderById($order_id);
    // Security: ensure order belongs to the logged in user
    if (!$order || $order['user_id'] != $_SESSION['user_id']) {
        $order = null;
    }
}
?>

<div class="min-h-[80vh] bg-PRAIRAVEE-cream flex items-center justify-center px-4 py-16">
    <div class="w-full max-w-2xl text-center">

        <!-- Success Icon -->
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <h1 class="text-4xl font-bold tracking-widest uppercase mb-4">Order Confirmed!</h1>
        <p class="text-gray-500 text-lg mb-8">
            Thank you, <strong><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Customer'); ?></strong>!<br>
            Your order has been placed and is being processed.
        </p>

        <?php if ($order): ?>
        <div class="bg-white border border-gray-100 rounded-sm shadow-sm text-left mb-10">
            <!-- Order Header -->
            <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center flex-wrap gap-4">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Order Number</p>
                    <p class="text-2xl font-bold text-PRAIRAVEE-green">#<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Date</p>
                    <p class="font-medium"><?php echo date('d M Y', strtotime($order['created_at'])); ?></p>
                </div>
                <div>
                    <span class="inline-block bg-yellow-100 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wider">
                        <?php echo ucfirst($order['status']); ?>
                    </span>
                </div>
            </div>

            <!-- Items -->
            <?php if (!empty($order['items'])): ?>
            <div class="px-8 py-6 border-b border-gray-100">
                <h3 class="text-sm font-semibold uppercase tracking-wider mb-4 text-gray-500">Items Ordered</h3>
                <div class="space-y-4">
                    <?php foreach ($order['items'] as $item): ?>
                    <div class="flex justify-between items-center text-sm">
                        <div>
                            <p class="font-medium"><?php echo htmlspecialchars($item['product_name']); ?></p>
                            <p class="text-gray-400 text-xs">
                                <?php
                                $parts = [];
                                if ($item['color_name']) $parts[] = ucfirst($item['color_name']);
                                if ($item['size'])       $parts[] = 'Size ' . $item['size'];
                                echo implode(', ', $parts) ?: 'Standard';
                                ?>
                                — Qty: <?php echo $item['quantity']; ?>
                            </p>
                        </div>
                        <p class="font-medium">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Shipping + Total -->
            <div class="px-8 py-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">Ship To</p>
                    <p class="font-medium"><?php echo htmlspecialchars($order['shipping_name']); ?></p>
                    <p class="text-sm text-gray-500"><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
                    <p class="text-sm text-gray-500"><?php echo htmlspecialchars($order['shipping_phone']); ?></p>
                </div>
                <div class="sm:text-right">
                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-2">Order Total</p>
                    <p class="text-2xl font-bold">$<?php echo number_format($order['total_amount'], 2); ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?= BASE_URL ?>/" class="bg-PRAIRAVEE-green text-white px-8 py-3 font-medium uppercase tracking-wider text-sm hover:bg-opacity-90 transition">
                Continue Shopping
            </a>
            <a href="<?= BASE_URL ?>/products" class="border border-PRAIRAVEE-green text-PRAIRAVEE-green px-8 py-3 font-medium uppercase tracking-wider text-sm hover:bg-PRAIRAVEE-green hover:text-white transition">
                Browse Collections
            </a>
        </div>

    </div>
</div>

<?php
$content = ob_get_clean();
require BASE_PATH . '/src/views/layouts/main.php';
?>
