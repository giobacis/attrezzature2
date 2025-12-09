
// theme-toggle.js — gestisce light/dark/auto con persistenza
(function(){
  var KEY = 'app-theme';
  var html = document.documentElement;
  function apply(theme){ html.setAttribute('data-theme', theme); }
  // inizializza dal localStorage (default: light)
  var saved = localStorage.getItem(KEY) || 'light';
  apply(saved);
  // click handler sui bottoni con data-theme
  document.addEventListener('click', function(e){
    var btn = e.target.closest('[data-theme]');
    if(!btn) return;
    var theme = btn.getAttribute('data-theme');
    localStorage.setItem(KEY, theme);
    apply(theme);
  });
})();
