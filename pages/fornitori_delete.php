<?php
session_start(); $required_role='IT';
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../config.php';
$id=(int)$_GET['id'];
$stmt=$pdo->prepare('DELETE FROM tbl_fornitori WHERE id_fornitore=?');
$stmt->execute([$id]);
header('Location: fornitori.php');
?>