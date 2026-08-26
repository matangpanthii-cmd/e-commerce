<?php
require_once BASE_PATH . '/src/middleware/admin_guard.php';
require_once BASE_PATH . '/src/models/Order.php';

$orderModel = new Order();

$admin_flash_success = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_success']);

$status_filter = $_GET['status'] ?? '';
$search        = trim($_GET['search'] ?? '');
$orders        = $orderModel->getAllOrders($status_filter, $search);

$admin_title      = 'Orders';
$admin_breadcrumb = 'Manage customer orders';

$status_colors = [
    'pending'   => 'bg-yellow-900/50 text-yellow-300',
    'paid'      => 'bg-blue-900/50 text-blue-300',
    'shipped'   => 'bg-purple-900/50 text-purple-300',
    'completed' => 'bg-green-900/50 text-green-300',
    'cancelled' => 'bg-red-900/50 text-red-300',
];
$all_statuses = ['', 'pending', 'paid', 'shipped', 'completed', 'cancelled'];

ob_start();
?>

<!-- Filters -->
<div class="flex flex-col sm:flex-row gap-3 mb-6">
    <form method="GET" action="<?= BASE_URL ?>/admin/orders" class="flex gap-3 flex-1">
        <input type="hidden" name="status" value="<?php echo htmlspecialchars($status_filter); ?>">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
               placeholder="Search customer name, email, or order #..."
               class="flex-1 bg-admin-card border border-admin-border text-admin-text text-sm px-4 py-2 rounded-lg focus:outline-none focus:border-admin-accent placeholder-admin-muted">
        <button type="submit" class="bg-admin-card border border-admin-border text-admin-muted px-4 py-2 rounded-lg text-sm hover:border-admin-accent transition">Search</button>
    </form>
</div>

<!-- Status Tabs -->
<div class="flex flex-wrap gap-2 mb-6">
    <?php foreach ($all_statuses as $s): ?>
    <a href="?status=<?php echo $s; ?><?php echo $search ? '&search='.urlencode($search) : ''; ?>"
       class="px-4 py-1.5 rounded-full text-xs font-semibold transition
              <?php echo $status_filter === $s
                ? 'bg-admin-accent text-black'
                : 'bg-admin-card border border-admin-border text-admin-muted hover:border-admin-accent'; ?>">
        <?php echo $s ? ucfirst($s) : 'All'; ?>
    </a>
    <?php endforeach; ?>
</div>

<div class="bg-admin-card border border-admin-border rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-admin-muted text-xs uppercase tracking-wider border-b border-admin-border">
                    <th class="px-6 py-4 text-left">Order</th>
                    <th class="px-6 py-4 text-left">Customer</th>
                    <th class="px-6 py-4 text-left">Date</th>
                    <th class="px-6 py-4 text-right">Total</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-admin-border">
                <?php if (empty($orders)): ?>
                <tr><td colspan="6" class="px-6 py-16 text-center text-admin-muted">No orders found.</td></tr>
                <?php else: ?>
                <?php foreach ($orders as $o): ?>
                <tr class="hover:bg-white/5 transition">
                    <td class="px-6 py-4 font-mono text-admin-accent">#<?php echo str_pad($o['id'], 6, '0', STR_PAD_LEFT); ?></td>
                    <td class="px-6 py-4">
                        <p class="text-white"><?php echo htmlspecialchars($o['customer_name']); ?></p>
                        <p class="text-admin-muted text-xs"><?php echo htmlspecialchars($o['customer_email']); ?></p>
                    </td>
                    <td class="px-6 py-4 text-admin-muted"><?php echo date('d M Y, H:i', strtotime($o['created_at'])); ?></td>
                    <td class="px-6 py-4 text-right text-white font-medium">$<?php echo number_format($o['total_amount'], 2); ?></td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold <?php echo $status_colors[$o['status']] ?? 'bg-gray-800 text-gray-300'; ?>">
                            <?php echo ucfirst($o['status']); ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="<?= BASE_URL ?>/admin/orders/<?php echo $o['id']; ?>" class="text-admin-accent hover:underline text-xs">View →</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="px-6 py-3 border-t border-admin-border text-admin-muted text-xs">
        <?php echo count($orders); ?> order<?php echo count($orders) !== 1 ? 's' : ''; ?> found
    </div>
</div>

<?php
$admin_content = ob_get_clean();
require BASE_PATH . '/src/views/admin/layouts/admin_layout.php';
?>
