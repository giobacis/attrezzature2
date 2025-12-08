<?php
session_start(); $required_role='IT';
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../config.php';
$descrizione=$_POST['descrizione'];
$note=$_POST['note'];
$stmt=$pdo->prepare('INSERT INTO tbl_categoria (descrizione, note, disponibile) VALUES (?, ?, 1)');
$stmt->execute([$descrizione, $note]);
header('Location: categorie.php');
?>