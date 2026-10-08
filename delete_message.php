<?php
require_once 'includes/config.php';
need_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $id = (int)($_POST['id'] ?? 0);
    // DELETE: admin can delete any message, users only their own
    if (is_admin()) $pdo->prepare('DELETE FROM messages WHERE id = ?')->execute([$id]);
    else $pdo->prepare('DELETE FROM messages WHERE id = ? AND user_id = ?')->execute([$id, user()['id']]);
}
header('Location: messages.php');
