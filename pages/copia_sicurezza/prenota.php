
<?php
// prenota.php — versione leggibile e compatibile (apici ASCII, nessun operatore ??, ogni istruzione su una riga)
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo 'Metodo non permesso';
  exit;
}

$id_hardware = isset($_POST['id_hardware']) ? (int)$_POST['id_hardware'] : 0;
$nome        = isset($_POST['nome']) ? trim($_POST['nome']) : '';
$cognome     = isset($_POST['cognome']) ? trim($_POST['cognome']) : '';
$ruolo       = isset($_POST['ruolo']) ? trim($_POST['ruolo']) : '';
$classe      = isset($_POST['classe']) ? trim($_POST['classe']) : '';
$cellulare   = isset($_POST['cellulare']) ? trim($_POST['cellulare']) : '';
$data_uscita = isset($_POST['data_uscita']) ? trim($_POST['data_uscita']) : '';
$data_rp     = isset($_POST['data_rientro_prevista']) ? trim($_POST['data_rientro_prevista']) : '';
$note        = isset($_POST['note']) ? trim($_POST['note']) : '';

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

header('Location: storico.php?msg=richiesta_inviata');
exit;
