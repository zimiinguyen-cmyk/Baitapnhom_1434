<?php
require __DIR__.'/../includes/functions.php';
require_admin();
require __DIR__.'/../config/db.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Method Not Allowed'); }
verify_csrf();
$id = filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT);
if (!$id) { http_response_code(400); exit('ID không hợp lệ.'); }
$stmt=$pdo->prepare('SELECT avatar FROM students WHERE id=?');
$stmt->execute([$id]);
$student=$stmt->fetch();
if ($student) { $delete=$pdo->prepare('DELETE FROM students WHERE id=?'); $delete->execute([$id]); remove_avatar($student['avatar']); }
redirect('manage.php?success=1');
