
<?php
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
include __DIR__ . '/../config.php';

// Use existing booking table `tbl_movmg` and join hardware descriptions from `tbl_hardware`
$stmt = $pdo->query("SELECT m.*, h.descrizione AS nome_attrezzatura FROM tbl_movmg m JOIN tbl_hardware h ON h.id = m.id_prodotto ORDER BY m.data_uscita DESC");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

	<!-- HERO top bar (preserve left nav) -->
	<section class="hero-cta text-white w-100">
		<div class="container d-flex flex-wrap align-items-center gap-2">
			<div class="brand fw-bold">Prenota • Calendario</div>
			<div class="ms-auto w-100 w-lg-auto d-flex align-items-center gap-2">
				<?php include __DIR__.'/../includes/user_badge.php'; ?>
				<div class="btn-group" role="group" aria-label="Tema">
					<button type="button" class="btn btn-sm btn-outline-light" data-theme="light" title="Tema chiaro">Chiaro</button>
					<button type="button" class="btn btn-sm btn-outline-light" data-theme="dark" title="Tema scuro">Scuro</button>
					<button type="button" class="btn btn-sm btn-outline-light" data-theme="auto" title="Segui sistema">Sistema</button>
				</div>
			</div>
		</div>
	</section>

<div class="container-fluid py-3">
	<div class="card shadow-sm">
		<div class="card-body">
			<div class="d-flex justify-content-between align-items-center mb-3">
				<h2 class="m-0"><i class="fas fa-calendar-alt"></i> Calendario Prenotazioni</h2>
			</div>

			<div class="table-responsive">
				<table class="table align-middle mb-0">
					<thead class="table-light"><tr>
						<th>Attrezzatura</th>
						<th>Utente</th>
						<th>Inizio</th>
						<th>Fine</th>
						<th>Stato</th>
					</tr></thead>
					<tbody>
					<?php
						$toggle = 0;
						if (!empty($rows)):
							foreach ($rows as $r):
								$cls = ($toggle++ % 2) ? 'row2' : 'row1';
					?>
						<tr class="<?= $cls ?>">
							<td><?= htmlspecialchars($r['nome_attrezzatura'] ?? '') ?></td>
							<td><?= htmlspecialchars(trim(($r['nome'] ?? '') . ' ' . ($r['cognome'] ?? ''))) ?></td>
							<td><?= htmlspecialchars($r['data_uscita'] ?? '') ?></td>
							<td><?= htmlspecialchars($r['data_prev_rientro'] ?? '') ?></td>
							<td>
								<?php
									$badge = 'secondary';
									if (!empty($r['stato'])) {
										switch ($r['stato']) {
											case 'in_attesa': $badge='warning'; break;
											case 'approvato': $badge='success'; break;
											case 'rifiutato': $badge='danger'; break;
											case 'rientrato': $badge='info'; break;
											default: $badge='secondary';
										}
									}
								?>
								<span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($r['stato'] ?? '') ?></span>
							</td>
						</tr>
					<?php
							endforeach;
						else:
					?>
						<tr><td colspan="5" class="text-center py-4">Nessuna prenotazione presente.</td></tr>
					<?php endif; ?>
						</tbody>
						</table>
						</div>

							</div>
						</div>
					</div>

					<?php include __DIR__ . '/../includes/footer.php'; ?>
