<?php
require_once BASE_PATH . '/src/models/Setting.php';
$_settingModel = new Setting();
$_settings = $_settingModel->getAllSettings();
$site_name = $_settings['site_name'] ?? 'ไพราวี PRAIRAVEE';
$site_copyright = $_settings['footer_copyright'] ?? '© 2026 ไพราวี PRAIRAVEE. All rights reserved.';
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($site_name) ?> | ยาดมสมุนไพรไทย</title>
    <!-- Google Fonts: Prompt (Thai) & Playfair Display (Luxury Serif) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Tailwind CSS (CDN for Development) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Prompt', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                    colors: {
                        PRAIRAVEE: {
                            green: '#1a332a', // Deeper, more luxurious green
                            cream: '#fcfbf9', // Lighter, cleaner ivory cream
                            gold: '#c2a373',  // Refined soft gold
                            light: '#eef2f0', // Very subtle sage tint
                            text: '#2a2a2a'
                        }
                    },
                    boxShadow: {
                        'soft': '0 20px 40px -15px rgba(26, 51, 42, 0.05)',
                        'glow': '0 0 40px -10px rgba(194, 163, 115, 0.3)',
                    },
                    borderRadius: {
                        'DEFAULT': '16px',
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* Custom Styles */
        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .nav-link {
            transition: color 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            color: #2d4a3e;
            font-weight: 500;
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #b89768;
        }

        .btn-primary {
            background-color: #2d4a3e;
            color: white;
            transition: background-color 0.3s ease, transform 0.2s ease;
            border-radius: 9999px;
            /* Pill shape */
        }

        .btn-primary:hover {
            background-color: #1e332a;
            transform: translateY(-1px);
        }

        .btn-outline {
            border: 1.5px solid #1a332a;
            color: #1a332a;
            border-radius: 9999px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-outline:hover {
            background-color: #1a332a;
            color: white;
            box-shadow: 0 10px 25px -5px rgba(26, 51, 42, 0.2);
        }

        /* Bottom Nav Mobile */
        .bottom-nav {
            padding-bottom: env(safe-area-inset-bottom);
        }
        
        /* Smooth scrolling */
        html { scroll-behavior: smooth; }
    </style>
</head>

<body class="bg-PRAIRAVEE-cream text-PRAIRAVEE-text font-sans flex flex-col min-h-screen selection:bg-PRAIRAVEE-gold/30 selection:text-PRAIRAVEE-green">

    <!-- Header (Glassmorphism) -->
    <header class="fixed w-full top-0 z-50 bg-white/70 backdrop-blur-md border-b border-white/20 shadow-sm transition-all duration-300" id="main-header">
        <div class="container mx-auto px-4 lg:px-8 py-3 flex justify-between items-center">

            <!-- Logo -->
            <a href="<?= BASE_URL ?>/" class="flex items-center space-x-2">
                <!-- Mockup Lotus Icon -->
                <svg class="w-8 h-8 text-PRAIRAVEE-gold drop-shadow-sm" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C12 2 15 5 15 9C15 11.5 13 14 12 16C11 14 9 11.5 9 9C9 5 12 2 12 2Z" />
                    <path d="M12 22C12 22 17 19 19 15C20.5 12 19 9 19 9C19 9 17 11 15 12C13 13 12 16 12 16Z"
                        opacity="0.8" />
                    <path d="M12 22C12 22 7 19 5 15C3.5 12 5 9 5 9C5 9 7 11 9 12C11 13 12 16 12 16Z" opacity="0.8" />
                </svg>
                <div class="flex flex-col">
                    <span class="text-xl font-semibold text-PRAIRAVEE-green leading-tight font-serif tracking-wide"><?= htmlspecialchars($site_name) ?></span>
                    <span class="text-[9px] text-gray-500 tracking-[0.2em] uppercase">ยาดมสมุนไพรไทย</span>
                </div>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex space-x-8 text-[13px] text-gray-600 tracking-wide">
                <a href="<?= BASE_URL ?>/"
                    class="nav-link <?= ($request_uri == '/' || $request_uri == '/home') ? 'active font-medium text-PRAIRAVEE-green' : 'hover:text-PRAIRAVEE-green' ?>">หน้าแรก</a>
                <a href="<?= BASE_URL ?>/products"
                    class="nav-link <?= ($request_uri == '/products') ? 'active font-medium text-PRAIRAVEE-green' : 'hover:text-PRAIRAVEE-green' ?>">สินค้าทั้งหมด</a>
            </nav>

            <!-- Actions -->
            <div class="flex items-center space-x-4">
                <!-- Search Icon -->
                <button class="text-gray-600 hover:text-PRAIRAVEE-green transition hidden sm:block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>

                <!-- User Account -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?= BASE_URL ?>/logout"
                        class="text-gray-600 hover:text-PRAIRAVEE-green transition hidden sm:block" title="ออกจากระบบ">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/login"
                        class="text-gray-600 hover:text-PRAIRAVEE-green transition hidden sm:block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </a>
                <?php endif; ?>

                <!-- Cart Icon -->
                <a href="<?= BASE_URL ?>/cart" class="text-gray-600 hover:text-PRAIRAVEE-green transition relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <?php
                    $cartCount = 0;
                    if (isset($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $item) {
                            $cartCount += $item['quantity'];
                        }
                    }
                    ?>
                    <?php if ($cartCount > 0): ?>
                        <span
                            class="absolute -top-1 -right-2 bg-PRAIRAVEE-green text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">
                            <?= $cartCount ?>
                        </span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow pb-16 lg:pb-0"> <!-- padding bottom for mobile nav -->
        <?php echo $content ?? ''; ?>
    </main>

    <!-- Footer (Desktop) -->
    <footer class="bg-PRAIRAVEE-green text-PRAIRAVEE-cream pt-16 pb-8 hidden lg:block border-t-4 border-PRAIRAVEE-gold relative overflow-hidden">
        <!-- Subtle background pattern or glow -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-PRAIRAVEE-gold/5 rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2"></div>
        
        <div class="container mx-auto px-6 lg:px-12 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12 mb-16">
                <!-- Brand Col -->
                <div class="md:col-span-5">
                    <h3 class="text-2xl font-serif font-bold mb-6 flex items-center space-x-3">
                        <svg class="w-8 h-8 text-PRAIRAVEE-gold" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C12 2 15 5 15 9C15 11.5 13 14 12 16C11 14 9 11.5 9 9C9 5 12 2 12 2Z" />
                            <path d="M12 22C12 22 17 19 19 15C20.5 12 19 9 19 9C19 9 17 11 15 12C13 13 12 16 12 16Z" opacity="0.5" />
                        </svg>
                        <span><?= htmlspecialchars($site_name) ?></span>
                    </h3>
                    <p class="text-PRAIRAVEE-light/60 text-sm leading-relaxed max-w-sm font-light">
                        ยาดมสมุนไพรไทย คัดสรรวัตถุดิบคุณภาพระดับพรีเมียม หอมสดชื่น อ่อนโยน สูดลึกแค่ไหนก็สบายใจ เพื่อความผ่อนคลายที่เหนือระดับ
                    </p>
                </div>
                
                <!-- Links Col -->
                <div class="md:col-span-3">
                    <h4 class="text-xs font-semibold mb-6 text-PRAIRAVEE-gold tracking-widest uppercase">Explore</h4>
                    <ul class="space-y-4 text-sm text-PRAIRAVEE-light/70 font-light">
                        <li><a href="<?= BASE_URL ?>/" class="hover:text-white hover:translate-x-1 transition duration-300 inline-block">หน้าแรก (Home)</a></li>
                        <li><a href="<?= BASE_URL ?>/products" class="hover:text-white hover:translate-x-1 transition duration-300 inline-block">สินค้าทั้งหมด (Products)</a></li>
                        <li><a href="<?= BASE_URL ?>/cart" class="hover:text-white hover:translate-x-1 transition duration-300 inline-block">ตะกร้าสินค้า (Cart)</a></li>
                    </ul>
                </div>
                
                <!-- Newsletter Col -->
                <div class="md:col-span-4">
                    <h4 class="text-xs font-semibold mb-6 text-PRAIRAVEE-gold tracking-widest uppercase">Newsletter</h4>
                    <p class="text-PRAIRAVEE-light/60 text-xs mb-4 font-light">รับข่าวสารและข้อเสนอพิเศษสำหรับคุณเท่านั้น</p>
                    <form class="flex mt-2 group relative">
                        <input type="email" placeholder="Your email address"
                            class="w-full px-4 py-3 bg-white/5 text-white border border-white/10 focus:outline-none focus:border-PRAIRAVEE-gold/50 text-sm rounded-full font-light transition backdrop-blur-sm">
                        <button type="submit"
                            class="absolute right-1 top-1 bottom-1 bg-PRAIRAVEE-gold px-6 text-white font-medium text-xs hover:bg-yellow-600 transition rounded-full">
                            SUBSCRIBE
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center text-PRAIRAVEE-light/40 text-xs font-light">
                <div class="mb-4 md:mb-0">
                    <?= htmlspecialchars($site_copyright) ?>
                </div>
                <div class="flex space-x-6">
                    <a href="#" class="hover:text-white transition">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bottom Navigation (Mobile) -->
    <div
        class="lg:hidden fixed bottom-0 left-0 right-0 bg-PRAIRAVEE-green text-PRAIRAVEE-light/70 flex justify-between items-center px-6 py-2 bottom-nav z-50 shadow-[0_-2px_10px_rgba(0,0,0,0.1)]">
        <a href="<?= BASE_URL ?>/"
            class="flex flex-col items-center space-y-1 hover:text-white <?= ($request_uri == '/' || $request_uri == '/home') ? 'text-white' : '' ?>">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="text-[10px]">หน้าแรก</span>
        </a>
        <a href="<?= BASE_URL ?>/products"
            class="flex flex-col items-center space-y-1 hover:text-white <?= ($request_uri == '/products') ? 'text-white' : '' ?>">
            <!-- Leaf Icon for สินค้า -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
            </svg>
            <span class="text-[10px]">สินค้า</span>
        </a>
        <a href="#" class="flex flex-col items-center space-y-1 hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
            <span class="text-[10px]">หมวดหมู่</span>
        </a>
        <a href="#" class="flex flex-col items-center space-y-1 hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
            </svg>
            <span class="text-[10px]">โปรโมชัน</span>
        </a>
        <a href="<?= BASE_URL ?>/login" class="flex flex-col items-center space-y-1 hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="text-[10px]">บัญชีของฉัน</span>
        </a>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            offset: 50,
            easing: 'ease-out-cubic'
        });
        
        // Dynamic Glass Navbar
        window.addEventListener('scroll', () => {
            const header = document.getElementById('main-header');
            if (window.scrollY > 20) {
                header.classList.add('shadow-md', 'bg-white/90');
                header.classList.remove('bg-white/70', 'shadow-sm');
            } else {
                header.classList.add('bg-white/70', 'shadow-sm');
                header.classList.remove('shadow-md', 'bg-white/90');
            }
        });
    </script>
</body>
</html>