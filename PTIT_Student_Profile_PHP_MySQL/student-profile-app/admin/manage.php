<?php
require __DIR__.'/../includes/functions.php';
require_admin();
require __DIR__.'/../config/db.php';
$q = trim((string)($_GET['q'] ?? ''));
$page = max(1,(int)($_GET['page'] ?? 1));
$limit = 10;
$condition = $q === '' ? '' : ' WHERE fullname LIKE :q1 OR student_code LIKE :q2';
$params = $q === '' ? [] : ['q1'=>'%'.$q.'%','q2'=>'%'.$q.'%'];
$count = $pdo->prepare('SELECT COUNT(*) FROM students'.$condition);
$count->execute($params);
$total = (int)$count->fetchColumn();
$pages = max(1,(int)ceil($total/$limit));
$page = min($page,$pages);
$stmt = $pdo->prepare('SELECT * FROM students'.$condition.' ORDER BY id DESC LIMIT :limit OFFSET :offset');
foreach ($params as $key=>$value) { $stmt->bindValue(':'.$key,$value); }
$stmt->bindValue(':limit',$limit,PDO::PARAM_INT);
$stmt->bindValue(':offset',($page-1)*$limit,PDO::PARAM_INT);
$stmt->execute();
$students = $stmt->fetchAll();
$pageTitle = 'Quản lý sinh viên'; $prefix = '../';
require __DIR__.'/../includes/header.php';
?>
<div class="admin-head"><div><div class="eyebrow">QUẢN TRỊ HỆ THỐNG</div><h1>Quản lý sinh viên</h1><p><?= $total ?> hồ sơ phù hợp</p></div><a class="btn primary" href="form.php">+ Thêm sinh viên</a></div>
<?php if (isset($_GET['success'])): ?><div class="alert success">Thao tác đã được thực hiện thành công.</div><?php endif; ?>
<form method="get" class="filters"><label><span>Tìm sinh viên</span><input name="q" value="<?= e($q) ?>" placeholder="Tên hoặc mã sinh viên"></label><button class="btn primary" type="submit">Tìm kiếm</button><a class="btn secondary" href="manage.php">Đặt lại</a></form>
<div class="table-wrap"><table><thead><tr><th>Ảnh</th><th>Mã SV</th><th>Họ và tên</th><th>Lớp</th><th>Email</th><th>Thao tác</th></tr></thead><tbody><?php foreach ($students as $student): ?><tr><td><img class="table-avatar" src="<?= e(avatar_url($student['avatar'],true)) ?>" alt=""></td><td><?= e($student['student_code']) ?></td><td><?= e($student['fullname']) ?></td><td><?= e($student['class_name']) ?></td><td><?= e($student['email']) ?></td><td class="actions"><a class="btn small secondary" href="form.php?id=<?= (int)$student['id'] ?>">Sửa</a><form class="inline delete-form" method="post" action="delete.php"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)$student['id'] ?>"><button class="btn small danger" type="submit">Xóa</button></form></td></tr><?php endforeach; ?><?php if (!$students): ?><tr><td colspan="6">Không có sinh viên phù hợp.</td></tr><?php endif; ?></tbody></table></div>
<?php if ($pages>1): ?><div class="pagination"><?php for ($i=1;$i<=$pages;$i++): ?><a class="<?= $i===$page?'active':'' ?>" href="?<?= e(http_build_query(['q'=>$q,'page'=>$i])) ?>"><?= $i ?></a><?php endfor; ?></div><?php endif; ?>
<?php require __DIR__.'/../includes/footer.php'; ?>
