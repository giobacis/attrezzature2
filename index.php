
<?php
// -------------------------------------------
// HOME - Gestione Attrezzature (index.php)
// -------------------------------------------
// Assunzioni:
// - $_SESSION['user_id'] indica utente loggato
// - $_SESSION['user_name'] contiene il nome dell’utente (opzionale)
// - Pagine: login.php, register.php, catalogo.php, prenotazioni.php, guida.php, logout.php
// -------------------------------------------

session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userName   = $isLoggedIn ? ($_SESSION['user_name'] ?? 'Profilo') : null;

// Helper: link di login con redirect
function link_or_login($target) {
  return "login.php?redirect=" . urlencode($target);
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="utf-8">
  <title>Gestione Attrezzature</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Sistema di prenotazione e catalogo attrezzature della Scuola d’Arte Applicata Andrea Fantoni.">
  <meta name="theme-color" content="#0f172a">

  <style>
    :root {
      --bg: #0f172a;          /* slate-900 */
      --text: #e2e8f0;        /* slate-200 */
      --muted: #94a3b8;       /* slate-400 */
      --primary: #2563eb;     /* blue-600 */
      --primary-hover: #1e40af;
      --secondary: #10b981;   /* emerald-500 */
      --card: #111827;        /* gray-900 */
      --border: #1f2937;      /* gray-800 */
      --focus: #f59e0b;       /* amber-500 */
    }
    * { box-sizing: border-box }
    html, body { height: 100% }
    body {
      margin: 0; background: var(--bg); color: var(--text);
      font-family: system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif;
      font-size: 16px; line-height: 1.5;
    }
    a { color: inherit; text-decoration: none }
    .container { width: 100%; max-width: 1100px; margin: 0 auto; padding: 0 16px; }

    header {
      border-bottom: 1px solid var(--border);
      background: rgba(17,24,39,.7);
      backdrop-filter: saturate(180%) blur(4px);
      position: sticky; top: 0; z-index: 50;
    }
    .nav { display: flex; align-items: center; justify-content: space-between; padding: 14px 0; gap: 12px; }
    .brand { display: flex; align-items: center; gap: 10px; font-weight: 700; letter-spacing: .2px }
    .brand-logo { width: 28px; height: 28px; border-radius: 6px; background: linear-gradient(135deg, var(--primary), #06b6d4) }
    .nav-left { display: flex; align-items: center; gap: 20px }
    .nav-links { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
    .nav-links a { padding: 8px 10px; border-radius: 8px; transition: .2s; }
    .nav-links a:hover { background: var(--border); }

    .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 10px 16px; border-radius: 10px; font-weight: 600; border: 1px solid transparent; transition: .2s }
    .btn-primary { background: var(--primary); color: #fff; }
    .btn-primary:hover { background: var(--primary-hover) }
    .btn-secondary { background: transparent; border-color: var(--border); color: var(--text) }
    .btn-secondary:hover { background: var(--border) }
    .cta-group { display: flex; gap: 12px; flex-wrap: wrap; justify-content: center; }

    .hero { padding: 56px 0 32px; text-align: center }
    .hero h1 { font-size: clamp(28px, 4vw, 40px); margin: 0 0 8px }
    .hero p { color: var(--muted); margin: 0 0 20px }
    .note { color: var(--muted); font-size: .95rem }

    .cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin: 32px 0 60px }
    @media (max-width: 900px){ .cards { grid-template-columns: 1fr 1fr } }
    @media (max-width: 640px){ .cards { grid-template-columns: 1fr } }
    .card { background: var(--card); border: 1px solid var(--border); border-radius: 16px; padding: 18px; display: flex; flex-direction: column; gap: 10px; min-height: 180px; }
    .card h3 { margin: 0; font-size: 1.2rem }
    .card p { margin: 0; color: var(--muted) }
    .card .card-actions { margin-top: auto }

    footer { border-top: 1px solid var(--border); padding: 20px 0; color: var(--muted); font-size: .95rem; text-align: center }

    .btn:focus, .nav-links a:focus, .card a:focus { outline: 2px dashed var(--focus); outline-offset: 2px }
  </style>
</head>
<body>

  <!-- Header -->
  <header>
    <div class="container nav">
      <div class="nav-left">
        <div class="brand" aria-label="Scuola d’Arte Applicata Andrea Fantoni - Gestione Attrezzature">
          <div class="brand-logo" aria-hidden="true"></div>
          <span>Gestione Attrezzature</span>
        </div>
        <nav class="nav-links" aria-label="Navigazione principale">
          <?php if ($isLoggedIn) { ?>
            <a href="catalogo.php">Catalogo</a>
            <a href="prenotazioni.php">Prenotazioni</a>
          <?php } else { ?>
            <a href="<?php echo htmlspecialchars(link_or_login('catalogo.php'), ENT_QUOTES, 'UTF-8'); ?>" title="Accedi per aprire il catalogo">Catalogo</a>
            <a href="<?php echo htmlspecialchars(link_or_login('prenotazioni.php'), ENT_QUOTES, 'UTF-8'); ?>" title="Accedi per prenotare">Prenotazioni</a>
          <?php } ?>
        </nav>
      </div>

      <div class="nav-links">
        <?php if ($isLoggedIn) { ?>
          <span aria-label="Utente loggato">👤 <?php echo htmlspecialchars($userName, ENT_QUOTES, 'UTF-8'); ?></span>
          <a href="logout.php">Esci</a>
        <?php } else { ?>
          <a href="register.php">Registrati</a>
          <a href="login.php">Accedi</a>
        <?php } ?>
      </div>
    </div>
  </header>

  <!-- Hero -->
  <section class="hero container">
    <h1>Prenotazioni & Catalogo attrezzature</h1>
    <p>Consulta il catalogo, verifica disponibilità e prenota le attrezzature della scuola.</p>

    <?php if ($isLoggedIn) { ?>
      <div class="cta-group" role="group" aria-label="Azioni principali">
        <a class="btn btn-primary" href="catalogo.php">Vai al Catalogo</a>
        <a class="btn btn-secondary" href="prenotazioni.php">Prenotazioni</a>
      </div>
    <?php } else { ?>
      <div class="cta-group" role="group" aria-label="Azioni principali">
        <a class="btn btn-primary" href="<?php echo htmlspecialchars(link_or_login('login.php'), ENT_QUOTES, 'UTF-8'); ?>">Accedi</a>
        <a class="btn btn-secondary" href="register.php">Registrati</a>
      </div>
      <p class="note" role="note">Per accedere a Catalogo e Prenotazioni è necessario effettuare il login.</p>
    <?php } ?>
  </section>

  <!-- Cards -->
  <section class="container cards" aria-label="Funzionalità principali">
    <article class="card">
      <h3>Catalogo</h3>
      <p>Consulta l’elenco delle attrezzature, schede e disponibilità.</p>
      <div class="card-actions">
        <?php if ($isLoggedIn) { ?>
          <a class="btn btn-primary" href="catalogo.php">Apri Catalogo</a>
        <?php } else { ?>
          <a class="btn btn-secondary" href="<?php echo htmlspecialchars(link_or_login('catalogo.php'), ENT_QUOTES, 'UTF-8'); ?>" title="Accedi per aprire il catalogo">Accedi per il Catalogo</a>
        <?php } ?>
      </div>
    </article>

    <article class="card">
      <h3>Prenotazioni</h3>
      <p>Richiedi e gestisci le prenotazioni delle attrezzature.</p>
      <div class="card-actions">
        <?php if ($isLoggedIn) { ?>
          <a class="btn btn-primary" href="prenotazioni.php">Apri Prenotazioni</a>
        <?php } else { ?>
          <a class="btn btn-secondary" href="<?php echo htmlspecialchars(link_or_login('prenotazioni.php'), ENT_QUOTES, 'UTF-8'); ?>" title="Accedi per prenotare">Accedi per le Prenotazioni</a>
        <?php } ?>
      </div>
    </article>

    <article class="card">
      <h3>Guida</h3>
      <p>Come funziona il sistema e regole d’uso delle attrezzature.</p>
      <div class="card-actions">
        <a class="btn btn-secondary" href="guida.php">Leggi la Guida</a>
      </div>
    </article>
  </section>

  <!-- Footer -->
  <footer>
    <div class="container">
      © Scuola d’Arte Applicata Andrea Fantoni —
      Supporto: <a href="mailto:servizi.google@scuolafantoni.it">servizi.google@scuolafantoni.it</a>
    </div>
  </footer>

</body>
</html>
