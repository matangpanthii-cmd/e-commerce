<?php ob_start(); ?>

<div class="bg-lumina-surface border-b border-gray-200">
    <div class="container mx-auto px-6 lg:px-12 py-8">
        <h1 class="text-3xl font-bold tracking-widest uppercase">Secure Checkout</h1>
    </div>
</div>

<div class="container mx-auto px-6 lg:px-12 py-12" x-data="{
    step: 1, // 1: Shipping, 2: Payment, 3: Review
    subtotal: 1075.00,
    tax: 86.00,
    get total() { return this.subtotal + this.tax; }
}">
    
    <div class="flex flex-col lg:flex-row gap-12">
        
        <!-- Checkout Flow -->
        <div class="w-full lg:w-2/3">
            
            <!-- Progress Tracker -->
            <div class="flex items-center justify-between mb-12 relative">
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-0.5 bg-gray-200 z-0"></div>
                <!-- Step 1 -->
                <div class="relative z-10 flex flex-col items-center">
                    <div :class="{'bg-lumina-navy text-white': step >= 1}" class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm bg-gray-200 text-gray-500 transition">1</div>
                    <span class="mt-2 text-xs font-semibold uppercase tracking-wider text-lumina-navy">Shipping</span>
                </div>
                <!-- Step 2 -->
                <div class="relative z-10 flex flex-col items-center">
                    <div :class="{'bg-lumina-navy text-white': step >= 2, 'bg-gray-200 text-gray-500': step < 2}" class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm transition">2</div>
                    <span :class="{'text-lumina-navy': step >= 2, 'text-gray-400': step < 2}" class="mt-2 text-xs font-semibold uppercase tracking-wider transition">Payment</span>
                </div>
                <!-- Step 3 -->
                <div class="relative z-10 flex flex-col items-center">
                    <div :class="{'bg-lumina-navy text-white': step >= 3, 'bg-gray-200 text-gray-500': step < 3}" class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm transition">3</div>
                    <span :class="{'text-lumina-navy': step >= 3, 'text-gray-400': step < 3}" class="mt-2 text-xs font-semibold uppercase tracking-wider transition">Review</span>
                </div>
            </div>

            <!-- Step 1: Shipping Information -->
            <div x-show="step === 1" x-transition.opacity>
                <h2 class="text-xl font-bold mb-6 border-b border-gray-200 pb-2">Shipping Information</h2>
                <form class="space-y-6" @submit.prevent="step = 2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                            <input type="text" required class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:border-lumina-navy rounded-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                            <input type="text" required class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:border-lumina-navy rounded-sm">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                        <input type="email" required class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:border-lumina-navy rounded-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Street Address *</label>
                        <input type="text" required class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:border-lumina-navy rounded-sm mb-3" placeholder="Street Address">
                        <input type="text" class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:border-lumina-navy rounded-sm" placeholder="Apt, Suite, Unit (optional)">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                            <input type="text" required class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:border-lumina-navy rounded-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">State/Province *</label>
                            <input type="text" required class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:border-lumina-navy rounded-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Postal Code *</label>
                            <input type="text" required class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:border-lumina-navy rounded-sm">
                        </div>
                    </div>

                    <div class="pt-6 text-right">
                        <button type="submit" class="bg-lumina-navy text-white px-8 py-4 font-medium uppercase tracking-wider text-sm hover:bg-opacity-90 transition shadow-md">Continue to Payment</button>
                    </div>
                </form>
            </div>

            <!-- Step 2: Payment Method -->
            <div x-show="step === 2" style="display: none;" x-transition.opacity>
                <h2 class="text-xl font-bold mb-6 border-b border-gray-200 pb-2">Payment Method</h2>
                
                <div class="space-y-4 mb-8">
                    <!-- Credit Card Option -->
                    <label class="flex items-center justify-between p-4 border border-lumina-navy bg-gray-50 rounded-sm cursor-pointer">
                        <div class="flex items-center space-x-3">
                            <input type="radio" name="payment" checked class="text-lumina-navy focus:ring-lumina-navy">
                            <span class="font-medium">Credit Card</span>
                        </div>
                        <div class="flex space-x-2">
                            <!-- Icons -->
                            <div class="w-8 h-5 bg-gray-200 rounded text-[8px] flex items-center justify-center font-bold">VISA</div>
                            <div class="w-8 h-5 bg-gray-200 rounded text-[8px] flex items-center justify-center font-bold">MC</div>
                        </div>
                    </label>

                    <!-- Digital Wallet Option -->
                    <label class="flex items-center justify-between p-4 border border-gray-200 hover:border-gray-300 rounded-sm cursor-pointer transition">
                        <div class="flex items-center space-x-3">
                            <input type="radio" name="payment" class="text-lumina-navy focus:ring-lumina-navy">
                            <span class="font-medium">Apple Pay / Google Pay</span>
                        </div>
                    </label>

                    <!-- Crypto/QR Option -->
                    <label class="flex items-center justify-between p-4 border border-gray-200 hover:border-gray-300 rounded-sm cursor-pointer transition">
                        <div class="flex items-center space-x-3">
                            <input type="radio" name="payment" class="text-lumina-navy focus:ring-lumina-navy">
                            <span class="font-medium">PromptPay QR / Crypto</span>
                        </div>
                    </label>
                </div>

                <!-- CC Form -->
                <div class="bg-gray-50 p-6 rounded-sm mb-8 border border-gray-200">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Card Number *</label>
                        <input type="text" placeholder="0000 0000 0000 0000" class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:border-lumina-navy rounded-sm font-mono">
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date *</label>
                            <input type="text" placeholder="MM/YY" class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:border-lumina-navy rounded-sm font-mono">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">CVC *</label>
                            <input type="text" placeholder="123" class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:border-lumina-navy rounded-sm font-mono">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name on Card *</label>
                        <input type="text" class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:border-lumina-navy rounded-sm">
                    </div>
                </div>

                <div class="flex justify-between items-center pt-6">
                    <button @click="step = 1" class="text-gray-500 hover:text-lumina-navy text-sm font-medium underline">Back to Shipping</button>
                    <button @click="step = 3" class="bg-lumina-navy text-white px-8 py-4 font-medium uppercase tracking-wider text-sm hover:bg-opacity-90 transition shadow-md">Review Order</button>
                </div>
            </div>

            <!-- Step 3: Review -->
            <div x-show="step === 3" style="display: none;" x-transition.opacity>
                <h2 class="text-xl font-bold mb-6 border-b border-gray-200 pb-2">Review Your Order</h2>
                
                <div class="bg-gray-50 p-6 rounded-sm mb-6 border border-gray-200 text-sm">
                    <h3 class="font-bold mb-2">Shipping To:</h3>
                    <p class="text-gray-600">John Doe</p>
                    <p class="text-gray-600">123 Example Street, Apt 4B</p>
                    <p class="text-gray-600">New York, NY 10001</p>
                    <p class="text-gray-600">johndoe@example.com</p>
                </div>

                <div class="bg-gray-50 p-6 rounded-sm mb-8 border border-gray-200 text-sm">
                    <h3 class="font-bold mb-2">Payment:</h3>
                    <p class="text-gray-600 flex items-center"><span class="w-8 h-5 bg-gray-300 rounded text-[8px] flex items-center justify-center font-bold text-white mr-2">VISA</span> Ending in 4242</p>
                </div>

                <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                    <button @click="step = 2" class="text-gray-500 hover:text-lumina-navy text-sm font-medium underline">Back to Payment</button>
                    <button @click="alert('Order Placed Successfully! Redirecting to confirmation page.')" class="bg-lumina-gold text-white px-8 py-4 font-bold uppercase tracking-wider hover:bg-yellow-600 transition shadow-lg">Place Order</button>
                </div>
            </div>

        </div>

        <!-- Persistent Order Summary Sidebar -->
        <div class="w-full lg:w-1/3">
            <div class="bg-gray-50 p-6 rounded-sm sticky top-24 border border-gray-100">
                <h2 class="text-lg font-bold mb-4">Summary</h2>
                
                <!-- Items Mini-list -->
                <div class="space-y-4 mb-6 pb-6 border-b border-gray-200">
                    <div class="flex items-start">
                        <div class="w-16 h-20 bg-gray-200 mr-4 flex-shrink-0">
                            <img src="https://images.unsplash.com/photo-1591047139829-d91aecb6caea?q=80&w=1936&auto=format&fit=crop" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-grow">
                            <h4 class="text-xs font-semibold">Navy Cashmere Overcoat</h4>
                            <p class="text-xs text-gray-500 mb-1">Navy, Size 50 / Qty: 1</p>
                            <p class="text-sm font-medium">$895.00</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-16 h-20 bg-gray-200 mr-4 flex-shrink-0">
                            <img src="https://images.unsplash.com/photo-1576566588028-4147f3842f27?q=80&w=1964&auto=format&fit=crop" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-grow">
                            <h4 class="text-xs font-semibold">Silk Blouse</h4>
                            <p class="text-xs text-gray-500 mb-1">Ivory, Size S / Qty: 1</p>
                            <p class="text-sm font-medium">$180.00</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-3 mb-6 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium" x-text="'$' + subtotal.toFixed(2)"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Shipping</span>
                        <span class="font-medium">Free</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tax (8%)</span>
                        <span class="font-medium" x-text="'$' + tax.toFixed(2)"></span>
                    </div>
                </div>
                
                <div class="border-t border-gray-200 pt-4">
                    <div class="flex justify-between items-center text-lg font-bold">
                        <span>Total</span>
                        <span x-text="'$' + total.toFixed(2)"></span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php 
$content = ob_get_clean(); 
require BASE_PATH . '/src/views/layouts/main.php'; 
?>
