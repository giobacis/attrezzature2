<?php
include __DIR__ . '/../config.php';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';

// Fetch categories
$stmt = $pdo->query('SELECT * FROM tbl_categoria ORDER BY id_categoria');
$cats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

  <!-- HERO top bar (preserve left nav) -->
  <section class="hero-cta text-white w-100">
    <div class="container d-flex flex-wrap align-items-center gap-2">
      <div class="brand fw-bold">Gestione • Categorie</div>
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
        <h2 class="m-0">Categorie</h2>
        <div class="col-auto"><a class="btn btn-success" href="add_attrezzatura.php"><i class="bi bi-plus-circle"></i> Aggiungi</a></div>
      </div>

      <form class='row g-3 mb-3' method='post' action='categorie_save.php'>
        <div class='col-md-4'><input type='text' name='descrizione' class='form-control' placeholder='Descrizione' required></div>
        <div class='col-md-4'><input type='text' name='note' class='form-control' placeholder='Note'></div>
        <div class='col-md-2'><button class='btn btn-success w-100'>Aggiungi</button></div>
      </form>

      <div class="table-responsive">
        <table class='table align-middle mb-0'>
          <thead class="table-light"><tr><th>ID</th><th>Descrizione</th><th>Note</th><th>Azioni</th></tr></thead>
          <tbody>
          <?php
            $toggle = 0;
            if(!empty($cats)):
              foreach ($cats as $r):
                $cls = ($toggle++ % 2) ? 'row2' : 'row1';
          ?>
            <tr class="<?= $cls ?>">
              <td class="text-muted"><?= (int)$r['id_categoria'] ?></td>
              <td><?= htmlspecialchars($r['descrizione']) ?></td>
              <td><?= htmlspecialchars($r['note']) ?></td>
              <td class="text-nowrap">
                <a href='categorie_edit.php?id=<?= (int)$r['id_categoria'] ?>' class='btn btn-sm btn-warning'>Modifica</a>
                <a href='categorie_delete.php?id=<?= (int)$r['id_categoria'] ?>' class='btn btn-sm btn-danger' onclick="return confirm('Eliminare?');">Elimina</a>
              </td>
            </tr>
          <?php
              endforeach;
            else:
          ?>
            <tr><td colspan="4" class="text-center py-4">Nessuna categoria presente.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>