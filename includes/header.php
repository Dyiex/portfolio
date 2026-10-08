<?php require_once __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title><?= e($title ?? 'Messages') ?> | Angelo Tindog</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap">
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav>
  <div class="bar">
    <a class="logo" href="index.html">Angelo Tindog</a>
    <div class="links">
      <a href="index.html#projects">Projects</a>
      <a href="messages.php">Messages</a>
      <?php if (user()): ?>
        <a href="logout.php">Logout (<?= e(user()['name']) ?>)</a>
      <?php else: ?>
        <a href="login.php">Login</a><a href="register.php">Register</a>
      <?php endif; ?>
    </div>
    <button class="tbtn" id="theme" type="button" aria-label="Toggle light or dark theme">◐</button>
  </div>
</nav>
<main class="page">
