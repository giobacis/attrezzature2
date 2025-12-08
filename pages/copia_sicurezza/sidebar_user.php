<?php
$current = basename($_SERVER['PHP_SELF'] ?? '');
function isActive($file, $current) { return $file === $current ? 'active' : ''; }
?>
<div class="card shadow-sm">
  <div class="card-header d-flex align-items-center gap-2">
    <i class="bi bi-person-vcard"></i> <span>Area Utente</span>
  </div>
  <div class="list-group list-group-flush">
    <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?php echo isActive('catalogo.php', $current); ?>" href="catalogo.php"><span>Catalogo</span><i class="bi bi-grid-3x3-gap"></i></a>
    <a class="list-group-item list-group-item-action d-flex justify-content_between align-items-center <?php echo isActive('storico.php', $current); ?>" href="storico.php"><span>Storico Prenotazioni</span><i class="bi bi-clock-history"></i></a>
    <a class="list-group-item list-group-item-action d-flex justify_content_between align-items-center" href="../logout.php"><span>Logout</span><i class="bi bi-box-arrow-right"></i></a>
  </div>
</div>