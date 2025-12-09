
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
  <!-- Allineamento grafico con index: usa common.css della app -->
  <link rel="stylesheet" href="assets/css/common.css">
  <!-- Micro-stili specifici del login: opzionale, puoi spostarli in assets/css/login.css -->
  <style>
    .login-wrap { min-height: 100vh; display: grid; place-items: center; padding: 2rem 1rem; }
    .login-card { width: 100%; max-width: 420px; }
    .brand-mini { display:flex; align-items:center; gap:.5rem; font-weight:700; margin-bottom: .75rem; }
    .brand-mini span { font-size: 1.25rem; }
  </style>
</head>
<body class="bg-light">
  <main class="login-wrap">
    <div class="card login-card shadow-sm">
      <div class="card-body p-4">
        <div class="brand-mini"><span>🎒</span><span>Gestione Attrezzature</span></div>
        <h1 class="h5 mb-3">Accedi</h1>
        <?php if ($error): ?>
          <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post" novalidate>
          <div class="mb-3">
            <label class="form-label" for="email">Email istituzionale</label>
            <input class="form-control" type="email" id="email" name="email" autocomplete="username" required>
          </div>
          <div class="mb-3">
            <label class="form-label" for="password">Password</label>
            <input class="form-control" type="password" id="password" name="password" autocomplete="current-password" required>
          </div>
          <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1">
            <label class="form-check-label" for="remember">Rimani connesso</label>
          </div>
          <button class="btn btn-primary w-100" type="submit">Accedi</button>
        </form>
        <hr class="my-4">
        <div class="d-flex justify-content-between">
          <a href="register.php">Registrati</a>
          <a href="catalogo.php">Vai al Catalogo</a>
        </div>
      </div>
    </div>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
