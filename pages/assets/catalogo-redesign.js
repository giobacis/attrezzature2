
/* --- PATCH Robustezza ID per prenotazione --- */
(function(){
  // Popolamento ID nel modal (desktop/tablet)
  var m=document.getElementById('prenotaModal');
  if(m){
    m.addEventListener('show.bs.modal',function(e){
      var b=e.relatedTarget; if(!b) return;
      var id=b.getAttribute('data-item-id');
      var label=b.getAttribute('data-item-label')||'Attrezzatura';
      var hid=document.getElementById('modalItemId');
      if(hid){ hid.value=id||''; }
      var lab=document.getElementById('modalItemLabel'); if(lab) lab.textContent=label;
      console.debug('[Prenota] show modal: id=', id, 'label=', label);
    });
    // Prima dell'invio, verifica che l'hidden sia valorizzato
    var form=document.getElementById('formPrenota');
    if(form){
      form.addEventListener('submit',function(ev){
        var hid=document.getElementById('modalItemId');
        if(!hid || !hid.value){
          ev.preventDefault();
          alert('Errore: ID attrezzatura non rilevato. Riapri il modal e riprova.');
          console.error('[Prenota] id_hardware mancante nel modal');
        }
      });
    }
  }
  // Mobile: redirect a pagina dedicata con ?id=
  var FLAG='__mobile_fullpage_redirect_installed__';
  if(!window[FLAG]){
    window[FLAG]=true;
    document.addEventListener('click',function(e){
      var btn=e.target.closest('[data-bs-target="#prenotaModal"]'); if(!btn) return;
      var isMobile=window.matchMedia('(max-width: 575.98px)').matches; if(!isMobile) return;
      e.preventDefault();
      var id=btn.getAttribute('data-item-id'); var label=encodeURIComponent(btn.getAttribute('data-item-label')||'');
      if(!id){ alert('Errore: ID attrezzatura non rilevato.'); console.error('[Prenota] ID mancante sul bottone'); return; }
      window.location.href='prenota_mobile.php?id='+id+'&label='+label;
    });
  }
})();
