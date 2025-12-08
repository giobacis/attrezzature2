
<?php
require '../includes/header.php';
require '../includes/sidebar.php';
require '../config/config.php';

$stmt=$pdo->query("SELECT * FROM attrezzature ORDER BY nome");
$rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h2 class="mb-4">Catalogo Attrezzature</h2>
<div class="row g-3">
<?php foreach($rows as $r): ?>
<div class="col-12 col-md-4 col-lg-3">
<div class="card h-100 shadow-sm">
<div class="card-body">
<h5 class="card-title"><?= htmlspecialchars($r['nome']) ?></h5>
<p class="card-text small text-muted"><?= htmlspecialchars($r['descrizione']) ?></p>
<span class="badge bg-success">Disponibile</span>
</div>
<div class="card-footer text-end">
<a href="prenota.php?id=<?= $r['id'] ?>" class="btn btn-primary btn-sm">Prenota</a>
</div>
</div>
</div>
<?php endforeach; ?>
</div>
<?php require '../includes/footer.php'; ?>
