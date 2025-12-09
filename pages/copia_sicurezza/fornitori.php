<?php
session_start(); $required_role='IT';
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
include __DIR__ . '/../config.php';
?>
<div class='p-4'>
<h2>Fornitori</h2>
<form class='row g-3 mb-3' method='post' action='fornitori_save.php'>
  <div class='col-md-4'><input type='text' name='ragione_sociale' class='form-control' placeholder='Ragione sociale' required></div>
  <div class='col-md-3'><input type='text' name='telefono' class='form-control' placeholder='Telefono'></div>
  <div class='col-md-3'><input type='email' name='mail' class='form-control' placeholder='Email'></div>
  <div class='col-md-2'><button class='btn btn-success w-100'>Aggiungi</button></div>
</form>
<table class='table table-striped'>
  <thead><tr><th>ID</th><th>Ragione sociale</th><th>Telefono</th><th>Email</th><th>Azioni</th></tr></thead>
  <tbody>
  <?php $stmt=$pdo->query('SELECT id_fornitore, ragione_sociale, telefono, mail FROM tbl_fornitori ORDER BY id_fornitore'); foreach($stmt as $f): ?>
    <tr>
      <td><?= $f['id_fornitore'] ?></td>
      <td><?= htmlspecialchars($f['ragione_sociale']) ?></td>
      <td><?= htmlspecialchars($f['telefono']) ?></td>
      <td><?= htmlspecialchars($f['mail']) ?></td>
      <td>
        <a href='fornitori_edit.php?id=<?= $f['id_fornitore'] ?>' class='btn btn-warning btn-sm'>Modifica</a>
        <a href='fornitori_delete.php?id=<?= $f['id_fornitore'] ?>' class='btn btn-danger btn-sm' onclick="return confirm('Eliminare?');">Elimina</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
</div></div></div>
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>
</body></html>