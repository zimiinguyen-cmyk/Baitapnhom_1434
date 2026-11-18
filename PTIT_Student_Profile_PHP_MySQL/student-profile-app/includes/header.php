<?php
$pageTitle = $pageTitle ?? 'Hồ sơ sinh viên';
$prefix = $prefix ?? '';
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pageTitle) ?> | PTIT Student Hub</title>
<link rel="stylesheet" href="<?= e($prefix) ?>assets/css/style.css">
<script src="<?= e($prefix) ?>assets/js/main.js" defer></script>
</head>
<body>
<header class="topbar"><div class="container nav"><a class="brand" href="<?= e($prefix) ?>index.php"><span class="brand-icon">P</span><span>PTIT <b>Student Hub</b></span></a><nav><a href="<?= e($prefix) ?>index.php">Danh sách sinh viên</a><?php if (is_admin()): ?><a href="<?= e($prefix) ?>admin/dashboard.php">Dashboard</a><a href="<?= e($prefix) ?>admin/manage.php">Quản lý</a><form class="inline" action="<?= e($prefix) ?>admin/logout.php" method="post"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><button class="nav-button" type="submit">Đăng xuất</button></form><?php else: ?><a class="nav-button" href="<?= e($prefix) ?>admin/login.php">Đăng nhập Admin</a><?php endif; ?></nav></div></header>
<main class="container">
