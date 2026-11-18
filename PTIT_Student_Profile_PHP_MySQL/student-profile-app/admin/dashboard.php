<?php
require __DIR__.'/../includes/functions.php';
require_admin();
require __DIR__.'/../config/db.php';
$total = (int)$pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
$classes = $pdo->query('SELECT class_name, COUNT(*) AS total FROM students GROUP BY class_name ORDER BY total DESC, class_name')->fetchAll();
$recent = $pdo->query('SELECT id,student_code,fullname,class_name,created_at FROM students ORDER BY created_at DESC,id DESC LIMIT 5')->fetchAll();
$pageTitle = 'Dashboard'; $prefix = '../';
require __DIR__.'/../includes/header.php';
?>
<div class="admin-head"><div><div class="eyebrow">TRANG QUẢN TRỊ</div><h1>Dashboard</h1><p>Xin chào, <?= e($_SESSION['admin_name'] ?? 'Admin') ?>.</p></div><a class="btn primary" href="form.php">+ Thêm sinh viên</a></div><div class="stats"><div class="stat"><span>Tổng số sinh viên</span><strong><?= $total ?></strong></div><div class="stat"><span>Số lớp đang quản lý</span><strong><?= count($classes) ?></strong></div></div><div class="dashboard-grid"><section class="panel"><h2>Thống kê theo lớp</h2><?php if (!$classes): ?><p>Chưa có dữ liệu.</p><?php else: ?><?php foreach ($classes as $row): ?><div class="bar-row"><div><span><?= e($row['class_name']) ?></span><b><?= (int)$row['total'] ?></b></div><div class="bar"><span style="width:<?= $total ? round((int)$row['total']/$total*100) : 0 ?>%"></span></div></div><?php endforeach; ?><?php endif; ?></section><section class="panel"><h2>Hồ sơ mới nhất</h2><?php foreach ($recent as $row): ?><a class="recent" href="form.php?id=<?= (int)$row['id'] ?>"><span><?= e($row['fullname']) ?><small><?= e($row['student_code']) ?> · <?= e($row['class_name']) ?></small></span><span>Chỉnh sửa →</span></a><?php endforeach; ?><a class="btn secondary" href="manage.php">Quản lý tất cả</a></section></div>
<?php require __DIR__.'/../includes/footer.php'; ?>
