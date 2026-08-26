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
<div class="bg-lumina-surface border-b border-gray-200">
    <div class="container mx-auto px-6 lg:px-12 py-4">
        <nav class="text-sm text-gray-500 font-medium">
            <a href="/pj2/public/" class="hover:text-lumina-gold transition">Home</a>
            <span class="mx-2">/</span>
            <a href="/pj2/public/products" class="hover:text-lumina-gold transition">Men's Tailoring</a>
            <span class="mx-2">/</span>
            <span class="text-lumina-navy"><?php echo htmlspecialchars($product['name']); ?></span>
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
                    <button @click="activeImage = img" :class="{'border-2 border-lumina-navy': activeImage === img}" class="h-32 w-24 shrink-0 overflow-hidden cursor-pointer rounded-sm border-2 border-transparent">
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

            <form action="/pj2/public/cart/add" method="POST">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <!-- Color Variants -->
                <?php if(!empty($colors)): ?>
                <div class="mb-8">
                    <div class="flex justify-between mb-3">
                        <span class="text-sm font-semibold uppercase tracking-wider">Color</span>
                        <span class="text-sm text-gray-500 capitalize" x-text="selectedColor"></span>
                    </div>
                    <div class="flex space-x-3">
                        <?php foreach($colors as $name => $hex): ?>
                        <button type="button" @click="selectedColor = '<?php echo htmlspecialchars($name); ?>'" :class="{'ring-2 ring-offset-2 ring-lumina-navy': selectedColor === '<?php echo htmlspecialchars($name); ?>'}" class="w-8 h-8 rounded-full shadow-sm border border-gray-200 focus:outline-none" style="background-color: <?php echo htmlspecialchars($hex); ?>;"></button>
                        <?php endforeach; ?>
                    </div>
                    <input type="hidden" name="color" :value="selectedColor">
                </div>
                <?php endif; ?>

                <!-- Size Picker -->
                <?php if(!empty($sizes)): ?>
                <div class="mb-8">
                    <div class="flex justify-between mb-3">
                        <span class="text-sm font-semibold uppercase tracking-wider">Size</span>
                        <a href="#" class="text-sm text-gray-500 underline hover:text-lumina-gold transition">Size Guide</a>
                    </div>
                    <div class="grid grid-cols-5 gap-3">
                        <?php foreach($sizes as $size): ?>
                        <button type="button" @click="selectedSize = '<?php echo htmlspecialchars($size); ?>'" :class="{'bg-lumina-navy text-white border-lumina-navy': selectedSize === '<?php echo htmlspecialchars($size); ?>', 'border-gray-300 text-gray-700 hover:border-gray-400': selectedSize !== '<?php echo htmlspecialchars($size); ?>'}" class="py-3 border text-sm transition"><?php echo htmlspecialchars($size); ?></button>
                        <?php endforeach; ?>
                    </div>
                    <input type="hidden" name="size" :value="selectedSize">
                </div>
                <?php endif; ?>

                <!-- Add to Cart & Quantity -->
                <div class="flex space-x-4 mb-12">
                    <!-- Quantity -->
                    <div class="flex items-center border border-gray-300">
                        <button type="button" @click="if(quantity > 1) quantity--" class="px-4 py-3 text-gray-500 hover:text-lumina-navy transition">-</button>
                        <span class="px-4 py-3 text-sm w-12 text-center" x-text="quantity"></span>
                        <button type="button" @click="quantity++" class="px-4 py-3 text-gray-500 hover:text-lumina-navy transition">+</button>
                        <input type="hidden" name="quantity" :value="quantity">
                    </div>
                    
                    <!-- Add Button -->
                    <button type="submit" class="flex-grow bg-lumina-navy text-white font-medium uppercase tracking-wider text-sm hover:bg-opacity-90 transition shadow-lg">
                        Add to Cart
                    </button>
                </div>
            </form>

            <!-- Accordion Details -->
            <div class="border-t border-gray-200">
                <!-- Details & Care -->
                <div class="border-b border-gray-200">
                    <button @click="activeTab = activeTab === 'details' ? '' : 'details'" class="flex justify-between items-center w-full py-4 text-left font-semibold uppercase tracking-wider text-sm">
                        <span>Details & Care</span>
                        <svg x-show="activeTab !== 'details'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        <svg x-show="activeTab === 'details'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" style="display: none;">
                            <path fill-rule="evenodd" d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="activeTab === 'details'" class="pb-4 text-sm text-gray-600 leading-relaxed" x-transition>
                        <ul class="list-disc pl-5 space-y-2">
                            <li>100% Italian Cashmere</li>
                            <li>Notched lapels, single-breasted button fastening</li>
                            <li>Two front flap pockets, one chest welt pocket</li>
                            <li>Fully lined in cupro</li>
                            <li>Dry clean only</li>
                            <li>Made in Italy</li>
                        </ul>
                    </div>
                </div>

                <!-- Shipping & Returns -->
                <div class="border-b border-gray-200">
                    <button @click="activeTab = activeTab === 'shipping' ? '' : 'shipping'" class="flex justify-between items-center w-full py-4 text-left font-semibold uppercase tracking-wider text-sm">
                        <span>Shipping & Returns</span>
                        <svg x-show="activeTab !== 'shipping'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        <svg x-show="activeTab === 'shipping'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" style="display: none;">
                            <path fill-rule="evenodd" d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="activeTab === 'shipping'" class="pb-4 text-sm text-gray-600 leading-relaxed" x-transition style="display: none;">
                        <p class="mb-2">Complimentary express shipping on all orders over $500.</p>
                        <p>We accept returns within 14 days of delivery. The item must be in its original, unworn condition with all tags attached.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    
    <!-- Complete the Look / Cross-Selling -->
    <div class="mt-32">
        <div class="text-center mb-12">
            <h2 class="text-2xl font-bold tracking-wide mb-4">Complete the Look</h2>
            <div class="w-12 h-1 bg-lumina-gold mx-auto"></div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 max-w-4xl mx-auto">
            <!-- Cross-sell 1 -->
            <a href="#" class="group cursor-pointer">
                <div class="bg-gray-100 overflow-hidden mb-4">
                    <img src="https://images.unsplash.com/photo-1596455607563-ad6193f76b11?q=80&w=1760&auto=format&fit=crop" class="w-full h-72 object-cover group-hover:scale-105 transition duration-500">
                </div>
                <div class="text-center">
                    <h3 class="text-sm font-semibold">Merino Wool Turtleneck</h3>
                    <p class="text-gray-500 text-sm">$250.00</p>
                </div>
            </a>
            <!-- Cross-sell 2 -->
            <a href="#" class="group cursor-pointer">
                <div class="bg-gray-100 overflow-hidden mb-4">
                    <img src="https://images.unsplash.com/photo-1620806877994-6b22c7104e9c?q=80&w=1740&auto=format&fit=crop" class="w-full h-72 object-cover group-hover:scale-105 transition duration-500">
                </div>
                <div class="text-center">
                    <h3 class="text-sm font-semibold">Tailored Wool Trousers</h3>
                    <p class="text-gray-500 text-sm">$320.00</p>
                </div>
            </a>
            <!-- Cross-sell 3 -->
            <a href="#" class="group cursor-pointer">
                <div class="bg-gray-100 overflow-hidden mb-4">
                    <img src="https://images.unsplash.com/photo-1614252209355-6b23d906e57f?q=80&w=1964&auto=format&fit=crop" class="w-full h-72 object-cover group-hover:scale-105 transition duration-500">
                </div>
                <div class="text-center">
                    <h3 class="text-sm font-semibold">Leather Chelsea Boots</h3>
                    <p class="text-gray-500 text-sm">$590.00</p>
                </div>
            </a>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require BASE_PATH . '/src/views/layouts/main.php'; 
?>
