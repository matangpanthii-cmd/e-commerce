<?php
ob_start();
require_once BASE_PATH . '/src/models/Product.php';

$productModel = new Product();

// Build cart items from session with real prices from DB
$cart_items = [];
$subtotal = 0;

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $cart_key => $item) {
        $price = $productModel->getPriceById($item['product_id']);
        $name_row = null;

        // Get product name and image quickly
        $p = $productModel->getProductById($item['product_id']);
        if (!$p) continue; // product deleted

        $line_total = $price * $item['quantity'];
        $subtotal += $line_total;
        $primary_image = $p['images'][0]['image_url'] ?? '';

        $cart_items[] = [
            'cart_key'    => $cart_key,
            'product_id'  => $item['product_id'],
            'name'        => $p['name'],
            'slug'        => $p['slug'],
            'color'       => $item['color'],
            'size'        => $item['size'],
            'quantity'    => $item['quantity'],
            'price'       => $price,
            'line_total'  => $line_total,
            'image'       => $primary_image,
        ];
    }
}

$tax   = $subtotal * 0.08;
$total = $subtotal + $tax;
?>

<div class="bg-PRAIRAVEE-cream border-b border-gray-200">
    <div class="container mx-auto px-6 lg:px-12 py-8">
        <h1 class="text-3xl font-bold tracking-widest uppercase">Your Cart</h1>
    </div>
</div>

<div class="container mx-auto px-6 lg:px-12 py-12">

<?php if (empty($cart_items)): ?>
    <div class="text-center py-24">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        <h2 class="text-2xl font-light text-gray-500 mb-6">Your cart is currently empty.</h2>
        <a href="<?= BASE_URL ?>/products" class="inline-block bg-PRAIRAVEE-green text-white px-8 py-3 font-medium uppercase tracking-wider text-sm hover:bg-opacity-90 transition">Continue Shopping</a>
    </div>
<?php else: ?>
    <div class="flex flex-col lg:flex-row gap-12">

        <!-- Cart Items -->
        <div class="w-full lg:w-2/3">
            <div class="hidden md:flex border-b border-gray-200 pb-4 text-xs font-semibold uppercase tracking-wider text-gray-500 mb-6">
                <div class="w-1/2">Product</div>
                <div class="w-1/6 text-center">Qty</div>
                <div class="w-1/6 text-right">Price</div>
                <div class="w-1/6 text-right">Total</div>
            </div>

            <div class="space-y-8">
            <?php foreach ($cart_items as $item): ?>
                <div class="flex flex-col md:flex-row items-start md:items-center border-b border-gray-200 pb-8 last:border-0 last:pb-0">
                    <!-- Image + Info -->
                    <div class="w-full md:w-1/2 flex items-center mb-4 md:mb-0">
                        <a href="<?= BASE_URL ?>/product/<?php echo htmlspecialchars($item['slug']); ?>" class="w-24 h-32 flex-shrink-0 bg-gray-100 mr-6 overflow-hidden">
                            <?php if ($item['image']): ?>
                                <img src="<?php echo htmlspecialchars($item['image']); ?>" class="w-full h-full object-cover" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <?php else: ?>
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400 text-xs">No Image</div>
                            <?php endif; ?>
                        </a>
                        <div>
                            <h3 class="font-medium mb-1">
                                <a href="<?= BASE_URL ?>/product/<?php echo htmlspecialchars($item['slug']); ?>" class="hover:text-PRAIRAVEE-gold transition">
                                    <?php echo htmlspecialchars($item['name']); ?>
                                </a>
                            </h3>
                            <p class="text-gray-500 text-sm mb-3">
                                <?php
                                $variant_parts = [];
                                if ($item['color']) $variant_parts[] = ucfirst($item['color']);
                                if ($item['size'])  $variant_parts[] = 'Size ' . $item['size'];
                                echo implode(', ', $variant_parts);
                                ?>
                            </p>
                            <!-- Remove -->
                            <form method="POST" action="<?= BASE_URL ?>/cart/remove" class="inline">
                                <input type="hidden" name="cart_key" value="<?php echo htmlspecialchars($item['cart_key']); ?>">
                                <button type="submit" class="text-sm text-gray-400 hover:text-red-500 underline transition">Remove</button>
                            </form>
                        </div>
                    </div>

                    <!-- Qty controls -->
                    <div class="w-full md:w-1/6 flex justify-start md:justify-center mb-4 md:mb-0">
                        <div class="flex items-center border border-gray-300">
                            <form method="POST" action="<?= BASE_URL ?>/cart/update" class="inline">
                                <input type="hidden" name="cart_key" value="<?php echo htmlspecialchars($item['cart_key']); ?>">
                                <input type="hidden" name="action" value="decrease">
                                <button type="submit" class="px-3 py-1 text-gray-500 hover:text-PRAIRAVEE-green transition">-</button>
                            </form>
                            <span class="px-3 py-1 text-sm w-10 text-center"><?php echo $item['quantity']; ?></span>
                            <form method="POST" action="<?= BASE_URL ?>/cart/update" class="inline">
                                <input type="hidden" name="cart_key" value="<?php echo htmlspecialchars($item['cart_key']); ?>">
                                <input type="hidden" name="action" value="increase">
                                <button type="submit" class="px-3 py-1 text-gray-500 hover:text-PRAIRAVEE-green transition">+</button>
                            </form>
                        </div>
                    </div>

                    <!-- Unit Price -->
                    <div class="w-full md:w-1/6 text-left md:text-right mb-2 md:mb-0 text-sm text-gray-500">
                        $<?php echo number_format($item['price'], 2); ?>
                    </div>

                    <!-- Line Total -->
                    <div class="w-full md:w-1/6 text-left md:text-right font-medium">
                        $<?php echo number_format($item['line_total'], 2); ?>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        </div>

        <!-- Order Summary Sidebar -->
        <div class="w-full lg:w-1/3">
            <div class="bg-gray-50 p-8 rounded-sm border border-gray-100">
                <h2 class="text-xl font-bold mb-6">Order Summary</h2>
                <div class="space-y-4 mb-6 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal (<?php echo count($cart_items); ?> items)</span>
                        <span class="font-medium">$<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Estimated Shipping</span>
                        <span class="font-medium text-green-600">Free</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Estimated Tax (8%)</span>
                        <span class="font-medium">$<?php echo number_format($tax, 2); ?></span>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4 mb-8">
                    <div class="flex justify-between items-center text-lg font-bold">
                        <span>Total</span>
                        <span>$<?php echo number_format($total, 2); ?></span>
                    </div>
                </div>

                <a href="<?= BASE_URL ?>/checkout" id="checkout-btn"
                   class="block w-full bg-PRAIRAVEE-green text-white text-center py-4 font-medium uppercase tracking-wider text-sm hover:bg-opacity-90 transition shadow-md">
                    Proceed to Checkout
                </a>

                <a href="<?= BASE_URL ?>/products" class="block text-center text-sm text-gray-500 mt-4 hover:text-PRAIRAVEE-green transition">
                    ← Continue Shopping
                </a>
            </div>
        </div>

    </div>
<?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require BASE_PATH . '/src/views/layouts/main.php';
?>
