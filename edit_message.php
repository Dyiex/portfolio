<?php
require_once 'includes/config.php';
need_login();
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$q = $pdo->prepare('SELECT * FROM messages WHERE id = ? AND user_id = ?'); $q->execute([$id, user()['id']]);
$m = $q->fetch();
if (!$m) { http_response_code(404); die('Message not found.'); }
$error = '';
// UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $subject = trim($_POST['subject'] ?? ''); $body = trim($_POST['body'] ?? '');
    if ($subject === '' || $body === '') { $error = 'Enter a subject and a message.'; }
    else {
        $pdo->prepare('UPDATE messages SET subject = ?, body = ? WHERE id = ? AND user_id = ?')->execute([$subject, $body, $id, user()['id']]);
        header('Location: messages.php'); exit;
    }
}
$title = 'Edit message'; include 'includes/header.php';
?>
<h1>Edit message</h1>
<?php if ($error): ?><p class="alert"><?= e($error) ?></p><?php endif; ?>
<form method="post" class="form">
  <?= csrf_field() ?><input type="hidden" name="id" value="<?= $id ?>">
  <label>Subject<input name="subject" maxlength="150" value="<?= e($m['subject']) ?>" required></label>
  <label>Message<textarea name="body" rows="5" required><?= e($m['body']) ?></textarea></label>
  <div class="row"><button class="btn p" type="submit">Save changes</button><a class="btn" href="messages.php">Cancel</a></div>
</form>
<?php include 'includes/footer.php'; ?>
