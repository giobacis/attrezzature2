
<?php
require __DIR__.'/config.php';
require __DIR__.'/../includes/auth_config.php';
session_start();

// compute base-to-root for links (same logic as in includes/user_badge.php)
$inPages = (strpos($_SERVER['SCRIPT_NAME'], '/pages/') !== false);
$baseToRoot = $inPages ? '../' : '';

// Require login for this page (redirects to pages/login.php if not authenticated)
require_once __DIR__ . '/../includes/require_login.php';

// --- Filtri per select ---
$cats = $pdo->query("SELECT id_categoria, descrizione FROM tbl_categoria ORDER BY descrizione")->fetchAll(PDO::FETCH_ASSOC);
$stats = $pdo->query("SELECT id_stato, descrizione FROM tbl_stati ORDER BY descrizione")->fetchAll(PDO::FETCH_ASSOC);
$pos   = $pdo->query("SELECT id_posizione, descrizione FROM tbl_posizioni ORDER BY descrizione")->fetchAll(PDO::FETCH_ASSOC);

// --- Parametri GET ---
$q         = trim($_GET['q'] ?? '');
$id_cat_f  = (int)($_GET['categoria'] ?? 0);
$id_stat_f = (int)($_GET['stato'] ?? 0);
$id_pos_f  = (int)($_GET['posizione'] ?? 0);
$solo_disp = (int)($_GET['solo_disp'] ?? 0);

// --- Costruzione WHERE + params (fix HY093: placeholder univoci) ---
$where  = [];
$params = [];

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

if ($id_cat_f > 0) {
    $where[] = 'h.id_categoria = :id_categoria';
    $params['id_categoria'] = $id_cat_f;
}
if ($id_stat_f > 0) {
    $where[] = 'h.id_stato_disp = :id_stato_disp';
    $params['id_stato_disp']  = $id_stat_f;
}
if ($id_pos_f > 0) {
    $where[] = 'h.id_posizione = :id_posizione';
    $params['id_posizione']   = $id_pos_f;
}
if ($solo_disp === 1) {
    $where[] = 'h.id_stato_disp = 9'; // disponibile
}

$sql = "SELECT h.id,h.descrizione,h.modello,h.codice_prodotto,h.seriale,
        p.descrizione AS posizione,
        c.descrizione AS categoria,
        s.id_stato AS id_stato,
        s.descrizione AS stato
        FROM tbl_hardware h
        JOIN tbl_categoria c ON c.id_categoria=h.id_categoria
        JOIN tbl_stati s     ON s.id_stato=h.id_stato_disp
        LEFT JOIN tbl_posizioni p ON p.id_posizione=h.id_posizione";

if ($where) { $sql .= ' WHERE '.implode(' AND ', $where); }
$sql .= ' ORDER BY h.id DESC';

$st = $pdo->prepare($sql);
$st->execute($params);
$items = $st->fetchAll(PDO::FETCH_ASSOC);

function statoBadgeClass($id){
  switch((int)$id){
    case 9: return 'success';
    case 3: return 'warning';
    case 5: return 'info';
    case 2: return 'danger';
    case 1: return 'secondary';
    case 4: return 'dark';
    case 6: return 'primary';
    case 7: return 'danger';
    case 8: return 'secondary';
    default: return 'secondary';
  }
}
?>
<!doctype html>
<html lang="it">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Prenota • Attrezzature</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/common.css">
  <link rel="stylesheet" href="assets/css/theme.css">
</head>
<body>

  <!-- HERO con gradiente e barra utente spostata dentro -->
  <section class="hero-cta text-white">
    <div class="container d-flex flex-wrap align-items-center gap-2">
      <div class="brand fw-bold">Prenota • Attrezzature</div>
      <div class="ms-auto w-100 w-lg-auto">
        <?php
          $userEmail = $_SESSION['email'] ?? null;
          $userRole  = $_SESSION['role'] ?? null;
        ?>
        <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['ADMIN','IT','SUPER_ADMIN'], true)): ?>
          <?php include __DIR__ . '/../includes/user_badge.php'; ?>
        <?php else: ?>
          <div class="hero-user d-flex flex-wrap align-items-center gap-2">
            <div class="me-auto">
              <span class="fw-semibold">Utente:</span>
              <span class="opacity-75"><?= htmlspecialchars($userEmail ?: '—') ?></span>
              <span class="ms-3 fw-semibold">Ruolo:</span>
              <span class="badge bg-light text-dark"><?= htmlspecialchars($userRole ?: '—') ?></span>
            </div>
            <div class="d-flex align-items-center gap-2">
              <div class="btn-group" role="group" aria-label="Tema">
                <button type="button" class="btn btn-sm btn-outline-light" data-theme="light" title="Tema chiaro">Chiaro</button>
                <button type="button" class="btn btn-sm btn-outline-light" data-theme="dark" title="Tema scuro">Scuro</button>
                <button type="button" class="btn btn-sm btn-outline-light" data-theme="auto" title="Segui sistema">Sistema</button>
              </div>
              <a class="btn btn-sm btn-light" href="<?php echo $baseToRoot; ?>logout.php">Logout</a>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <main class="app-main">
    <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['ADMIN','IT','SUPER_ADMIN'], true)): ?>
      <div class="container">
        <div class="row">
          <aside class="col-lg-3 mb-3">
            <div class="card sticky-top">
              <div class="card-body">
                <h6 class="mb-3">Area Admin</h6>
                <nav class="nav flex-column">
                  <a class="nav-link" href="dashboard.php"><i class="bi bi-house-door me-2"></i> Dashboard</a>
                  <a class="nav-link" href="attrezzature.php"><i class="bi bi-tools me-2"></i> Attrezzature</a>
                  <a class="nav-link" href="categorie.php"><i class="bi bi-tags me-2"></i> Categorie</a>
                  <a class="nav-link" href="fornitori.php"><i class="bi bi-briefcase me-2"></i> Fornitori</a>
                  <a class="nav-link" href="posizioni.php"><i class="bi bi-geo-alt me-2"></i> Posizioni</a>
                  <hr />
                  <a class="nav-link" href="gestione_prenotazioni.php"><i class="bi bi-ui-checks-grid me-2"></i> Prenotazioni</a>
                  <a class="nav-link" href="prenotazioni.php"><i class="bi bi-calendar3 me-2"></i> Calendario</a>
                </nav>
              </div>
            </div>
          </aside>
          <div class="col-lg-9">
    <?php else: ?>
      <div class="container">
    <?php endif; ?>
      <div class="mb-4">
        <h2 class="h5 mb-1">Prenota l'attrezzatura giusta, al momento giusto.</h2>
        <p class="text-muted mb-0">Filtra, cerca e invia la richiesta in un minuto. La disponibilità è aggiornata in tempo reale.</p>
      </div>

      <!-- FILTRI -->
      <div class="card mb-3">
        <div class="card-body">
          <form method="get" class="row g-3 align-items-end">
            <div class="col-sm-6 col-lg-4">
              <label class="form-label" for="q">Cerca</label>
              <input type="search" id="q" name="q" value="<?= htmlspecialchars($q) ?>" class="form-control" placeholder="Descrizione, modello, codice..." />
            </div>
            <div class="col-sm-6 col-lg-3">
              <label class="form-label" for="categoria">Categoria</label>
              <select id="categoria" name="categoria" class="form-control">
                <option value="0">Tutte</option>
                <?php foreach($cats as $c): ?>
                  <option value="<?= (int)$c['id_categoria'] ?>" <?= ($id_cat_f===(int)$c['id_categoria']?'selected':'') ?>>
                    <?= htmlspecialchars($c['descrizione']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-sm-6 col-lg-3">
              <label class="form-label" for="stato">Stato</label>
              <select id="stato" name="stato" class="form-control">
                <option value="0">Tutti</option>
                <?php foreach($stats as $s): ?>
                  <option value="<?= (int)$s['id_stato'] ?>" <?= ($id_stat_f===(int)$s['id_stato']?'selected':'') ?>>
                    <?= htmlspecialchars($s['descrizione']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-sm-6 col-lg-2">
              <label class="form-label" for="posizione">Posizione</label>
              <select id="posizione" name="posizione" class="form-control">
                <option value="0">Tutte</option>
                <?php foreach($pos as $p): ?>
                  <option value="<?= (int)$p['id_posizione'] ?>" <?= ($id_pos_f===(int)$p['id_posizione']?'selected':'') ?>>
                    <?= htmlspecialchars($p['descrizione']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-sm-6 col-lg-2">
              <div class="form-check mt-4">
                <input class="form-check-input" type="checkbox" value="1" id="solo_disp" name="solo_disp" <?= $solo_disp===1?'checked':''; ?> />
                <label class="form-check-label" for="solo_disp">Solo disponibili</label>
              </div>
            </div>
            <div class="col-12 d-flex gap-2">
              <button type="submit" class="btn btn-primary">Applica</button>
              <a href="catalogo.php" class="btn btn-outline">Reset</a>
            </div>
          </form>
        </div>
      </div>

      <!-- RISULTATI -->
      <div class="mb-2 d-flex align-items-center justify-content-between">
        <h3 class="h6 mb-0">Risultati</h3>
        <span class="text-muted"><?= count($items) ?> elementi</span>
      </div>

      <?php if (!$items): ?>
        <div class="alert alert-secondary">Nessuna attrezzatura trovata. Modifica i filtri o togli "Solo disponibili".</div>
      <?php else: ?>
        <div class="cards">
          <?php foreach($items as $it): ?>
            <div class="card h-100">
              <div class="card-body">
                <h5 class="card-title mb-1"><?= htmlspecialchars($it['descrizione']) ?></h5>
                <p class="card-text">Modello: <?= htmlspecialchars($it['modello'] ?: '—') ?></p>
                <p class="text-muted mb-2">Categoria: <?= htmlspecialchars($it['categoria'] ?: '—') ?> · Posizione: <?= htmlspecialchars($it['posizione'] ?: '—') ?></p>
                <p class="text-muted mb-0">Codice: <?= htmlspecialchars($it['codice_prodotto'] ?: '—') ?> · Seriale: <?= htmlspecialchars($it['seriale'] ?: '—') ?></p>
              </div>
              <div class="card-footer d-flex align-items-center justify-content-between">
                <span class="badge bg-<?= statoBadgeClass($it['id_stato']) ?>"><?= htmlspecialchars($it['stato']) ?></span>
                <?php if ((int)$it['id_stato'] === 9): ?>
                  <a class="btn btn-primary" href="prenota.php?id=<?= (int)$it['id'] ?>">Prenota</a>
                <?php else: ?>
                  <span class="text-muted">Non prenotabile nello stato attuale.</span>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

    </div>
  </main>

  <footer class="app-footer mt-4">
    <div class="container">© Scuola d’Arte Applicata Andrea Fantoni · Prenotazioni attrezzature</div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/theme-toggle.js"></script>
</body>
</html>
