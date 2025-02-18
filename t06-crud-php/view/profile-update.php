<?php
require_once __DIR__ . "/../src/dao/user-dao.php";
require_once __DIR__ . "/../database.php";

$database = Database::getInstance();
$dao = new UserDao($database);

if (!isset($_GET["id"])) {
    die("Parametro id não informado");
}

$id = $_GET["id"];
$user = $dao->getUserById($id);

if (!$user) {
    die("usuário não encontrado");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar perfil</title>
    <link rel="stylesheet" href="../public/css/profile-update.css">
</head>
<body>
    <div class="form-container">
        <h1>Atualize seu perfil</h1>
        <form method="POST" action="/../src/controllers/users/update-user.php?id=<?= $id ?>" class="form-group">
            <div class="form-wrapper">
                <div class="form-control">
                    <label for="name">Nome: </label>
                    <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" id="name" required />
                </div>
                <div class="form-control">
                    <label for="phone">Telefone: </label>
                    <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" id="phone" required />
                </div>
                <div class="form-control">
                    <label for="email">Email: </label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" id="email" required />
                </div>
                <div class="form-control">
                    <label for="bio">Bio: </label>
                    <input type="text" name="bio" value="<?= htmlspecialchars($user['bio']) ?>" id="bio" required />
                </div>
            </div>
            <div class="btn-wrapper">
                <button class="btn" name="edit" >Confirmar</button>
                <span class="perfil-link"><a href="/view/profile.php" class="link">Volte ao perfil</a></span>
            </div>
        </form>
    </div>
</body>
</html>