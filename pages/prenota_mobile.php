<?php
require_once __DIR__ . '/config.php';
$id=(int)($_GET['id']??0); $label=trim($_GET['label']??'Attrezzatura');
?>
<!doctype html>
<html lang="it" data-bs-theme="light">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>Prenota • <?php echo htmlspecialchars($label); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/catalogo-redesign.css" rel="stylesheet">
</head>
<body>
  <section class="hero-cta text-white">
    <div class="container py-4 d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center gap-2">
        <a class="btn btn-light btn-sm" href="catalogo.php"><i class="bi bi-arrow-left"></i></a>
        <h1 class="h5 mb-0">Prenota</h1>
      </div>
      <span class="small"><?php echo htmlspecialchars($label); ?></span>
    </div>
  </section>
  <main class="container py-3">
    <form method="post" action="prenota.php" class="vstack gap-3">
      <input type="hidden" name="id_hardware" value="<?php echo (int)$id; ?>">
      <div class="row g-3">
        <div class="col-12"><label class="form-label">Nome</label><input type="text" name="nome" class="form-control" required autocomplete="given-name"></div>
        <div class="col-12"><label class="form-label">Cognome</label><input type="text" name="cognome" class="form-control" required autocomplete="family-name"></div>
        <div class="col-12"><label class="form-label">Ruolo</label><select name="ruolo" class="form-select" required><option value="">Seleziona…</option><option>Docente</option><option>Studente</option></select></div>
        <div class="col-12"><label class="form-label">Classe</label><input type="text" name="classe" class="form-control" placeholder="Es. 4A Grafica" required></div>
        <div class="col-12"><label class="form-label">Cellulare</label><input type="tel" name="cellulare" class="form-control" placeholder="Es. +39…" required autocomplete="tel"></div>
        <div class="col-12"><label class="form-label">Data Uscita</label><input type="date" name="data_uscita" class="form-control" id="dataUscita" required></div>
        <div class="col-12"><label class="form-label">Data Rientro prevista</label><input type="date" name="data_rientro_prevista" class="form-control" id="dataRientro" required></div>
        <div class="col-12"><label class="form-label">Note</label><textarea name="note" class="form-control" rows="4" placeholder="Dettagli utili (destinazione, utilizzo, orari)…"></textarea></div>
      </div>
      <div class="mobile-cta border-top mt-2">
        <div class="container px-0 py-2 d-flex gap-2">
          <a class="btn btn-outline-secondary flex-grow-1" href="catalogo.php">Annulla</a>
          <button type="submit" class="btn btn-primary flex-grow-1">Invia richiesta</button>
        </div>
      </div>
    </form>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    var today=new Date().toISOString().slice(0,10); var dU=document.getElementById('dataUscita'); var dR=document.getElementById('dataRientro'); if(dU)dU.min=today; if(dR)dR.min=today; function check(){ if(dU&&dR&&dU.value&&dR.value&&dR.value<dU.value){ dR.setCustomValidity("Il rientro non puo essere precedente all'uscita."); } else if(dR){ dR.setCustomValidity(''); } } if(dU)dU.addEventListener('change',check); if(dR)dR.addEventListener('change',check);
  </script>
</body>
</html>