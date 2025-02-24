<?php
require_once __DIR__ . "/../configs.php";
require_once __DIR__ . "../../src/dao/user-dao.php";
require_once __DIR__ . "/../database.php";

$database = Database::getInstance();
$dao = new UserDao($database);

if (!isset($_GET["id"])) {
    die("Parâmetro id não informado");
}

$id = $_GET["id"];
$user = $dao->getUserById($id);

if (!$user) {
    die("Usuário não encontrado");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Perfil</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/profile-update.css">
</head>

<body>
    <div class="form-container">
        <h1>Atualize seu perfil</h1>
        <form method="POST" action="<?= BASE_URL ?>src/controllers/users/update-user.php?id=<?= $id ?>" class="form-group" enctype="multipart/form-data">
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
            <div class="form-control">
                <label for="profile_pic">Foto de Perfil: </label>
                <input type="file" name="profile_pic" id="profile_pic" accept="image/*" />
            </div>
            <div class="btn-wrapper">
                <button class="btn" name="edit">Confirmar</button>
            </div>
            <div class="btn-wrapper">
                <button class="btn btn-danger" name="delete" onclick="return confirm('Tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita.');">
                    Deletar Usuário
                </button>
            </div>
        </form>
    </div>
</body>

</html>