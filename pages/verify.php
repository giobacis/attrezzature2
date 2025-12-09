<?php
require __DIR__.'/config.php';
$msg = '';
if (!empty($_GET['token']) && !empty($_GET['email'])) {
  $token = $_GET['token'];
  $email = $_GET['email'];
  try {
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
  } catch (Throwable $e) {
    // log in produzione
  }
}
?>
<!doctype html>
<html lang="it">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Verifica</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/common.css">
  <style>
    .auth-wrap { min-height: 100vh; display:grid; place-items:center; padding: 2rem 1rem; }
    .auth-card { width:100%; max-width: 520px; }
  </style>
</head>
<body class="bg-light">
  <main class="auth-wrap">
    <div class="card auth-card shadow-sm">
      <div class="card-body p-4">
        <h1 class="h5 mb-3">Verifica account</h1>
        <?php if ($msg): ?>
          <div class="alert alert-success"><?php echo htmlspecialchars($msg); ?></div>
        <?php else: ?>
          <div class="alert alert-secondary">Link non valido o già utilizzato.</div>
        <?php endif; ?>
        <div class="d-flex justify-content-between">
          <a href="login.php" class="btn btn-primary">Vai al login</a>
          <a href="catalogo.php" class="btn btn-outline-secondary">Vai al Catalogo</a>
        </div>
      </div>
    </div>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
