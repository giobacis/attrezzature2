
ATTREZZATURE — PATCH LOGIN/REGISTRAZIONE (Fase 1)
===============================================

1) Copia i file nella tua copia di progetto, mantenendo la struttura:
   - includes/auth_config.php
   - includes/smtp_config.php (configura SMTP_USERNAME/PASSWORD)
   - includes/auth_check.php (sostituisci)
   - pages/login.php (sostituisci)
   - pages/register.php (nuovo)
   - pages/verify.php (nuovo)
   - pages/tools_seed_admin.php (nuovo)
   - sql/users.sql (importa nel DB)

2) Importa SQL:
   - Apri phpMyAdmin sul DB db_attrezzature
   - Importa sql/users.sql

3) Crea il super-admin:
   - Apri http://localhost/attrezzature/pages/tools_seed_admin.php
   - Inserisci la password per giovanni.bacis@cfpscuolafantoni.org
   - Dopo, puoi fare login come ADMIN.

4) SMTP:
   - Modifica includes/smtp_config.php con utente e app-password del dominio @cfpscuolafantoni.org
   - Esempio: SMTP_USERNAME='servizi.google@cfpscuolafantoni.org' ; SMTP_PASSWORD='app password'

5) Registrazione utenti FRONT-END:
   - Apri pages/register.php, gli utenti con email dei domini:
     @cfpscuolafantoni.org, @liceoartisticofantoni.com, @scuolafantoni.it
     riceveranno una mail con link di conferma.

6) Accesso BACK-END:
   - Pagine IT/ADMIN devono iniziare con:
     session_start(); $required_role='IT'; require __DIR__.'/../includes/auth_check.php';

Note: per sicurezza in produzione aggiungi CSRF sui form e imposta cookie di sessione sicuri.
