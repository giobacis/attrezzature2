
<?php
include __DIR__ . '/../config.php';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
$rows=$pdo->query("SELECT id_posizione, descrizione, note FROM tbl_posizioni ORDER BY descrizione")->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container-fluid py-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Posizioni</h4>
    <a class="btn btn-success" href="add_posizione.php"><i class="bi bi-plus-circle"></i> Aggiungi Posizione</a>
  </div>
  <div class="card">
    <div class="table-responsive">
      <table id="posTable" class="table table-hover align-middle mb-0">
        <thead class="table-light"><tr><th>ID</th><th>Descrizione</th><th>Note</th><th>Azioni</th></tr></thead>
        <tbody>
          <?php foreach($rows as $r): ?>
            <tr>
              <td class="text-muted"><?= (int)$r['id_posizione'] ?></td>
              <td><?= htmlspecialchars($r['descrizione']) ?></td>
              <td><?= htmlspecialchars($r['note'] ?? '') ?></td>
              <td>
                <a class="btn btn-sm btn-outline-primary" href="edit_posizione.php?id=<?= urlencode($r['id_posizione']) ?>"><i class="bi bi-pencil-square"></i></a>
                <a class="btn btn-sm btn-outline-danger" href="delete_posizione.php?id=<?= urlencode($r['id_posizione']) ?>" onclick="return confirm('Eliminare questa posizione?');"><i class="bi bi-trash3"></i></a>
              </td>
            </tr>
          <?php endforeach; if(empty($rows)): ?>
            <tr><td colspan="4" class="text-center py-4">Nessuna posizione trovata.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
$(function(){ $('#posTable').DataTable({language:{decimal:',',thousands:'.',emptyTable:'Nessun dato disponibile',info:'Mostra _START_ a _END_ di _TOTAL_ righe',infoEmpty:'Mostra 0 a 0 di 0 righe',infoFiltered:'(filtrate da _MAX_ righe totali)',lengthMenu:'Mostra _MENU_ righe',loadingRecords:'Caricamento...',processing:'Elaborazione...',search:'Cerca:',zeroRecords:'Nessun risultato',paginate:{first:'Prima',last:'Ultima',next:'Successiva',previous:'Precedente'}},pageLength:10,order:[[1,'asc']],columnDefs:[{orderable:false,targets:-1}]});});
</script>
<?php include __DIR__ . '/../includes/footer.php'; ?>
