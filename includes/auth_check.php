
<?php
// includes/auth_config.php — helper ruoli + base path (compatibile PHP 8.2)

// Domini email ammessi alla registrazione front-end
$ALLOWED_DOMAINS = array(
  'cfpscuolafantoni.org',
  'liceoartisticofantoni.com',
  'scuolafantoni.it',
);

/**
 * Restituisce il base path web dell'app (es: /attrezzature2)
 * Senza usare backslash o str_replace: solo dirname() e rtrim()
 */
if (!function_exists('app_base_path')) {
  function app_base_path() {
    $script = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '/';
    $dir    = rtrim(dirname($script), '/');       // es: /attrezzature2 o /attrezzature2/pages
    // Se siamo in /.../pages, risali alla root app
    if (substr($dir, -6) === '/pages') {
      $dir = rtrim(substr($dir, 0, -6), '/');
    }
    return $dir === '' ? '/' : $dir;             // es: / (root) oppure /attrezzature2
  }
}

/**
 * Redirect post-login in base al ruolo
 * - ADMIN -> /pages/home.php
 * - IT    -> /pages/dashboard.php
 * - USER  -> /pages/catalogo.php
 */
if (!function_exists('redirect_after_login')) {
  function redirect_after_login($role) {
    $base = app_base_path();
    if ($role === 'ADMIN') {
      header('Location: ' . $base . '/pages/home.php');
    } elseif ($role === 'IT') {
      header('Location: ' . $base . '/pages/dashboard.php');
    } else {
      header('Location: ' . $base . '/pages/catalogo.php');
    }
    exit;
  }
}
