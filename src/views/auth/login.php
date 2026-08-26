<?php
ob_start();
require_once BASE_PATH . '/src/models/User.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        $userModel = new User();
        $result = $userModel->login($email, $password);

        if ($result['success']) {
            $_SESSION['user_id']   = $result['user']['id'];
            $_SESSION['user_name'] = $result['user']['name'];
            $_SESSION['user_role'] = $result['user']['role'];

            if ($result['user']['role'] === 'admin') {
                header("Location: " . BASE_URL . "/admin");
            } else {
                $redirect = $_SESSION['redirect_after_login'] ?? '<?= BASE_URL ?>/';
                unset($_SESSION['redirect_after_login']);
                header("Location: " . $redirect);
            }
            exit;
        } else {
            $error = $result['message'];
        }
    }
}
?>

<div class="min-h-[80vh] bg-lumina-surface flex items-center justify-center px-4 py-16">
    <div class="w-full max-w-md">
        <div class="text-center mb-10">
            <a href="<?= BASE_URL ?>/" class="text-3xl font-bold tracking-widest uppercase">Lumina</a>
            <p class="text-gray-500 mt-2 text-sm">Sign in to your account</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-8">
            <?php if ($error): ?>
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md text-sm">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>/login" id="login-form">
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" id="email" name="email" required autocomplete="email"
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                           class="w-full px-4 py-3 border border-gray-300 rounded-sm text-sm focus:outline-none focus:ring-2 focus:ring-lumina-navy focus:border-transparent transition"
                           placeholder="you@example.com">
                </div>
                <div class="mb-8">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password"
                           class="w-full px-4 py-3 border border-gray-300 rounded-sm text-sm focus:outline-none focus:ring-2 focus:ring-lumina-navy focus:border-transparent transition"
                           placeholder="••••••••">
                </div>
                <button type="submit" id="login-btn"
                        class="w-full btn-primary py-3 font-semibold text-sm uppercase tracking-wider">
                    Sign In
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Don't have an account?
                <a href="<?= BASE_URL ?>/register" class="text-lumina-navy font-semibold hover:text-lumina-gold transition">Create one</a>
            </p>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require BASE_PATH . '/src/views/layouts/main.php';
?>
