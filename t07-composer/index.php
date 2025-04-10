<?php
session_start();
require_once __DIR__ . '/dir-config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "view/login.php");
    exit;
}

$userName = isset($_SESSION['user_name']);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
    <link rel="stylesheet" href="./public/css/feed.css">
</head>

<body>
    <?php require_once __DIR__ . '/view/feed.php'; ?>
</body>

</html>