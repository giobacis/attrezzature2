
<?php
// index.php — Home senza sidebar, link corretti `pages/...` e CSS esterno
?>
<!doctype html>
<html lang="it">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestione Attrezzature Audio-Video e Informatiche</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="pages/assets/css/common.css">
  </head>
  <body>
    <header class="app-header">
      <div class="container d-flex align-items-center">
        <a class="brand" href="index.php">Gestione Attrezzature Audio-Video e Informatiche</a>
        <div class="app-actions ms-auto">
          <a class="btn btn-outline" href="pages/login.php">Login</a>
        </div>
      </div>
    </header>

    <main class="app-main">
      <div class="container">
        <div class="mb-4">
          <h1 class="h4 mb-2">Benvenuto</h1>
          <p class="text-muted">Seleziona una sezione oppure usa i pulsanti rapidi.</p>
        </div>

        <div class="cards">
          <div class="card h-100">
            <div class="card-body">
              <h5 class="card-title">Catalogo Attrezzature</h5>
              <p class="card-text">Sfoglia le attrezzature disponibili e filtra per categoria.</p>
            </div>
            <div class="card-footer">
              <a class="btn btn-primary w-100" href="pages/catalogo.php">Apri catalogo</a>
            </div>
          </div>

          <div class="card h-100">
            <div class="card-body">
              <h5 class="card-title">Area riservata</h5>
              <p class="card-text">Accesso all'area riservata. Permette di gestiere o consultare le Attrezzature e le schede tecniche</p>
            </div>
            <div class="card-footer">
              <a class="btn btn-primary w-100" href="pages/dashboard.php">Accedi alla dasboard</a>
            </div>
          </div>
          <!-- 
          <div class="card h-100">
            <div class="card-body">
              <h5 class="card-title">Attrezzature</h5>
              <p class="card-text">Aggiungi, modifica e organizza le attrezzature.</p>
            </div>
            <div class="card-footer">
              <a class="btn btn-primary w-100" href="pages/attrezzature.php">Gestisci attrezzature</a>
            </div>
          </div>

          <div class="card h-100">
            <div class="card-body">
              <h5 class="card-title">Storico</h5>
              <p class="card-text">Consulta lo storico dei movimenti e degli interventi.</p>
            </div>
            <div class="card-footer">
              <a class="btn btn-primary w-100" href="pages/storico.php">Apri storico</a>
            </div>
          </div> -->
        </div>
      </div>
    </main>

    <footer class="app-footer mt-4">
      <div class="container">© Scuola d’Arte Applicata Andrea Fantoni</div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  </body>
</html>
