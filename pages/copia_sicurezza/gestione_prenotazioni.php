<?php
session_start();
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
include __DIR__ . '/../config.php';

$stato = $_GET['stato'] ?? '';
$query = "SELECT * FROM tbl_movmg";
if ($stato) {
    $query .= " WHERE stato=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$stato]);
} else {
    $stmt = $pdo->query($query);
}
$prenotazioni = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class='p-4'>
<h2 class='mb-4'><i class='fas fa-tasks'></i> Gestione Prenotazioni</h2>
<form method='GET' class='row g-3 mb-3'>
    <div class='col-auto'>
        <select name='stato' class='form-select'>
            <option value=''>Tutti</option>
            <option value='in_attesa'>In Attesa</option>
            <option value='approvato'>Approvato</option>
            <option value='rifiutato'>Rifiutato</option>
            <option value='rientrato'>Rientrato</option>
        </select>
    </div>
    <div class='col-auto'>
        <button type='submit' class='btn btn-primary'><i class='fas fa-filter'></i> Filtra</button>
    </div>
</form>
<table class='table table-striped table-hover shadow-sm'>
<thead class='table-dark'>
<tr>
<th>Nome</th><th>Cognome</th><th>Email</th><th>Attrezzatura</th><th>Data Uscita</th><th>Data Rientro Previsto</th><th>Stato</th><th>Azioni</th>
</tr>
</thead>
<tbody>
<?php foreach ($prenotazioni as $p): ?>
<tr>
<td><?= htmlspecialchars($p['nome']) ?></td>
<td><?= htmlspecialchars($p['cognome']) ?></td>
<td><?= htmlspecialchars($p['email']) ?></td>
<td><?= htmlspecialchars($p['id_prodotto']) ?></td>
<td><?= htmlspecialchars($p['data_uscita']) ?></td>
<td><?= htmlspecialchars($p['data_prev_rientro']) ?></td>
<td>
<?php
$badgeClass = 'secondary';
switch($p['stato']) {
    case 'in_attesa': $badgeClass='warning'; break;
    case 'approvato': $badgeClass='success'; break;
    case 'rifiutato': $badgeClass='danger'; break;
    case 'rientrato': $badgeClass='info'; break;
}
?>
<span class='badge bg-<?= $badgeClass ?>'><?= htmlspecialchars($p['stato']) ?></span>
</td>
<td>
<?php if ($p['stato'] === 'in_attesa'): ?>
<a href="prenotazione_approva.php?id_prodotto=<?= $p['id_prodotto'] ?>&data_uscita=<?= $p['data_uscita'] ?>&data_prev_rientro=<?= $p['data_prev_rientro'] ?>&email=<?= urlencode($p['email']) ?>" class="btn btn-success btn-sm"><i class='fas fa-check'></i></a>
<a href="prenotazione_rifiuta.php?id_prodotto=<?= $p['id_prodotto'] ?>&data_uscita=<?= $p['data_uscita'] ?>&data_prev_rientro=<?= $p['data_prev_rientro'] ?>" class="btn btn-danger btn-sm"><i class='fas fa-times'></i></a>
<?php elseif ($p['stato'] === 'approvato'): ?>
<a href="prenotazione_rientro.php?id_prodotto=<?= $p['id_prodotto'] ?>&data_uscita=<?= $p['data_uscita'] ?>&data_prev_rientro=<?= $p['data_prev_rientro'] ?>" class="btn btn-primary btn-sm"><i class='fas fa-undo'></i></a>
<?php endif; ?>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
