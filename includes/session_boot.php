<?php
if (session_status() === PHP_SESSION_NONE) {
  ini_set('session.cookie_httponly', '1');
  ini_set('session.cookie_samesite', 'Lax');
  // ini_set('session.cookie_secure', '1'); // abilita su HTTPS
  session_start();
}
?>
