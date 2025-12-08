
<?php
require __DIR__.'/config.php';
require __DIR__.'/../includes/smtp_config.php';
require __DIR__.'/../includes/mail_helper.php';

$sent = false; $error = ''; $diag = array();
$to      = isset($_POST['to']) ? trim($_POST['to']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : 'Test invio email';
$body    = isset($_POST['body']) ? trim($_POST['body']) : "Questo è un test.
Se lo ricevi, l'SMTP è configurato correttamente.";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if ($to === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
    $error = 'Inserisci un indirizzo email valido.';
  } else {
    $diag['SMTP_ENABLED']    = isset($SMTP_ENABLED) ? ($SMTP_ENABLED ? 'true' : 'false') : 'undefined';
    $diag['SMTP_HOST']       = isset($SMTP_HOST) ? $SMTP_HOST : 'undefined';
    $diag['SMTP_PORT']       = isset($SMTP_PORT) ? $SMTP_PORT : 'undefined';
    $diag['SMTP_SECURE']     = isset($SMTP_SECURE) ? $SMTP_SECURE : 'undefined';
    $diag['SMTP_USERNAME']   = isset($SMTP_USERNAME) ? $SMTP_USERNAME : 'undefined';
    $diag['SMTP_FROM_EMAIL'] = isset($SMTP_FROM_EMAIL) ? $SMTP_FROM_EMAIL : 'undefined';

    $sent = send_app_mail($to, $subject, $body);
    if (!$sent) { $error = 'Invio fallito. Controlla App Password (senza spazi) e From = username.'; }
  }
}
?>
<!doctype html>
<html lang="it">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Test invio email</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-4">
  <h1 class="h4 mb-3">Test invio email (PHPMailer)</h1>
  <p class="text-muted">Compila e invia una mail di prova. Sotto vedi la diagnostica della configurazione.</p>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>
  <?php if ($sent): ?>
    <div class="alert alert-success">Email di test inviata (controlla anche lo spam).</div>
  <?php endif; ?>

  <form method="post" class="card p-3 mb-4" novalidate>
    <div class="mb-3">
      <label class="form-label">Destinatario *</label>
      <input name="to" type="email" class="form-control" required value="<?php echo htmlspecialchars($to); ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Oggetto</label>
      <input name="subject" class="form-control" value="<?php echo htmlspecialchars($subject); ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Corpo</label>
      <textarea name="body" rows="5" class="form-control"><?php echo htmlspecialchars($body); ?></textarea>
    </div>
    <button class="btn btn-primary" type="submit">Invia test</button>
    <a class="btn btn-outline-secondary" href="catalogo.php">Torna al catalogo</a>
  </form>

  <?php if (!empty($diag)): ?>
    <div class="card mt-3">
      <div class="card-header">Diagnostica SMTP</div>
      <div class="card-body">
        <ul class="mb-0">
          <?php foreach ($diag as $k=>$v) echo '<li><strong>'.htmlspecialchars($k).'</strong>: '.htmlspecialchars($v).'</li>'; ?>
        </ul>
        <p class="small text-muted mt-2">Suggerimenti: App Password senza spazi; From=<?= htmlspecialchars($SMTP_USERNAME ?? '') ?> o alias verificato; TLS(587) o SSL(465).</p>
      </div>
    </div>
  <?php endif; ?>
</div>
</body>
</html>
