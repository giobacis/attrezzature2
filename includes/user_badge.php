<?php
require_once __DIR__.'/session_boot.php';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : null;
$role  = isset($_SESSION['role'])  ? $_SESSION['role']  : null;
$inPages = (strpos($_SERVER['SCRIPT_NAME'],'/pages/') !== false);
$baseToRoot = $inPages ? '../' : '';
?>
<div class="d-flex align-items-center gap-2 small">
  <?php if ($email): ?>
    <span class="badge text-bg-primary">Utente: <?php echo htmlspecialchars($email); ?></span>
    <span class="badge text-bg-secondary">Ruolo: <?php echo htmlspecialchars($role ?: 'USER'); ?></span>
    <a class="btn btn-sm btn-outline-danger" href="<?php echo $baseToRoot; ?>logout.php">Logout</a>
  <?php else: ?>
    <a class="btn btn-sm btn-primary" href="<?php echo $baseToRoot; ?>pages/login.php">Accedi</a>
    <a class="btn btn-sm btn-outline-secondary" href="<?php echo $baseToRoot; ?>pages/register.php">Registrati</a>
  <?php endif; ?>
</div>
