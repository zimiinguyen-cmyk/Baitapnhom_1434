<?php
require __DIR__.'/../includes/functions.php';
require __DIR__.'/../config/db.php';
if (is_admin()) { redirect('dashboard.php'); }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? AND role = ?');
    $stmt->execute([$username, 'admin']);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int)$user['id'];
        $_SESSION['admin_name'] = $user['username'];
        redirect('dashboard.php');
    }
    $error = 'Tên đăng nhập hoặc mật khẩu không chính xác.';
}
$pageTitle = 'Đăng nhập quản trị'; $prefix = '../';
require __DIR__.'/../includes/header.php';
?>
<div class="auth-box"><div class="eyebrow">QUẢN TRỊ HỆ THỐNG</div><h1>Đăng nhập Admin</h1><p>Đăng nhập để quản lý hồ sơ sinh viên.</p><?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?><form method="post" class="stack"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><label>Tên đăng nhập<input name="username" autocomplete="username" required maxlength="50"></label><label>Mật khẩu<input type="password" name="password" autocomplete="current-password" required></label><button class="btn primary" type="submit">Đăng nhập</button></form></div>
<?php require __DIR__.'/../includes/footer.php'; ?>
