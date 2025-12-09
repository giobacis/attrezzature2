
<?php
// includes/footer.php
?>
  <footer class="py-3 text-center small text-muted">
    &copy; 2025 Gestione Attrezzature - Tutti i diritti riservati
  </footer>
</main>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Global theme toggler: set data-theme on <html> and persist choice
  (function(){
    function applyTheme(t){
      var html=document.documentElement;
      if(t==='auto'){ html.removeAttribute('data-theme'); }
      else { html.setAttribute('data-theme', t); }
      try{ localStorage.setItem('app_theme', t); }catch(e){}
    }
    var saved=null; try{ saved=localStorage.getItem('app_theme'); }catch(e){}
    if(saved) applyTheme(saved);
    document.addEventListener('click', function(e){
      var b = e.target.closest('[data-theme]'); if(!b) return;
      applyTheme(b.getAttribute('data-theme'));
    });
  })();
    // Initialize Bootstrap tooltips (for elements using data-bs-toggle="tooltip")
    (function(){
      try{
        var tlist = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tlist.forEach(function(el){ if(window.bootstrap && bootstrap.Tooltip) new bootstrap.Tooltip(el); });
      }catch(e){ /* ignore */ }
    })();
</script>
</body>
</html>
