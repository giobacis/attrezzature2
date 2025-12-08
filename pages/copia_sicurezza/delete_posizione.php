
<?php
session_start(); $required_role='IT';
include __DIR__ . '/../config.php';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
$id=(int)($_GET['id']??0); if($id<=0){ header('Location: posizioni.php'); exit; }
try{ $stmt=$pdo->prepare('DELETE FROM tbl_posizioni WHERE id_posizione=?'); $stmt->execute([$id]); header('Location: posizioni.php'); exit; }
catch(PDOException $e){ $msg='Impossibile eliminare: posizione in uso da una o più attrezzature.'; $err=htmlspecialchars($e->getMessage()); }
?>
<div class="container-fluid py-3">
  <div class="alert alert-warning">
    <h5 class="alert-heading">Eliminazione non completata</h5>
    <p><?= $msg ?? 'Errore.' ?></p>
    <p class="small text-muted">Dettagli: <?= $err ?? '' ?></p>
    <a class="btn btn-outline-secondary" href="posizioni.php"><i class="bi bi-arrow-left"></i> Torna alle Posizioni</a>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
