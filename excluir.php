<?php
require_once 'config.php';

$id = (int)($_GET['id'] ?? 0);

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM clientes WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

header('Location: index.php');
exit;
