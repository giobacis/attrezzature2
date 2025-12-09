<?php
session_start(); $required_role='IT';
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
include __DIR__ . '/../config.php';
$id=(int)$_GET['id'];
if($_SERVER['REQUEST_METHOD']==='POST'){
  $stmt=$pdo->prepare('UPDATE tbl_fornitori SET ragione_sociale=?, telefono=?, mail=? WHERE id_fornitore=?');
  $stmt->execute([$_POST['ragione_sociale'], $_POST['telefono'], $_POST['mail'], $id]);
  header('Location: fornitori.php'); exit;
}
$stmt=$pdo->prepare('SELECT * FROM tbl_fornitori WHERE id_fornitore=?');
$stmt->execute([$id]);
$f=$stmt->fetch();
?>
<div class='p-4'>
<div class='card shadow'>
  <div class='card-header bg-primary text-white'>Modifica Fornitore</div>
  <div class='card-body'>
    <form method='post'>
      <div class='mb-3'><label class='form-label'>Ragione sociale</label><input type='text' name='ragione_sociale' class='form-control' value='<?= htmlspecialchars($f['ragione_sociale']) ?>'></div>
      <div class='mb-3'><label class='form-label'>Telefono</label><input type='text' name='telefono' class='form-control' value='<?= htmlspecialchars($f['telefono']) ?>'></div>
      <div class='mb-3'><label class='form-label'>Email</label><input type='email' name='mail' class='form-control' value='<?= htmlspecialchars($f['mail']) ?>'></div>
      <button class='btn btn-primary'>Salva</button>
      <a href='fornitori.php' class='btn btn-secondary'>Annulla</a>
    </form>
  </div>
</div>
</div></div></div>
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>
</body></html>