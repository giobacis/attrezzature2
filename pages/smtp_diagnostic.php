
<?php
require __DIR__.'/config.php';
require __DIR__.'/../includes/smtp_config.php';
require __DIR__.'/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$log = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $mail = new PHPMailer(true);
  try {
    $mail->isSMTP();
    $mail->Host       = (string)$SMTP_HOST;
    $mail->Port       = (int)$SMTP_PORT;
    $mail->SMTPAuth   = true;
    $mail->Username   = (string)$SMTP_USERNAME;
    $mail->Password   = (string)$SMTP_PASSWORD;
    $mail->SMTPSecure = (string)$SMTP_SECURE;
    $mail->AuthType   = 'LOGIN';
    $mail->SMTPDebug  = 3;
    $mail->Debugoutput= function($str, $level) use (&$log) { $log .= '['.date('H:i:s').'] '.$str."
"; };

    // handshake di prova: connessione e AUTH
    $mail->preSend(); // forza la costruzione
    // forza connessione
    if (!$mail->smtpConnect()) { throw new Exception('Connessione SMTP fallita'); }
    // AUTH di prova
    if (!$mail->smtp->authenticate($SMTP_USERNAME, $SMTP_PASSWORD)) { throw new Exception('AUTH fallita'); }
    $mail->smtpClose();
  } catch (Exception $e) {
    $error = 'Diagnostica: '.$e->getMessage();
  }
}
?>
<!doctype html>
<html lang="it">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Diagnostica SMTP</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-4">
  <h1 class="h4 mb-3">Diagnostica SMTP (dry‑run)</h1>
  <p class="text-muted">Esegue solo connessione e autenticazione, senza inviare email.</p>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <form method="post" class="card p-3 mb-3">
    <button class="btn btn-primary" type="submit">Esegui test</button>
    <a class="btn btn-outline-secondary" href="test_email.php">Test invio</a>
  </form>

  <?php if (!empty($log)): ?>
    <div class="card">
      <div class="card-header">Log</div>
      <div class="card-body"><pre style="white-space:pre-wrap;"><?php echo htmlspecialchars($log); ?></pre></div>
    </div>
  <?php endif; ?>
</div>
</body>
</html>
