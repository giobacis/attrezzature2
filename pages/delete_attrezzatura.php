
<?php
session_start();
$required_role = 'IT';
include __DIR__ . '/../config.php';
$id = (int)($_GET['id'] ?? 0);
if ($id > 0) { $stmt = $pdo->prepare('DELETE FROM tbl_hardware WHERE id = ?'); $stmt->execute([$id]); }
header('Location: attrezzature.php');
