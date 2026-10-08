<?php
require_once 'includes/config.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $q = $pdo->prepare('SELECT * FROM users WHERE email = ?'); $q->execute([trim($_POST['email'] ?? '')]);
    $u = $q->fetch();
    if ($u && password_verify($_POST['password'] ?? '', $u['password'])) {
        session_regenerate_id(true);
        $_SESSION['user'] = ['id' => $u['id'], 'name' => $u['name'], 'role' => $u['role']];
        header('Location: messages.php'); exit;
    }
    $error = 'Wrong email or password.';
}
$title = 'Login'; include 'includes/header.php';
?>
<h1>Log in</h1>
<?php if ($error): ?><p class="alert"><?= e($error) ?></p><?php endif; ?>
<form method="post" class="form">
  <?= csrf_field() ?>
  <label>Email<input type="email" name="email" required></label>
  <label>Password<input type="password" name="password" required></label>
  <button class="btn p" type="submit">Log in</button>
</form>
<p class="muted">No account? <a href="register.php">Register</a></p>
<?php include 'includes/footer.php'; ?>
