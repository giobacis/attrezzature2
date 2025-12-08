
<?php
session_start(); $required_role='IT';
include __DIR__ . '/../config.php';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';

$id=(int)($_GET['id']??0); if($id<=0){ header('Location: attrezzature.php'); exit; }
$cats=$pdo->query("SELECT id_categoria,descrizione FROM tbl_categoria ORDER BY descrizione")->fetchAll(PDO::FETCH_ASSOC);
$stats=$pdo->query("SELECT id_stato,descrizione FROM tbl_stati ORDER BY descrizione")->fetchAll(PDO::FETCH_ASSOC);
$prods=$pdo->query("SELECT id_produttore,CompanyName FROM tbl_produttori ORDER BY CompanyName")->fetchAll(PDO::FETCH_ASSOC);
$forns=$pdo->query("SELECT id_fornitore,ragione_sociale FROM tbl_fornitori ORDER BY ragione_sociale")->fetchAll(PDO::FETCH_ASSOC);
$pos=$pdo->query("SELECT id_posizione,descrizione FROM tbl_posizioni ORDER BY descrizione")->fetchAll(PDO::FETCH_ASSOC);
$stmt=$pdo->prepare("SELECT * FROM tbl_hardware WHERE id=?"); $stmt->execute([$id]); $item=$stmt->fetch(PDO::FETCH_ASSOC); if(!$item){ header('Location: attrezzature.php'); exit; }
$errors=[]; if($_SERVER['REQUEST_METHOD']==='POST'){
  $item['descrizione']=trim($_POST['descrizione']??'');
  $item['id_categoria']=(int)($_POST['id_categoria']??0);
  $item['id_stato_disp']=(int)($_POST['id_stato_disp']??0);
  $item['modello']=trim($_POST['modello']??'');
  $item['id_posizione']=(int)($_POST['id_posizione']??0);
  $item['codice_prodotto']=trim($_POST['codice_prodotto']??'');
  $item['seriale']=trim($_POST['seriale']??'');
  $item['id_produttore']=(int)($_POST['id_produttore']??0);
  $item['id_fornitore']=(int)($_POST['id_fornitore']??0);
  $item['note']=trim($_POST['note']??'');
  if($item['descrizione']==='')$errors[]='La descrizione è obbligatoria.';
  if($item['id_categoria']<=0)$errors[]='La categoria è obbligatoria.';
  if($item['id_stato_disp']<=0)$errors[]='Lo stato è obbligatorio.';
  if(!$errors){ try{
    $sql="UPDATE tbl_hardware SET descrizione=:descrizione,id_categoria=:id_categoria,id_stato_disp=:id_stato_disp,modello=:modello,id_posizione=:id_posizione,codice_prodotto=:codice_prodotto,seriale=:seriale,id_produttore=:id_produttore,id_fornitore=:id_fornitore,note=:note WHERE id=:id";
    $stmt=$pdo->prepare($sql);
    $stmt->execute([':descrizione'=>$item['descrizione'],':id_categoria'=>$item['id_categoria'],':id_stato_disp'=>$item['id_stato_disp'],':modello'=>$item['modello']?:null,':id_posizione'=>$item['id_posizione']?:null,':codice_prodotto'=>$item['codice_prodotto']?:null,':seriale'=>$item['seriale']?:null,':id_produttore'=>$item['id_produttore']?:null,':id_fornitore'=>$item['id_fornitore']?:null,':note'=>$item['note']?:null,':id'=>$id]);
    header('Location: attrezzature.php'); exit;
  }catch(PDOException $e){ if($e->getCode()==='23000'){ $m=$e->getMessage(); if(stripos($m,'seriale')!==false)$errors[]='Il seriale è già presente.'; elseif(stripos($m,'codice_prodotto')!==false)$errors[]='Il codice prodotto è già presente.'; else $errors[]='Violazione di vincolo di unicità.'; } else $errors[]='Errore database: '.$e->getMessage(); }}
}
?>
<div class="container-fluid py-3">
  <h4 class="mb-3">Modifica Attrezzatura</h4>
  <?php if($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul></div><?php endif; ?>
  <form method="post" class="card p-3">
    <div class="row g-3">
      <div class="col-md-6"><label class="form-label">Descrizione *</label><input class="form-control" name="descrizione" required value="<?= htmlspecialchars($item['descrizione'] ?? '') ?>"></div>
      <div class="col-md-3"><label class="form-label">Categoria *</label><select class="form-select" name="id_categoria" required><option value="">Seleziona…</option><?php foreach($cats as $c): ?><option value="<?= (int)$c['id_categoria'] ?>" <?= ((int)$item['id_categoria']===(int)$c['id_categoria'])?'selected':'' ?>><?= htmlspecialchars($c['descrizione']) ?></option><?php endforeach; ?></select></div>
      <div class="col-md-3"><label class="form-label">Stato *</label><select class="form-select" name="id_stato_disp" required><option value="">Seleziona…</option><?php foreach($stats as $s): ?><option value="<?= (int)$s['id_stato'] ?>" <?= ((int)$item['id_stato_disp']===(int)$s['id_stato'])?'selected':'' ?>><?= htmlspecialchars($s['descrizione']) ?></option><?php endforeach; ?></select></div>
      <div class="col-md-4"><label class="form-label">Modello</label><input class="form-control" name="modello" value="<?= htmlspecialchars($item['modello'] ?? '') ?>"></div>
      <div class="col-md-4"><label class="form-label">Posizione</label><select class="form-select" name="id_posizione"><option value="0">Non specificata</option><?php foreach($pos as $p): ?><option value="<?= (int)$p['id_posizione'] ?>" <?= ((int)($item['id_posizione'] ?? 0)===(int)$p['id_posizione'])?'selected':'' ?>><?= htmlspecialchars($p['descrizione']) ?></option><?php endforeach; ?></select></div>
      <div class="col-md-4"><label class="form-label">Codice prodotto</label><input class="form-control" name="codice_prodotto" value="<?= htmlspecialchars($item['codice_prodotto'] ?? '') ?>"></div>
      <div class="col-md-4"><label class="form-label">Seriale</label><input class="form-control" name="seriale" value="<?= htmlspecialchars($item['seriale'] ?? '') ?>"></div>
      <div class="col-md-4"><label class="form-label">Produttore</label><select class="form-select" name="id_produttore"><option value="0">Non specificato</option><?php foreach($prods as $pr): ?><option value="<?= (int)$pr['id_produttore'] ?>" <?= ((int)$item['id_produttore']===(int)$pr['id_produttore'])?'selected':'' ?>><?= htmlspecialchars($pr['CompanyName']) ?></option><?php endforeach; ?></select></div>
      <div class="col-md-4"><label class="form-label">Fornitore</label><select class="form-select" name="id_fornitore"><option value="0">Non specificato</option><?php foreach($forns as $f): ?><option value="<?= (int)$f['id_fornitore'] ?>" <?= ((int)$item['id_fornitore']===(int)$f['id_fornitore'])?'selected':'' ?>><?= htmlspecialchars($f['ragione_sociale']) ?></option><?php endforeach; ?></select></div>
      <div class="col-12"><label class="form-label">Note</label><textarea class="form-control" rows="3" name="note"><?= htmlspecialchars($item['note'] ?? '') ?></textarea></div>
    </div>
    <div class="mt-3 d-flex gap-2"><button class="btn btn-primary" type="submit"><i class="bi bi-check2-circle"></i> Salva</button><a class="btn btn-outline-secondary" href="attrezzature.php"><i class="bi bi-arrow-left"></i> Annulla</a></div>
  </form>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
