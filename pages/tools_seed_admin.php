
<?php
// pages/tools_seed_admin.php — crea/aggiorna super-admin
require __DIR__.'/config.php';
session_start();

$default_email = 'giovanni.bacis@cfpscuolafantoni.org';
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? $default_email);
  $pass  = $_POST['password'] ?? '';
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $msg = 'Email non valida';
  } elseif ($pass === '' || strlen($pass) < 8) {
    $msg = 'Password troppo corta (min 8)';
  } else {
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    // upsert admin attivo
    $st = $pdo->prepare('SELECT id FROM users WHERE email=?');
    $st->execute([$email]);
    if ($u = $st->fetch()) {
      $up = $pdo->prepare('UPDATE users SET password_hash=?, role="ADMIN", status="active", verify_token=NULL WHERE id=?');
      $up->execute([$hash, (int)$u['id']]);
      $msg = 'Admin aggiornato';
    } else {
      $ins = $pdo->prepare('INSERT INTO users (email,password_hash,role,status) VALUES (?,?,"ADMIN","active")');
      $ins->execute([$email,$hash]);
      $msg = 'Admin creato';
    }
  }
}
?>
<!doctype html>
<html lang="it">
<head><meta charset="utf-8"><title>Seed Admin</title></head>
<body>
  <h1>Seed Super-Admin</h1>
  <?php if ($msg): ?><p><?= htmlspecialchars($msg) ?></p><?php endif; ?>
  <form method="post">
    <label>Email admin <input type="email" name="email" value="<?= htmlspecialchars($default_email) ?>" required></label><br>
    <label>Password <input type="password" name="password" required></label><br>
    <button type="submit">Crea/Aggiorna</button>
  </form>
  <p><a href="../login.php">Vai al login</a></p>
</body>
</html>
