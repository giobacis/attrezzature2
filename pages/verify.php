
<?php
require __DIR__.'/config.php';
session_start();

$token = $_GET['token'] ?? '';
$email = $_GET['email'] ?? '';
$msg = 'Link non valido o già usato.';

if ($token !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $st = $pdo->prepare('SELECT id,status FROM users WHERE email=? AND verify_token=? LIMIT 1');
  $st->execute([$email,$token]);
  if ($u = $st->fetch()) {
    if ($u['status'] !== 'active') {
      $up = $pdo->prepare('UPDATE users SET status="active", verify_token=NULL WHERE id=?');
      $up->execute([(int)$u['id']]);
      $msg = 'Account confermato! Ora puoi accedere.';
    } else {
      $msg = 'Account già attivo. Puoi accedere.';
    }
  }
}
?>
<!doctype html>
<html lang="it">
<head><meta charset="utf-8"><title>Verifica</title></head>
<body>
  <p><?= htmlspecialchars($msg) ?></p>
  <p><a href="login.php">Vai al login</a></p>
</body>
</html>
