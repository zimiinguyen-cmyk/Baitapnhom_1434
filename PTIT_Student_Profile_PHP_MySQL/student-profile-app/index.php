<?php
require __DIR__.'/includes/functions.php';
require __DIR__.'/config/db.php';
$q = trim((string)($_GET['q'] ?? ''));
$class = trim((string)($_GET['class'] ?? ''));
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 9;
$where = [];
$params = [];
if ($q !== '') { $where[] = '(fullname LIKE :q1 OR student_code LIKE :q2)'; $params['q1'] = '%'.$q.'%'; $params['q2'] = '%'.$q.'%'; }
if ($class !== '') { $where[] = 'class_name = :class'; $params['class'] = $class; }
$condition = $where ? ' WHERE '.implode(' AND ', $where) : '';
$count = $pdo->prepare('SELECT COUNT(*) FROM students'.$condition);
$count->execute($params);
$total = (int)$count->fetchColumn();
$pages = max(1, (int)ceil($total/$limit));
$page = min($page, $pages);
$offset = ($page - 1) * $limit;
$stmt = $pdo->prepare('SELECT * FROM students'.$condition.' ORDER BY created_at DESC, id DESC LIMIT :limit OFFSET :offset');
foreach ($params as $key => $value) { $stmt->bindValue(':'.$key, $value); }
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$students = $stmt->fetchAll();
$classes = $pdo->query('SELECT DISTINCT class_name FROM students ORDER BY class_name')->fetchAll(PDO::FETCH_COLUMN);
$pageTitle = 'Danh sách sinh viên';
require __DIR__.'/includes/header.php';
?>
<section class="hero"><div><div class="eyebrow">HỆ THỐNG QUẢN LÝ HỒ SƠ SINH VIÊN</div><h1>Khám phá hồ sơ <span>sinh viên</span></h1><p>Tra cứu thông tin, tìm kiếm theo mã sinh viên và khám phá các thành viên trong lớp.</p></div><div class="hero-stat"><strong><?= $total ?></strong><span>Hồ sơ phù hợp</span></div></section>
<form class="filters" method="get"><label><span>Tìm theo tên hoặc mã sinh viên</span><input name="q" value="<?= e($q) ?>" placeholder="Nhập tên hoặc mã sinh viên"></label><label><span>Lọc theo lớp</span><select name="class"><option value="">Tất cả các lớp</option><?php foreach ($classes as $item): ?><option value="<?= e($item) ?>" <?= $class === $item ? 'selected' : '' ?>><?= e($item) ?></option><?php endforeach; ?></select></label><button class="btn primary" type="submit">Tìm kiếm</button><a class="btn secondary" href="index.php">Đặt lại</a></form>
<div class="section-head"><h2>Danh sách sinh viên</h2><span><?= $total ?> kết quả</span></div>
<?php if (!$students): ?><div class="empty">Không tìm thấy sinh viên phù hợp.</div><?php else: ?><div class="cards"><?php foreach ($students as $student): ?><article class="student-card"><img class="avatar" src="<?= e(avatar_url($student['avatar'])) ?>" alt="Ảnh đại diện <?= e($student['fullname']) ?>"><div class="card-content"><div class="tag"><?= e($student['class_name']) ?></div><h3><?= e($student['fullname']) ?></h3><p>Mã SV: <?= e($student['student_code']) ?></p><a class="card-link" href="detail.php?id=<?= (int)$student['id'] ?>">Xem hồ sơ →</a></div></article><?php endforeach; ?></div><?php endif; ?>
<?php if ($pages > 1): ?><div class="pagination"><?php for ($i=1;$i<=$pages;$i++): ?><a class="<?= $i === $page ? 'active' : '' ?>" href="?<?= e(http_build_query(['q'=>$q,'class'=>$class,'page'=>$i])) ?>"><?= $i ?></a><?php endfor; ?></div><?php endif; ?>
<?php require __DIR__.'/includes/footer.php'; ?>
