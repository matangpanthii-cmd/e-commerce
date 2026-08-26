<?php
require_once BASE_PATH . '/src/middleware/admin_guard.php';
require_once BASE_PATH . '/src/models/Order.php';

$orderModel = new Order();

// Get order ID from URL: /admin/orders/{id}
$segments = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
$order_id = 0;
foreach ($segments as $k => $seg) {
    if ($seg === 'orders' && isset($segments[$k+1]) && is_numeric($segments[$k+1])) {
        $order_id = (int)$segments[$k+1];
    }
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_status'])) {
    $orderModel->updateOrderStatus($order_id, $_POST['new_status']);
    $_SESSION['flash_success'] = 'Order status updated.';
    header("Location: " . BASE_URL . "/admin/orders/{$order_id}");
    exit;
}

$order = $orderModel->getOrderById($order_id);
if (!$order) {
    header("Location: " . BASE_URL . "/admin/orders");
    exit;
}

$admin_flash_success = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_success']);

$admin_title      = 'Order #' . str_pad($order['id'], 6, '0', STR_PAD_LEFT);
$admin_breadcrumb = '<a href="<?= BASE_URL ?>/admin/orders" class="hover:text-admin-accent">Orders</a> → Detail';

$status_colors = [
    'pending'   => 'bg-yellow-900/50 text-yellow-300 border-yellow-700',
    'paid'      => 'bg-blue-900/50 text-blue-300 border-blue-700',
    'shipped'   => 'bg-purple-900/50 text-purple-300 border-purple-700',
    'completed' => 'bg-green-900/50 text-green-300 border-green-700',
    'cancelled' => 'bg-red-900/50 text-red-300 border-red-700',
];

ob_start();
?>

<div class="max-w-4xl">

<!-- Header row -->
<div class="flex items-center justify-between mb-6">
    <a href="<?= BASE_URL ?>/admin/orders" class="text-admin-muted hover:text-admin-text text-sm flex items-center space-x-1 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        <span>Back to Orders</span>
    </a>
    <!-- Status Badge -->
    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold border <?php echo $status_colors[$order['status']] ?? 'bg-gray-800 text-gray-300 border-gray-600'; ?>">
        <?php echo ucfirst($order['status']); ?>
    </span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left: Items + Info -->
    <div class="lg:col-span-2 space-y-6">

        <!-- Order Items -->
        <div class="bg-admin-card border border-admin-border rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-admin-border">
                <h3 class="text-white font-semibold">Items Ordered (<?php echo count($order['items']); ?>)</h3>
            </div>
            <div class="divide-y divide-admin-border">
                <?php if (empty($order['items'])): ?>
                <div class="px-6 py-8 text-center text-admin-muted text-sm">No items found.</div>
                <?php else: ?>
                <?php foreach ($order['items'] as $item): ?>
                <div class="px-6 py-4 flex items-center space-x-4">
                    <div class="w-14 h-16 bg-admin-bg rounded-md overflow-hidden flex-shrink-0">
                        <?php if ($item['product_image']): ?>
                            <img src="<?php echo htmlspecialchars($item['product_image']); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-admin-muted text-xs">No img</div>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-white font-medium truncate"><?php echo htmlspecialchars($item['product_name']); ?></p>
                        <p class="text-admin-muted text-xs">
                            <?php
                            $parts = [];
                            if ($item['color_name']) $parts[] = ucfirst($item['color_name']);
                            if ($item['size'])       $parts[] = 'Size ' . $item['size'];
                            echo implode(', ', $parts) ?: 'Standard';
                            ?>
                        </p>
                        <p class="text-admin-muted text-xs">Qty: <?php echo $item['quantity']; ?></p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-white font-medium">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                        <p class="text-admin-muted text-xs">$<?php echo number_format($item['price'], 2); ?> each</p>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <!-- Order Totals -->
            <div class="px-6 py-4 border-t border-admin-border bg-admin-bg/50 space-y-2 text-sm">
                <div class="flex justify-between text-admin-muted">
                    <span>Subtotal</span>
                    <span>$<?php echo number_format($order['total_amount'] / 1.08, 2); ?></span>
                </div>
                <div class="flex justify-between text-admin-muted">
                    <span>Tax (8%)</span>
                    <span>$<?php echo number_format($order['total_amount'] - ($order['total_amount'] / 1.08), 2); ?></span>
                </div>
                <div class="flex justify-between text-white font-bold text-base pt-2 border-t border-admin-border">
                    <span>Total</span>
                    <span>$<?php echo number_format($order['total_amount'], 2); ?></span>
                </div>
            </div>
        </div>

        <!-- Shipping Info -->
        <div class="bg-admin-card border border-admin-border rounded-xl p-6">
            <h3 class="text-white font-semibold mb-4">Shipping Information</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-admin-muted text-xs uppercase tracking-wider mb-1">Name</p>
                    <p class="text-white"><?php echo htmlspecialchars($order['shipping_name'] ?? '—'); ?></p>
                </div>
                <div>
                    <p class="text-admin-muted text-xs uppercase tracking-wider mb-1">Phone</p>
                    <p class="text-white"><?php echo htmlspecialchars($order['shipping_phone'] ?? '—'); ?></p>
                </div>
                <div class="col-span-2">
                    <p class="text-admin-muted text-xs uppercase tracking-wider mb-1">Address</p>
                    <p class="text-white"><?php echo nl2br(htmlspecialchars($order['shipping_address'] ?? '—')); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Customer + Actions -->
    <div class="space-y-6">
        <!-- Customer -->
        <div class="bg-admin-card border border-admin-border rounded-xl p-6">
            <h3 class="text-white font-semibold mb-4">Customer</h3>
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-admin-accent rounded-full flex items-center justify-center">
                    <span class="text-black font-bold text-sm"><?php echo strtoupper(substr($order['customer_name'] ?? 'U', 0, 1)); ?></span>
                </div>
                <div>
                    <p class="text-white text-sm font-medium"><?php echo htmlspecialchars($order['customer_name']); ?></p>
                    <p class="text-admin-muted text-xs"><?php echo htmlspecialchars($order['customer_email']); ?></p>
                </div>
            </div>
            <div class="text-xs text-admin-muted space-y-1">
                <p>Order placed: <?php echo date('d M Y, H:i', strtotime($order['created_at'])); ?></p>
            </div>
        </div>

        <!-- Update Status -->
        <div class="bg-admin-card border border-admin-border rounded-xl p-6">
            <h3 class="text-white font-semibold mb-4">Update Status</h3>
            <form method="POST" action="<?= BASE_URL ?>/admin/orders/<?php echo $order['id']; ?>" id="status-form">
                <select name="new_status" id="new_status"
                        class="w-full bg-admin-bg border border-admin-border text-admin-text text-sm px-4 py-2.5 rounded-lg focus:outline-none focus:border-admin-accent transition mb-3">
                    <?php foreach (['pending','paid','shipped','completed','cancelled'] as $s): ?>
                    <option value="<?php echo $s; ?>" <?php echo $order['status'] === $s ? 'selected' : ''; ?>>
                        <?php echo ucfirst($s); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" id="btn-update-status"
                        class="w-full bg-admin-accent text-black py-2.5 rounded-lg text-sm font-semibold hover:bg-yellow-400 transition">
                    Update Status
                </button>
            </form>
        </div>
    </div>
</div>
</div>

<?php
$admin_content = ob_get_clean();
require BASE_PATH . '/src/views/admin/layouts/admin_layout.php';
?>
