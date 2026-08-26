<?php
require_once BASE_PATH . '/src/middleware/admin_guard.php';
require_once BASE_PATH . '/src/models/Order.php';
require_once BASE_PATH . '/src/models/Product.php';
require_once BASE_PATH . '/src/models/User.php';

$orderModel   = new Order();
$productModel = new Product();
$userModel    = new User();

$total_orders   = $orderModel->countAll();
$total_products = $productModel->countAll();
$total_users    = $userModel->countAll();
$today_revenue  = $orderModel->getTodayRevenue();
$pending_orders = $orderModel->countByStatus('pending');
$recent_orders  = $orderModel->getRecentOrders(7);

$admin_title = 'Dashboard';
$admin_breadcrumb = 'Overview of your store';

$status_colors = [
    'pending'   => 'bg-yellow-900/50 text-yellow-300',
    'paid'      => 'bg-blue-900/50 text-blue-300',
    'shipped'   => 'bg-purple-900/50 text-purple-300',
    'completed' => 'bg-green-900/50 text-green-300',
    'cancelled' => 'bg-red-900/50 text-red-300',
];

ob_start();
?>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    <!-- Today Revenue -->
    <div class="stat-card bg-admin-card border border-admin-border rounded-xl p-6">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-admin-accent/20 rounded-lg flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-admin-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <p class="text-admin-muted text-xs font-medium uppercase tracking-wider mb-1">Today's Revenue</p>
        <p class="text-2xl font-bold text-white">$<?php echo number_format($today_revenue, 2); ?></p>
    </div>

    <!-- Total Orders -->
    <div class="stat-card bg-admin-card border border-admin-border rounded-xl p-6">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
        </div>
        <p class="text-admin-muted text-xs font-medium uppercase tracking-wider mb-1">Total Orders</p>
        <p class="text-2xl font-bold text-white"><?php echo number_format($total_orders); ?></p>
        <?php if ($pending_orders > 0): ?>
        <p class="text-yellow-400 text-xs mt-1"><?php echo $pending_orders; ?> pending</p>
        <?php endif; ?>
    </div>

    <!-- Products -->
    <div class="stat-card bg-admin-card border border-admin-border rounded-xl p-6">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
        </div>
        <p class="text-admin-muted text-xs font-medium uppercase tracking-wider mb-1">Total Products</p>
        <p class="text-2xl font-bold text-white"><?php echo number_format($total_products); ?></p>
    </div>

    <!-- Users -->
    <div class="stat-card bg-admin-card border border-admin-border rounded-xl p-6">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>
        <p class="text-admin-muted text-xs font-medium uppercase tracking-wider mb-1">Total Members</p>
        <p class="text-2xl font-bold text-white"><?php echo number_format($total_users); ?></p>
    </div>
</div>

<!-- Quick Actions -->
<div class="flex flex-wrap gap-3 mb-8">
    <a href="<?= BASE_URL ?>/admin/products/create" class="flex items-center space-x-2 bg-admin-accent text-black px-4 py-2 rounded-lg text-sm font-semibold hover:bg-yellow-400 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
        <span>Add Product</span>
    </a>
    <a href="<?= BASE_URL ?>/admin/categories" class="flex items-center space-x-2 bg-admin-card border border-admin-border text-admin-text px-4 py-2 rounded-lg text-sm font-medium hover:border-admin-accent transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
        <span>Manage Categories</span>
    </a>
    <a href="<?= BASE_URL ?>/admin/orders" class="flex items-center space-x-2 bg-admin-card border border-admin-border text-admin-text px-4 py-2 rounded-lg text-sm font-medium hover:border-admin-accent transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
        <span>View Orders</span>
    </a>
</div>

<!-- Recent Orders Table -->
<div class="bg-admin-card border border-admin-border rounded-xl overflow-hidden">
    <div class="px-6 py-5 border-b border-admin-border flex items-center justify-between">
        <h2 class="text-white font-semibold">Recent Orders</h2>
        <a href="<?= BASE_URL ?>/admin/orders" class="text-admin-accent text-sm hover:underline">View all →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-admin-muted text-xs uppercase tracking-wider border-b border-admin-border">
                    <th class="px-6 py-3 text-left">Order</th>
                    <th class="px-6 py-3 text-left">Customer</th>
                    <th class="px-6 py-3 text-left">Date</th>
                    <th class="px-6 py-3 text-right">Total</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-admin-border">
                <?php if (empty($recent_orders)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-admin-muted">No orders yet.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($recent_orders as $order): ?>
                <tr class="hover:bg-white/5 transition">
                    <td class="px-6 py-4 font-mono text-admin-accent">#<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></td>
                    <td class="px-6 py-4 text-white"><?php echo htmlspecialchars($order['customer_name']); ?></td>
                    <td class="px-6 py-4 text-admin-muted"><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                    <td class="px-6 py-4 text-right text-white font-medium">$<?php echo number_format($order['total_amount'], 2); ?></td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold <?php echo $status_colors[$order['status']] ?? 'bg-gray-800 text-gray-300'; ?>">
                            <?php echo ucfirst($order['status']); ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="<?= BASE_URL ?>/admin/orders/<?php echo $order['id']; ?>" class="text-admin-accent hover:underline text-xs">View</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$admin_content = ob_get_clean();
require BASE_PATH . '/src/views/admin/layouts/admin_layout.php';
?>
