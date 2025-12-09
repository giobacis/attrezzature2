<?php
session_start();
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
include __DIR__ . '/../config.php';
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$id_prodotto = (int)($_GET['id_prodotto'] ?? 0);
$data_uscita = $_GET['data_uscita'] ?? '';
$data_prev_rientro = $_GET['data_prev_rientro'] ?? '';
$email = $_GET['email'] ?? '';

?>
<div class='p-4'>
<div class='card shadow-sm p-4'>
<?php
if ($id_prodotto && $data_uscita && $data_prev_rientro && $email) {
    $stmt = $pdo->prepare("UPDATE tbl_movmg SET stato='approvato' WHERE id_prodotto=? AND data_uscita=? AND data_prev_rientro=?");
    $stmt->execute([$id_prodotto, $data_uscita, $data_prev_rientro]);

    $stmt2 = $pdo->prepare("UPDATE tbl_hardware SET id_stato_disp=5 WHERE id=?");
    $stmt2->execute([$id_prodotto]);

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tuoaccount@gmail.com';
        $mail->Password = 'PASSWORD_APP';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('tuoaccount@gmail.com', 'Gestione Attrezzature');
        $mail->addAddress($email);
        $mail->Subject = 'Prenotazione approvata';
        $mail->Body = "Gentile utente,\nLa tua prenotazione è stata approvata. Puoi ritirare l'attrezzatura il giorno: $data_uscita.";
        $mail->send();

        echo "<div class='alert alert-success'><i class='fas fa-check-circle'></i> Prenotazione approvata e email inviata.</div>";
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>Errore invio email.</div>";
    }
    echo "<a href='gestione_prenotazioni.php' class='btn btn-primary mt-3'><i class='fas fa-arrow-left'></i> Torna alla gestione</a>";
} else {
    echo "<div class='alert alert-warning'>Dati mancanti.</div>";
    echo "<a href='gestione_prenotazioni.php' class='btn btn-secondary mt-3'>Torna indietro</a>";
}
?>
</div>
</div>
