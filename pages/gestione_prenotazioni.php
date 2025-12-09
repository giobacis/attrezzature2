<?php
include __DIR__ . '/../config.php';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';

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

    <!-- HERO top bar (preserve left nav) -->
    <section class="hero-cta text-white w-100">
        <div class="container d-flex flex-wrap align-items-center gap-2">
            <div class="brand fw-bold">Gestione • Prenotazioni</div>
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
                <h2 class="m-0"><i class='fas fa-tasks'></i> Gestione Prenotazioni</h2>
            </div>

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

            <div class="table-responsive">
                <table class='table align-middle mb-0'>
                <thead class='table-light'>
                <tr>
                <th>Nome</th><th>Cognome</th><th>Email</th><th>Attrezzatura</th><th>Data Uscita</th><th>Data Rientro Previsto</th><th>Stato</th><th>Azioni</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    $toggle = 0;
                    if (!empty($prenotazioni)):
                        foreach ($prenotazioni as $p):
                            $cls = ($toggle++ % 2) ? 'row2' : 'row1';
                ?>
                <tr class="<?= $cls ?>">
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
                <a href="prenotazione_approva.php?id_prodotto=<?= urlencode($p['id_prodotto']) ?>&data_uscita=<?= urlencode($p['data_uscita']) ?>&data_prev_rientro=<?= urlencode($p['data_prev_rientro']) ?>&email=<?= urlencode($p['email']) ?>" class="btn btn-success btn-sm"><i class='fas fa-check'></i></a>
                <a href="prenotazione_rifiuta.php?id_prodotto=<?= urlencode($p['id_prodotto']) ?>&data_uscita=<?= urlencode($p['data_uscita']) ?>&data_prev_rientro=<?= urlencode($p['data_prev_rientro']) ?>" class="btn btn-danger btn-sm"><i class='fas fa-times'></i></a>
                <?php elseif ($p['stato'] === 'approvato'): ?>
                <a href="prenotazione_rientro.php?id_prodotto=<?= urlencode($p['id_prodotto']) ?>&data_uscita=<?= urlencode($p['data_uscita']) ?>&data_prev_rientro=<?= urlencode($p['data_prev_rientro']) ?>" class="btn btn-primary btn-sm"><i class='fas fa-undo'></i></a>
                <?php endif; ?>
                </td>
                </tr>
                <?php
                        endforeach;
                    else:
                ?>
                    <tr><td colspan="8" class="text-center py-4">Nessuna prenotazione presente.</td></tr>
                <?php endif; ?>
                </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
