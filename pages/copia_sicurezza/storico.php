<?php
session_start(); $required_role='USER';
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar_user.php';
include __DIR__ . '/../config.php';
?>
<div class='p-4'>
<h2>Storico Prenotazioni</h2>
<table class='table table-striped'>
<thead><tr><th>Attrezzatura</th><th>Data Uscita</th><th>Rientro Previsto</th><th>Stato</th></tr></thead>
<tbody>
<?php
$stmt=$pdo->prepare('SELECT h.descrizione,m.data_uscita,m.data_prev_rientro,m.stato FROM tbl_movmg m JOIN tbl_hardware h ON h.id=m.id_prodotto WHERE m.email=? ORDER BY m.data_uscita DESC');
$stmt->execute([$_SESSION['email']]);
foreach($stmt as $r):?>
<tr>
  <td><?= htmlspecialchars($r['descrizione']) ?></td>
  <td><?= $r['data_uscita'] ?></td>
  <td><?= $r['data_prev_rientro'] ?></td>
  <td><span class='badge bg-<?php echo ($r['stato']==='approvato')?'success':(($r['stato']==='rifiutato')?'danger':'secondary'); ?>'><?= htmlspecialchars($r['stato']) ?></span></td>
</tr>
<?php endforeach;?>
</tbody>
</table>
</div>
</div></div>
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>
</body></html>