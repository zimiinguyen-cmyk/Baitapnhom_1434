<?php
require __DIR__.'/../includes/functions.php';
require_admin();
require __DIR__.'/../config/db.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
$student = null;
if ($id) { $stmt=$pdo->prepare('SELECT * FROM students WHERE id=?'); $stmt->execute([$id]); $student=$stmt->fetch(); if (!$student) { http_response_code(404); exit('Không tìm thấy sinh viên.'); } }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $postId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: 0;
    if ($postId !== $id) { http_response_code(400); exit('Dữ liệu không hợp lệ.'); }
    try {
        $data = student_validation($_POST);
        $check=$pdo->prepare('SELECT id FROM students WHERE student_code=? AND id<>?');
        $check->execute([$data['student_code'],$id]);
        if ($check->fetch()) { throw new RuntimeException('Mã sinh viên đã tồn tại.'); }
        $avatar=store_avatar($_FILES['avatar'] ?? [], $student['avatar'] ?? null);
        if ($id) {
            $stmt=$pdo->prepare('UPDATE students SET student_code=?,fullname=?,class_name=?,email=?,phone=?,hobbies=?,major=?,skills=?,avatar=? WHERE id=?');
            $stmt->execute([$data['student_code'],$data['fullname'],$data['class_name'],$data['email'],$data['phone'],$data['hobbies'],$data['major'],$data['skills'],$avatar,$id]);
            if ($avatar !== $student['avatar']) { remove_avatar($student['avatar']); }
        } else {
            $stmt=$pdo->prepare('INSERT INTO students (student_code,fullname,class_name,email,phone,hobbies,major,skills,avatar,created_by) VALUES (?,?,?,?,?,?,?,?,?,?)');
            $stmt->execute([$data['student_code'],$data['fullname'],$data['class_name'],$data['email'],$data['phone'],$data['hobbies'],$data['major'],$data['skills'],$avatar,(int)$_SESSION['admin_id']]);
        }
        redirect('manage.php?success=1');
    } catch (RuntimeException $e) { $error=$e->getMessage(); }
    catch (PDOException $e) { $error='Không thể lưu dữ liệu. Kiểm tra mã sinh viên và kết nối CSDL.'; }
}
$values = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : ($student ?: []);
$pageTitle = $id ? 'Sửa sinh viên' : 'Thêm sinh viên'; $prefix='../';
require __DIR__.'/../includes/header.php';
?>
<div class="breadcrumb"><a href="manage.php">Quản lý sinh viên</a> / <?= $id ? 'Chỉnh sửa' : 'Thêm mới' ?></div><div class="form-panel"><div class="eyebrow">QUẢN TRỊ HỒ SƠ</div><h1><?= $id ? 'Chỉnh sửa hồ sơ' : 'Thêm sinh viên mới' ?></h1><?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?><form method="post" enctype="multipart/form-data" class="student-form" id="student-form"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= $id ?>"><div class="form-grid"><label>Mã sinh viên <span>*</span><input name="student_code" maxlength="20" required value="<?= e($values['student_code'] ?? '') ?>"></label><label>Họ và tên <span>*</span><input name="fullname" maxlength="100" required value="<?= e($values['fullname'] ?? '') ?>"></label><label>Lớp <span>*</span><input name="class_name" maxlength="50" required value="<?= e($values['class_name'] ?? '') ?>"></label><label>Ngành học<input name="major" maxlength="100" value="<?= e($values['major'] ?? '') ?>"></label><label>Email<input type="email" name="email" maxlength="100" value="<?= e($values['email'] ?? '') ?>"></label><label>Số điện thoại<input name="phone" maxlength="20" value="<?= e($values['phone'] ?? '') ?>"></label><label class="wide">Sở thích<textarea name="hobbies" rows="3"><?= e($values['hobbies'] ?? '') ?></textarea></label><label class="wide">Kỹ năng<textarea name="skills" rows="3"><?= e($values['skills'] ?? '') ?></textarea></label><label class="wide">Ảnh đại diện (JPG, PNG, WEBP; tối đa 2 MB)<input type="file" name="avatar" id="avatar" accept="image/jpeg,image/png,image/webp"></label></div><div class="avatar-preview"><img id="avatar-preview" src="<?= e(avatar_url($student['avatar'] ?? null,true)) ?>" alt="Xem trước ảnh"></div><div class="form-actions"><button class="btn primary" type="submit"><?= $id ? 'Lưu thay đổi' : 'Thêm sinh viên' ?></button><a class="btn secondary" href="manage.php">Hủy</a></div></form></div>
<?php require __DIR__.'/../includes/footer.php'; ?>
