// Shared prenota JS: handles AJAX submission for the modal and standalone form
(function(){
  function showToast(msg,type){
    try{
      var toastHtml = '<div class="toast align-items-center text-bg-'+type+' border-0" role="alert" aria-live="assertive" aria-atomic="true"><div class="d-flex"><div class="toast-body">'+msg+'</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button></div></div>';
      var container = document.getElementById('toastContainer');
      if(!container){ container = document.createElement('div'); container.id='toastContainer'; container.className='position-fixed top-0 end-0 p-3'; container.style.zIndex=9999; document.body.appendChild(container); }
      container.insertAdjacentHTML('beforeend', toastHtml);
      var el = container.lastElementChild;
      if(window.bootstrap && bootstrap.Toast){ new bootstrap.Toast(el,{delay:3000}).show(); }
    }catch(e){ console.log('toast error',e); }
  }

  function bind(){
    var btn = document.getElementById('btnInvia');
    var spinner = document.getElementById('btnSpinner');
    var form = document.getElementById('formPrenota');
    if(!btn || !form) return;
    btn.addEventListener('click', function(){
      btn.disabled = true; if(spinner) spinner.classList.remove('d-none');
      var fd = new FormData(form);
      // ensure product id is present
      if(!fd.get('id_prodotto') && window.itemId){ fd.append('id_prodotto', window.itemId); }
      fetch('prenota.php', { method: 'POST', headers: { 'Accept': 'application/json' }, body: new URLSearchParams(fd) })
        .then(function(r){ return r.json(); })
        .then(function(resp){
          if(resp && resp.status === 'ok'){
            showToast('Richiesta inviata! In attesa di approvazione.','success');
            // if inside modal, hide and reset
            try{ var modalEl=document.getElementById('prenotaModal'); if(modalEl && bootstrap.Modal){ bootstrap.Modal.getInstance(modalEl)?.hide(); form.reset(); } }
            catch(e){}
            if(window.calendarRefetch) window.calendarRefetch();
            // standalone flow: if no modal present, try to close popup or redirect back to catalogo
            try{
              var modalEl = document.getElementById('prenotaModal');
              if(!modalEl){
                if(window.opener && !window.opener.closed){ window.close(); }
                else { window.location = 'catalogo.php'; }
              }
            }catch(e){}
          } else {
            showToast(resp && resp.message ? resp.message : 'Errore server.','danger');
          }
        }).catch(function(){ showToast('Errore di rete o server.','danger'); })
        .finally(function(){ btn.disabled = false; if(spinner) spinner.classList.add('d-none'); });
    });
  }

  // theme toggle binding (if a #toggleTheme button exists on the page)
  function bindThemeToggle(){
    var toggle = document.getElementById('toggleTheme');
    if(!toggle) return;
    toggle.addEventListener('click', function(){
      document.body.classList.toggle('bg-dark'); document.body.classList.toggle('text-light');
      document.querySelectorAll('.card').forEach(function(c){ c.classList.toggle('bg-dark'); c.classList.toggle('text-light'); });
    });
  }

  if(document.readyState === 'loading') document.addEventListener('DOMContentLoaded', function(){ bind(); bindThemeToggle(); }); else { bind(); bindThemeToggle(); }
})();
