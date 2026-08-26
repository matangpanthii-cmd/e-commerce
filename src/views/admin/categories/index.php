<?php
require_once BASE_PATH . '/src/middleware/admin_guard.php';
require_once BASE_PATH . '/src/models/Category.php';

$categoryModel = new Category();
$errors  = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['_action'] ?? '';
    $name   = trim($_POST['name'] ?? '');
    $id     = (int)($_POST['cat_id'] ?? 0);

    if ($action === 'create') {
        if (empty($name)) {
            $errors[] = 'Category name is required.';
        } else {
            $slug = $categoryModel->generateSlug($name);
            if ($categoryModel->create($name, $slug)) {
                $success = 'Category "' . htmlspecialchars($name) . '" created.';
            } else {
                $errors[] = 'Failed to create category. Slug may already exist.';
            }
        }
    } elseif ($action === 'update' && $id) {
        if (empty($name)) {
            $errors[] = 'Category name is required.';
        } else {
            $slug = $categoryModel->generateSlug($name);
            $categoryModel->update($id, $name, $slug);
            $success = 'Category updated.';
        }
    } elseif ($action === 'delete' && $id) {
        $categoryModel->delete($id);
        $success = 'Category deleted.';
    }
}

$categories = $categoryModel->getAll();

$admin_title      = 'Categories';
$admin_breadcrumb = 'Manage product categories';
ob_start();
?>

<div class="max-w-3xl">
<?php if ($success): ?>
<div class="mb-6 bg-green-900/40 border border-green-700 text-green-300 px-5 py-3 rounded-lg text-sm"><?php echo $success; ?></div>
<?php endif; ?>
<?php if (!empty($errors)): ?>
<div class="mb-6 bg-red-900/40 border border-red-700 text-red-300 px-5 py-3 rounded-lg text-sm">
    <ul class="list-disc pl-4 space-y-1"><?php foreach ($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?></ul>
</div>
<?php endif; ?>

<!-- Add Category -->
<div class="bg-admin-card border border-admin-border rounded-xl p-6 mb-6">
    <h3 class="text-white font-semibold mb-4">Add New Category</h3>
    <form method="POST" action="<?= BASE_URL ?>/admin/categories" class="flex gap-3">
        <input type="hidden" name="_action" value="create">
        <input type="text" name="name" required placeholder="Category name..."
               class="flex-1 bg-admin-bg border border-admin-border text-admin-text text-sm px-4 py-2.5 rounded-lg focus:outline-none focus:border-admin-accent transition placeholder-admin-muted">
        <button type="submit" id="btn-create-category"
                class="bg-admin-accent text-black px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-yellow-400 transition flex-shrink-0">
            Create
        </button>
    </form>
</div>

<!-- Category List -->
<div class="bg-admin-card border border-admin-border rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-admin-border">
        <h3 class="text-white font-semibold">All Categories <span class="text-admin-muted font-normal text-sm">(<?php echo count($categories); ?>)</span></h3>
    </div>
    <?php if (empty($categories)): ?>
    <div class="px-6 py-12 text-center text-admin-muted text-sm">No categories yet.</div>
    <?php else: ?>
    <div class="divide-y divide-admin-border">
        <?php foreach ($categories as $cat): ?>
        <div class="px-6 py-4 flex items-center justify-between" x-data="{ editing: false }">
            <!-- View mode -->
            <div x-show="!editing" class="flex items-center space-x-4 flex-1">
                <div>
                    <p class="text-white font-medium"><?php echo htmlspecialchars($cat['name']); ?></p>
                    <p class="text-admin-muted text-xs font-mono"><?php echo htmlspecialchars($cat['slug']); ?> · <?php echo $cat['product_count']; ?> products</p>
                </div>
            </div>
            <!-- Edit mode -->
            <form method="POST" action="<?= BASE_URL ?>/admin/categories" x-show="editing" class="flex-1 flex gap-2 mr-4">
                <input type="hidden" name="_action" value="update">
                <input type="hidden" name="cat_id" value="<?php echo $cat['id']; ?>">
                <input type="text" name="name" value="<?php echo htmlspecialchars($cat['name']); ?>" required
                       class="flex-1 bg-admin-bg border border-admin-border text-admin-text text-sm px-3 py-2 rounded-lg focus:outline-none focus:border-admin-accent transition">
                <button type="submit" class="bg-admin-accent text-black px-4 py-2 rounded-lg text-sm font-semibold hover:bg-yellow-400 transition">Save</button>
                <button type="button" @click="editing=false" class="border border-admin-border text-admin-muted px-3 py-2 rounded-lg text-sm hover:border-admin-text transition">Cancel</button>
            </form>

            <div class="flex items-center space-x-3" x-show="!editing">
                <button @click="editing=true" class="text-admin-accent hover:underline text-xs">Edit</button>
                <form method="POST" action="<?= BASE_URL ?>/admin/categories"
                      onsubmit="return confirm('Delete this category? Products will become uncategorized.')">
                    <input type="hidden" name="_action" value="delete">
                    <input type="hidden" name="cat_id" value="<?php echo $cat['id']; ?>">
                    <button type="submit" class="text-red-400 hover:text-red-300 text-xs">Delete</button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<?php
$admin_content = ob_get_clean();
require BASE_PATH . '/src/views/admin/layouts/admin_layout.php';
?>
