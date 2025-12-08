
<?php
// includes/smtp_config.php — compila con i tuoi dati (Gmail/Workspace)
$SMTP_ENABLED    = true;            // true per usare SMTP
$SMTP_HOST       = 'smtp.gmail.com';
$SMTP_PORT       = 587;             // 465 per SSL
$SMTP_SECURE     = 'tls';           // 'tls' o 'ssl'
$SMTP_USERNAME   = 'servizi.google@scuolafantoni.it';
$SMTP_PASSWORD   = 'peqcujxpbvpvwwcl';
$SMTP_FROM_EMAIL = $SMTP_USERNAME;  // From coerente con utente autenticato
$SMTP_FROM_NAME  = 'Prenotazioni Attrezzature';
?>
