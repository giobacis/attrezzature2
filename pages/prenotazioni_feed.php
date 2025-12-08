<?php
session_start();
$required_role='USER';
include __DIR__.'/../includes/auth_check.php';
include __DIR__.'/../config.php';
header('Content-Type: application/json');
$sql="SELECT m.id_prodotto,m.data_uscita,m.data_prev_rientro,m.stato,h.descrizione FROM tbl_movmg m JOIN tbl_hardware h ON h.id=m.id_prodotto WHERE m.stato IN('approvato','in_attesa')";
$stmt=$pdo->query($sql);
$events=[];foreach($stmt as $r){$end=date('Y-m-d',strtotime($r['data_prev_rientro'].' +1 day'));$color=$r['stato']==='approvato'?'#28a745':'#ffc107';$events[]=['title'=>$r['descrizione'],'start'=>$r['data_uscita'],'end'=>$end,'allDay'=>true,'color'=>$color];}echo json_encode($events);?>