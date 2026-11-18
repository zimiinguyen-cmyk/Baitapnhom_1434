<?php
declare(strict_types=1);
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
function e(?string $value): string { return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) { $_SESSION['csrf'] = bin2hex(random_bytes(32)); }
    return $_SESSION['csrf'];
}
function verify_csrf(): void {
    if (!hash_equals(csrf_token(), (string)($_POST['csrf'] ?? ''))) { http_response_code(419); exit('Phiên biểu mẫu không hợp lệ.'); }
}
function is_admin(): bool { return isset($_SESSION['admin_id']); }
function require_admin(): void { if (!is_admin()) { header('Location: login.php'); exit; } }
function redirect(string $path): never { header('Location: '.$path); exit; }
function avatar_url(?string $avatar, bool $admin = false): string {
    $base = $admin ? '../' : '';
    return $base.'uploads/'.rawurlencode($avatar ?: 'default-avatar.svg');
}
function store_avatar(array $file, ?string $old = null): ?string {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) { return $old; }
    if (($file['error'] ?? -1) !== UPLOAD_ERR_OK || ($file['size'] ?? 0) > 2 * 1024 * 1024) { throw new RuntimeException('Ảnh không hợp lệ hoặc vượt quá 2 MB.'); }
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $types = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if (!isset($types[$mime]) || getimagesize($file['tmp_name']) === false) { throw new RuntimeException('Chỉ chấp nhận JPG, PNG hoặc WEBP.'); }
    $name = bin2hex(random_bytes(16)).'.'.$types[$mime];
    if (!move_uploaded_file($file['tmp_name'], __DIR__.'/../uploads/'.$name)) { throw new RuntimeException('Không thể lưu ảnh.'); }
    return $name;
}
function remove_avatar(?string $avatar): void {
    if ($avatar && $avatar !== 'default-avatar.svg' && preg_match('/^[a-f0-9]{32}\.(jpg|png|webp)$/', $avatar)) {
        $path = __DIR__.'/../uploads/'.$avatar;
        if (is_file($path)) { unlink($path); }
    }
}
function student_validation(array $input): array {
    $data = [];
    foreach (['student_code','fullname','class_name','email','phone','hobbies','major','skills'] as $key) { $data[$key] = trim((string)($input[$key] ?? '')); }
    if ($data['student_code'] === '' || mb_strlen($data['student_code']) > 20) { throw new RuntimeException('Mã sinh viên bắt buộc và tối đa 20 ký tự.'); }
    if ($data['fullname'] === '' || mb_strlen($data['fullname']) > 100) { throw new RuntimeException('Họ tên bắt buộc và tối đa 100 ký tự.'); }
    if ($data['class_name'] === '' || mb_strlen($data['class_name']) > 50) { throw new RuntimeException('Lớp bắt buộc và tối đa 50 ký tự.'); }
    if ($data['email'] !== '' && (!filter_var($data['email'], FILTER_VALIDATE_EMAIL) || strlen($data['email']) > 100)) { throw new RuntimeException('Email không hợp lệ.'); }
    if ($data['phone'] !== '' && !preg_match('/^[0-9+().\s-]{8,20}$/', $data['phone'])) { throw new RuntimeException('Số điện thoại không hợp lệ.'); }
    if (mb_strlen($data['major']) > 100) { throw new RuntimeException('Ngành học quá dài.'); }
    return $data;
}
