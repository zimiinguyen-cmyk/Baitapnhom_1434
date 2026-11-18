<?php
require __DIR__.'/includes/functions.php';
require __DIR__.'/config/db.php';
$count=(int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn();
if ($count > 0) { http_response_code(403); exit('Đã có tài khoản quản trị. Hãy xóa setup_admin.php khỏi máy chủ.'); }
$error=''; $success='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    verify_csrf();
    $username=trim((string)($_POST['username'] ?? ''));
    $password=(string)($_POST['password'] ?? '');
    if (!preg_match('/^[a-zA-Z0-9_]{4,50}$/',$username)) { $error='Tên đăng nhập phải có 4-50 ký tự chữ, số hoặc dấu gạch dưới.'; }
    elseif (strlen($password)<10) { $error='Mật khẩu phải dài ít nhất 10 ký tự.'; }
    else { $stmt=$pdo->prepare('INSERT INTO users (username,password,role) VALUES (?, ?, ?)'); $stmt->execute([$username,password_hash($password,PASSWORD_DEFAULT),'admin']); $success='Đã tạo Admin. Hãy xóa file setup_admin.php ngay và đăng nhập.'; }
}
$pageTitle='Thiết lập Admin';
require __DIR__.'/includes/header.php';
?>
<div class="auth-box"><h1>Thiết lập Admin lần đầu</h1><?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?><?php if ($success): ?><div class="alert success"><?= e($success) ?></div><a class="btn primary" href="admin/login.php">Đăng nhập</a><?php else: ?><form method="post" class="stack"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><label>Tên đăng nhập<input name="username" required minlength="4" maxlength="50" pattern="[a-zA-Z0-9_]+"></label><label>Mật khẩu (ít nhất 10 ký tự)<input type="password" name="password" required minlength="10"></label><button class="btn primary" type="submit">Tạo tài khoản</button></form><?php endif; ?></div>
<?php require __DIR__.'/includes/footer.php'; ?>
