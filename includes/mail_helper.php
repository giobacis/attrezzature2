
<?php
// includes/mail_helper.php — helper centralizzato per invio email
require_once __DIR__.'/smtp_config.php';
require_once __DIR__.'/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function send_app_mail($toEmail, $subject, $body, $toName = '') {
  $mail = new PHPMailer(true);
  try {
    if (!empty($GLOBALS['SMTP_ENABLED'])) {
      $mail->isSMTP();
      $mail->Host       = (string)$GLOBALS['SMTP_HOST'];
      $mail->Port       = (int)$GLOBALS['SMTP_PORT'];
      $mail->SMTPAuth   = true;
      $mail->Username   = (string)$GLOBALS['SMTP_USERNAME'];
      $mail->Password   = (string)$GLOBALS['SMTP_PASSWORD'];
      $mail->SMTPSecure = (string)$GLOBALS['SMTP_SECURE'];
      $mail->AuthType   = 'LOGIN';
    } else {
      $mail->isMail();
    }

    $fromEmail = !empty($GLOBALS['SMTP_FROM_EMAIL']) ? $GLOBALS['SMTP_FROM_EMAIL'] : $GLOBALS['SMTP_USERNAME'];
    $fromName  = !empty($GLOBALS['SMTP_FROM_NAME'])  ? $GLOBALS['SMTP_FROM_NAME']  : 'Prenotazioni Attrezzature';

    $mail->setFrom($fromEmail, $fromName);
    $mail->Sender = $fromEmail; // envelope
    if (!empty($fromEmail)) {
      $mail->addReplyTo($fromEmail, $fromName);
    }

    $mail->addAddress($toEmail, $toName ?: $toEmail);
    $mail->Subject = $subject;
    $mail->Body    = $body;

    return $mail->send();
  } catch (Exception $e) {
    // In produzione: loggare su file
    return false;
  }
}
?>
