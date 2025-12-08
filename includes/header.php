
<?php
// includes/header.php
?><!doctype html>
<html lang="it">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gestione Attrezzature</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root{ --bg-sidebar:#111827; --fg:#e5e7eb; --accent:#0d6efd; }
    body{ background:#f7f7fb; }
    .app{ display:grid; grid-template-columns: 260px 1fr; min-height:100vh; }
    .app-sidebar{ background:var(--bg-sidebar); color:var(--fg); padding:1rem; }
    .app-sidebar a{ color:var(--fg); text-decoration:none; display:block; padding:.5rem 0; }
    .app-sidebar a:hover{ color:#fff; }
    .app-content{ background:#f7f7fb; }
    .table thead th{ background:#f0f2f6; }
    .badge.bg-secondary-subtle{ background:#eff2f6; color:#475569; }
    .badge.bg-info-subtle{ background:#e0f2fe; color:#0ea5e9; }
    /* DataTables toolbar on one row */
    .dt-toolbar{ display:flex; flex-wrap:wrap; gap:.5rem; align-items:center; justify-content:space-between; }
    .dt-toolbar .dt-buttons{ display:flex; gap:.5rem; flex-wrap:wrap; }
    .dt-toolbar .dataTables_filter{ margin:0; }
    .dt-buttons .btn{ color:#fff !important; }
  </style>
</head>
<body>
<div class="app">
