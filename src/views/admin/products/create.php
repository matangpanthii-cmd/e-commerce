<?php
require_once BASE_PATH . '/src/middleware/admin_guard.php';
require_once BASE_PATH . '/src/models/Product.php';
require_once BASE_PATH . '/src/models/Category.php';
require_once BASE_PATH . '/src/helpers/upload.php';

$productModel  = new Product();
$categoryModel = new Category();
$categories    = $categoryModel->getAll();

$errors = [];
$form   = [
    'name'        => '',
    'category_id' => '',
    'price'       => '',
    'status'      => 'active',
    'description' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form['name']        = trim($_POST['name'] ?? '');
    $form['category_id'] = $_POST['category_id'] ?? '';
    $form['price']       = $_POST['price'] ?? '';
    $form['status']      = $_POST['status'] ?? 'active';
    $form['description'] = trim($_POST['description'] ?? '');

    if (empty($form['name']))  $errors[] = 'Product name is required.';
    if (!is_numeric($form['price']) || $form['price'] <= 0) $errors[] = 'Please enter a valid price.';

    if (empty($errors)) {
        $slug       = $productModel->generateUniqueSlug($form['name']);
        $product_id = $productModel->createProduct([
            'category_id' => $form['category_id'] ?: null,
            'name'        => $form['name'],
            'slug'        => $slug,
            'description' => $form['description'],
            'price'       => $form['price'],
            'status'      => $form['status'],
        ]);

        if ($product_id) {
            // Handle image uploads
            if (!empty($_FILES['images']['name'][0])) {
                $first = true;
                foreach ($_FILES['images']['tmp_name'] as $i => $tmp) {
                    if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                        $upload_err = '';
                        $file_arr = [
                            'name'     => $_FILES['images']['name'][$i],
                            'tmp_name' => $tmp,
                            'error'    => $_FILES['images']['error'][$i],
                            'size'     => $_FILES['images']['size'][$i],
                            'type'     => $_FILES['images']['type'][$i],
                        ];
                        $url = handleImageUpload($file_arr, $upload_err);
                        if ($url) {
                            $productModel->addImage($product_id, $url, $first);
                            $first = false;
                        }
                    }
                }
            }

            // Handle variants
            $v_colors     = $_POST['variant_color']     ?? [];
            $v_color_hexs = $_POST['variant_color_hex'] ?? [];
            $v_sizes      = $_POST['variant_size']      ?? [];
            $v_stocks     = $_POST['variant_stock']     ?? [];
            foreach ($v_colors as $i => $color) {
                if (!empty($color) || !empty($v_sizes[$i])) {
                    $productModel->addVariant(
                        $product_id,
                        $color,
                        $v_color_hexs[$i] ?? '#000000',
                        $v_sizes[$i] ?? '',
                        (int)($v_stocks[$i] ?? 0)
                    );
                }
            }

            $_SESSION['flash_success'] = 'Product "' . $form['name'] . '" created successfully!';
            header("Location: " . BASE_URL . "/admin/products");
            exit;
        } else {
            $errors[] = 'Failed to create product. Please try again.';
        }
    }
}

$admin_title      = 'Add Product';
$admin_breadcrumb = '<a href="<?= BASE_URL ?>/admin/products" class="hover:text-admin-accent">Products</a> → Add New';
ob_start();
?>

<div class="max-w-4xl">
<?php if (!empty($errors)): ?>
<div class="mb-6 bg-red-900/40 border border-red-700 text-red-300 px-5 py-4 rounded-lg text-sm">
    <ul class="list-disc pl-4 space-y-1">
        <?php foreach ($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form method="POST" action="<?= BASE_URL ?>/admin/products/create" enctype="multipart/form-data" id="create-product-form">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Col: Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-admin-card border border-admin-border rounded-xl p-6">
                <h3 class="text-white font-semibold mb-5">Product Information</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-admin-muted text-xs font-medium uppercase tracking-wider mb-1.5">Product Name *</label>
                        <input type="text" name="name" required
                               value="<?php echo htmlspecialchars($form['name']); ?>"
                               class="w-full bg-admin-bg border border-admin-border text-admin-text text-sm px-4 py-2.5 rounded-lg focus:outline-none focus:border-admin-accent transition placeholder-admin-muted"
                               placeholder="e.g. Navy Cashmere Overcoat">
                    </div>
                    <div>
                        <label class="block text-admin-muted text-xs font-medium uppercase tracking-wider mb-1.5">Description</label>
                        <textarea name="description" rows="5"
                                  class="w-full bg-admin-bg border border-admin-border text-admin-text text-sm px-4 py-2.5 rounded-lg focus:outline-none focus:border-admin-accent transition resize-none placeholder-admin-muted"
                                  placeholder="Describe this product..."><?php echo htmlspecialchars($form['description']); ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Images -->
            <div class="bg-admin-card border border-admin-border rounded-xl p-6">
                <h3 class="text-white font-semibold mb-5">Product Images</h3>
                <label class="block cursor-pointer">
                    <div class="border-2 border-dashed border-admin-border rounded-xl p-8 text-center hover:border-admin-accent transition" id="drop-zone">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-admin-muted mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-admin-muted text-sm">Click to upload images</p>
                        <p class="text-admin-muted text-xs mt-1">JPG, PNG, WebP up to 5MB each. First image = primary.</p>
                        <input type="file" name="images[]" multiple accept="image/*" class="hidden" id="image-input"
                               onchange="previewImages(this)">
                    </div>
                </label>
                <div id="image-previews" class="mt-4 grid grid-cols-4 gap-3"></div>
            </div>

            <!-- Variants -->
            <div class="bg-admin-card border border-admin-border rounded-xl p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-white font-semibold">Variants (Color / Size / Stock)</h3>
                    <button type="button" onclick="addVariantRow()"
                            class="text-admin-accent text-sm border border-admin-accent px-3 py-1 rounded-lg hover:bg-admin-accent hover:text-black transition">
                        + Add Variant
                    </button>
                </div>
                <div id="variant-rows" class="space-y-3">
                    <!-- Rows added by JS -->
                </div>
                <p class="text-admin-muted text-xs mt-3">Leave empty if no variants needed.</p>
            </div>
        </div>

        <!-- Right Col: Meta -->
        <div class="space-y-6">
            <div class="bg-admin-card border border-admin-border rounded-xl p-6">
                <h3 class="text-white font-semibold mb-5">Pricing & Status</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-admin-muted text-xs font-medium uppercase tracking-wider mb-1.5">Price (USD) *</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-admin-muted text-sm">$</span>
                            <input type="number" name="price" step="0.01" min="0" required
                                   value="<?php echo htmlspecialchars($form['price']); ?>"
                                   class="w-full bg-admin-bg border border-admin-border text-admin-text text-sm pl-8 pr-4 py-2.5 rounded-lg focus:outline-none focus:border-admin-accent transition"
                                   placeholder="0.00">
                        </div>
                    </div>
                    <div>
                        <label class="block text-admin-muted text-xs font-medium uppercase tracking-wider mb-1.5">Status</label>
                        <select name="status" class="w-full bg-admin-bg border border-admin-border text-admin-text text-sm px-4 py-2.5 rounded-lg focus:outline-none focus:border-admin-accent transition">
                            <option value="active"   <?php echo $form['status']==='active'   ? 'selected' : ''; ?>>Active</option>
                            <option value="new_in"   <?php echo $form['status']==='new_in'   ? 'selected' : ''; ?>>New In</option>
                            <option value="sale"     <?php echo $form['status']==='sale'     ? 'selected' : ''; ?>>Sale</option>
                            <option value="inactive" <?php echo $form['status']==='inactive' ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-admin-card border border-admin-border rounded-xl p-6">
                <h3 class="text-white font-semibold mb-5">Category</h3>
                <select name="category_id" class="w-full bg-admin-bg border border-admin-border text-admin-text text-sm px-4 py-2.5 rounded-lg focus:outline-none focus:border-admin-accent transition">
                    <option value="">— No Category —</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php echo $form['category_id'] == $cat['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <a href="<?= BASE_URL ?>/admin/categories" class="text-admin-accent text-xs hover:underline mt-2 block">Manage categories →</a>
            </div>

            <div class="flex flex-col gap-3">
                <button type="submit" class="w-full bg-admin-accent text-black py-3 rounded-lg font-semibold text-sm hover:bg-yellow-400 transition">
                    Create Product
                </button>
                <a href="<?= BASE_URL ?>/admin/products" class="w-full text-center border border-admin-border text-admin-muted py-3 rounded-lg text-sm hover:border-admin-text transition">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</form>
</div>

<script>
function previewImages(input) {
    const container = document.getElementById('image-previews');
    container.innerHTML = '';
    Array.from(input.files).forEach((file, i) => {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.className = 'relative aspect-square bg-admin-bg rounded-lg overflow-hidden border border-admin-border';
            div.innerHTML = `
                <img src="${e.target.result}" class="w-full h-full object-cover">
                ${i === 0 ? '<span class="absolute top-1 left-1 bg-admin-accent text-black text-xs px-1.5 py-0.5 rounded font-bold">Primary</span>' : ''}
            `;
            container.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

let variantCount = 0;
function addVariantRow() {
    const i = variantCount++;
    const row = document.createElement('div');
    row.className = 'grid grid-cols-12 gap-2 items-center';
    row.innerHTML = `
        <div class="col-span-4">
            <input type="text" name="variant_color[]" placeholder="Color name"
                   class="w-full bg-admin-bg border border-admin-border text-admin-text text-xs px-3 py-2 rounded-lg focus:outline-none focus:border-admin-accent transition placeholder-admin-muted">
        </div>
        <div class="col-span-2">
            <input type="color" name="variant_color_hex[]" value="#000000"
                   class="w-full h-9 bg-admin-bg border border-admin-border rounded-lg cursor-pointer">
        </div>
        <div class="col-span-3">
            <input type="text" name="variant_size[]" placeholder="Size (S/M/L)"
                   class="w-full bg-admin-bg border border-admin-border text-admin-text text-xs px-3 py-2 rounded-lg focus:outline-none focus:border-admin-accent transition placeholder-admin-muted">
        </div>
        <div class="col-span-2">
            <input type="number" name="variant_stock[]" value="0" min="0"
                   class="w-full bg-admin-bg border border-admin-border text-admin-text text-xs px-3 py-2 rounded-lg focus:outline-none focus:border-admin-accent transition">
        </div>
        <div class="col-span-1 text-center">
            <button type="button" onclick="this.closest('div.grid').remove()" class="text-red-400 hover:text-red-300 text-lg leading-none">×</button>
        </div>
    `;
    document.getElementById('variant-rows').appendChild(row);
}
// Add one row by default
addVariantRow();
</script>

<?php
$admin_content = ob_get_clean();
require BASE_PATH . '/src/views/admin/layouts/admin_layout.php';
?>
