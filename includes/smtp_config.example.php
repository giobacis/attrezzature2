
<?php
// includes/smtp_config.php (esempio) — copia/rename in smtp_config.php e imposta i valori
$SMTP_ENABLED   = true;            // true per usare SMTP
$SMTP_HOST      = 'smtp.gmail.com';
$SMTP_PORT      = 587;             // 465 per SSL
$SMTP_SECURE    = 'tls';           // 'tls' o 'ssl'
$SMTP_USERNAME  = 'servizi.google@cfpscuolafantoni.org';
$SMTP_PASSWORD  = 'APP_PASSWORD_GOOGLE';         // App Password (16 chars)
$SMTP_FROM_EMAIL= 'no-reply@cfpscuolafantoni.org';
$SMTP_FROM_NAME = 'Prenotazioni Attrezzature';
?>
