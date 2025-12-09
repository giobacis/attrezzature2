<?php
require_once __DIR__ . '/config.php';
include __DIR__.'/../includes/user_badge.php';

$cats  = $pdo->query("SELECT id_categoria, descrizione FROM tbl_categoria ORDER BY descrizione")->fetchAll(PDO::FETCH_ASSOC);
$stats = $pdo->query("SELECT id_stato, descrizione FROM tbl_stati ORDER BY descrizione")->fetchAll(PDO::FETCH_ASSOC);
$pos   = $pdo->query("SELECT id_posizione, descrizione FROM tbl_posizioni ORDER BY descrizione")->fetchAll(PDO::FETCH_ASSOC);
$q = trim($_GET['q'] ?? '');
$id_cat_f  = (int)($_GET['categoria'] ?? 0);
$id_stat_f = (int)($_GET['stato'] ?? 0);
$id_pos_f  = (int)($_GET['posizione'] ?? 0);
$solo_disp = (int)($_GET['solo_disp'] ?? 0);
$where = [];$params = [];
// if ($q !== '') { $where[] = "(h.descrizione LIKE :q OR h.modello LIKE :q OR p.descrizione LIKE :q OR h.codice_prodotto LIKE :q OR h.seriale LIKE :q OR c.descrizione LIKE :q OR s.descrizione LIKE :q)"; $params['q'] = '%'.$q.'%'; }

if ($q !== '') {
    $where[] = '('
        . 'h.descrizione      LIKE :q1 OR '
        . 'h.modello          LIKE :q2 OR '
        . 'p.descrizione      LIKE :q3 OR '
        . 'h.codice_prodotto  LIKE :q4 OR '
        . 'h.seriale          LIKE :q5 OR '
        . 'c.descrizione      LIKE :q6 OR '
        . 's.descrizione      LIKE :q7'
        . ')';

    $like = '%'.$q.'%';
    $params['q1'] = $like;
    $params['q2'] = $like;
    $params['q3'] = $like;
    $params['q4'] = $like;
    $params['q5'] = $like;
    $params['q6'] = $like;
    $params['q7'] = $like;
}

if ($id_cat_f > 0) { $where[] = 'h.id_categoria = :id_categoria';   $params['id_categoria']   = $id_cat_f; }
if ($id_stat_f > 0) { $where[] = 'h.id_stato_disp = :id_stato_disp'; $params['id_stato_disp'] = $id_stat_f; }
if ($id_pos_f > 0) { $where[] = 'h.id_posizione   = :id_posizione';   $params['id_posizione']  = $id_pos_f; }
if ($solo_disp === 1) { $where[] = 'h.id_stato_disp = 9'; }
$sql = "SELECT h.id,h.descrizione,h.modello,h.codice_prodotto,h.seriale,
               p.descrizione AS posizione,
               c.descrizione AS categoria,
               s.id_stato AS id_stato,
               s.descrizione AS stato
        FROM tbl_hardware h
        JOIN tbl_categoria c ON c.id_categoria=h.id_categoria
        JOIN tbl_stati s ON s.id_stato=h.id_stato_disp
        LEFT JOIN tbl_posizioni p ON p.id_posizione=h.id_posizione";
if ($where) { $sql .= ' WHERE '.implode(' AND ', $where); }
$sql .= ' ORDER BY h.id DESC';
$st = $pdo->prepare($sql); $st->execute($params); $items = $st->fetchAll(PDO::FETCH_ASSOC);
function statoBadgeClass($id){ switch((int)$id){ case 9:return 'success';case 3:return 'warning';case 5:return 'info';case 2:return 'danger';case 1:return 'secondary';case 4:return 'dark';case 6:return 'primary';case 7:return 'danger';case 8:return 'secondary';default:return 'secondary'; } }
?>
<!doctype html>
<html lang="it" data-bs-theme="light">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Prenota • Attrezzature</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/catalogo-redesign.css" rel="stylesheet">
  
  
</head>
<body>
  

<section class="hero-cta text-white">
  
<?php
// Se vuoi che il catalogo richieda login:
// $required_role = null; // oppure 'USER','IT','ADMIN' per limitare
require __DIR__.'/../includes/require_login.php';
?>

  <div class="container py-5">
    <div class="row align-items-center">
      <div class="col-12 col-lg-8">
        <h1 class="display-6 fw-bold mb-2">Prenota l'attrezzatura giusta, al momento giusto.</h1>
        <p class="lead mb-3">Filtra, cerca e invia la richiesta in un minuto. La disponibilita e aggiornata in tempo reale.</p>
        <div class="d-flex gap-2 flex-wrap">
          <a class="btn btn-light btn-lg d-lg-none" data-bs-toggle="collapse" href="#filtersCollapse" role="button" aria-expanded="false" aria-controls="filtersCollapse"><i class="bi bi-sliders"></i> Mostra filtri</a>
          <button class="btn btn-outline-light btn-lg dropdown-toggle" type="button" data-bs-toggle="dropdown">Tema</button>
          <ul class="dropdown-menu">
            <li><button class="dropdown-item" data-theme="light">Chiaro</button></li>
            <li><button class="dropdown-item" data-theme="dark">Scuro</button></li>
            <li><button class="dropdown-item" data-theme="auto">Sistema</button></li>
          </ul>
        </div>
      </div>
      <div class="col-lg-4 d-none d-lg-block text-end"><i class="bi bi-tools" style="font-size:5rem;opacity:.9"></i></div>
    </div>
  </div>
</section>

<div class="mobile-cta d-lg-none">
  <div class="container px-3 py-2 d-flex justify-content-between align-items-center">
    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#filtersCollapse"><i class="bi bi-sliders"></i> Filtri</button>
    <form method="get" action="catalogo.php" class="d-flex align-items-center gap-2">
      <input type="hidden" name="q" value="<?php echo htmlspecialchars($q); ?>">
      <input type="hidden" name="categoria" value="<?php echo (int)$id_cat_f; ?>">
      <input type="hidden" name="stato" value="<?php echo (int)$id_stat_f; ?>">
      <input type="hidden" name="posizione" value="<?php echo (int)$id_pos_f; ?>">
      <div class="form-check form-switch m-0">
        <input class="form-check-input" type="checkbox" id="soloDispSwitch" name="solo_disp" value="1" <?php echo $solo_disp===1?'checked':''; ?> onchange="this.form.submit()">
        <label class="form-check-label" for="soloDispSwitch">Solo disp.</label>
      </div>
    </form>
  </div>
</div>

<div class="container-fluid my-4">
  <div class="row g-4">
    <aside class="col-12 col-lg-3">
      <div class="card shadow-sm sticky-top d-none d-lg-block" style="top:1rem;">
        <div class="card-header d-flex align-items-center gap-2"><i class="bi bi-funnel"></i><span>Filtri</span></div>
        <div class="card-body">
          <form method="get" action="catalogo.php" class="vstack gap-3">
            <div class="input-group input-group-lg">
              <span class="input-group-text"><i class="bi bi-search"></i></span>
              <input class="form-control" type="search" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Cerca nome, modello o posizione">
            </div>
            <div>
              <label class="form-label">Categoria</label>
              <select name="categoria" class="form-select">
                <option value="0">Tutte</option>
                <?php foreach($cats as $c): ?><option value="<?php echo (int)$c['id_categoria']; ?>" <?php echo $id_cat_f==(int)$c['id_categoria']?'selected':''; ?>><?php echo htmlspecialchars($c['descrizione']); ?></option><?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="form-label">Stato</label>
              <select name="stato" class="form-select">
                <option value="0">Tutti</option>
                <?php foreach($stats as $s): ?><option value="<?php echo (int)$s['id_stato']; ?>" <?php echo $id_stat_f==(int)$s['id_stato']?'selected':''; ?>><?php echo htmlspecialchars($s['descrizione']); ?></option><?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="form-label">Posizione</label>
              <select name="posizione" class="form-select">
                <option value="0">Tutte</option>
                <?php foreach($pos as $p): ?><option value="<?php echo (int)$p['id_posizione']; ?>" <?php echo $id_pos_f==(int)$p['id_posizione']?'selected':''; ?>><?php echo htmlspecialchars($p['descrizione']); ?></option><?php endforeach; ?>
              </select>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="1" id="soloDisp" name="solo_disp" <?php echo $solo_disp===1?'checked':''; ?>>
              <label class="form-check-label" for="soloDisp">Solo disponibili</label>
            </div>
            <div class="d-grid gap-2">
              <button class="btn btn-primary" type="submit"><i class="bi bi-filter-circle"></i> Applica</button>
              <a class="btn btn-outline-secondary" href="catalogo.php"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
            </div>
          </form>
        </div>
      </div>

      <div class="card shadow-sm d-lg-none collapse" id="filtersCollapse">
        <div class="card-header d-flex align-items-center gap-2"><i class="bi bi-funnel"></i><span>Filtri</span></div>
        <div class="card-body">
          <form method="get" action="catalogo.php" class="vstack gap-3">
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-search"></i></span>
              <input class="form-control" type="search" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Cerca...">
            </div>
            <div>
              <label class="form-label">Categoria</label>
              <select name="categoria" class="form-select">
                <option value="0">Tutte</option>
                <?php foreach($cats as $c): ?><option value="<?php echo (int)$c['id_categoria']; ?>" <?php echo $id_cat_f==(int)$c['id_categoria']?'selected':''; ?>><?php echo htmlspecialchars($c['descrizione']); ?></option><?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="form-label">Stato</label>
              <select name="stato" class="form-select">
                <option value="0">Tutti</option>
                <?php foreach($stats as $s): ?><option value="<?php echo (int)$s['id_stato']; ?>" <?php echo $id_stat_f==(int)$s['id_stato']?'selected':''; ?>><?php echo htmlspecialchars($s['descrizione']); ?></option><?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="form-label">Posizione</label>
              <select name="posizione" class="form-select">
                <option value="0">Tutte</option>
                <?php foreach($pos as $p): ?><option value="<?php echo (int)$p['id_posizione']; ?>" <?php echo $id_pos_f==(int)$p['id_posizione']?'selected':''; ?>><?php echo htmlspecialchars($p['descrizione']); ?></option><?php endforeach; ?>
              </select>
            </div>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="soloDisp_m" name="solo_disp" value="1" <?php echo $solo_disp===1?'checked':''; ?>>
              <label class="form-check-label" for="soloDisp_m">Solo disponibili</label>
            </div>
            <div class="d-grid gap-2">
              <button class="btn btn-primary" type="submit"><i class="bi bi-filter-circle"></i> Applica</button>
              <a class="btn btn-outline-secondary" href="catalogo.php"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
            </div>
          </form>
        </div>
      </div>
    </aside>

    <main class="col-12 col-lg-9">
      <div class="d-flex align-items-center justify-content_between mb-2">
        <h2 class="h5 mb-0">Risultati</h2>
        <span class="text-secondary"><?php echo count($items); ?> elementi</span>
      </div>
      <?php if(!$items): ?>
        <div class="alert alert-warning">Nessuna attrezzatura trovata. Modifica i filtri o togli "Solo disponibili".</div>
      <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
          <?php foreach($items as $it): ?>
            <div class="col">
              <div class="card card-elevated h-100">
                <div class="card-body d-flex flex-column">
                  <div class="d-flex align-items-start justify-content-between mb-1">
                    <h3 class="h6 mb-0"><?php echo htmlspecialchars($it['descrizione']); ?></h3>
                    <span class="badge rounded-pill bg-<?php echo statoBadgeClass($it['id_stato']); ?>"><?php echo htmlspecialchars($it['stato']); ?></span>
                  </div>
                  <div class="small text-secondary mb-2">Modello: <span class="text-body"><?php echo htmlspecialchars($it['modello']??'-'); ?></span></div>
                  <ul class="list-unstyled small mb-3">
                    <li><i class="bi bi-collection me-1"></i> Categoria: <span class="text-body"><?php echo htmlspecialchars($it['categoria']); ?></span></li>
                    <li><i class="bi bi-geo-alt me-1"></i> Posizione: <span class="text-body"><?php echo htmlspecialchars($it['posizione']??'—'); ?></span></li>
                    <li><i class="bi bi-hash me-1"></i> Codice: <span class="text-body"><?php echo htmlspecialchars($it['codice_prodotto']??'—'); ?></span></li>
                    <li><i class="bi bi-upc-scan me-1"></i> Seriale: <span class="text-body"><?php echo htmlspecialchars($it['seriale']??'—'); ?></span></li>
                  </ul>
                  <div class="mt-auto d-grid">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#prenotaModal" data-item-id="<?php echo (int)$it['id']; ?>" data-item-label="<?php echo htmlspecialchars($it['descrizione']); ?>" <?php echo ((int)$it['id_stato'])!==9?'disabled':''; ?>>
                      <i class="bi bi-calendar2-plus"></i> Prenota
                    </button>
                    <?php if(((int)$it['id_stato'])!==9): ?><small class="text-secondary mt-2">Non prenotabile nello stato attuale.</small><?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </main>
  </div>
</div>

<div class="modal fade" id="prenotaModal" tabindex="-1" aria_hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable modal-fullscreen-sm-down">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-calendar2-plus me-1"></i> Prenota: <span id="modalItemLabel">Attrezzatura</span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
      </div>
      <form method="post" action="prenota.php" id="formPrenota">
        <div class="modal-body">
          <input type="hidden" name="id_hardware" id="modalItemId">
          <div class="row g-3">
            <div class="col-sm-6"><label class="form-label">Nome</label><input type="text" name="nome" class="form-control" required></div>
            <div class="col-sm-6"><label class="form-label">Cognome</label><input type="text" name="cognome" class="form-control" required></div>
            <div class="col-sm-6"><label class="form-label">Ruolo</label>
              <select name="ruolo" class="form-select" required>
                <option value="">Seleziona…</option>
                <option>Docente</option>
                <option>Studente</option>
              </select>
            </div>
            <div class="col-sm-6"><label class="form-label">Classe</label><input type="text" name="classe" class="form-control" placeholder="Es. 4A Grafica" required></div>
            <div class="col-sm-6"><label class="form-label">Cellulare</label><input type="tel" name="cellulare" class="form-control" placeholder="Es. +39…" required></div>
            <div class="col-sm-6"><label class="form-label">Data Uscita</label><input type="date" name="data_uscita" class="form-control" id="dataUscita" required></div>
            <div class="col-sm-6"><label class="form-label">Data Rientro prevista</label><input type="date" name="data_rientro_prevista" class="form-control" id="dataRientro" required></div>
            <div class="col-12"><label class="form-label">Note</label><textarea name="note" class="form-control" rows="3" placeholder="Dettagli utili (destinazione, utilizzo, orari)…"></textarea></div>
          </div>
        </div>
        <div class="modal-footer d-flex justify-content-between">
          <small class="text-secondary">La richiesta verra inviata per approvazione.</small>
          <div>
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Chiudi</button>
            <button type="submit" class="btn btn-primary">Invia richiesta</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<footer class="border-top py-4 mt-5 text-center text-secondary small">Scuola d'Arte Applicata A. Fantoni · Prenotazioni attrezzature</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/catalogo-redesign.js"></script>
</body>
</html>