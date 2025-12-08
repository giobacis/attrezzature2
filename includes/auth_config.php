<?php
$ALLOWED_DOMAINS = array('cfpscuolafantoni.org','liceoartisticofantoni.com','scuolafantoni.it');
if (!function_exists('app_base_path')) {
  function app_base_path() {
    $script = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '/';
    $dir = rtrim(dirname($script), '/');
    if (substr($dir, -6) === '/pages') { $dir = rtrim(substr($dir, 0, -6), '/'); }
    return $dir === '' ? '/' : $dir;
  }
}
if (!function_exists('redirect_after_login')) {
  function redirect_after_login($role) {
    $base = app_base_path();
    if ($role === 'ADMIN') header('Location: ' . $base . '/pages/home.php');
    elseif ($role === 'IT') header('Location: ' . $base . '/pages/dashboard.php');
    else header('Location: ' . $base . '/pages/catalogo.php');
    exit;
  }
}
?>
