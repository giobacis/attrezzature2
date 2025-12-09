
<?php
// prenota.php — versione leggibile e compatibile (apici ASCII, nessun operatore ??, ogni istruzione su una riga)
require_once __DIR__ . '/../config.php';
// ensure session is active so CSRF token can be read/created
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
if (empty($_SESSION['csrf_token'])) {
  try { $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); } catch (Exception $e) { $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32)); }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Support both modal (id_prodotto) and standalone form (id_hardware)
  $id_hardware = 0;
  if (isset($_POST['id_hardware'])) { $id_hardware = (int)$_POST['id_hardware']; }
  if (!$id_hardware && isset($_POST['id_prodotto'])) { $id_hardware = (int)$_POST['id_prodotto']; }

  $nome        = isset($_POST['nome']) ? trim($_POST['nome']) : '';
  $cognome     = isset($_POST['cognome']) ? trim($_POST['cognome']) : '';
  $ruolo       = isset($_POST['ruolo']) ? trim($_POST['ruolo']) : '';
  $classe      = isset($_POST['classe']) ? trim($_POST['classe']) : '';
  $cellulare   = isset($_POST['cellulare']) ? trim($_POST['cellulare']) : '';
  $data_uscita = isset($_POST['data_uscita']) ? trim($_POST['data_uscita']) : '';
  // accept both names for rientro field coming from different forms
  $data_rp     = isset($_POST['data_rientro_prevista']) ? trim($_POST['data_rientro_prevista']) : (isset($_POST['data_prev_rientro']) ? trim($_POST['data_prev_rientro']) : '');
  $note        = isset($_POST['note']) ? trim($_POST['note']) : '';

  // detect AJAX requests (XHR) or JSON-accepting clients
  $isAjax = false;
  if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') { $isAjax = true; }
  if (!$isAjax && !empty($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) { $isAjax = true; }

  // CSRF validation: ensure token present and matches session
  $csrfOk = true;
  if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $csrfOk = false;
  }
  if (!$csrfOk) {
    if ($isAjax) {
      header('Content-Type: application/json');
      echo json_encode(['status' => 'error', 'message' => 'Token CSRF non valido']);
      exit;
    }
    http_response_code(403);
    echo '<h1>Accesso non autorizzato</h1><p>Token CSRF non valido.</p>';
    exit;
  }

$errors = array();
if ($id_hardware <= 0) { $errors[] = 'Attrezzatura non valida'; }
if ($nome === '')      { $errors[] = 'Nome obbligatorio'; }
if ($cognome === '')   { $errors[] = 'Cognome obbligatorio'; }
if ($ruolo === '')     { $errors[] = 'Ruolo obbligatorio'; }
if ($classe === '')    { $errors[] = 'Classe obbligatoria'; }
if ($cellulare === '') { $errors[] = 'Cellulare obbligatorio'; }
if ($data_uscita === '') { $errors[] = 'Data uscita obbligatoria'; }
if ($data_rp === '')     { $errors[] = 'Data rientro obbligatoria'; }

// Confronto date in formato YYYY-MM-DD (string compare sicuro per ISO8601)
if ($data_uscita !== '' && $data_rp !== '' && $data_rp < $data_uscita) {
  $errors[] = "Il rientro non puo essere precedente all'uscita"; // uso stringa tra doppi apici
}

  if (!empty($errors)) {
    if ($isAjax) {
      header('Content-Type: application/json');
      echo json_encode(['status' => 'error', 'message' => implode('; ', $errors)]);
      exit;
    }
    http_response_code(400);
    echo '<h1>Errore nella richiesta</h1><ul>';
    foreach ($errors as $msg) {
      echo '<li>' . htmlspecialchars($msg) . '</li>';
    }
    echo '</ul><p><a href="catalogo.php">Torna al catalogo</a></p>';
    exit;
  }

$sql = "INSERT INTO tbl_movmg (
          id_prodotto,
          data_uscita,
          data_prev_rientro,
          email,
          nome,
          cognome,
          ruolo,
          classe,
          cellulare,
          note,
          controllato,
          stato
        ) VALUES (
          :id, :du, :dr, :em, :no, :co, :ru, :cl, :ce, :nt, 0, 'in_attesa'
        )";

  try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
      ':id' => $id_hardware,
      ':du' => $data_uscita,
      ':dr' => $data_rp,
      ':em' => '',
      ':no' => $nome,
      ':co' => $cognome,
      ':ru' => $ruolo,
      ':cl' => $classe,
      ':ce' => $cellulare,
      ':nt' => $note
    ));

    if ($isAjax) {
      header('Content-Type: application/json');
      echo json_encode(['status' => 'ok']);
      exit;
    }

    header('Location: storico.php?msg=richiesta_inviata');
    exit;

  } catch (Exception $e) {
    if ($isAjax) {
      header('Content-Type: application/json');
      echo json_encode(['status' => 'error', 'message' => 'Errore server: ' . $e->getMessage()]);
      exit;
    }
    throw $e;
  }

} else {
  // If accessed via GET show a simple booking form when ?id=... is provided
  $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
  if ($id <= 0) {
    header('Location: catalogo.php');
    exit;
  }
  include __DIR__ . '/../includes/header.php';
  // no left user sidebar for standalone booking page — render an empty sidebar slot to keep app grid
  ?>
  <aside class="app-sidebar"></aside>
  <main class="app-content">
  <?php
  // fetch item info if available
  $stmt = $pdo->prepare('SELECT id, descrizione FROM tbl_hardware WHERE id = ?');
  $stmt->execute([$id]);
  $item = $stmt->fetch(PDO::FETCH_ASSOC);
  ?>
  <div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="mb-0"><i class="fas fa-calendar-plus"></i> Prenota: <?= htmlspecialchars($item['descrizione'] ?? 'Attrezzatura') ?></h2>
      <div>
        <a href="catalogo.php" class="btn btn-outline-secondary btn-sm me-2"><i class="fas fa-arrow-left me-1"></i> Torna al catalogo</a>
        <button id="toggleTheme" class="btn btn-outline-dark btn-sm">Tema Scuro/Chiaro</button>
      </div>
    </div>
    <form id="formPrenota" autocomplete="off">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
      <input type="hidden" name="id_prodotto" id="itemId" value="<?= (int)$id ?>">
      <div class="row g-2">
        <div class="col-md-6"><label class="form-label">Nome</label><input type="text" name="nome" class="form-control" required></div>
        <div class="col-md-6"><label class="form-label">Cognome</label><input type="text" name="cognome" class="form-control" required></div>
        <div class="col-md-4"><label class="form-label">Ruolo</label><select name="ruolo" class="form-select" required><option value="">Seleziona…</option><option value="Docente">Docente</option><option value="Studente">Studente</option></select></div>
        <div class="col-md-4"><label class="form-label">Classe</label><input type="text" name="classe" class="form-control" required></div>
        <div class="col-md-4"><label class="form-label">Cellulare</label><input type="tel" name="cellulare" class="form-control" required></div>
        <div class="col-md-6"><label class="form-label">Data Uscita</label><input type="date" name="data_uscita" class="form-control" required></div>
        <div class="col-md-6"><label class="form-label">Data Rientro prevista</label><input type="date" name="data_prev_rientro" class="form-control" required></div>
        <div class="col-12"><label class="form-label">Note</label><textarea name="note" class="form-control" rows="2"></textarea></div>
      </div>
      <div class="modal-footer p-0 mt-3 d-flex gap-2">
        <a class="btn btn-outline-secondary" href="catalogo.php">Annulla</a>
        <button type="button" class="btn btn-success ms-auto" id="btnInvia">
          <span class="spinner-border spinner-border-sm me-1 d-none" id="btnSpinner" role="status" aria-hidden="true"></span>
          <i class="fas fa-check"></i> Invia richiesta
        </button>
      </div>
    </form>
  </div>
  <?php
  include __DIR__ . '/../includes/footer.php';
  ?>
  <script src="assets/js/prenota.js"></script>
  <?php
  exit;
}
