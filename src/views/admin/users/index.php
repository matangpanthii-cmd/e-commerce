<?php
require_once BASE_PATH . '/src/middleware/admin_guard.php';
require_once BASE_PATH . '/src/models/User.php';

$userModel = new User();

$admin_flash_success = $_SESSION['flash_success'] ?? null;
$admin_flash_error   = $_SESSION['flash_error']   ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action  = $_POST['_action'] ?? '';
    $user_id = (int)($_POST['user_id'] ?? 0);

    if ($action === 'update_role' && $user_id) {
        // Prevent admin from demoting themselves
        if ($user_id === (int)$_SESSION['user_id']) {
            $_SESSION['flash_error'] = 'You cannot change your own role.';
        } else {
            $userModel->updateRole($user_id, $_POST['role'] ?? 'customer');
            $_SESSION['flash_success'] = 'User role updated.';
        }
    } elseif ($action === 'delete' && $user_id) {
        if ($user_id === (int)$_SESSION['user_id']) {
            $_SESSION['flash_error'] = 'You cannot delete your own account.';
        } else {
            $userModel->deleteUser($user_id);
            $_SESSION['flash_success'] = 'User deleted.';
        }
    }
    header("Location: " . BASE_URL . "/admin/users");
    exit;
}

$search = trim($_GET['search'] ?? '');
$users  = $userModel->getAllUsers($search);

$admin_title      = 'Users';
$admin_breadcrumb = 'Manage customer & admin accounts';
ob_start();
?>

<div class="flex items-center justify-between mb-6">
    <form method="GET" action="<?= BASE_URL ?>/admin/users" class="flex gap-3">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
               placeholder="Search name or email..."
               class="bg-admin-card border border-admin-border text-admin-text text-sm px-4 py-2 rounded-lg focus:outline-none focus:border-admin-accent w-64 placeholder-admin-muted">
        <button type="submit" class="bg-admin-card border border-admin-border text-admin-muted px-4 py-2 rounded-lg text-sm hover:border-admin-accent transition">Search</button>
        <?php if ($search): ?>
            <a href="<?= BASE_URL ?>/admin/users" class="border border-admin-border text-admin-muted px-4 py-2 rounded-lg text-sm hover:border-red-500 hover:text-red-400 transition">Clear</a>
        <?php endif; ?>
    </form>
</div>

<div class="bg-admin-card border border-admin-border rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-admin-muted text-xs uppercase tracking-wider border-b border-admin-border">
                    <th class="px-6 py-4 text-left">User</th>
                    <th class="px-6 py-4 text-left">Role</th>
                    <th class="px-6 py-4 text-left">Joined</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-admin-border">
                <?php if (empty($users)): ?>
                <tr><td colspan="4" class="px-6 py-16 text-center text-admin-muted">No users found.</td></tr>
                <?php else: ?>
                <?php foreach ($users as $u):
                    $is_me = $u['id'] == $_SESSION['user_id'];
                ?>
                <tr class="hover:bg-white/5 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0
                                <?php echo $u['role'] === 'admin' ? 'bg-admin-accent' : 'bg-admin-border'; ?>">
                                <span class="<?php echo $u['role'] === 'admin' ? 'text-black' : 'text-admin-muted'; ?> font-bold text-xs">
                                    <?php echo strtoupper(substr($u['name'], 0, 1)); ?>
                                </span>
                            </div>
                            <div>
                                <p class="text-white font-medium">
                                    <?php echo htmlspecialchars($u['name']); ?>
                                    <?php if ($is_me): ?><span class="text-admin-accent text-xs ml-1">(You)</span><?php endif; ?>
                                </p>
                                <p class="text-admin-muted text-xs"><?php echo htmlspecialchars($u['email']); ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <?php if (!$is_me): ?>
                        <form method="POST" action="<?= BASE_URL ?>/admin/users" class="flex items-center gap-2">
                            <input type="hidden" name="_action" value="update_role">
                            <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                            <select name="role" onchange="this.form.submit()"
                                    class="bg-admin-bg border border-admin-border text-admin-text text-xs px-3 py-1.5 rounded-lg focus:outline-none focus:border-admin-accent transition">
                                <option value="customer" <?php echo $u['role'] === 'customer' ? 'selected' : ''; ?>>Customer</option>
                                <option value="admin"    <?php echo $u['role'] === 'admin'    ? 'selected' : ''; ?>>Admin</option>
                            </select>
                        </form>
                        <?php else: ?>
                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-admin-accent/20 text-admin-accent">Admin</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-admin-muted text-xs"><?php echo date('d M Y', strtotime($u['created_at'])); ?></td>
                    <td class="px-6 py-4 text-right">
                        <?php if (!$is_me): ?>
                        <form method="POST" action="<?= BASE_URL ?>/admin/users"
                              onsubmit="return confirm('Delete <?php echo htmlspecialchars(addslashes($u['name'])); ?>? This cannot be undone.')">
                            <input type="hidden" name="_action" value="delete">
                            <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                            <button type="submit" class="text-red-400 hover:text-red-300 text-xs hover:underline">Delete</button>
                        </form>
                        <?php else: ?>
                        <span class="text-admin-border text-xs">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="px-6 py-3 border-t border-admin-border text-admin-muted text-xs">
        <?php echo count($users); ?> user<?php echo count($users) !== 1 ? 's' : ''; ?>
    </div>
</div>

<?php
$admin_content = ob_get_clean();
require BASE_PATH . '/src/views/admin/layouts/admin_layout.php';
?>
