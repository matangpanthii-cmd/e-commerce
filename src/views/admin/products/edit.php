<?php
require_once BASE_PATH . '/src/middleware/admin_guard.php';
require_once BASE_PATH . '/src/models/Product.php';
require_once BASE_PATH . '/src/models/Category.php';
require_once BASE_PATH . '/src/helpers/upload.php';

$productModel  = new Product();
$categoryModel = new Category();

// Get product ID from URL: /admin/products/{id}/edit
$segments   = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
$product_id = 0;
foreach ($segments as $k => $seg) {
    if ($seg === 'edit' && isset($segments[$k-1])) {
        $product_id = (int)$segments[$k-1];
    }
}

$product = $productModel->getProductById($product_id);
if (!$product) {
    header("Location: " . BASE_URL . "/admin/products");
    exit;
}

$categories = $categoryModel->getAll();
$errors = [];

// ---- Handle POST ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['_action'] ?? 'update';

    // Delete image
    if ($action === 'delete_image') {
        $image_id = (int)($_POST['image_id'] ?? 0);
        $url = $productModel->deleteImage($image_id);
        if ($url) deleteUploadedFile($url);
        header("Location: " . BASE_URL . "/admin/products/{$product_id}/edit");
        exit;
    }

    // Delete variant
    if ($action === 'delete_variant') {
        $productModel->deleteVariant((int)($_POST['variant_id'] ?? 0));
        header("Location: " . BASE_URL . "/admin/products/{$product_id}/edit");
        exit;
    }

    // Update product
    if ($action === 'update') {
        $name        = trim($_POST['name'] ?? '');
        $category_id = $_POST['category_id'] ?? null;
        $price       = $_POST['price'] ?? '';
        $status      = $_POST['status'] ?? 'active';
        $description = trim($_POST['description'] ?? '');

        if (empty($name))  $errors[] = 'Product name is required.';
        if (!is_numeric($price) || $price <= 0) $errors[] = 'Please enter a valid price.';

        if (empty($errors)) {
            $slug = $productModel->generateUniqueSlug($name, $product_id);
            $productModel->updateProduct($product_id, [
                'category_id' => $category_id ?: null,
                'name'        => $name,
                'slug'        => $slug,
                'description' => $description,
                'price'       => $price,
                'status'      => $status,
            ]);

            // New images
            if (!empty($_FILES['images']['name'][0])) {
                $isFirst = empty($product['images']);
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
                            $productModel->addImage($product_id, $url, $isFirst);
                            $isFirst = false;
                        }
                    }
                }
            }

            // New variants
            $v_colors     = $_POST['variant_color']     ?? [];
            $v_hexs       = $_POST['variant_color_hex'] ?? [];
            $v_sizes      = $_POST['variant_size']      ?? [];
            $v_stocks     = $_POST['variant_stock']     ?? [];
            foreach ($v_colors as $i => $color) {
                if (!empty($color) || !empty($v_sizes[$i])) {
                    $productModel->addVariant($product_id, $color, $v_hexs[$i] ?? '#000000', $v_sizes[$i] ?? '', (int)($v_stocks[$i] ?? 0));
                }
            }

            $_SESSION['flash_success'] = 'Product updated successfully!';
            header("Location: " . BASE_URL . "/admin/products/{$product_id}/edit");
            exit;
        }
    }
    // Refresh product data
    $product = $productModel->getProductById($product_id);
}

$admin_title      = 'Edit Product';
$admin_breadcrumb = '<a href="<?= BASE_URL ?>/admin/products" class="hover:text-admin-accent">Products</a> → Edit';
$admin_flash_success = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_success']);

ob_start();
?>

<div class="max-w-4xl">
<?php if (!empty($errors)): ?>
<div class="mb-6 bg-red-900/40 border border-red-700 text-red-300 px-5 py-4 rounded-lg text-sm">
    <ul class="list-disc pl-4 space-y-1"><?php foreach ($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?></ul>
</div>
<?php endif; ?>

<form method="POST" action="<?= BASE_URL ?>/admin/products/<?php echo $product_id; ?>/edit" enctype="multipart/form-data">
<input type="hidden" name="_action" value="update">

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Col -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Basic Info -->
        <div class="bg-admin-card border border-admin-border rounded-xl p-6">
            <h3 class="text-white font-semibold mb-5">Product Information</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-admin-muted text-xs font-medium uppercase tracking-wider mb-1.5">Product Name *</label>
                    <input type="text" name="name" required value="<?php echo htmlspecialchars($product['name']); ?>"
                           class="w-full bg-admin-bg border border-admin-border text-admin-text text-sm px-4 py-2.5 rounded-lg focus:outline-none focus:border-admin-accent transition">
                </div>
                <div>
                    <label class="block text-admin-muted text-xs font-medium uppercase tracking-wider mb-1.5">Description</label>
                    <textarea name="description" rows="5"
                              class="w-full bg-admin-bg border border-admin-border text-admin-text text-sm px-4 py-2.5 rounded-lg focus:outline-none focus:border-admin-accent transition resize-none"><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>
            </div>
        </div>

        <!-- Current Images -->
        <div class="bg-admin-card border border-admin-border rounded-xl p-6">
            <h3 class="text-white font-semibold mb-5">Images</h3>
            <?php if (!empty($product['images'])): ?>
            <div class="grid grid-cols-4 gap-3 mb-5">
                <?php foreach ($product['images'] as $img): ?>
                <div class="relative group aspect-square bg-admin-bg rounded-lg overflow-hidden border border-admin-border">
                    <img src="<?php echo htmlspecialchars($img['image_url']); ?>" class="w-full h-full object-cover">
                    <?php if ($img['is_primary']): ?>
                    <span class="absolute top-1 left-1 bg-admin-accent text-black text-xs px-1.5 py-0.5 rounded font-bold">Primary</span>
                    <?php endif; ?>
                    <form method="POST" action="<?= BASE_URL ?>/admin/products/<?php echo $product_id; ?>/edit"
                          class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 flex items-center justify-center transition"
                          onsubmit="return confirm('Delete this image?')">
                        <input type="hidden" name="_action" value="delete_image">
                        <input type="hidden" name="image_id" value="<?php echo $img['id']; ?>">
                        <button type="submit" class="bg-red-600 text-white text-xs px-3 py-1.5 rounded-lg hover:bg-red-500 transition">Delete</button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <label class="block cursor-pointer">
                <div class="border-2 border-dashed border-admin-border rounded-xl p-5 text-center hover:border-admin-accent transition">
                    <p class="text-admin-muted text-sm">Upload additional images</p>
                    <input type="file" name="images[]" multiple accept="image/*" class="hidden" onchange="previewImages(this)">
                </div>
            </label>
            <div id="image-previews" class="mt-3 grid grid-cols-4 gap-3"></div>
        </div>

        <!-- Existing Variants -->
        <div class="bg-admin-card border border-admin-border rounded-xl p-6">
            <h3 class="text-white font-semibold mb-5">Variants</h3>
            <?php if (!empty($product['variants'])): ?>
            <div class="space-y-2 mb-5">
                <?php foreach ($product['variants'] as $v): ?>
                <div class="flex items-center justify-between bg-admin-bg border border-admin-border rounded-lg px-4 py-2.5 text-sm">
                    <div class="flex items-center space-x-3">
                        <div class="w-5 h-5 rounded-full border border-admin-border" style="background-color: <?php echo htmlspecialchars($v['color_hex'] ?? '#888'); ?>;"></div>
                        <span class="text-white"><?php echo htmlspecialchars($v['color_name'] ?? '—'); ?></span>
                        <span class="text-admin-muted">·</span>
                        <span class="text-admin-muted">Size: <?php echo htmlspecialchars($v['size'] ?? '—'); ?></span>
                        <span class="text-admin-muted">·</span>
                        <span class="text-admin-muted">Stock: <?php echo $v['stock']; ?></span>
                    </div>
                    <form method="POST" action="<?= BASE_URL ?>/admin/products/<?php echo $product_id; ?>/edit"
                          onsubmit="return confirm('Delete this variant?')">
                        <input type="hidden" name="_action" value="delete_variant">
                        <input type="hidden" name="variant_id" value="<?php echo $v['id']; ?>">
                        <button type="submit" class="text-red-400 hover:text-red-300 text-xs">Delete</button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <div class="border-t border-admin-border pt-4">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-admin-muted text-xs uppercase tracking-wider font-medium">Add New Variants</p>
                    <button type="button" onclick="addVariantRow()"
                            class="text-admin-accent text-sm border border-admin-accent px-3 py-1 rounded-lg hover:bg-admin-accent hover:text-black transition">+ Add</button>
                </div>
                <div id="variant-rows" class="space-y-2"></div>
            </div>
        </div>
    </div>

    <!-- Right Col -->
    <div class="space-y-6">
        <div class="bg-admin-card border border-admin-border rounded-xl p-6">
            <h3 class="text-white font-semibold mb-5">Pricing & Status</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-admin-muted text-xs font-medium uppercase tracking-wider mb-1.5">Price (USD) *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-admin-muted text-sm">$</span>
                        <input type="number" name="price" step="0.01" min="0" required
                               value="<?php echo htmlspecialchars($product['price']); ?>"
                               class="w-full bg-admin-bg border border-admin-border text-admin-text text-sm pl-8 pr-4 py-2.5 rounded-lg focus:outline-none focus:border-admin-accent transition">
                    </div>
                </div>
                <div>
                    <label class="block text-admin-muted text-xs font-medium uppercase tracking-wider mb-1.5">Status</label>
                    <select name="status" class="w-full bg-admin-bg border border-admin-border text-admin-text text-sm px-4 py-2.5 rounded-lg focus:outline-none focus:border-admin-accent transition">
                        <?php foreach (['active','new_in','sale','inactive'] as $s): ?>
                        <option value="<?php echo $s; ?>" <?php echo $product['status']===$s ? 'selected' : ''; ?>>
                            <?php echo ucfirst(str_replace('_',' ',$s)); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="bg-admin-card border border-admin-border rounded-xl p-6">
            <h3 class="text-white font-semibold mb-5">Category</h3>
            <select name="category_id" class="w-full bg-admin-bg border border-admin-border text-admin-text text-sm px-4 py-2.5 rounded-lg focus:outline-none focus:border-admin-accent transition">
                <option value="">— No Category —</option>
                <?php foreach ($categories as $cat): ?>
                <option value="<?php echo $cat['id']; ?>" <?php echo $product['category_id'] == $cat['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($cat['name']); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="flex flex-col gap-3">
            <button type="submit" class="w-full bg-admin-accent text-black py-3 rounded-lg font-semibold text-sm hover:bg-yellow-400 transition">
                Save Changes
            </button>
            <a href="<?= BASE_URL ?>/product/<?php echo $product['slug']; ?>" target="_blank"
               class="w-full text-center border border-admin-border text-admin-muted py-2.5 rounded-lg text-sm hover:border-admin-accent hover:text-admin-accent transition">
                View on Store ↗
            </a>
            <a href="<?= BASE_URL ?>/admin/products" class="w-full text-center border border-admin-border text-admin-muted py-2.5 rounded-lg text-sm hover:border-admin-text transition">
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
    Array.from(input.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.className = 'aspect-square bg-admin-bg rounded-lg overflow-hidden border border-admin-border';
            div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
            container.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}
function addVariantRow() {
    const row = document.createElement('div');
    row.className = 'grid grid-cols-12 gap-2 items-center';
    row.innerHTML = `
        <div class="col-span-4"><input type="text" name="variant_color[]" placeholder="Color" class="w-full bg-admin-bg border border-admin-border text-admin-text text-xs px-3 py-2 rounded-lg focus:outline-none focus:border-admin-accent placeholder-admin-muted"></div>
        <div class="col-span-2"><input type="color" name="variant_color_hex[]" value="#000000" class="w-full h-9 bg-admin-bg border border-admin-border rounded-lg cursor-pointer"></div>
        <div class="col-span-3"><input type="text" name="variant_size[]" placeholder="Size" class="w-full bg-admin-bg border border-admin-border text-admin-text text-xs px-3 py-2 rounded-lg focus:outline-none focus:border-admin-accent placeholder-admin-muted"></div>
        <div class="col-span-2"><input type="number" name="variant_stock[]" value="0" min="0" class="w-full bg-admin-bg border border-admin-border text-admin-text text-xs px-3 py-2 rounded-lg focus:outline-none focus:border-admin-accent"></div>
        <div class="col-span-1 text-center"><button type="button" onclick="this.closest('div.grid').remove()" class="text-red-400 hover:text-red-300 text-lg">×</button></div>
    `;
    document.getElementById('variant-rows').appendChild(row);
}
</script>

<?php
$admin_content = ob_get_clean();
require BASE_PATH . '/src/views/admin/layouts/admin_layout.php';
?>
