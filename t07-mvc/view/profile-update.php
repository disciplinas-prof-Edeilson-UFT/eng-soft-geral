<?php
require_once __DIR__ . "/../dir-config.php";
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Perfil</title>
    <link rel="stylesheet" href="/css/profile-update.css">
</head>

<body>
    <?php if(!empty($error)): ?>
        <script>
            alert("<?php echo htmlspecialchars($error); ?>");
        </script>
    <?php 
    elseif(!empty($errors) && is_array($errors)): ?>
        <script>
            alert("<?php foreach($errors as $err){ echo htmlspecialchars($err) . '\n'; } ?>");
        </script>
    <?php endif; ?>

    <div class="form-container">
        <h1>Atualize seu perfil</h1>
        <form method="POST" action="/profile/<?= $user_id ?>/edit" class="form-group" enctype="multipart/form-data">
            <div class="form-wrapper">
                <div class="form-control">
                    <label for="username">Nome: </label>
                    <input type="text" name="username" value="<?= htmlspecialchars($user->getUsername()) ?>" id="username"/>
                </div>
                <div class="form-control">
                    <label for="phone">Telefone: </label>
                    <input type="tel" name="phone" value="<?= htmlspecialchars($user->getPhone()) ?>" id="phone"/>
                </div>
                <div class="form-control">
                    <label for="email">Email: </label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user->getEmail()) ?>" id="email"/>
                </div>
                <div class="form-control">
                    <label for="bio">Bio: </label>
                    <input type="text" name="bio" value="<?= htmlspecialchars($user->getBio() ?? '') ?>" id="bio" />
                </div>
            </div>
            <div class="form-control">
                <label for="profile_pic">Foto de Perfil: </label>
                <input type="file" name="profile_pic_url" id="profile_pic_url" accept="image/*" />
            </div>
            <div class="btn-wrapper">
                <button class="btn" name="edit">Confirmar</button>
            </div>
            <div class="btn-wrapper">
                <button class="btn btn-danger" name="delete" onclick="return confirm('Tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita.');">
                    Deletar Usuário
                </button>
                <button class="btn btn-danger" name="logout" onclick="return confirm('Tem certeza que deseja sair?');">
                    Sair
                </button>
            </div>
        </form> 
    </div>
</body>

</html>