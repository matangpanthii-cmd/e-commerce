<?php ob_start(); ?>

<div class="container mx-auto px-6 lg:px-12 py-32 text-center min-h-[70vh] flex flex-col items-center justify-center relative">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-PRAIRAVEE-gold/5 rounded-full blur-3xl -z-10"></div>
    <h1 class="text-8xl md:text-9xl font-bold text-PRAIRAVEE-green/10 mb-2 font-serif relative">
        404
        <span class="absolute inset-0 flex items-center justify-center text-3xl md:text-4xl text-PRAIRAVEE-green font-serif z-10 drop-shadow-md">Not Found</span>
    </h1>
    <h2 class="text-2xl font-semibold text-PRAIRAVEE-green mb-4">ขออภัย ไม่พบหน้าที่คุณค้นหา</h2>
    <p class="text-gray-500 mb-10 max-w-md mx-auto font-light leading-relaxed">หน้าที่คุณพยายามเข้าถึงอาจถูกลบไปแล้ว หรือคุณอาจพิมพ์ URL ผิดพลาด โปรดกลับสู่หน้าหลักเพื่อเริ่มต้นใหม่</p>
    <a href="<?= BASE_URL ?>/" class="inline-block bg-PRAIRAVEE-green text-white px-10 py-3.5 rounded-full font-semibold uppercase tracking-widest text-xs hover:bg-[#152922] transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
        Return to Homepage
    </a>
</div>

<?php 
$content = ob_get_clean(); 
require BASE_PATH . '/src/views/layouts/main.php'; 
?>
