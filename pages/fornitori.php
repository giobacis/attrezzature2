<?php
include __DIR__ . '/../config.php';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';

// Fetch fornitori
$stmt = $pdo->query('SELECT id_fornitore, ragione_sociale, telefono, mail FROM tbl_fornitori ORDER BY id_fornitore');
$fornitori = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

  <!-- HERO top bar (preserve left nav) -->
  <section class="hero-cta text-white w-100">
    <div class="container d-flex flex-wrap align-items-center gap-2">
      <div class="brand fw-bold">Gestione • Fornitori</div>
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
        <h2 class="m-0">Fornitori</h2>
        <div class="col-auto"><a class="btn btn-success" href="fornitori.php"><i class="bi bi-plus-circle"></i> Aggiungi</a></div>
      </div>

      <form class='row g-3 mb-3' method='post' action='fornitori_save.php'>
        <div class='col-md-4'><input type='text' name='ragione_sociale' class='form-control' placeholder='Ragione sociale' required></div>
        <div class='col-md-3'><input type='text' name='telefono' class='form-control' placeholder='Telefono'></div>
        <div class='col-md-3'><input type='email' name='mail' class='form-control' placeholder='Email'></div>
        <div class='col-md-2'><button class='btn btn-success w-100'>Aggiungi</button></div>
      </form>

      <div class="table-responsive">
        <table class='table align-middle mb-0'>
          <thead class="table-light"><tr><th>ID</th><th>Ragione sociale</th><th>Telefono</th><th>Email</th><th>Azioni</th></tr></thead>
          <tbody>
          <?php
            $toggle = 0;
            if(!empty($fornitori)):
              foreach($fornitori as $f):
                $cls = ($toggle++ % 2) ? 'row2' : 'row1';
          ?>
            <tr class="<?= $cls ?>">
              <td class="text-muted"><?= (int)$f['id_fornitore'] ?></td>
              <td><?= htmlspecialchars($f['ragione_sociale']) ?></td>
              <td><?= htmlspecialchars($f['telefono']) ?></td>
              <td><?= htmlspecialchars($f['mail']) ?></td>
              <td class="text-nowrap">
                <a href='fornitori_edit.php?id=<?= (int)$f['id_fornitore'] ?>' class='btn btn-sm btn-warning'>Modifica</a>
                <a href='fornitori_delete.php?id=<?= (int)$f['id_fornitore'] ?>' class='btn btn-sm btn-danger' onclick="return confirm('Eliminare?');">Elimina</a>
              </td>
            </tr>
          <?php
              endforeach;
            else:
          ?>
            <tr><td colspan="5" class="text-center py-4">Nessun fornitore presente.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>