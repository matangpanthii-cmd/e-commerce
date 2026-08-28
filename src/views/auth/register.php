<?php
require_once BASE_PATH . '/src/models/User.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!empty($name) && !empty($email) && !empty($password)) {
        if ($password === $confirm_password) {
            $userModel = new User();
            $result = $userModel->register($name, $email, $password);

            if ($result['success']) {
                // Auto login after registration
                $loginResult = $userModel->login($email, $password);
                if ($loginResult['success']) {
                    $_SESSION['user_id'] = $loginResult['user']['id'];
                    $_SESSION['user_name'] = $loginResult['user']['name'];
                    $_SESSION['user_role'] = $loginResult['user']['role'];
                    header("Location: " . BASE_URL . "/");
                    exit;
                }
            } else {
                $error = $result['message'];
            }
        } else {
            $error = "Passwords do not match.";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>
<?php ob_start(); ?>

<div class="min-h-screen bg-PRAIRAVEE-cream flex items-center justify-center px-4 py-32 relative overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-PRAIRAVEE-gold/10 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute bottom-1/4 left-1/4 w-[500px] h-[500px] bg-PRAIRAVEE-green/5 rounded-full blur-3xl"></div>

    <div class="w-full max-w-md relative z-10" data-aos="fade-up">
        <div class="text-center mb-10">
            <a href="<?= BASE_URL ?>/" class="inline-block">
                <svg class="w-12 h-12 text-PRAIRAVEE-gold mx-auto mb-4 drop-shadow-sm" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C12 2 15 5 15 9C15 11.5 13 14 12 16C11 14 9 11.5 9 9C9 5 12 2 12 2Z" />
                    <path d="M12 22C12 22 17 19 19 15C20.5 12 19 9 19 9C19 9 17 11 15 12C13 13 12 16 12 16Z" opacity="0.8" />
                    <path d="M12 22C12 22 7 19 5 15C3.5 12 5 9 5 9C5 9 7 11 9 12C11 13 12 16 12 16Z" opacity="0.8" />
                </svg>
            </a>
            <h1 class="text-3xl font-bold font-serif text-PRAIRAVEE-green mb-2">Create Account</h1>
            <p class="text-gray-500 text-sm font-light">Join us and experience the finest Thai herbs.</p>
        </div>

        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-soft border border-white p-8 md:p-10">
            <?php if ($error): ?>
                <div class="mb-6 bg-red-50/80 backdrop-blur-sm border border-red-100 text-red-600 px-4 py-3 rounded-2xl text-sm text-center">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>/register">
                <div class="mb-5">
                    <label for="name" class="block text-xs font-semibold tracking-widest uppercase text-gray-500 mb-2">Full Name</label>
                    <input type="text" id="name" name="name" required 
                        value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                        class="w-full px-5 py-3.5 bg-white border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-PRAIRAVEE-gold/50 focus:border-transparent transition-all shadow-sm">
                </div>
                <div class="mb-5">
                    <label for="email" class="block text-xs font-semibold tracking-widest uppercase text-gray-500 mb-2">Email Address</label>
                    <input type="email" id="email" name="email" required 
                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                        class="w-full px-5 py-3.5 bg-white border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-PRAIRAVEE-gold/50 focus:border-transparent transition-all shadow-sm">
                </div>
                <div class="mb-5">
                    <label for="password" class="block text-xs font-semibold tracking-widest uppercase text-gray-500 mb-2">Password</label>
                    <input type="password" id="password" name="password" required 
                        class="w-full px-5 py-3.5 bg-white border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-PRAIRAVEE-gold/50 focus:border-transparent transition-all shadow-sm">
                </div>
                <div class="mb-8">
                    <label for="confirm_password" class="block text-xs font-semibold tracking-widest uppercase text-gray-500 mb-2">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required 
                        class="w-full px-5 py-3.5 bg-white border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-PRAIRAVEE-gold/50 focus:border-transparent transition-all shadow-sm">
                </div>
                
                <button type="submit" 
                    class="w-full bg-PRAIRAVEE-green text-white py-4 rounded-full font-semibold text-xs uppercase tracking-widest hover:bg-[#152922] transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                    Register
                </button>
            </form>

            <div class="mt-8 pt-8 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-500 font-light">
                    Already have an account?
                    <a href="<?= BASE_URL ?>/login"
                        class="text-PRAIRAVEE-green font-semibold hover:text-PRAIRAVEE-gold transition ml-1">Sign in here</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require BASE_PATH . '/src/views/layouts/main.php'; 
?>
