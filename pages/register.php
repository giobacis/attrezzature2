
<?php
require __DIR__.'/config.php';
require __DIR__.'/../includes/auth_config.php';
require __DIR__.'/../includes/smtp_config.php';
require __DIR__.'/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

$errors = [];
$done = false;
$first = '';
$last  = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $pass1 = (string)($_POST['password'] ?? '');
  $pass2 = (string)($_POST['password_confirm'] ?? '');
  $first = trim($_POST['first_name'] ?? '');
  $last  = trim($_POST['last_name'] ?? '');

  if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Email non valida.';
  } else {
    $domain = strtolower(substr(strrchr($email, '@'), 1));
    $allowed = array_map('strtolower', $ALLOWED_DOMAINS);
    if (!in_array($domain, $allowed, true)) {
      $errors[] = 'Email non appartenente ai domini consentiti.';
    }
  }

  if ($pass1 === '' || strlen($pass1) < 8) {
    $errors[] = 'Password troppo corta (min 8 caratteri).';
  }
  if ($pass1 !== $pass2) {
    $errors[] = 'Le password non coincidono.';
  }

  if (!$errors) {
    $st = $pdo->prepare('SELECT 1 FROM users WHERE email=?');
    $st->execute([$email]);
    if ($st->fetch()) {
      $errors[] = 'Esiste già un account con questa email.';
    }
  }

  if (!$errors) {
    $token = bin2hex(random_bytes(32));
    $hash  = password_hash($pass1, PASSWORD_DEFAULT);

    $ins = $pdo->prepare('INSERT INTO users (email,password_hash,role,status,verify_token,first_name,last_name)
                          VALUES (?,?,?,?,?,?,?)');
    $ins->execute([$email,$hash,'USER','pending_email',$token,$first ?: null,$last ?: null]);

    $base = app_base_path();
    $verifyUrl = $base . '/pages/verify.php?token=' . urlencode($token) . '&email=' . urlencode($email);

    $mail = new PHPMailer(true);
    try {
      if ($SMTP_ENABLED) {
        $mail->isSMTP();
        $mail->Host       = $SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = $SMTP_USERNAME;
        $mail->Password   = $SMTP_PASSWORD;
        $mail->SMTPSecure = $SMTP_SECURE;
        $mail->Port       = $SMTP_PORT;
      } else {
        $mail->isMail();
      }
      $fromEmail = $SMTP_FROM_EMAIL ?: 'no-reply@localhost';
      $fromName  = $SMTP_FROM_NAME  ?: 'Prenotazioni';
      $mail->setFrom($fromEmail, $fromName);
      $mail->addAddress($email);
      $mail->Subject = 'Conferma registrazione';
      $mail->Body    = "Ciao,
conferma la tua registrazione cliccando questo link:
" . (isset($_SERVER['HTTP_HOST']) ? ( (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']==='on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] ) : '') . $verifyUrl . "

Se non hai richiesto l'account ignora questa email.";
      $mail->send();
    } catch (Exception $e) {
      // log in produzione
    }

    $done = true;
  }
}
?>
<!doctype html>
<html lang="it">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registrazione</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-body p-4">
            <h1 class="h4 mb-3">Registrazione utente</h1>
            <?php if ($done): ?>
              <div class="alert alert-success">Registrazione avviata. Controlla la tua email per confermare l’account.</div>
              <p><a href="login.php" class="btn btn-primary">Vai al login</a></p>
            <?php else: ?>
              <?php if ($errors): ?>
                <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul></div>
              <?php endif; ?>
              <form method="post" novalidate>
                <div class="mb-3">
                  <label class="form-label">Nome</label>
                  <input class="form-control" name="first_name" value="<?php echo htmlspecialchars($first); ?>">
                </div>
                <div class="mb-3">
                  <label class="form-label">Cognome</label>
                  <input class="form-control" name="last_name"  value="<?php echo htmlspecialchars($last); ?>">
                </div>
                <div class="mb-3">
                  <label class="form-label">Email*</label>
                  <input class="form-control" name="email" type="email" required value="<?php echo htmlspecialchars($email); ?>">
                </div>
                <div class="mb-3">
                  <label class="form-label">Password*</label>
                  <input class="form-control" name="password" type="password" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Conferma Password*</label>
                  <input class="form-control" name="password_confirm" type="password" required>
                </div>
                <button class="btn btn-primary w-100" type="submit">Crea account</button>
              </form>
              <div class="mt-3 d-flex justify-content-between">
                <a href="login.php">Hai già un account? Accedi</a>
                <a href="catalogo.php">Vai al Catalogo</a>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
