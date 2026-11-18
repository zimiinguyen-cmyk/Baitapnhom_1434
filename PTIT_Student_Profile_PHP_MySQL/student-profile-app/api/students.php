<?php
require __DIR__.'/../config/db.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
if ($_SERVER['REQUEST_METHOD'] !== 'GET') { http_response_code(405); echo json_encode(['error'=>'Method Not Allowed']); exit; }
$q=trim((string)($_GET['q'] ?? ''));
$stmt=$pdo->prepare('SELECT id,student_code,fullname,class_name,major FROM students WHERE fullname LIKE ? OR student_code LIKE ? ORDER BY fullname LIMIT 50');
$stmt->execute(['%'.$q.'%','%'.$q.'%']);
echo json_encode(['data'=>$stmt->fetchAll()],JSON_UNESCAPED_UNICODE);
