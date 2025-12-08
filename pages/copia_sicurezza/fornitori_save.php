<?php
session_start(); $required_role='IT';
include __DIR__ . '/../includes/auth_check.php';
include __DIR__ . '/../config.php';
$stmt=$pdo->prepare('INSERT INTO tbl_fornitori (ragione_sociale, telefono, mail) VALUES (?, ?, ?)');
$stmt->execute([$_POST['ragione_sociale'], $_POST['telefono'], $_POST['mail']]);
header('Location: fornitori.php');
?>