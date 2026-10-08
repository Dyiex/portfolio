<?php
// Database connection + helpers. Change the credentials if yours differ.
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=portfolio;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function user() { return $_SESSION['user'] ?? null; }
function is_admin() { return (user()['role'] ?? '') === 'admin'; }
function need_login() { if (!user()) { header('Location: login.php'); exit; } }
function token() { if (empty($_SESSION['t'])) $_SESSION['t'] = bin2hex(random_bytes(16)); return $_SESSION['t']; }
function csrf_field() { return '<input type="hidden" name="t" value="' . token() . '">'; }
function check_csrf() { if (!hash_equals($_SESSION['t'] ?? '', $_POST['t'] ?? '')) die('Invalid request.'); }
