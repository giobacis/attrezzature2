
<?php
// pages/dashboard.php — template completo per area IT/ADMIN
$required_role = 'IT';
require __DIR__.'/../includes/require_login.php';
?>
<!doctype html>
<html lang="it" data-bs-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard IT</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="assets/css/common.css">
  <link rel="stylesheet" href="assets/css/theme.css">
  <style>
    .card-icon {font-size: 1.75rem; line-height: 1;}
  </style>
</head>
<body>

  <section class="hero-cta text-white">
    <div class="container d-flex flex-wrap align-items-center gap-2">
      <div class="brand fw-bold">Dashboard • Attrezzature</div>
      <div class="ms-auto w-100 w-lg-auto d-flex align-items-center gap-2">
        <?php include __DIR__.'/../includes/user_badge.php'; ?>
        <div class="btn-group" role="group" aria-label="Tema">
          <button type="button" class="btn btn-sm btn-outline-light" data-theme="light" title="Tema chiaro">Chiaro</button>
          <button type="button" class="btn btn-sm btn-outline-light" data-theme="dark" title="Tema scuro">Scuro</button>
          <button type="button" class="btn btn-sm btn-outline-light" data-theme="auto" title="Segui sistema">Sistema</button>
        </div>
      </div>
    </div>
  </section>

  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h1 class="h4 mb-0">Dashboard IT</h1>
      <div class="d-flex align-items-center gap-2">
        <a class="btn btn-outline-secondary" href="home.php">Home Admin</a>
        <a class="btn btn-outline-dark" href="catalogo.php">Apri catalogo</a>
      </div>
    </div>
    <p class="text-muted">Area riservata a IT/ADMIN. Usa le scorciatoie qui sotto per entrare nella gestione.</p>

    <div class="row g-3">
      <!-- Inventario & anagrafiche -->
      <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center mb-2">
              <div class="card-icon me-2">📦</div>
              <h5 class="card-title mb-0">Inventario</h5>
            </div>
            <p class="card-text">Gestisci attrezzature, categorie, fornitori, posizioni.</p>
            <div class="d-flex flex-wrap gap-2">
              <a class="btn btn-primary" href="attrezzature.php">Attrezzature</a>
              <a class="btn btn-outline-secondary" href="categorie.php">Categorie</a>
              <a class="btn btn-outline-secondary" href="fornitori.php">Fornitori</a>
              <a class="btn btn-outline-secondary" href="posizioni.php">Posizioni</a>
            </div>
          </div>
        </div>
      </div>

      <!-- Prenotazioni -->
      <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center mb-2">
              <div class="card-icon me-2">🗓️</div>
              <h5 class="card-title mb-0">Prenotazioni</h5>
            </div>
            <p class="card-text">Approva, rifiuta, gestisci rientri e calendario.</p>
            <div class="d-flex flex-wrap gap-2">
              <a class="btn btn-primary" href="gestione_prenotazioni.php">Gestione</a>
              <a class="btn btn-outline-secondary" href="prenotazioni.php">Calendario</a>
            </div>
          </div>
        </div>
      </div>

      <!-- Utenti & accessi -->
      <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center mb-2">
              <div class="card-icon me-2">👥</div>
              <h5 class="card-title mb-0">Utenti & Accessi</h5>
            </div>
            <p class="card-text">Crea/gestisci utenti IT, abilita/disabilita account.</p>
            <div class="d-flex flex-wrap gap-2">
              <a class="btn btn-outline-secondary" href="users_admin.php">Gestione utenti</a>
              <a class="btn btn-outline-secondary" href="tools_seed_admin.php">Seed Super‑Admin</a>
            </div>
          </div>
        </div>
      </div>

      <!-- Sistema -->
      <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center mb-2">
              <div class="card-icon me-2">⚙️</div>
              <h5 class="card-title mb-0">Sistema</h5>
            </div>
            <p class="card-text">Diagnostica SMTP, info PHP, strumenti.</p>
            <div class="d-flex flex-wrap gap-2">
              <a class="btn btn-outline-secondary" href="smtp_diagnostic.php">Diagnostica SMTP</a>
              <a class="btn btn-outline-secondary" href="test_email.php">Test Email</a>
              <a class="btn btn-outline-secondary" href="info.php">phpinfo()</a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Simple theme switcher: sets data-theme on <html> (matches theme.css selectors)
    (function(){
      function applyTheme(t){
        var html=document.documentElement;
        if(t==='auto'){ html.removeAttribute('data-theme'); }
        else { html.setAttribute('data-theme', t); }
        try{ localStorage.setItem('app_theme', t); }catch(e){}
      }
      var saved=null; try{ saved=localStorage.getItem('app_theme'); }catch(e){}
      if(saved) applyTheme(saved);
      document.querySelectorAll('[data-theme]').forEach(function(b){ b.addEventListener('click',function(){ applyTheme(b.getAttribute('data-theme')); }); });
    })();
  </script>
</body>
</html>
