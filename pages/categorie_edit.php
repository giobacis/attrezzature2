<?php
session_start(); $required_role='IT';
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
include __DIR__ . '/../config.php';
$id=(int)$_GET['id'];
if($_SERVER['REQUEST_METHOD']==='POST'){
  $descrizione=$_POST['descrizione'];
  $note=$_POST['note'];
  $stmt=$pdo->prepare('UPDATE tbl_categoria SET descrizione=?, note=? WHERE id_categoria=?');
  $stmt->execute([$descrizione,$note,$id]);
  header('Location: categorie.php'); exit;
}
$stmt=$pdo->prepare('SELECT * FROM tbl_categoria WHERE id_categoria=?');
$stmt->execute([$id]);
$r=$stmt->fetch();
?>
<div class='col-md-10 p-4'>
<div class='card shadow'>
  <div class='card-header bg-primary text-white'>Modifica Categoria</div>
  <div class='card-body'>
    <form method='post'>
      <div class='mb-3'><label class='form-label'>Descrizione</label><input type='text' name='descrizione' class='form-control' value='<?= htmlspecialchars($r['descrizione']) ?>'></div>
      <div class='mb-3'><label class='form-label'>Note</label><input type='text' name='note' class='form-control' value='<?= htmlspecialchars($r['note']) ?>'></div>
      <button class='btn btn-primary'>Salva</button>
      <a href='categorie.php' class='btn btn-secondary'>Annulla</a>
    </form>
  </div>
</div>
</div></div></div>
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>
</body></html>