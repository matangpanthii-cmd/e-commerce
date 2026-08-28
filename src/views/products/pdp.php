<?php 
ob_start(); 
require_once BASE_PATH . '/src/models/Product.php';
$productModel = new Product();
$product = $productModel->getProductBySlug($product_slug);

if (!$product) {
    http_response_code(404);
    require BASE_PATH . '/src/views/404.php';
    exit;
}

// Extract variants
$colors = [];
$sizes = [];
foreach ($product['variants'] as $variant) {
    if (!isset($colors[$variant['color_name']])) {
        $colors[$variant['color_name']] = $variant['color_hex'];
    }
    if (!in_array($variant['size'], $sizes)) {
        $sizes[] = $variant['size'];
    }
}
$default_color = !empty($colors) ? array_key_first($colors) : '';
$default_size = !empty($sizes) ? $sizes[0] : '';
?>

<!-- Breadcrumbs -->
<div class="bg-PRAIRAVEE-cream border-b border-gray-200">
    <div class="container mx-auto px-6 lg:px-12 py-4">
        <nav class="text-sm text-gray-500 font-medium">
            <a href="<?= BASE_URL ?>/" class="hover:text-PRAIRAVEE-gold transition">หน้าแรก</a>
            <span class="mx-2">/</span>
            <a href="<?= BASE_URL ?>/products" class="hover:text-PRAIRAVEE-gold transition">สินค้าทั้งหมด</a>
            <span class="mx-2">/</span>
            <span class="text-PRAIRAVEE-green"><?php echo htmlspecialchars($product['name']); ?></span>
        </nav>
    </div>
</div>

<div class="container mx-auto px-6 lg:px-12 py-12" x-data="{
    activeTab: 'details',
    selectedColor: '<?php echo $default_color; ?>',
    selectedSize: '<?php echo $default_size; ?>',
    quantity: 1,
    activeImage: '<?php echo htmlspecialchars($product['images'][0]['image_url'] ?? 'https://via.placeholder.com/800x1000?text=No+Image'); ?>',
    images: [
        <?php foreach($product['images'] as $img): ?>
        '<?php echo htmlspecialchars($img['image_url']); ?>',
        <?php endforeach; ?>
    ]
}">
    <div class="flex flex-col lg:flex-row gap-16">
        
        <!-- High-Res Gallery -->
        <div class="w-full lg:w-3/5 flex flex-col-reverse md:flex-row gap-4">
            <!-- Thumbnails -->
            <div class="flex md:flex-col gap-4 overflow-x-auto md:overflow-y-auto w-full md:w-24 shrink-0 no-scrollbar">
                <template x-for="img in images" :key="img">
                    <button @click="activeImage = img" :class="{'border-2 border-PRAIRAVEE-green': activeImage === img}" class="h-32 w-24 shrink-0 overflow-hidden cursor-pointer rounded-sm border-2 border-transparent">
                        <img :src="img" class="w-full h-full object-cover">
                    </button>
                </template>
            </div>
            
            <!-- Primary Image -->
            <div class="flex-grow bg-gray-100 overflow-hidden rounded-sm group relative">
                <img :src="activeImage" class="w-full h-[600px] lg:h-[800px] object-cover cursor-zoom-in group-hover:scale-105 transition duration-700">
            </div>
        </div>

        <!-- Product Info & Actions -->
        <div class="w-full lg:w-2/5 flex flex-col">
            <h1 class="text-3xl font-bold mb-2 tracking-wide"><?php echo htmlspecialchars($product['name']); ?></h1>
            <p class="text-2xl text-gray-700 font-light mb-6">$<?php echo number_format($product['price'], 2); ?></p>
            
            <p class="text-gray-500 text-sm leading-relaxed mb-8">
                <?php echo htmlspecialchars($product['description']); ?>
            </p>

            <form action="<?= BASE_URL ?>/cart/add" method="POST">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <!-- Color Variants -->
                <?php if(!empty($colors)): ?>
                <div class="mb-8">
                    <div class="flex justify-between mb-3">
                        <span class="text-sm font-semibold uppercase tracking-wider">สี (Color)</span>
                        <span class="text-sm text-gray-500 capitalize" x-text="selectedColor"></span>
                    </div>
                    <div class="flex space-x-3">
                        <?php foreach($colors as $name => $hex): ?>
                        <button type="button" @click="selectedColor = '<?php echo htmlspecialchars($name); ?>'" :class="{'ring-2 ring-offset-2 ring-PRAIRAVEE-green': selectedColor === '<?php echo htmlspecialchars($name); ?>'}" class="w-8 h-8 rounded-full shadow-sm border border-gray-200 focus:outline-none" style="background-color: <?php echo htmlspecialchars($hex); ?>;"></button>
                        <?php endforeach; ?>
                    </div>
                    <input type="hidden" name="color" :value="selectedColor">
                </div>
                <?php endif; ?>

                <!-- Size Picker -->
                <?php if(!empty($sizes)): ?>
                <div class="mb-8">
                    <div class="flex justify-between mb-3">
                        <span class="text-sm font-semibold uppercase tracking-wider">ขนาด (Size)</span>
                        <a href="#" class="text-sm text-gray-500 underline hover:text-PRAIRAVEE-gold transition">แนะนำขนาด (Size Guide)</a>
                    </div>
                    <div class="grid grid-cols-5 gap-3">
                        <?php foreach($sizes as $size): ?>
                        <button type="button" @click="selectedSize = '<?php echo htmlspecialchars($size); ?>'" :class="{'bg-PRAIRAVEE-green text-white border-PRAIRAVEE-green': selectedSize === '<?php echo htmlspecialchars($size); ?>', 'border-gray-300 text-gray-700 hover:border-gray-400': selectedSize !== '<?php echo htmlspecialchars($size); ?>'}" class="py-3 border text-sm transition"><?php echo htmlspecialchars($size); ?></button>
                        <?php endforeach; ?>
                    </div>
                    <input type="hidden" name="size" :value="selectedSize">
                </div>
                <?php endif; ?>

                <!-- Add to Cart & Quantity -->
                <div class="flex space-x-4 mb-12">
                    <!-- Quantity -->
                    <div class="flex items-center border border-gray-300">
                        <button type="button" @click="if(quantity > 1) quantity--" class="px-4 py-3 text-gray-500 hover:text-PRAIRAVEE-green transition">-</button>
                        <span class="px-4 py-3 text-sm w-12 text-center" x-text="quantity"></span>
                        <button type="button" @click="quantity++" class="px-4 py-3 text-gray-500 hover:text-PRAIRAVEE-green transition">+</button>
                        <input type="hidden" name="quantity" :value="quantity">
                    </div>
                    
                    <!-- Add Button -->
                    <button type="submit" class="flex-grow bg-PRAIRAVEE-green text-white font-medium uppercase tracking-wider text-sm hover:bg-opacity-90 transition shadow-lg">
                        หยิบใส่ตะกร้า
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require BASE_PATH . '/src/views/layouts/main.php'; 
?>
