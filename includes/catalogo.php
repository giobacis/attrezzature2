<?php
session_start();
$required_role='USER';
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar_user.php';
include __DIR__ . '/../config.php';
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$stmt=$pdo->query("SELECT h.id, h.descrizione, c.descrizione AS cat, s.id_stato AS id_stato, s.descrizione AS stato FROM tbl_hardware h JOIN tbl_categoria c ON h.id_categoria=c.id_categoria JOIN tbl_stati s ON h.id_stato_disp=s.id_stato ORDER BY h.descrizione ASC");
?>
<div class="col-md-10 p-4">
<div class="d-flex justify-content-between align-items-center mb-3">
<h2 class="mb-0"><i class="fas fa-box"></i> Catalogo Attrezzature</h2>
<button id="toggleTheme" class="btn btn-outline-dark btn-sm">Tema Scuro/Chiaro</button>
</div>
<div class="row">
<div class="col-lg-8">
<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
<?php foreach($stmt as $item): ?>
<div class="col">
<div class="card h-100 shadow border-0">
<div class="card-body text-center">
<div class="display-4 text-primary mb-2"><i class="fas fa-box"></i></div>
<h6 class="card-title mb-1 fw-bold"><?php echo htmlspecialchars($item['descrizione']); ?></h6>
<div class="mb-2"><span class="badge bg-secondary"><?php echo htmlspecialchars($item['cat']); ?></span> <span class="badge bg-info text-dark"><?php echo htmlspecialchars($item['stato']); ?></span></div>
<button class="btn btn-success btn-sm w-100" data-bs-toggle="modal" data-bs-target="#prenotaModal" data-id="<?php echo (int)$item['id']; ?>" data-name="<?php echo htmlspecialchars($item['descrizione']); ?>"><i class="fas fa-calendar-plus me-1"></i> Prenota</button>
</div></div></div>
<?php endforeach; ?>
</div></div>
<div class="col-lg-4">
<div class="mb-2"><span class="badge" style="background:#28a745">Approvate</span> <span class="badge" style="background:#ffc107;color:#212529">In attesa</span></div>
<div id="calendar" class="bg-white p-2 rounded border"></div>
</div></div></div>
<div class="modal fade" id="prenotaModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header bg-primary text-white"><h5 class="modal-title">Prenota: <span id="itemName"></span></h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div><form id="formPrenota" autocomplete="off"><div class="modal-body"><input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>"><input type="hidden" name="id_prodotto" id="itemId"><div class="row g-2"><div class="col-md-6"><label class="form-label">Nome</label><input type="text" name="nome" class="form-control" required></div><div class="col-md-6"><label class="form-label">Cognome</label><input type="text" name="cognome" class="form-control" required></div><div class="col-md-4"><label class="form-label">Ruolo</label><select name="ruolo" class="form-select" required><option value="">Seleziona…</option><option value="Docente">Docente</option><option value="Studente">Studente</option></select></div><div class="col-md-4"><label class="form-label">Classe</label><input type="text" name="classe" class="form-control" required></div><div class="col-md-4"><label class="form-label">Cellulare</label><input type="tel" name="cellulare" class="form-control" required></div><div class="col-md-6"><label class="form-label">Data Uscita</label><input type="date" name="data_uscita" class="form-control" required></div><div class="col-md-6"><label class="form-label">Data Rientro prevista</label><input type="date" name="data_prev_rientro" class="form-control" required></div><div class="col-12"><label class="form-label">Note</label><textarea name="note" class="form-control" rows="2"></textarea></div></div></div><div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Chiudi</button><button type="button" class="btn btn-success" id="btnInvia"><span class="spinner-border spinner-border-sm me-1 d-none" id="btnSpinner" role="status" aria-hidden="true"></span><i class="fas fa-check"></i> Invia richiesta</button></div></form></div></div></div>
<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999"><div id="toastContainer"></div></div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script><link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet"><script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script>$(function(){$('#prenotaModal').on('show.bs.modal',function(e){const btn=$(e.relatedTarget);$('#itemId').val(btn.data('id'));$('#itemName').text(btn.data('name'));});const calEl=document.getElementById('calendar');if(calEl){const cal=new FullCalendar.Calendar(calEl,{initialView:'dayGridMonth',locale:'it',firstDay:1,height:'auto',themeSystem:'bootstrap5',headerToolbar:{left:'prev,next today',center:'title',right:'dayGridMonth,timeGridWeek'},eventSources:[{url:'prenotazioni_feed.php',method:'GET'}],eventDidMount:function(info){$(info.el).tooltip({title:info.event.title,placement:'top',trigger:'hover'});}});cal.render();window.calendarRefetch=function(){cal.refetchEvents();};}$('#btnInvia').on('click',function(){const $btn=$(this);const $spinner=$('#btnSpinner');const $form=$('#formPrenota');$btn.prop('disabled',true);$spinner.removeClass('d-none');$.ajax({url:'prenota.php',method:'POST',data:$form.serialize(),dataType:'json'}).done(function(resp){if(resp&&resp.status==='ok'){showToast('Richiesta inviata! In attesa di approvazione.','success');setTimeout(function(){bootstrap.Modal.getInstance(document.getElementById('prenotaModal')).hide();$form[0].reset();if(window.calendarRefetch)window.calendarRefetch();},1200);}else{showToast(resp.message||'Errore server.','danger');}}).fail(function(){showToast('Errore di rete o server.','danger');}).always(function(){$btn.prop('disabled',false);$spinner.addClass('d-none');});});function showToast(msg,type){const toastHtml='<div class="toast align-items-center text-bg-'+type+' border-0" role="alert" aria-live="assertive" aria-atomic="true"><div class="d-flex"><div class="toast-body">'+msg+'</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button></div></div>';const $toast=$(toastHtml);$('#toastContainer').append($toast);const toast=new bootstrap.Toast($toast[0],{delay:3000});toast.show();}$('#toggleTheme').on('click',function(){$('body').toggleClass('bg-dark text-light');$('.card').toggleClass('bg-dark text-light');});});</script>