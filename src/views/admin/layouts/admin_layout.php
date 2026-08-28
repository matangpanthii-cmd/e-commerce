<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($admin_title) ? htmlspecialchars($admin_title) . ' — ' : ''; ?>ไพราวี Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        admin: {
                            bg:      '#0f1117',
                            sidebar: '#161b27',
                            card:    '#1e2332',
                            border:  '#2a3048',
                            accent:  '#d4af37',
                            text:    '#e2e8f0',
                            muted:   '#8892a4',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { -webkit-font-smoothing: antialiased; }
        .sidebar-link { transition: background 0.18s, color 0.18s; }
        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(212, 175, 55, 0.1);
            color: #d4af37;
        }
        .sidebar-link.active { border-left: 3px solid #d4af37; }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #2a3048; border-radius: 2px; }
        .stat-card { transition: transform 0.2s, box-shadow 0.2s; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.3); }
    </style>
</head>
<body class="bg-admin-bg text-admin-text font-sans">

<?php
// Determine active menu item
$current = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
function isActive(string $path, string $current): string {
    // strip base path prefix
    $base = '<?= BASE_URL ?>';
    $c = str_replace($base, '', $current);
    return (strpos($c, $path) === 0) ? 'active' : '';
}
?>

<div class="flex min-h-screen">

    <!-- ======= Sidebar ======= -->
    <aside class="w-64 bg-admin-sidebar border-r border-admin-border flex-shrink-0 flex flex-col fixed h-full z-40">
        <!-- Brand -->
        <div class="px-6 py-6 border-b border-admin-border">
            <a href="<?= BASE_URL ?>/admin" class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-admin-accent rounded-sm flex items-center justify-center">
                    <span class="text-black font-black text-xs">L</span>
                </div>
                <div>
                    <p class="font-bold text-white tracking-widest text-sm uppercase">ไพราวี</p>
                    <p class="text-admin-muted text-xs">Admin Panel</p>
                </div>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
            <p class="text-admin-muted text-xs font-semibold uppercase tracking-wider px-3 mb-3">Main</p>

            <a href="<?= BASE_URL ?>/admin" id="nav-dashboard"
               class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-md text-sm font-medium text-admin-text <?php echo isActive('/admin', $current) && $current === str_replace('<?= BASE_URL ?>', '', $current) ? 'active' : ''; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Dashboard</span>
            </a>

            <p class="text-admin-muted text-xs font-semibold uppercase tracking-wider px-3 mt-5 mb-3">Catalog</p>

            <a href="<?= BASE_URL ?>/admin/products" id="nav-products"
               class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-md text-sm font-medium text-admin-text <?php echo isActive('/admin/products', $current); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <span>Products</span>
            </a>

            <a href="<?= BASE_URL ?>/admin/categories" id="nav-categories"
               class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-md text-sm font-medium text-admin-text <?php echo isActive('/admin/categories', $current); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                <span>Categories</span>
            </a>

            <p class="text-admin-muted text-xs font-semibold uppercase tracking-wider px-3 mt-5 mb-3">Sales</p>

            <a href="<?= BASE_URL ?>/admin/orders" id="nav-orders"
               class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-md text-sm font-medium text-admin-text <?php echo isActive('/admin/orders', $current); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span>Orders</span>
            </a>

            <p class="text-admin-muted text-xs font-semibold uppercase tracking-wider px-3 mt-5 mb-3">System</p>

            <a href="<?= BASE_URL ?>/admin/users" id="nav-users"
               class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-md text-sm font-medium text-admin-text <?php echo isActive('/admin/users', $current); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Users</span>
            </a>

            <a href="<?= BASE_URL ?>/admin/settings" id="nav-settings"
               class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-md text-sm font-medium text-admin-text <?php echo isActive('/admin/settings', $current); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>ตั้งค่าเว็บไซต์</span>
            </a>
        </nav>

        <!-- Bottom: Profile + Store Link -->
        <div class="px-3 py-4 border-t border-admin-border space-y-1">
            <a href="<?= BASE_URL ?>/" target="_blank"
               class="sidebar-link flex items-center space-x-3 px-3 py-2 rounded-md text-sm text-admin-muted">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
                <span>View Store</span>
            </a>
            <a href="<?= BASE_URL ?>/logout"
               class="sidebar-link flex items-center space-x-3 px-3 py-2 rounded-md text-sm text-admin-muted">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <!-- ======= Main Content ======= -->
    <div class="ml-64 flex-1 flex flex-col min-h-screen">

        <!-- Top Header Bar -->
        <header class="bg-admin-sidebar border-b border-admin-border px-8 py-4 flex items-center justify-between sticky top-0 z-30">
            <div>
                <h1 class="text-white font-semibold text-lg"><?php echo isset($admin_title) ? htmlspecialchars($admin_title) : 'Dashboard'; ?></h1>
                <?php if (isset($admin_breadcrumb)): ?>
                <p class="text-admin-muted text-xs mt-0.5"><?php echo $admin_breadcrumb; ?></p>
                <?php endif; ?>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-admin-accent rounded-full flex items-center justify-center">
                        <span class="text-black font-bold text-xs"><?php echo strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)); ?></span>
                    </div>
                    <div class="hidden md:block">
                        <p class="text-sm font-medium text-white"><?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?></p>
                        <p class="text-xs text-admin-muted">Administrator</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-8">
            <?php if (isset($admin_flash_success)): ?>
            <div class="mb-6 bg-green-900/40 border border-green-700 text-green-300 px-5 py-3 rounded-md text-sm flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                <span><?php echo htmlspecialchars($admin_flash_success); ?></span>
            </div>
            <?php endif; ?>
            <?php if (isset($admin_flash_error)): ?>
            <div class="mb-6 bg-red-900/40 border border-red-700 text-red-300 px-5 py-3 rounded-md text-sm flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                <span><?php echo htmlspecialchars($admin_flash_error); ?></span>
            </div>
            <?php endif; ?>

            <?php echo $admin_content ?? ''; ?>
        </main>
    </div>
</div>

</body>
</html>
