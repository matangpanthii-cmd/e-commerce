<?php 
ob_start(); 
require_once BASE_PATH . '/src/models/Product.php';
$productModel = new Product();
$trendingProducts = $productModel->getTrendingProducts(4);
?>

<!-- Hero Section -->
<section class="relative h-[80vh] flex items-center justify-center overflow-hidden">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=2070&auto=format&fit=crop" alt="Hero Background" class="w-full h-full object-cover object-center opacity-90">
        <div class="absolute inset-0 bg-black bg-opacity-30"></div>
    </div>
    
    <!-- Hero Content -->
    <div class="relative z-10 text-center text-white px-6">
        <h1 class="text-5xl md:text-7xl font-bold tracking-tight mb-6">Effortless Sophistication</h1>
        <p class="text-lg md:text-xl font-light mb-10 max-w-2xl mx-auto">Discover the new season collection. Tailored for the modern individual who values craftsmanship and timeless style.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="<?= BASE_URL ?>/products?category=men" class="px-8 py-3 bg-white text-lumina-navy font-medium hover:bg-gray-100 transition rounded-sm">Shop Men</a>
            <a href="<?= BASE_URL ?>/products?category=women" class="px-8 py-3 bg-transparent border border-white text-white font-medium hover:bg-white hover:text-lumina-navy transition rounded-sm">Shop Women</a>
        </div>
    </div>
</section>

<!-- Curated Collections -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-6 lg:px-12">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold tracking-wide mb-4">Curated Collections</h2>
            <div class="w-16 h-1 bg-lumina-gold mx-auto"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Collection 1 -->
            <a href="<?= BASE_URL ?>/products?category=tailoring" class="group relative block h-96 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1594938298603-c8148c4dae35?q=80&w=1780&auto=format&fit=crop" alt="Men's Tailoring" class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-8 w-full">
                    <h3 class="text-2xl font-bold text-white mb-2">Men's Tailoring</h3>
                    <p class="text-gray-300 mb-4 opacity-0 group-hover:opacity-100 transition duration-500 transform translate-y-4 group-hover:translate-y-0 text-sm">Precision cuts and premium fabrics.</p>
                    <span class="text-white text-sm uppercase tracking-widest font-semibold pb-1 border-b border-white inline-block">Explore</span>
                </div>
            </a>
            
            <!-- Collection 2 -->
            <a href="<?= BASE_URL ?>/products?category=women" class="group relative block h-96 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=1920&auto=format&fit=crop" alt="Women's Collection" class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-8 w-full">
                    <h3 class="text-2xl font-bold text-white mb-2">Women's Collection</h3>
                    <p class="text-gray-300 mb-4 opacity-0 group-hover:opacity-100 transition duration-500 transform translate-y-4 group-hover:translate-y-0 text-sm">Elegance in every silhouette.</p>
                    <span class="text-white text-sm uppercase tracking-widest font-semibold pb-1 border-b border-white inline-block">Explore</span>
                </div>
            </a>
            
            <!-- Collection 3 -->
            <a href="<?= BASE_URL ?>/products?category=accessories" class="group relative block h-96 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1543163521-1bf539c55dd2?q=80&w=1760&auto=format&fit=crop" alt="Fine Accessories" class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-8 w-full">
                    <h3 class="text-2xl font-bold text-white mb-2">Fine Accessories</h3>
                    <p class="text-gray-300 mb-4 opacity-0 group-hover:opacity-100 transition duration-500 transform translate-y-4 group-hover:translate-y-0 text-sm">The perfect finishing touch.</p>
                    <span class="text-white text-sm uppercase tracking-widest font-semibold pb-1 border-b border-white inline-block">Explore</span>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- Trending Now (Alpine Carousel Demo) -->
<section class="py-20 bg-lumina-surface">
    <div class="container mx-auto px-6 lg:px-12">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl font-bold tracking-wide mb-2">Trending Now</h2>
                <div class="w-16 h-1 bg-lumina-gold"></div>
            </div>
            <a href="<?= BASE_URL ?>/products" class="hidden md:inline-block text-sm uppercase tracking-widest font-semibold pb-1 border-b border-lumina-navy hover:text-lumina-gold hover:border-lumina-gold transition">View All</a>
        </div>
        
        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach($trendingProducts as $idx => $product): ?>
            <!-- Product Card -->
            <div class="group cursor-pointer <?php echo ($idx === 3) ? 'hidden lg:block' : ''; ?>">
                <div class="relative overflow-hidden bg-gray-100 mb-4 rounded-t-md">
                    <?php if($product['status'] === 'new_in'): ?>
                        <div class="absolute top-3 left-3 z-10 bg-white px-2 py-1 text-xs font-bold uppercase tracking-wider">New In</div>
                    <?php elseif($product['status'] === 'sale'): ?>
                        <div class="absolute top-3 left-3 z-10 bg-black text-white px-2 py-1 text-xs font-bold uppercase tracking-wider">Sale</div>
                    <?php endif; ?>
                    
                    <img src="<?php echo htmlspecialchars($product['primary_image'] ?? 'https://via.placeholder.com/400x500?text=No+Image'); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-80 object-cover object-center group-hover:scale-105 transition duration-500">
                    
                    <!-- Quick Add Overlay -->
                    <div class="absolute inset-x-0 bottom-0 p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-gradient-to-t from-black/60 to-transparent flex justify-center">
                        <a href="<?= BASE_URL ?>/product/<?php echo $product['slug']; ?>" class="w-full bg-white text-lumina-navy py-2 font-medium text-sm hover:bg-lumina-gold hover:text-white transition shadow-lg transform translate-y-4 group-hover:translate-y-0 duration-300 text-center block">View Details</a>
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-semibold mb-1 group-hover:text-lumina-gold transition"><a href="<?= BASE_URL ?>/product/<?php echo $product['slug']; ?>"><?php echo htmlspecialchars($product['name']); ?></a></h3>
                    <p class="text-gray-500 text-sm mb-2"><?php echo htmlspecialchars($product['category_name']); ?></p>
                    <div class="flex items-center space-x-2">
                        <?php if($product['status'] === 'sale'): ?>
                            <p class="font-medium text-red-600">$<?php echo number_format($product['price'], 2); ?></p>
                            <p class="text-gray-400 text-sm line-through">$<?php echo number_format($product['price'] * 1.25, 2); ?></p>
                        <?php else: ?>
                            <p class="font-medium">$<?php echo number_format($product['price'], 2); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Brand Values -->
<section class="py-24 bg-lumina-navy text-white text-center">
    <div class="container mx-auto px-6 max-w-4xl">
        <h2 class="text-3xl font-bold tracking-widest uppercase mb-8">Conscious Craftsmanship</h2>
        <p class="text-lg text-gray-300 mb-12 font-light leading-relaxed">We believe in creating pieces that stand the test of time, both in style and durability. Our materials are ethically sourced, and our manufacturing processes are designed to minimize environmental impact.</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 border-t border-gray-700 pt-12">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-4 text-lumina-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h4 class="font-semibold uppercase tracking-wider mb-2">Sustainable Sourcing</h4>
                <p class="text-sm text-gray-400">Materials selected with the environment in mind.</p>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-4 text-lumina-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                </svg>
                <h4 class="font-semibold uppercase tracking-wider mb-2">Ethical Production</h4>
                <p class="text-sm text-gray-400">Fair wages and safe working conditions for all artisans.</p>
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-4 text-lumina-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <h4 class="font-semibold uppercase tracking-wider mb-2">Guaranteed Quality</h4>
                <p class="text-sm text-gray-400">Lifetime warranty on hardware and stitching.</p>
            </div>
        </div>
    </div>
</section>

<?php 
$content = ob_get_clean(); 
require BASE_PATH . '/src/views/layouts/main.php'; 
?>
