<?php
require __DIR__.'/../includes/functions.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Method Not Allowed'); }
verify_csrf();
$_SESSION = [];
session_regenerate_id(true);
redirect('login.php');
