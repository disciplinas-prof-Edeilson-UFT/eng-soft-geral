<?php
session_start();
require_once __DIR__ . '/configs.php';
$userName = isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Visitante';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
    <link rel="stylesheet" href="../public/css/home.css">
</head>
<body>
    <?php  header("Location: " . BASE_URL . "view/login.php"); ?>
</body>
</html>