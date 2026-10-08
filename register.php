<?php
require_once 'includes/config.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $name = trim($_POST['name'] ?? ''); $email = trim($_POST['email'] ?? ''); $pass = $_POST['password'] ?? '';
    if ($name === '') $errors[] = 'Enter your name.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email.';
    if (strlen($pass) < 8) $errors[] = 'Password must be at least 8 characters.';
    if (!$errors) {
        $q = $pdo->prepare('SELECT id FROM users WHERE email = ?'); $q->execute([$email]);
        if ($q->fetch()) { $errors[] = 'That email is already registered.'; }
        else {
            $pdo->prepare('INSERT INTO users (name,email,password) VALUES (?,?,?)')
                ->execute([$name, $email, password_hash($pass, PASSWORD_DEFAULT)]);
            session_regenerate_id(true);
            $_SESSION['user'] = ['id' => $pdo->lastInsertId(), 'name' => $name, 'role' => 'user'];
            header('Location: messages.php'); exit;
        }
    }
}
$title = 'Register'; include 'includes/header.php';
?>
<h1>Create an account</h1>
<p class="muted">Register to send me a message.</p>
<?php foreach ($errors as $er): ?><p class="alert"><?= e($er) ?></p><?php endforeach; ?>
<form method="post" class="form">
  <?= csrf_field() ?>
  <label>Name<input name="name" value="<?= e($_POST['name'] ?? '') ?>" required></label>
  <label>Email<input type="email" name="email" value="<?= e($_POST['email'] ?? '') ?>" required></label>
  <label>Password (8+ characters)<input type="password" name="password" minlength="8" required></label>
  <button class="btn p" type="submit">Register</button>
</form>
<p class="muted">Already registered? <a href="login.php">Log in</a></p>
<?php include 'includes/footer.php'; ?>
