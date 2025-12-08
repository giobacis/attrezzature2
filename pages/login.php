
<?php
require __DIR__.'/config.php';
require __DIR__.'/../includes/auth_config.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $pass  = (string)($_POST['password'] ?? '');

  if ($email === '' || $pass === '') {
    $error = 'Inserisci email e password.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'Formato email non valido.';
  } else {
    try {
      $st = $pdo->prepare('SELECT id,email,password_hash,role,status FROM users WHERE email=? LIMIT 1');
      $st->execute([$email]);
      $u = $st->fetch(PDO::FETCH_ASSOC);
    } catch (Throwable $e) {
      $u = false;
    }

    if (!$u) {
      $error = 'Credenziali non valide.';
    } elseif ($u['status'] !== 'active') {
      $error = 'Account non attivo. Controlla la mail per la conferma.';
    } elseif (!password_verify($pass, $u['password_hash'])) {
      $error = 'Credenziali non valide.';
    } else {
      session_regenerate_id(true);
      $_SESSION['user_id'] = (int)$u['id'];
      $_SESSION['email']   = $u['email'];
      $_SESSION['role']    = $u['role'];
      redirect_after_login($u['role']);
    }
  }
}
?>
<!doctype html>
<html lang="it">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card shadow-sm">
          <div class="card-body p-4">
            <h1 class="h4 mb-3">Accedi</h1>
            <?php if ($error): ?>
              <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="post" novalidate>
              <div class="mb-3">
                <label class="form-label">Email</label>
                <input class="form-control" type="email" name="email" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Password</label>
                <input class="form-control" type="password" name="password" required>
              </div>
              <button class="btn btn-primary w-100" type="submit">Accedi</button>
            </form>
            <div class="mt-3 d-flex justify-content-between">
              <a href="register.php">Registrati</a>
              <a href="catalogo.php">Vai al Catalogo</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
