<?php ob_start(); ?>

<div class="container mx-auto px-6 lg:px-12 py-32 text-center">
    <h1 class="text-8xl font-bold text-gray-200 mb-4">404</h1>
    <h2 class="text-2xl font-semibold text-lumina-navy mb-6">Page Not Found</h2>
    <p class="text-gray-500 mb-10">Sorry, the page you are looking for does not exist or has been moved.</p>
    <a href="<?= BASE_URL ?>/" class="inline-block bg-lumina-navy text-white px-8 py-3 font-medium uppercase tracking-wider text-sm hover:bg-opacity-90 transition">Return Home</a>
</div>

<?php 
$content = ob_get_clean(); 
require BASE_PATH . '/src/views/layouts/main.php'; 
?>
