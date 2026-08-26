<?php ob_start(); ?>

<div class="bg-lumina-surface border-b border-gray-200">
    <div class="container mx-auto px-6 lg:px-12 py-8">
        <h1 class="text-3xl font-bold tracking-widest uppercase">Your Cart</h1>
    </div>
</div>

<div class="container mx-auto px-6 lg:px-12 py-12" x-data="{
    items: [
        { id: 1, name: 'Navy Cashmere Overcoat', variant: 'Navy, Size 50', price: 895.00, qty: 1, img: 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?q=80&w=1936&auto=format&fit=crop' },
        { id: 2, name: 'Silk Blouse', variant: 'Ivory, Size S', price: 180.00, qty: 1, img: 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?q=80&w=1964&auto=format&fit=crop' }
    ],
    get subtotal() {
        return this.items.reduce((sum, item) => sum + (item.price * item.qty), 0);
    },
    get tax() {
        return this.subtotal * 0.08;
    },
    get total() {
        return this.subtotal + this.tax;
    },
    removeItem(id) {
        this.items = this.items.filter(item => item.id !== id);
    }
}">
    <template x-if="items.length === 0">
        <div class="text-center py-24">
            <h2 class="text-2xl font-light text-gray-500 mb-6">Your cart is currently empty.</h2>
            <a href="/pj2/public/products" class="inline-block bg-lumina-navy text-white px-8 py-3 font-medium uppercase tracking-wider text-sm hover:bg-opacity-90 transition">Continue Shopping</a>
        </div>
    </template>

    <template x-if="items.length > 0">
        <div class="flex flex-col lg:flex-row gap-12">
            
            <!-- Cart Items -->
            <div class="w-full lg:w-2/3">
                <!-- Headers -->
                <div class="hidden md:flex border-b border-gray-200 pb-4 text-xs font-semibold uppercase tracking-wider text-gray-500 mb-6">
                    <div class="w-1/2">Product</div>
                    <div class="w-1/6 text-center">Quantity</div>
                    <div class="w-1/6 text-right">Price</div>
                    <div class="w-1/6 text-right">Total</div>
                </div>
                
                <!-- Items list -->
                <div class="space-y-8">
                    <template x-for="item in items" :key="item.id">
                        <div class="flex flex-col md:flex-row items-start md:items-center border-b border-gray-200 pb-8 last:border-0 last:pb-0">
                            
                            <!-- Product Details -->
                            <div class="w-full md:w-1/2 flex items-center mb-4 md:mb-0">
                                <a :href="'/pj2/public/product/' + item.id" class="w-24 h-32 flex-shrink-0 bg-gray-100 mr-6">
                                    <img :src="item.img" class="w-full h-full object-cover">
                                </a>
                                <div>
                                    <h3 class="font-medium text-lg mb-1"><a :href="'/pj2/public/product/' + item.id" x-text="item.name"></a></h3>
                                    <p class="text-gray-500 text-sm mb-3" x-text="item.variant"></p>
                                    <button @click="removeItem(item.id)" class="text-sm text-gray-400 hover:text-red-500 underline transition">Remove</button>
                                </div>
                            </div>
                            
                            <!-- Quantity -->
                            <div class="w-full md:w-1/6 flex justify-start md:justify-center mb-4 md:mb-0">
                                <div class="flex items-center border border-gray-300">
                                    <button @click="if(item.qty > 1) item.qty--" class="px-3 py-1 text-gray-500 hover:text-lumina-navy transition">-</button>
                                    <span class="px-2 py-1 text-sm w-8 text-center" x-text="item.qty"></span>
                                    <button @click="item.qty++" class="px-3 py-1 text-gray-500 hover:text-lumina-navy transition">+</button>
                                </div>
                            </div>
                            
                            <!-- Unit Price -->
                            <div class="w-full md:w-1/6 text-left md:text-right mb-2 md:mb-0 text-sm text-gray-500" x-text="'$' + item.price.toFixed(2)"></div>
                            
                            <!-- Total Price -->
                            <div class="w-full md:w-1/6 text-left md:text-right font-medium" x-text="'$' + (item.price * item.qty).toFixed(2)"></div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="w-full lg:w-1/3">
                <div class="bg-gray-50 p-8 rounded-sm">
                    <h2 class="text-xl font-bold mb-6">Order Summary</h2>
                    
                    <div class="space-y-4 mb-6 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium" x-text="'$' + subtotal.toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Estimated Shipping</span>
                            <span class="font-medium">Free</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Estimated Tax</span>
                            <span class="font-medium" x-text="'$' + tax.toFixed(2)"></span>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-4 mb-8">
                        <div class="flex justify-between items-center text-lg font-bold">
                            <span>Total</span>
                            <span x-text="'$' + total.toFixed(2)"></span>
                        </div>
                    </div>
                    
                    <a href="/pj2/public/checkout" class="block w-full bg-lumina-navy text-white text-center py-4 font-medium uppercase tracking-wider text-sm hover:bg-opacity-90 transition shadow-md">
                        Proceed to Checkout
                    </a>
                    
                    <div class="mt-6 flex justify-center space-x-2">
                        <!-- Simulated Payment Icons -->
                        <div class="w-10 h-6 bg-gray-200 rounded text-[10px] flex items-center justify-center font-bold text-gray-500">VISA</div>
                        <div class="w-10 h-6 bg-gray-200 rounded text-[10px] flex items-center justify-center font-bold text-gray-500">MC</div>
                        <div class="w-10 h-6 bg-gray-200 rounded text-[10px] flex items-center justify-center font-bold text-gray-500">AMEX</div>
                        <div class="w-10 h-6 bg-gray-200 rounded text-[10px] flex items-center justify-center font-bold text-gray-500">GPay</div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

<?php 
$content = ob_get_clean(); 
require BASE_PATH . '/src/views/layouts/main.php'; 
?>
