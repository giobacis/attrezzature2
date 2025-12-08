
<?php
require '../includes/header.php';
require '../includes/sidebar.php';
require '../config/config.php';

$stmt=$pdo->query("SELECT p.*, a.nome FROM prenotazioni p JOIN attrezzature a ON p.attrezzatura_id=a.id ORDER BY data_inizio DESC");
$rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h2 class="mb-4">Prenotazioni</h2>
<div class="table-responsive">
<table class="table table-striped">
<thead><tr>
<th>Attrezzatura</th><th>Utente</th><th>Inizio</th><th>Fine</th><th>Stato</th>
</tr></thead>
<tbody>
<?php foreach($rows as $r): ?>
<tr>
<td><?= htmlspecialchars($r['nome']) ?></td>
<td><?= htmlspecialchars($r['utente']) ?></td>
<td><?= $r['data_inizio'] ?></td>
<td><?= $r['data_fine'] ?></td>
<td><span class="badge bg-info"><?= $r['stato'] ?></span></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
<?php require '../includes/footer.php'; ?>
