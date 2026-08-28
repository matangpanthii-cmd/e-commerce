<?php
ob_start();
require_once BASE_PATH . '/src/models/Product.php';
require_once BASE_PATH . '/src/models/Category.php';

$productModel = new Product();
$categoryModel = new Category();

$categorySlug = $_GET['category'] ?? null;
$sort = $_GET['sort'] ?? 'recommended';

// Fetch all categories for the filter bar
$categories = $categoryModel->getAll();

// We need to map slug to ID if the user clicks a category (since the DB might use IDs or Slugs)
$categoryId = null;
if ($categorySlug) {
    foreach ($categories as $cat) {
        if ($cat['slug'] === $categorySlug) {
            $categoryId = $cat['id'];
            break;
        }
    }
}

// Pass category_id if found, otherwise null (or raw slug if the model handles it)
// Looking at getAllProducts it accepts $category_id
$products = $productModel->getAllProducts($categoryId, $sort);
?>

<!-- Page Header -->
<div class="bg-PRAIRAVEE-cream pt-32 pb-16 relative overflow-hidden" data-aos="fade-down">
    <!-- Subtle glow behind header -->
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[800px] h-[400px] bg-PRAIRAVEE-green/5 rounded-full blur-3xl"></div>
    
    <div class="container mx-auto px-6 lg:px-12 text-center relative z-10">
        <h1 class="text-4xl md:text-5xl font-serif font-bold mb-4 text-PRAIRAVEE-green">สินค้าทั้งหมด</h1>
        <p class="text-gray-500 max-w-xl mx-auto text-sm md:text-base font-light leading-relaxed">
            ยาดมสมุนไพรไทย คัดสรรวัตถุดิบคุณภาพ หอมสดชื่น อ่อนโยน ในทุกลมหายใจ เพื่อความผ่อนคลายที่เหนือระดับ
        </p>
        
        <!-- Breadcrumb -->
        <div class="mt-8 text-xs flex items-center justify-center space-x-3 text-gray-400 uppercase tracking-widest font-semibold">
            <a href="<?= BASE_URL ?>/" class="hover:text-PRAIRAVEE-green transition">Home</a>
            <span class="w-1 h-1 rounded-full bg-PRAIRAVEE-gold"></span>
            <span class="text-PRAIRAVEE-green">Products</span>
        </div>
    </div>
</div>

<!-- Category Quick Filter (Glassmorphism Sticky) -->
<div class="bg-white/80 backdrop-blur-md border-y border-white/20 sticky top-[68px] z-40 shadow-sm transition-all duration-300">
    <div class="container mx-auto px-6 overflow-x-auto hide-scrollbar">
        <div class="flex items-center space-x-2 py-4 min-w-max justify-center">
            <a href="<?= BASE_URL ?>/products"
                class="px-6 py-2 rounded-full text-xs font-semibold tracking-wider transition-all duration-300 <?= !$categorySlug ? 'bg-PRAIRAVEE-green text-white shadow-soft' : 'text-gray-500 hover:text-PRAIRAVEE-green hover:bg-PRAIRAVEE-light/50' ?>">ALL COLLECTION</a>
            
            <?php foreach ($categories as $cat): ?>
                <a href="<?= BASE_URL ?>/products?category=<?= htmlspecialchars($cat['slug']) ?>"
                    class="px-6 py-2 rounded-full text-xs font-semibold tracking-wider transition-all duration-300 <?= $categorySlug === $cat['slug'] ? 'bg-PRAIRAVEE-green text-white shadow-soft' : 'text-gray-500 hover:text-PRAIRAVEE-green hover:bg-PRAIRAVEE-light/50' ?>">
                    <?= htmlspecialchars($cat['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="container mx-auto px-6 lg:px-12 py-10">

    <div class="flex flex-col md:flex-row justify-between items-center mb-10 border-b border-gray-100 pb-4">
        <p class="text-xs text-gray-400 tracking-widest uppercase font-semibold mb-4 md:mb-0"><?php echo count($products); ?> Products</p>
        <div class="relative">
            <select onchange="window.location.href='?category=<?php echo urlencode($categorySlug ?? ''); ?>&sort='+this.value"
                class="text-sm border-none bg-PRAIRAVEE-light/30 py-2.5 pl-5 pr-10 focus:outline-none focus:ring-2 focus:ring-PRAIRAVEE-gold/50 rounded-full text-PRAIRAVEE-green font-medium cursor-pointer appearance-none transition-all duration-300 hover:bg-PRAIRAVEE-light/50">
                <option value="recommended" <?php echo $sort == 'recommended' ? 'selected' : ''; ?>>เรียงตาม: แนะนำ</option>
                <option value="newest" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>เรียงตาม: ใหม่ล่าสุด</option>
                <option value="price_asc" <?php echo $sort == 'price_asc' ? 'selected' : ''; ?>>ราคา: น้อย → มาก</option>
                <option value="price_desc" <?php echo $sort == 'price_desc' ? 'selected' : ''; ?>>ราคา: มาก → น้อย</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-PRAIRAVEE-green">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </div>
    </div>

    <?php if (empty($products)): ?>
        <div class="text-center py-20">
            <div class="w-16 h-16 bg-PRAIRAVEE-light rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-PRAIRAVEE-green" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h2 class="text-xl font-medium text-gray-600 mb-2">ไม่พบสินค้า</h2>
            <p class="text-gray-400 mb-6">ลองค้นหาด้วยหมวดหมู่อื่น</p>
            <a href="<?= BASE_URL ?>/products"
                class="inline-block px-6 py-2 bg-PRAIRAVEE-green text-white rounded-full text-sm hover:bg-opacity-90 transition">ดูสินค้าทั้งหมด</a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($products as $index => $product): ?>
                <div class="group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-soft transition-all duration-500 hover:-translate-y-2 relative" data-aos="fade-up" data-aos-delay="<?= ($index % 4) * 100 ?>">
                    
                    <?php if (isset($product['status']) && $product['status'] === 'new_in'): ?>
                        <span class="absolute top-4 left-4 z-10 bg-PRAIRAVEE-gold text-white px-3 py-1 text-[10px] uppercase tracking-widest font-semibold rounded-full shadow-sm">New</span>
                    <?php elseif (isset($product['status']) && $product['status'] === 'sale'): ?>
                        <span class="absolute top-4 left-4 z-10 bg-[#e05d5d] text-white px-3 py-1 text-[10px] uppercase tracking-widest font-semibold rounded-full shadow-sm">Sale</span>
                    <?php endif; ?>
                    
                    <a href="<?= BASE_URL ?>/product/<?= $product['slug'] ?>" class="block relative aspect-square overflow-hidden bg-[#fdfdfd]">
                        <img src="<?= htmlspecialchars($product['primary_image'] ?? 'https://via.placeholder.com/400x400?text=PRAIRAVEE') ?>"
                            alt="<?= htmlspecialchars($product['name']) ?>"
                            class="w-full h-full object-cover mix-blend-multiply transform group-hover:scale-105 transition duration-700 ease-out">
                        <!-- Hover Overlay -->
                        <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition duration-300"></div>
                    </a>
                    <div class="p-6 text-center">
                        <?php 
                            // Find category name dynamically
                            $catName = 'ยาดมสมุนไพร';
                            foreach ($categories as $cat) {
                                if ($cat['id'] == $product['category_id']) {
                                    $catName = $cat['name'];
                                    break;
                                }
                            }
                        ?>
                        <p class="text-[11px] text-gray-400 uppercase tracking-widest mb-2 font-medium"><?= htmlspecialchars($catName) ?></p>
                        <a href="<?= BASE_URL ?>/product/<?= $product['slug'] ?>">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1 line-clamp-1 group-hover:text-PRAIRAVEE-green transition"><?= htmlspecialchars($product['name']) ?></h3>
                        </a>
                        <p class="text-sm text-gray-500 mb-4 line-clamp-2"><?= htmlspecialchars($product['description'] ?? '') ?></p>
                        <div class="flex items-center justify-center space-x-3">
                            <span class="text-lg font-serif font-bold text-PRAIRAVEE-green">฿<?= number_format($product['price'], 2) ?></span>
                            
                            <!-- Add to cart quick action -->
                            <form action="<?= BASE_URL ?>/cart/add" method="POST" class="inline">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-8 h-8 rounded-full bg-PRAIRAVEE-light flex items-center justify-center text-PRAIRAVEE-green hover:bg-PRAIRAVEE-green hover:text-white transition duration-300 transform group-hover:scale-110">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require BASE_PATH . '/src/views/layouts/main.php';
?>