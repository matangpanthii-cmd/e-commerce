<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUMINA | Premium E-commerce</title>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS (CDN for Development) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        lumina: {
                            navy: '#1a2b3c',
                            surface: '#f8f9fa',
                            gold: '#d4af37',
                            muted: '#6c757d'
                        }
                    },
                    borderRadius: {
                        'DEFAULT': '8px',
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* Custom Styles for Premium Feel */
        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .nav-link {
            transition: color 0.3s ease;
        }
        .nav-link:hover {
            color: #d4af37;
        }
        .btn-primary {
            background-color: #1a2b3c;
            color: white;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .btn-primary:hover {
            background-color: #111c27;
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="bg-lumina-surface text-lumina-navy font-sans flex flex-col min-h-screen">

    <!-- Header -->
    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="container mx-auto px-6 lg:px-12 py-4 flex justify-between items-center">
            <!-- Logo -->
            <a href="<?= BASE_URL ?>/" class="text-2xl font-bold tracking-widest uppercase">Lumina</a>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex space-x-8">
                <a href="<?= BASE_URL ?>/products?category=new" class="nav-link text-sm font-medium">New Arrivals</a>
                <a href="<?= BASE_URL ?>/products?category=men" class="nav-link text-sm font-medium">Men</a>
                <a href="<?= BASE_URL ?>/products?category=women" class="nav-link text-sm font-medium">Women</a>
                <a href="<?= BASE_URL ?>/products?category=accessories" class="nav-link text-sm font-medium">Accessories</a>
            </nav>

            <!-- Actions -->
            <div class="flex items-center space-x-6">
                <!-- User Account -->
                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="hidden md:flex items-center space-x-4">
                        <span class="text-sm font-medium">Hi, <?php echo htmlspecialchars(explode(' ', trim($_SESSION['user_name']))[0]); ?></span>
                        <a href="<?= BASE_URL ?>/logout" class="text-gray-600 hover:text-lumina-gold transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </a>
                    </div>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/login" class="text-gray-800 hover:text-lumina-gold transition hidden md:block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </a>
                <?php endif; ?>

                <!-- Search Icon -->
                <button class="hover:text-lumina-gold transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
                <!-- Cart Icon -->
                <a href="<?= BASE_URL ?>/cart" class="hover:text-lumina-gold transition relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="absolute -top-2 -right-2 bg-lumina-gold text-white text-xs font-bold w-4 h-4 rounded-full flex items-center justify-center">0</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        <?php echo $content ?? ''; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-lumina-navy text-white pt-16 pb-8">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div>
                    <h3 class="text-xl font-bold tracking-widest mb-6">LUMINA</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">Effortless sophistication for the modern individual. Discover our curated collections of premium lifestyle essentials.</p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wider mb-6">Shop</h4>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition">New Arrivals</a></li>
                        <li><a href="#" class="hover:text-white transition">Men's Tailoring</a></li>
                        <li><a href="#" class="hover:text-white transition">Women's Collection</a></li>
                        <li><a href="#" class="hover:text-white transition">Fine Accessories</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wider mb-6">Support</h4>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Contact Us</a></li>
                        <li><a href="#" class="hover:text-white transition">Shipping & Returns</a></li>
                        <li><a href="#" class="hover:text-white transition">Size Guide</a></li>
                        <li><a href="#" class="hover:text-white transition">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wider mb-6">Newsletter</h4>
                    <p class="text-gray-400 text-sm mb-4">Subscribe for exclusive offers and the latest news.</p>
                    <form class="flex">
                        <input type="email" placeholder="Your email address" class="w-full px-4 py-2 bg-gray-800 text-white border border-gray-700 focus:outline-none focus:border-gray-500 text-sm">
                        <button type="submit" class="bg-lumina-gold px-4 py-2 text-white font-medium text-sm hover:bg-yellow-600 transition">Subscribe</button>
                    </form>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-500 text-xs">&copy; 2026 LUMINA. All rights reserved.</p>
                <div class="flex space-x-4 mt-4 md:mt-0 text-gray-500 text-sm">
                    <a href="#" class="hover:text-white transition">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
