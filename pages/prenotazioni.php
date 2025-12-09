
<?php
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
include __DIR__ . '/../config.php';

// Use existing booking table `tbl_movmg` and join hardware descriptions from `tbl_hardware`
$stmt = $pdo->query("SELECT m.*, h.descrizione AS nome_attrezzatura FROM tbl_movmg m JOIN tbl_hardware h ON h.id = m.id_prodotto ORDER BY m.data_uscita DESC");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
	<td><?= htmlspecialchars($r['nome_attrezzatura'] ?? '') ?></td>
	<td><?= htmlspecialchars(trim(($r['nome'] ?? '') . ' ' . ($r['cognome'] ?? ''))) ?></td>
	<td><?= htmlspecialchars($r['data_uscita'] ?? '') ?></td>
	<td><?= htmlspecialchars($r['data_prev_rientro'] ?? '') ?></td>
	<td><span class="badge bg-info"><?= htmlspecialchars($r['stato'] ?? '') ?></span></td>
	</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
