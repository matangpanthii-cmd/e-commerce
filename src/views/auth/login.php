<?php
require_once BASE_PATH . '/src/models/User.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        $userModel = new User();
        $result = $userModel->login($email, $password);

        if ($result['success']) {
            $_SESSION['user_id'] = $result['user']['id'];
            $_SESSION['user_name'] = $result['user']['name'];
            $_SESSION['user_role'] = $result['user']['role'];
            header("Location: /pj2/public/");
            exit;
        } else {
            $error = $result['message'];
        }
    } else {
        $error = "Please enter email and password.";
    }
}
?>
<?php ob_start(); ?>

<div class="container mx-auto px-6 py-24 flex justify-center items-center">
    <div class="w-full max-w-md bg-white p-8 rounded-sm shadow-sm border border-gray-100">
        <h1 class="text-2xl font-bold tracking-widest uppercase mb-6 text-center">Login</h1>
        
        <?php if($error): ?>
            <div class="bg-red-50 text-red-500 p-3 mb-6 text-sm text-center border border-red-100">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/pj2/public/login">
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" id="email" name="email" required class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:border-lumina-navy rounded-sm">
            </div>
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" id="password" name="password" required class="w-full px-4 py-3 border border-gray-300 focus:outline-none focus:border-lumina-navy rounded-sm">
            </div>
            <button type="submit" class="w-full bg-lumina-navy text-white px-8 py-3 font-medium uppercase tracking-wider text-sm hover:bg-opacity-90 transition mb-4 shadow-md">
                Sign In
            </button>
            <div class="text-center text-sm">
                <span class="text-gray-500">Don't have an account?</span>
                <a href="/pj2/public/register" class="text-lumina-navy hover:text-lumina-gold font-medium transition ml-1">Register here</a>
            </div>
        </form>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require BASE_PATH . '/src/views/layouts/main.php'; 
?>
