<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Você precisa fazer login para acessar esta página!";
    header("Location: C:/xampp/htdocs/eng-soft-geral/t05-sessao/view/html/login.php");
    exit();
}

$userName = htmlspecialchars($_SESSION['user_name']);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="./view/css/dashboard.css">
</head>

<body>
    <div class="container">
        <h1>Bem-vindo, <?php echo $userName; ?>!</h1>
        <p>Você está logado com sucesso.</p>
    </div>
</body>

</html>