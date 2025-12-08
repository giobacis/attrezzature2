<?php
require_once __DIR__.'/session_boot.php';
if (!isset($_SESSION['email']) || !isset($_SESSION['role'])) {
  $base = isset($_SERVER['SCRIPT_NAME']) ? rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') : '';
  if (substr($base, -6) === '/pages') { $base = rtrim(substr($base, 0, -6), '/'); }
  header('Location: ' . ($base ?: '/') . '/pages/login.php');
  exit;
}
if (isset($required_role) && $_SESSION['role'] !== $required_role && $_SESSION['role'] !== 'ADMIN') {
  require_once __DIR__.'/auth_config.php';
  redirect_after_login($_SESSION['role']);
}
?>
