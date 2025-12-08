
<?php
// debug_prenota.php — stampa grezza dell'input per verificare il POST
header('Content-Type: text/plain; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo "Metodo: ".$_SERVER['REQUEST_METHOD']."

";
  echo "Usa test_form.html per inviare POST a prenota.php";
  exit;
}
// Mostra superglobali
echo "$_POST:
"; var_export($_POST); echo "

";
// Mostra valori attesi
$keys = ['id_hardware','nome','cognome','ruolo','classe','cellulare','data_uscita','data_rientro_prevista','note'];
foreach ($keys as $k) {
  echo $k.' = '.(isset($_POST[$k])?$_POST[$k]:'<manca>')."
";
}
