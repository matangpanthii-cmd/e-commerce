<?php
require_once BASE_PATH . '/src/middleware/admin_guard.php';
require_once BASE_PATH . '/src/models/Product.php';

$productModel = new Product();

// Handle delete
$admin_flash_success = $_SESSION['flash_success'] ?? null;
$admin_flash_error   = $_SESSION['flash_error']   ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['_action'] ?? '') === 'delete') {
    $id = (int)($_POST['product_id'] ?? 0);
    if ($id && $productModel->deleteProduct($id)) {
        $_SESSION['flash_success'] = 'Product deleted successfully.';
    } else {
        $_SESSION['flash_error'] = 'Could not delete product.';
    }
    header("Location: " . BASE_URL . "/admin/products");
    exit;
}

$search   = trim($_GET['search'] ?? '');
$products = $productModel->getAllAdmin($search);

$admin_title      = 'Products';
$admin_breadcrumb = 'Manage your product catalog';

ob_start();
?>

<div class="flex items-center justify-between mb-6">
    <form method="GET" action="<?= BASE_URL ?>/admin/products" class="flex gap-3">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
               placeholder="Search products..."
               class="bg-admin-card border border-admin-border text-admin-text text-sm px-4 py-2 rounded-lg focus:outline-none focus:border-admin-accent w-64 placeholder-admin-muted">
        <button type="submit" class="bg-admin-card border border-admin-border text-admin-muted px-4 py-2 rounded-lg text-sm hover:border-admin-accent transition">Search</button>
        <?php if ($search): ?>
            <a href="<?= BASE_URL ?>/admin/products" class="border border-admin-border text-admin-muted px-4 py-2 rounded-lg text-sm hover:border-red-500 hover:text-red-400 transition">Clear</a>
        <?php endif; ?>
    </form>
    <a href="<?= BASE_URL ?>/admin/products/create" id="btn-add-product"
       class="flex items-center space-x-2 bg-admin-accent text-black px-4 py-2 rounded-lg text-sm font-semibold hover:bg-yellow-400 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
        <span>Add Product</span>
    </a>
</div>

<div class="bg-admin-card border border-admin-border rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-admin-muted text-xs uppercase tracking-wider border-b border-admin-border">
                    <th class="px-6 py-4 text-left">Product</th>
                    <th class="px-6 py-4 text-left">Category</th>
                    <th class="px-6 py-4 text-right">Price</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-admin-border">
                <?php if (empty($products)): ?>
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center text-admin-muted">
                        <?php echo $search ? 'No products match your search.' : 'No products yet. Add your first product!'; ?>
                    </td>
                </tr>
                <?php else: ?>
                <?php
                $status_badge = [
                    'active'   => 'bg-green-900/50 text-green-300',
                    'new_in'   => 'bg-blue-900/50 text-blue-300',
                    'sale'     => 'bg-red-900/50 text-red-300',
                    'inactive' => 'bg-gray-700 text-gray-400',
                ];
                foreach ($products as $p): ?>
                <tr class="hover:bg-white/5 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-14 bg-admin-bg rounded-md overflow-hidden flex-shrink-0">
                                <?php if ($p['primary_image']): ?>
                                    <img src="<?php echo htmlspecialchars($p['primary_image']); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center text-admin-muted text-xs">No img</div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="text-white font-medium"><?php echo htmlspecialchars($p['name']); ?></p>
                                <p class="text-admin-muted text-xs font-mono"><?php echo htmlspecialchars($p['slug']); ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-admin-muted"><?php echo htmlspecialchars($p['category_name'] ?? '—'); ?></td>
                    <td class="px-6 py-4 text-right text-white font-medium">$<?php echo number_format($p['price'], 2); ?></td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold <?php echo $status_badge[$p['status']] ?? 'bg-gray-700 text-gray-300'; ?>">
                            <?php echo ucfirst(str_replace('_', ' ', $p['status'])); ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-3">
                            <a href="<?= BASE_URL ?>/admin/products/<?php echo $p['id']; ?>/edit"
                               class="text-admin-accent hover:underline text-xs">Edit</a>
                            <form method="POST" action="<?= BASE_URL ?>/admin/products"
                                  onsubmit="return confirm('Delete \'<?php echo htmlspecialchars(addslashes($p['name'])); ?>\'? This cannot be undone.')">
                                <input type="hidden" name="_action" value="delete">
                                <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                                <button type="submit" class="text-red-400 hover:text-red-300 text-xs hover:underline">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="px-6 py-3 border-t border-admin-border text-admin-muted text-xs">
        <?php echo count($products); ?> product<?php echo count($products) !== 1 ? 's' : ''; ?> found
    </div>
</div>

<?php
$admin_content = ob_get_clean();
require BASE_PATH . '/src/views/admin/layouts/admin_layout.php';
?>
