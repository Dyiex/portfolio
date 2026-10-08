<?php
require_once 'includes/config.php';
need_login();
$error = '';
// CREATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $subject = trim($_POST['subject'] ?? ''); $body = trim($_POST['body'] ?? '');
    if ($subject === '' || $body === '') { $error = 'Enter a subject and a message.'; }
    else {
        $pdo->prepare('INSERT INTO messages (user_id,subject,body) VALUES (?,?,?)')->execute([user()['id'], $subject, $body]);
        header('Location: messages.php'); exit;
    }
}
// READ: admin sees everyone's messages, users see their own
if (is_admin()) {
    $rows = $pdo->query('SELECT m.*, u.name, u.email FROM messages m JOIN users u ON u.id = m.user_id ORDER BY m.created_at DESC')->fetchAll();
} else {
    $q = $pdo->prepare('SELECT m.*, u.name, u.email FROM messages m JOIN users u ON u.id = m.user_id WHERE m.user_id = ? ORDER BY m.created_at DESC');
    $q->execute([user()['id']]); $rows = $q->fetchAll();
}
$title = 'Messages'; include 'includes/header.php';
?>
<h1>Messages</h1>
<p class="muted"><?= is_admin() ? 'Admin view: all messages.' : 'Send me a message. You can edit or delete your own.' ?></p>
<?php if ($error): ?><p class="alert"><?= e($error) ?></p><?php endif; ?>
<form method="post" class="form">
  <?= csrf_field() ?>
  <label>Subject<input name="subject" maxlength="150" required></label>
  <label>Message<textarea name="body" rows="4" required></textarea></label>
  <button class="btn p" type="submit">Send message</button>
</form>
<h2>Your inbox</h2>
<?php if (!$rows): ?><p class="muted">No messages yet. Write your first one above.</p><?php endif; ?>
<?php foreach ($rows as $m): ?>
  <article class="msg">
    <h3><?= e($m['subject']) ?></h3>
    <p class="muted"><?= e($m['name']) ?><?= is_admin() ? ' (' . e($m['email']) . ')' : '' ?> · <?= e($m['created_at']) ?><?= $m['updated_at'] ? ' · edited' : '' ?></p>
    <p><?= nl2br(e($m['body'])) ?></p>
    <div class="row">
      <?php if ($m['user_id'] == user()['id']): ?><a class="btn" href="edit_message.php?id=<?= (int)$m['id'] ?>">Edit</a><?php endif; ?>
      <form method="post" action="delete_message.php" onsubmit="return confirm('Delete this message?')">
        <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$m['id'] ?>">
        <button class="btn danger" type="submit">Delete</button>
      </form>
    </div>
  </article>
<?php endforeach; ?>
<?php include 'includes/footer.php'; ?>
