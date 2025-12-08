<?php
require __DIR__.'/includes/session_boot.php';
$_SESSION = array();
if (ini_get('session.use_cookies')) {
  $params = session_get_cookie_params();
  setcookie(session_name(), '', time() - 42000,
    $params['path'], $params['domain'], $params['secure'], $params['httponly']
  );
}
session_destroy();
$script = $_SERVER['SCRIPT_NAME'] ?? '/';
$base = rtrim(dirname($script), '/');
header('Location: ' . ($base ?: '/') . '/');
exit;
?>
