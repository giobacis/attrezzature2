<?php
require __DIR__.'/includes/session_boot.php';
require __DIR__.'/includes/auth_config.php';
if (isset($_SESSION['role']) && isset($_SESSION['email'])) { redirect_after_login($_SESSION['role']); }
?>
<!doctype html>
<html lang="it">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Scuola Fantoni • Gestione Attrezzature</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>body{min-height:100vh;display:flex;align-items:center}.hero{max-width:900px;margin:auto}</style>
</head>
<body class="bg-light">
  <div class="container pt-3">
    <?php include __DIR__.'/includes/user_badge.php'; ?>
  </div>
  <main class="hero text-center p-4">
    <h1 class="display-6 mb-2">Prenotazioni & Inventario Attrezzature</h1>
    <p class="text-muted mb-4">Scuola d'Arte Applicata A. Fantoni</p>
    <div class="d-flex flex-wrap justify-content-center gap-2">
      <a class="btn btn-primary btn-lg" href="pages/login.php">Accedi</a>
      <a class="btn btn-outline-secondary btn-lg" href="pages/register.php">Registrati</a>
      <a class="btn btn-outline-dark btn-lg" href="pages/catalogo.php">Vai al Catalogo</a>
    </div>
  </main>
</body>
</html>
