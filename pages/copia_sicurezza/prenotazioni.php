<?php
session_start();
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
?>
<div class='col-md-10 p-4'>
<h2 class='mb-4'><i class='fas fa-calendar-alt'></i> Calendario Prenotazioni</h2>
<div id='calendar' class='border rounded p-3 bg-white shadow-sm'></div>
</div></div></div>
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/main.min.css' rel='stylesheet'>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/main.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var calendarEl=document.getElementById('calendar');
  var calendar=new FullCalendar.Calendar(calendarEl,{initialView:'dayGridMonth',events:'prenotazioni_feed.php'});
  calendar.render();
});
</script>
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>
</body></html>
