
<?php
// Home amministrativa — riservata a ADMIN
session_start();
$required_role='ADMIN';
require __DIR__.'/../includes/auth_check.php';
?>
<!doctype html>
<html lang="it">
<head>
  <meta charset="utf-8">
  <title>Home Amministrativa</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
  <div class="container py-4">
    <header class="mb-4">
      <h1 class="h3 mb-1">Home amministrativa</h1>
      <p class="text-muted mb-0">Benvenuto, <?= htmlspecialchars($_SESSION['email'] ?? '') ?>.</p>
    </header>

    <div class="row g-3">
      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Inventario & Schede</h5>
            <p class="card-text">Gestisci attrezzature, categorie, fornitori e posizioni.</p>
            <div class="d-flex gap-2">
              <a class="btn btn-primary" href="dashboard.php">Dashboard IT</a>
              <a class="btn btn-outline-secondary" href="attrezzature.php">Attrezzature</a>
              <a class="btn btn-outline-secondary" href="categorie.php">Categorie</a>
              <a class="btn btn-outline-secondary" href="fornitori.php">Fornitori</a>
              <a class="btn btn-outline-secondary" href="posizioni.php">Posizioni</a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Prenotazioni</h5>
            <p class="card-text">Approva, rifiuta, registra rientri e consulta il calendario.</p>
            <div class="d-flex gap-2">
              <a class="btn btn-primary" href="gestione_prenotazioni.php">Gestione</a>
              <a class="btn btn-outline-secondary" href="prenotazioni.php">Calendario</a>
              <a class="btn btn-outline-secondary" href="catalogo.php">Catalogo (front‑end)</a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Utenti & Accessi</h5>
            <p class="card-text">(Prossimo step) Crea/gestisci utenti IT e abilita/disabilita account.</p>
            <div class="d-flex gap-2">
              <a class="btn btn-outline-secondary disabled" href="#" aria-disabled="true">Gestione utenti</a>
              <a class="btn btn-outline-secondary" href="tools_seed_admin.php">Seed Super‑Admin</a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Sistema</h5>
            <p class="card-text">Info PHP e configurazione SMTP.</p>
            <div class="d-flex gap-2">
              <a class="btn btn-outline-secondary" href="info.php">phpinfo()</a>
              <a class="btn btn-outline-secondary" href="../includes/smtp_config.php">smtp_config.php</a>
              <a class="btn btn-outline-danger" href="../logout.php">Logout</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
