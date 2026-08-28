<?php
ob_start();
require_once BASE_PATH . '/src/models/Product.php';
require_once BASE_PATH . '/src/models/Setting.php';

$productModel = new Product();
$settingModel = new Setting();

$trendingProducts = $productModel->getTrendingProducts(4);
$s = $settingModel->getAllSettings(); // $s['hero_bg_image'] etc.

// Helper: get setting with fallback
function setting(array $s, string $key, string $fallback = ''): string {
    return htmlspecialchars($s[$key] ?? $fallback);
}
?>

<!-- Hero Section -->
<section class="relative pt-24 pb-24 md:pt-36 md:pb-32 overflow-hidden bg-PRAIRAVEE-cream">
    <!-- Decorative background elements -->
    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-PRAIRAVEE-green/5 blur-3xl animate-pulse"></div>
    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-PRAIRAVEE-gold/10 blur-3xl"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-8">
            
            <!-- Left: Text content -->
            <div class="w-full lg:w-1/2 text-left z-20" data-aos="fade-right" data-aos-duration="1000">
                <div class="inline-flex items-center space-x-2 bg-white/60 backdrop-blur-sm px-4 py-1.5 rounded-full mb-6 border border-PRAIRAVEE-gold/20 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-PRAIRAVEE-gold animate-pulse"></span>
                    <span class="text-xs font-semibold text-PRAIRAVEE-green uppercase tracking-wider">Premium Selection</span>
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-PRAIRAVEE-green leading-[1.15] mb-6">
                    <?= setting($s, 'hero_title', 'หอม สดชื่น ผ่อนคลาย') ?><br>
                    <span class="text-PRAIRAVEE-gold text-3xl md:text-4xl lg:text-5xl block mt-2"><?= setting($s, 'hero_subtitle', 'ด้วยสมุนไพรไทยแท้') ?></span>
                </h1>
                
                <p class="text-gray-600 mb-10 max-w-lg text-base md:text-lg leading-relaxed">
                    <?= setting($s, 'hero_description') ?>
                </p>
                
                <a href="<?= BASE_URL ?>/products"
                    class="inline-flex justify-center items-center px-8 py-3.5 bg-PRAIRAVEE-green text-white font-medium hover:bg-[#1e332a] rounded-full transition duration-300 shadow-lg hover:shadow-xl hover:-translate-y-1 group">
                    เลือกซื้อสินค้า
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 transform group-hover:translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>

            <!-- Right: Image Composition -->
            <div class="w-full lg:w-1/2 relative mt-12 lg:mt-0" data-aos="fade-left" data-aos-duration="1200" data-aos-delay="200">
                <!-- Main Image Card (Hero Background) -->
                <div class="relative rounded-[2rem] overflow-hidden shadow-2xl border-[6px] border-white z-10 transform lg:-rotate-2 hover:rotate-0 transition duration-500 ease-out mx-auto max-w-lg">
                    <div class="aspect-[4/3] w-full">
                        <img src="<?= setting($s, 'hero_bg_image') ?>" alt="Hero Main"
                            class="w-full h-full object-cover">
                    </div>
                </div>
                
                <!-- Floating Product Image -->
                <?php if (!empty($s['hero_product_image'])): ?>
                <div class="absolute -bottom-8 -right-4 md:-bottom-12 md:-right-8 lg:-bottom-16 lg:-right-4 w-40 md:w-56 lg:w-64 z-20 rounded-2xl overflow-hidden shadow-2xl border-4 border-white transform rotate-3 hover:-rotate-2 transition duration-500 ease-out bg-white">
                    <img src="<?= setting($s, 'hero_product_image') ?>" alt="Product"
                        class="w-full h-auto object-cover">
                </div>
                <?php endif; ?>
            </div>
            
        </div>
    </div>
</section>

<!-- Features Bar -->
<section class="py-12 bg-white relative z-20 -mt-8 mx-4 md:mx-auto max-w-7xl rounded-3xl shadow-soft" data-aos="fade-up" data-aos-offset="-50">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-gray-50">
            <div class="flex flex-col items-center group">
                <div class="w-12 h-12 rounded-full bg-PRAIRAVEE-light flex items-center justify-center text-PRAIRAVEE-green mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                </div>
                <h4 class="text-sm font-semibold text-gray-800">สมุนไพรไทยแท้</h4>
                <p class="text-xs text-gray-500">คัดสรรวัตถุดิบคุณภาพ</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-12 h-12 rounded-full bg-PRAIRAVEE-light flex items-center justify-center text-PRAIRAVEE-green mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" /></svg>
                </div>
                <h4 class="text-sm font-semibold text-gray-800">หอม สดชื่น</h4>
                <p class="text-xs text-gray-500">ผ่อนคลาย อ่อนโยน</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-12 h-12 rounded-full bg-PRAIRAVEE-light flex items-center justify-center text-PRAIRAVEE-green mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                </div>
                <h4 class="text-sm font-semibold text-gray-800">ปลอดภัย</h4>
                <p class="text-xs text-gray-500">ใช้ได้ทุกเพศทุกวัย</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-12 h-12 rounded-full bg-PRAIRAVEE-light flex items-center justify-center text-PRAIRAVEE-green mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h4 class="text-sm font-semibold text-gray-800">เป็นมิตรต่อสิ่งแวดล้อม</h4>
                <p class="text-xs text-gray-500">บรรจุภัณฑ์รีไซเคิลได้</p>
            </div>
        </div>
    </div>
</section>

<!-- Trending Products -->
<section class="py-24 bg-PRAIRAVEE-cream">
    <div class="container mx-auto px-6">
        <div class="flex justify-between items-end mb-12" data-aos="fade-up">
            <div>
                <span class="text-PRAIRAVEE-gold text-sm tracking-[0.2em] uppercase font-semibold mb-2 block">Our Collection</span>
                <h2 class="text-3xl md:text-4xl font-bold text-PRAIRAVEE-green font-serif">สินค้ายอดนิยม</h2>
            </div>
            <a href="<?= BASE_URL ?>/products"
                class="hidden md:inline-flex items-center text-sm font-medium text-PRAIRAVEE-gold hover:text-PRAIRAVEE-green transition group">
                ดูสินค้าทั้งหมด
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 transform group-hover:translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach($trendingProducts as $index => $product): ?>
                <div class="group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-soft transition-all duration-500 hover:-translate-y-2 relative" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                    
                    <?php if($product['is_new'] ?? false): ?>
                        <span class="absolute top-4 left-4 z-10 bg-PRAIRAVEE-gold text-white px-3 py-1 text-[10px] uppercase tracking-widest font-semibold rounded-full shadow-sm">New</span>
                    <?php endif; ?>
                    
                    <a href="<?= BASE_URL ?>/product/<?= $product['slug'] ?>" class="block relative aspect-square overflow-hidden bg-[#fdfdfd]">
                        <img src="<?= htmlspecialchars($product['primary_image'] ?? 'https://via.placeholder.com/400x400?text=PRAIRAVEE') ?>"
                            alt="<?= htmlspecialchars($product['name']) ?>"
                            class="w-full h-full object-cover mix-blend-multiply transform group-hover:scale-105 transition duration-700 ease-out">
                        <!-- Hover Overlay -->
                        <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition duration-300"></div>
                    </a>
                    <div class="p-6 text-center">
                        <p class="text-[11px] text-gray-400 uppercase tracking-widest mb-2 font-medium"><?= htmlspecialchars($product['category_name'] ?? 'ยาดมสมุนไพร') ?></p>
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
                                <button type="submit" class="w-8 h-8 rounded-full bg-PRAIRAVEE-light flex items-center justify-center text-PRAIRAVEE-green hover:bg-PRAIRAVEE-green hover:text-white transition duration-300 transform group-hover:scale-110" title="Add to Cart">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="mt-12 text-center md:hidden">
            <a href="<?= BASE_URL ?>/products"
                class="inline-flex items-center text-sm font-medium text-PRAIRAVEE-green hover:text-PRAIRAVEE-gold transition group">
                ดูสินค้าทั้งหมด
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 transform group-hover:translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
            </a>
        </div>
    </div>
</section>

<!-- Promotions Banners -->
<section class="py-16 bg-white overflow-hidden relative">
    <div class="absolute right-0 top-1/2 -translate-y-1/2 w-1/3 h-[500px] bg-PRAIRAVEE-gold/5 rounded-full blur-3xl hidden lg:block"></div>
    <div class="container mx-auto px-6 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Promo 1 -->
            <div class="bg-PRAIRAVEE-green rounded-[2rem] p-10 text-white relative overflow-hidden flex items-center min-h-[260px] shadow-soft group" data-aos="fade-up">
                <div class="absolute inset-0 bg-gradient-to-r from-PRAIRAVEE-green to-PRAIRAVEE-green/60 z-10"></div>
                <div class="relative z-20 w-3/4">
                    <span class="text-PRAIRAVEE-gold text-[10px] tracking-widest uppercase font-semibold mb-2 block">Exclusive</span>
                    <h2 class="text-3xl font-bold mb-3 font-serif leading-tight"><?= setting($s, 'promo1_title', 'ซื้อ 2 แถม 1') ?></h2>
                    <p class="text-sm text-white/70 mb-8 font-light"><?= setting($s, 'promo1_subtitle', 'เฉพาะเดือนนี้เท่านั้น') ?></p>
                    <a href="<?= BASE_URL ?>/products" class="inline-block border border-PRAIRAVEE-gold text-PRAIRAVEE-gold hover:bg-PRAIRAVEE-gold hover:text-white px-6 py-2.5 rounded-full text-xs font-semibold tracking-wider transition-all duration-300">DISCOVER MORE</a>
                </div>
                <?php if (!empty($s['promo1_image'])): ?>
                <div class="absolute right-0 top-0 bottom-0 w-2/3 z-0">
                    <img src="<?= setting($s, 'promo1_image') ?>" class="w-full h-full object-cover opacity-60 mix-blend-screen group-hover:scale-110 transition duration-700" alt="Promo 1">
                </div>
                <?php endif; ?>
            </div>

            <!-- Promo 2 -->
            <div class="bg-gradient-to-br from-[#f8f1e6] to-[#f0e4d0] rounded-[2rem] p-10 text-PRAIRAVEE-text relative flex items-center justify-between min-h-[260px] shadow-soft group" data-aos="fade-up" data-aos-delay="200">
                <div class="relative z-20 w-2/3">
                    <span class="text-PRAIRAVEE-green text-[10px] tracking-widest uppercase font-semibold mb-2 block">Privilege</span>
                    <h2 class="text-3xl font-bold mb-3 font-serif leading-tight text-PRAIRAVEE-green"><?= setting($s, 'promo2_title', 'จัดส่งฟรี') ?></h2>
                    <p class="text-gray-600 text-sm mb-8 font-light"><?= setting($s, 'promo2_subtitle', 'เมื่อสั่งซื้อครบ 499 บาท') ?></p>
                    <div class="inline-flex items-center text-[11px] font-medium text-white bg-PRAIRAVEE-gold px-4 py-2 rounded-full shadow-sm">
                        <span class="w-1.5 h-1.5 bg-white rounded-full mr-2 animate-pulse"></span>
                        พร้อมส่งมอบถึงมือคุณ
                    </div>
                </div>
                <div class="text-PRAIRAVEE-gold opacity-30 group-hover:opacity-60 transition duration-500 absolute right-10 transform group-hover:scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-32 w-32" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Magazine Section (Articles) -->
<section class="py-24 bg-PRAIRAVEE-cream relative overflow-hidden">
    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="text-PRAIRAVEE-gold text-sm tracking-[0.2em] uppercase font-semibold mb-2 block">Journal</span>
            <h2 class="text-3xl md:text-4xl font-bold text-PRAIRAVEE-green font-serif">สาระน่ารู้</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            
            <div class="group cursor-pointer" data-aos="fade-up" data-aos-delay="100">
                <div class="relative rounded-3xl overflow-hidden shadow-soft aspect-[16/10] mb-6">
                    <img src="<?= setting($s, 'article1_image') ?>" alt="Article 1"
                        class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700 ease-out"
                        onerror="this.src='https://via.placeholder.com/600x400?text=Journal'">
                    <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition duration-500"></div>
                </div>
                <div class="pl-2">
                    <span class="text-PRAIRAVEE-gold text-[10px] tracking-widest uppercase font-semibold mb-2 block">Wellness</span>
                    <h3 class="text-2xl font-serif font-bold text-PRAIRAVEE-green mb-3 group-hover:text-PRAIRAVEE-gold transition duration-300">
                        <?= setting($s, 'article1_title') ?>
                    </h3>
                    <p class="text-gray-500 text-sm font-light leading-relaxed max-w-md line-clamp-3">
                        <?= setting($s, 'article1_description') ?>
                    </p>
                </div>
            </div>

            <div class="group cursor-pointer" data-aos="fade-up" data-aos-delay="300">
                <div class="relative rounded-3xl overflow-hidden shadow-soft aspect-[16/10] mb-6">
                    <img src="<?= setting($s, 'article2_image') ?>" alt="Article 2"
                        class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700 ease-out"
                        onerror="this.src='https://via.placeholder.com/600x400?text=Journal'">
                    <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition duration-500"></div>
                </div>
                <div class="pl-2">
                    <span class="text-PRAIRAVEE-gold text-[10px] tracking-widest uppercase font-semibold mb-2 block">Lifestyle</span>
                    <h3 class="text-2xl font-serif font-bold text-PRAIRAVEE-green mb-3 group-hover:text-PRAIRAVEE-gold transition duration-300">
                        <?= setting($s, 'article2_title') ?>
                    </h3>
                    <p class="text-gray-500 text-sm font-light leading-relaxed max-w-md line-clamp-3">
                        <?= setting($s, 'article2_description') ?>
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
require BASE_PATH . '/src/views/layouts/main.php';
?>