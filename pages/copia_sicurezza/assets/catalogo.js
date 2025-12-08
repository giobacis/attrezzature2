
(function(){
  const stored = localStorage.getItem('theme');
  setTheme(stored || 'auto');
  document.querySelectorAll('[data-theme]').forEach(btn => {
    btn.addEventListener('click', () => setTheme(btn.getAttribute('data-theme')));
  });
  function setTheme(mode){
    if(mode === 'auto'){
      const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
      document.documentElement.setAttribute('data-bs-theme', prefersDark ? 'dark' : 'light');
    } else {
      document.documentElement.setAttribute('data-bs-theme', mode);
    }
    localStorage.setItem('theme', mode);
  }
  const modalEl = document.getElementById('prenotaModal');
  if(modalEl){
    modalEl.addEventListener('show.bs.modal', event => {
      const btn = event.relatedTarget;
      if(!btn) return;
      const id    = btn.getAttribute('data-item-id');
      const label = btn.getAttribute('data-item-label');
      document.getElementById('modalItemId').value = id || '';
      document.getElementById('modalItemLabel').textContent = label || 'Attrezzatura';
    });
  }
  const today = new Date().toISOString().slice(0,10);
  const dU = document.getElementById('dataUscita');
  const dR = document.getElementById('dataRientro');
  if(dU) dU.min = today;
  if(dR) dR.min = today;
  function checkDates(){
    if(dU && dR && dU.value && dR.value && dR.value < dU.value){
      dR.setCustomValidity("Il rientro non può essere precedente all'uscita.");
    } else if(dR){
      dR.setCustomValidity('');
    }
  }
  if(dU) dU.addEventListener('change', checkDates);
  if(dR) dR.addEventListener('change', checkDates);
})();
