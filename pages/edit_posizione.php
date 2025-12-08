
<?php
session_start(); $required_role='IT';
include __DIR__ . '/../config.php';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
$id=(int)($_GET['id']??0); if($id<=0){ header('Location: posizioni.php'); exit; }
$stmt=$pdo->prepare('SELECT * FROM tbl_posizioni WHERE id_posizione=?'); $stmt->execute([$id]); $item=$stmt->fetch(PDO::FETCH_ASSOC); if(!$item){ header('Location: posizioni.php'); exit; }
$errors=[]; if($_SERVER['REQUEST_METHOD']==='POST'){
  $item['descrizione']=trim($_POST['descrizione']??'');
  $item['note']=trim($_POST['note']??'');
  if($item['descrizione']==='') $errors[]='La descrizione è obbligatoria.';
  if(!$errors){ try{ $stmt=$pdo->prepare('UPDATE tbl_posizioni SET descrizione=:d,note=:n WHERE id_posizione=:id'); $stmt->execute([':d'=>$item['descrizione'],':n'=>$item['note']?:null,':id'=>$id]); header('Location: posizioni.php'); exit; } catch(PDOException $e){ if($e->getCode()==='23000') $errors[]='Esiste già una posizione con questa descrizione.'; else $errors[]='Errore database: '.$e->getMessage(); } }
}
?>
<div class="container-fluid py-3">
  <h4 class="mb-3">Modifica Posizione</h4>
  <?php if($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul></div><?php endif; ?>
  <form method="post" class="card p-3">
    <div class="mb-3"><label class="form-label">Descrizione *</label><input class="form-control" name="descrizione" required value="<?= htmlspecialchars($item['descrizione'] ?? '') ?>"></div>
    <div class="mb-3"><label class="form-label">Note</label><textarea class="form-control" rows="3" name="note"><?= htmlspecialchars($item['note'] ?? '') ?></textarea></div>
    <div class="d-flex gap-2"><button class="btn btn-primary" type="submit"><i class="bi bi-check2-circle"></i> Salva</button><a class="btn btn-outline-secondary" href="posizioni.php"><i class="bi bi-arrow-left"></i> Indietro</a></div>
  </form>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
