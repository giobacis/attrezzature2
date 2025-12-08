<?php
session_start(); $required_role='IT';
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
?>
<div class='col-md-10 p-4'>
  <h2>Dashboard IT</h2>
  <p>Benvenuto nella gestione interna.</p>
  <div class='row g-3'>
    <div class='col-md-4'>
      <div class='card shadow-sm'>
        <div class='card-body'>
          <h5 class='card-title'>Attrezzature</h5>
          <p class='card-text'>Gestione inventario e schede tecniche.</p>
          <a class='btn btn-primary' href='attrezzature.php'>Vai</a>
        </div>
      </div>
    </div>
    <div class='col-md-4'>
      <div class='card shadow-sm'>
        <div class='card-body'>
          <h5 class='card-title'>Categorie & Fornitori</h5>
          <p class='card-text'>Gestione anagrafiche di base.</p>
          <a class='btn btn-outline-primary me-2' href='categorie.php'>Categorie</a>
          <a class='btn btn-outline-primary' href='fornitori.php'>Fornitori</a>
        </div>
      </div>
    </div>
    <div class='col-md-4'>
      <div class='card shadow-sm'>
        <div class='card-body'>
          <h5 class='card-title'>Prenotazioni</h5>
          <p class='card-text'>Approva richieste e calendario.</p>
          <a class='btn btn-outline-success me-2' href='gestione_prenotazioni.php'>Gestione</a>
          <a class='btn btn-outline-success' href='prenotazioni.php'>Calendario</a>
        </div>
      </div>
    </div>
  </div>
</div>
</div></div>
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>
</body></html>