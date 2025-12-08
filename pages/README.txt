Pacchetto completo (REdesign + mobile + prenota fix)

1) Copia i file in C:\xampp\htdocs\attrezzature\pages\
2) Apri http://localhost/attrezzature/pages/info.php e verifica PHP attivo.
3) Modifica config.php se usi credenziali diverse da root senza password.
4) Apri http://localhost/attrezzature/pages/catalogo.php
   - Desktop/Tablet: Prenota apre il modal (ID popolato automaticamente).
   - Mobile: Prenota reindirizza a prenota_mobile.php?id=### (ID nell'hidden del form).
5) Invia la richiesta: verrai reindirizzato su storico.php?msg=richiesta_inviata.

Note:
- Il modal e scrollabile e fullscreen sotto 576px.
- CSS usa 100dvh e safe-area per evitare bottoni tagliati su iOS.
- prenota.php e la versione minimal con apici ASCII per evitare parse error.
