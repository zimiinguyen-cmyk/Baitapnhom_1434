<?php
require __DIR__.'/includes/functions.php';
require __DIR__.'/config/db.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$stmt = $pdo->prepare('SELECT * FROM students WHERE id = ?');
$stmt->execute([$id ?: 0]);
$student = $stmt->fetch();
if (!$student) { http_response_code(404); $pageTitle = 'Không tìm thấy'; require __DIR__.'/includes/header.php'; echo '<div class="empty">Không tìm thấy hồ sơ sinh viên. <a href="index.php">Quay lại</a></div>'; require __DIR__.'/includes/footer.php'; exit; }
$pageTitle = $student['fullname'];
require __DIR__.'/includes/header.php';
?>
<div class="breadcrumb"><a href="index.php">Danh sách sinh viên</a> / Chi tiết hồ sơ</div>
<div class="profile"><aside class="profile-aside"><img src="<?= e(avatar_url($student['avatar'])) ?>" alt="Ảnh đại diện <?= e($student['fullname']) ?>"><h2><?= e($student['fullname']) ?></h2><div class="tag"><?= e($student['class_name']) ?></div><p><?= e($student['student_code']) ?></p></aside><section class="profile-info"><div class="section-head"><h2>Thông tin cá nhân</h2></div><dl><div><dt>Mã sinh viên</dt><dd><?= e($student['student_code']) ?></dd></div><div><dt>Họ và tên</dt><dd><?= e($student['fullname']) ?></dd></div><div><dt>Lớp</dt><dd><?= e($student['class_name']) ?></dd></div><div><dt>Ngành học</dt><dd><?= e($student['major'] ?: 'Chưa cập nhật') ?></dd></div><div><dt>Email</dt><dd><?= e($student['email'] ?: 'Chưa cập nhật') ?></dd></div><div><dt>Số điện thoại</dt><dd><?= e($student['phone'] ?: 'Chưa cập nhật') ?></dd></div><div><dt>Sở thích</dt><dd><?= nl2br(e($student['hobbies'] ?: 'Chưa cập nhật')) ?></dd></div><div><dt>Kỹ năng</dt><dd><?= nl2br(e($student['skills'] ?: 'Chưa cập nhật')) ?></dd></div></dl><a class="btn secondary" href="index.php">← Quay lại danh sách</a></section></div>
<?php require __DIR__.'/includes/footer.php'; ?>
