<?php
session_start(); $required_role='IT';
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
include __DIR__ . '/../config.php';
?>
<div class='p-4'>
<h2>Categorie</h2>
<form class='row g-3 mb-3' method='post' action='categorie_save.php'>
  <div class='col-md-4'><input type='text' name='descrizione' class='form-control' placeholder='Descrizione' required></div>
  <div class='col-md-4'><input type='text' name='note' class='form-control' placeholder='Note'></div>
  <div class='col-md-2'><button class='btn btn-success w-100'>Aggiungi</button></div>
</form>
<table class='table table-striped'>
  <thead><tr><th>ID</th><th>Descrizione</th><th>Note</th><th>Azioni</th></tr></thead>
  <tbody>
  <?php $stmt=$pdo->query('SELECT * FROM tbl_categoria ORDER BY id_categoria'); foreach ($stmt as $r): ?>
    <tr>
      <td><?= $r['id_categoria'] ?></td>
      <td><?= htmlspecialchars($r['descrizione']) ?></td>
      <td><?= htmlspecialchars($r['note']) ?></td>
      <td>
        <a href='categorie_edit.php?id=<?= $r['id_categoria'] ?>' class='btn btn-warning btn-sm'>Modifica</a>
        <a href='categorie_delete.php?id=<?= $r['id_categoria'] ?>' class='btn btn-danger btn-sm' onclick="return confirm('Eliminare?');">Elimina</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
</div></div></div>
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>
</body></html>