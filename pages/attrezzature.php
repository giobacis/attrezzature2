
<?php
include __DIR__ . '/../config.php';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';

$cats = $pdo->query("SELECT id_categoria, descrizione FROM tbl_categoria ORDER BY descrizione")->fetchAll(PDO::FETCH_ASSOC);
$stats= $pdo->query("SELECT id_stato, descrizione FROM tbl_stati ORDER BY descrizione")->fetchAll(PDO::FETCH_ASSOC);
$pos  = $pdo->query("SELECT id_posizione, descrizione FROM tbl_posizioni ORDER BY descrizione")->fetchAll(PDO::FETCH_ASSOC);

$q = trim($_GET['q'] ?? '');
$id_cat_f=(int)($_GET['categoria'] ?? 0);
$id_stat_f=(int)($_GET['stato'] ?? 0);
$id_pos_f=(int)($_GET['posizione'] ?? 0);

$where=[]; $params=[];
if($q!==''){ $where[]="(h.descrizione LIKE :q OR h.modello LIKE :q OR p.descrizione LIKE :q OR h.codice_prodotto LIKE :q OR h.seriale LIKE :q OR c.descrizione LIKE :q OR s.descrizione LIKE :q)"; $params['q']='%' . $q . '%'; }
if($id_cat_f>0){ $where[]='h.id_categoria=:id_categoria'; $params['id_categoria']=$id_cat_f; }
if($id_stat_f>0){ $where[]='h.id_stato_disp=:id_stato_disp'; $params['id_stato_disp']=$id_stat_f; }
if($id_pos_f>0){ $where[]='h.id_posizione=:id_posizione'; $params['id_posizione']=$id_pos_f; }

$sql = "SELECT h.id,h.descrizione,h.modello,p.descrizione AS posizione,c.descrizione AS categoria,s.descrizione AS stato
        FROM tbl_hardware h
        JOIN tbl_categoria c ON c.id_categoria=h.id_categoria
        JOIN tbl_stati s ON s.id_stato=h.id_stato_disp
        LEFT JOIN tbl_posizioni p ON p.id_posizione=h.id_posizione";
if($where){ $sql .= ' WHERE ' . implode(' AND ',$where); }
$sql .= ' ORDER BY h.id DESC';
$stmt=$pdo->prepare($sql); $stmt->execute($params); $items=$stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container-fluid py-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <form class="row g-2" method="get" action="">
      <div class="col-auto"><input class="form-control" name="q" placeholder="Cerca… (descrizione, modello, posizione, codice, seriale)" value="<?= htmlspecialchars($q) ?>"></div>
      <div class="col-auto"><select class="form-select" name="categoria"><option value="0">Tutte le categorie</option><?php foreach($cats as $c): ?><option value="<?= (int)$c['id_categoria'] ?>" <?= $id_cat_f===(int)$c['id_categoria']?'selected':'' ?>><?= htmlspecialchars($c['descrizione']) ?></option><?php endforeach; ?></select></div>
      <div class="col-auto"><select class="form-select" name="stato"><option value="0">Tutti gli stati</option><?php foreach($stats as $s): ?><option value="<?= (int)$s['id_stato'] ?>" <?= $id_stat_f===(int)$s['id_stato']?'selected':'' ?>><?= htmlspecialchars($s['descrizione']) ?></option><?php endforeach; ?></select></div>
      <div class="col-auto"><select class="form-select" name="posizione"><option value="0">Tutte le posizioni</option><?php foreach($pos as $p): ?><option value="<?= (int)$p['id_posizione'] ?>" <?= $id_pos_f===(int)$p['id_posizione']?'selected':'' ?>><?= htmlspecialchars($p['descrizione']) ?></option><?php endforeach; ?></select></div>
      <div class="col-auto"><button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Filtra</button><a class="btn btn-outline-secondary" href="attrezzature.php"><i class="bi bi-x-circle"></i> Reset</a></div>
    </form>
    <div class="col-auto"><a class="btn btn-success" href="add_attrezzatura.php"><i class="bi bi-plus-circle"></i> Aggiungi Attrezzatura</a></div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table id="itemsTable" class="table table-hover align-middle mb-0">
          <thead class="table-light"><tr><th>ID</th><th>Descrizione</th><th>Modello</th><th>Posizione</th><th>Categoria</th><th>Stato</th><th>Azioni</th></tr></thead>
          <tbody>
            <?php if(!empty($items)): foreach($items as $it): ?>
              <tr>
                <td class="text-muted"><?= htmlspecialchars($it['id']) ?></td>
                <td><?= htmlspecialchars($it['descrizione']) ?></td>
                <td><?= htmlspecialchars($it['modello'] ?? '') ?></td>
                <td><?= htmlspecialchars($it['posizione'] ?? '—') ?></td>
                <td><span class="badge bg-secondary-subtle text-secondary"><?= htmlspecialchars($it['categoria'] ?? '—') ?></span></td>
                <td><span class="badge bg-info-subtle text-info"><?= htmlspecialchars($it['stato'] ?? '—') ?></span></td>
                <td class="text-nowrap">
                  <a class="btn btn-sm btn-outline-secondary" href="edit_attrezzatura.php?id=<?= urlencode($it['id']) ?>">
                    <i class="bi bi-pencil-square me-1"></i> Modifica
                  </a>
                  <a class="btn btn-sm btn-outline-danger" href="delete_attrezzatura.php?id=<?= urlencode($it['id']) ?>" onclick="return confirm('Eliminare questa attrezzatura?');">
                    <i class="bi bi-trash3 me-1"></i> Elimina
                  </a>
                </td>
              </tr>
            <?php endforeach; else: ?>
              <tr><td colspan="7" class="text-center py-4">Nessuna attrezzatura trovata.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script>
$(function(){
  const itLang={decimal:',',thousands:'.',emptyTable:'Nessun dato disponibile',info:'Mostra _START_ a _END_ di _TOTAL_ righe',infoEmpty:'Mostra 0 a 0 di 0 righe',infoFiltered:'(filtrate da _MAX_ righe totali)',lengthMenu:'Mostra _MENU_ righe',loadingRecords:'Caricamento...',processing:'Elaborazione...',search:'Cerca nella tabella:',zeroRecords:'Nessun risultato corrispondente',paginate:{first:'Prima',last:'Ultima',next:'Successiva',previous:'Precedente'}};
  const domToolbar="<'dt-toolbar d-flex'<'dt-buttons btn-group flex-wrap'B><'ms-auto'f>>t<'d-flex justify-content-between align-items-center mt-2'lip>";
  $('#itemsTable').DataTable({language:itLang,stateSave:true,pageLength:10,order:[[1,'asc']],dom:domToolbar,buttons:[
    {extend:'csv',text:'<i class="bi bi-filetype-csv"></i> <span>CSV</span>',className:'btn btn-primary btn-sm'},
    {extend:'excel',text:'<i class="bi bi-file-earmark-spreadsheet"></i> <span>Excel</span>',className:'btn btn-success btn-sm'},
    {extend:'print',text:'<i class="bi bi-printer"></i> <span>Stampa</span>',className:'btn btn-dark btn-sm'}
  ],columnDefs:[{orderable:false,targets:-1}]});
});
</script>
<?php include __DIR__ . '/../includes/footer.php'; ?>
