<?php 
ob_start(); 
require_once BASE_PATH . '/src/models/Product.php';

$productModel = new Product();
$category = $_GET['category'] ?? null;
$sort = $_GET['sort'] ?? 'recommended';

$products = $productModel->getAllProducts($category, $sort);
?>

<!-- Page Header -->
<div class="bg-lumina-navy text-white py-16">
    <div class="container mx-auto px-6 lg:px-12 text-center">
        <h1 class="text-4xl font-bold tracking-widest uppercase mb-4">Shop All Collections</h1>
        <p class="text-gray-300 max-w-2xl mx-auto">Explore our curated selection of premium garments and accessories, designed for effortless sophistication.</p>
    </div>
</div>

<!-- Main Content -->
<div class="container mx-auto px-6 lg:px-12 py-12" x-data="{ 
    mobileFiltersOpen: false,
    sortBy: 'recommended',
    priceRange: 1000
}">
    
    <div class="flex flex-col lg:flex-row gap-12">
        <!-- Sidebar Filters -->
        <aside class="w-full lg:w-1/4">
            <div class="flex justify-between items-center lg:hidden mb-6">
                <h2 class="text-xl font-bold">Filters</h2>
                <button @click="mobileFiltersOpen = !mobileFiltersOpen" class="text-lumina-navy flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                </button>
            </div>
            
            <div class="lg:block" :class="{'hidden': !mobileFiltersOpen}">
                <!-- Category Filter -->
                <div class="mb-8 border-b border-gray-200 pb-8">
                    <h3 class="text-lg font-semibold mb-4 uppercase tracking-wider text-sm">Category</h3>
                    <ul class="space-y-3">
                        <li><label class="flex items-center space-x-3 cursor-pointer"><input type="checkbox" class="form-checkbox text-lumina-gold rounded-sm border-gray-300 focus:ring-lumina-gold focus:ring-opacity-50"> <span>Men's Tailoring</span></label></li>
                        <li><label class="flex items-center space-x-3 cursor-pointer"><input type="checkbox" class="form-checkbox text-lumina-gold rounded-sm border-gray-300 focus:ring-lumina-gold focus:ring-opacity-50"> <span>Women's Collection</span></label></li>
                        <li><label class="flex items-center space-x-3 cursor-pointer"><input type="checkbox" class="form-checkbox text-lumina-gold rounded-sm border-gray-300 focus:ring-lumina-gold focus:ring-opacity-50"> <span>Outerwear</span></label></li>
                        <li><label class="flex items-center space-x-3 cursor-pointer"><input type="checkbox" class="form-checkbox text-lumina-gold rounded-sm border-gray-300 focus:ring-lumina-gold focus:ring-opacity-50"> <span>Knitwear</span></label></li>
                        <li><label class="flex items-center space-x-3 cursor-pointer"><input type="checkbox" class="form-checkbox text-lumina-gold rounded-sm border-gray-300 focus:ring-lumina-gold focus:ring-opacity-50"> <span>Essentials</span></label></li>
                        <li><label class="flex items-center space-x-3 cursor-pointer"><input type="checkbox" class="form-checkbox text-lumina-gold rounded-sm border-gray-300 focus:ring-lumina-gold focus:ring-opacity-50"> <span>Accessories</span></label></li>
                    </ul>
                </div>

                <!-- Price Filter -->
                <div class="mb-8 border-b border-gray-200 pb-8">
                    <h3 class="text-lg font-semibold mb-4 uppercase tracking-wider text-sm flex justify-between">
                        Price Range 
                        <span class="text-gray-500 font-normal" x-text="'$' + priceRange"></span>
                    </h3>
                    <input type="range" x-model="priceRange" min="0" max="2000" class="w-full accent-lumina-gold">
                    <div class="flex justify-between text-xs text-gray-500 mt-2">
                        <span>$0</span>
                        <span>$2000+</span>
                    </div>
                </div>

                <!-- Size Filter -->
                <div class="mb-8 border-b border-gray-200 pb-8">
                    <h3 class="text-lg font-semibold mb-4 uppercase tracking-wider text-sm">Size</h3>
                    <div class="grid grid-cols-3 gap-2">
                        <button class="border border-gray-300 hover:border-lumina-navy py-2 text-sm text-center transition">XS</button>
                        <button class="border border-gray-300 hover:border-lumina-navy py-2 text-sm text-center transition">S</button>
                        <button class="border border-gray-300 hover:border-lumina-navy py-2 text-sm text-center transition bg-lumina-navy text-white">M</button>
                        <button class="border border-gray-300 hover:border-lumina-navy py-2 text-sm text-center transition">L</button>
                        <button class="border border-gray-300 hover:border-lumina-navy py-2 text-sm text-center transition">XL</button>
                    </div>
                </div>
                
                <button class="w-full bg-lumina-navy text-white py-3 font-medium hover:bg-opacity-90 transition">Apply Filters</button>
            </div>
        </aside>

        <!-- Product Grid -->
        <main class="w-full lg:w-3/4">
            <div class="flex justify-between items-center mb-6">
                <p class="text-sm text-gray-500"><?php echo count($products); ?> Results</p>
                <select onchange="window.location.href='?category=<?php echo urlencode($category ?? ''); ?>&sort='+this.value" class="text-sm border-gray-300 py-2 pl-3 pr-8 focus:outline-none focus:border-lumina-navy">
                    <option value="recommended" <?php echo $sort == 'recommended' ? 'selected' : ''; ?>>Recommended</option>
                    <option value="newest" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>Newest In</option>
                    <option value="price_asc" <?php echo $sort == 'price_asc' ? 'selected' : ''; ?>>Price: Low to High</option>
                    <option value="price_desc" <?php echo $sort == 'price_desc' ? 'selected' : ''; ?>>Price: High to Low</option>
                </select>
            </div>

            <?php if(empty($products)): ?>
                <div class="text-center py-12">
                    <h2 class="text-xl font-medium text-gray-600 mb-2">No products found.</h2>
                    <p class="text-gray-400">Try adjusting your filters or search terms.</p>
                </div>
            <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8">
                <?php foreach($products as $product): ?>
                <!-- Product Card -->
                <div class="group cursor-pointer">
                    <div class="relative overflow-hidden bg-gray-100 mb-4 rounded-t-md">
                        <?php if(isset($product['status']) && $product['status'] === 'new_in'): ?>
                            <div class="absolute top-3 left-3 z-10 bg-white px-2 py-1 text-xs font-bold uppercase tracking-wider">New In</div>
                        <?php elseif(isset($product['status']) && $product['status'] === 'sale'): ?>
                            <div class="absolute top-3 left-3 z-10 bg-black text-white px-2 py-1 text-xs font-bold uppercase tracking-wider">Sale</div>
                        <?php endif; ?>
                        
                        <img src="<?php echo htmlspecialchars($product['primary_image'] ?? 'https://via.placeholder.com/400x500?text=No+Image'); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-full h-80 object-cover object-center group-hover:scale-105 transition duration-500">
                        <div class="absolute inset-x-0 bottom-0 p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-gradient-to-t from-black/60 to-transparent flex justify-center">
                            <a href="<?= BASE_URL ?>/product/<?php echo $product['slug']; ?>" class="w-full bg-white text-lumina-navy py-2 font-medium text-sm hover:bg-lumina-gold hover:text-white transition shadow-lg transform translate-y-4 group-hover:translate-y-0 duration-300 text-center block">View Details</a>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold mb-1 group-hover:text-lumina-gold transition"><a href="<?= BASE_URL ?>/product/<?php echo $product['slug']; ?>"><?php echo htmlspecialchars($product['name']); ?></a></h3>
                        <p class="text-gray-500 text-sm mb-2"><?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></p>
                        <div class="flex items-center space-x-2">
                            <?php if(isset($product['status']) && $product['status'] === 'sale'): ?>
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
            <?php endif; ?>

            <!-- Pagination -->
            <div class="mt-16 flex justify-center">
                <nav class="flex items-center space-x-2">
                    <button class="p-2 border border-gray-300 rounded hover:bg-gray-50 transition text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <button class="w-10 h-10 border border-lumina-navy bg-lumina-navy text-white font-medium rounded">1</button>
                    <button class="w-10 h-10 border border-gray-300 hover:border-lumina-navy font-medium rounded transition">2</button>
                    <button class="w-10 h-10 border border-gray-300 hover:border-lumina-navy font-medium rounded transition">3</button>
                    <span class="px-2 text-gray-500">...</span>
                    <button class="p-2 border border-gray-300 rounded hover:bg-gray-50 transition text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </nav>
            </div>

        </main>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require BASE_PATH . '/src/views/layouts/main.php'; 
?>
