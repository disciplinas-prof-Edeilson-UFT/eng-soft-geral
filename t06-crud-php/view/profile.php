<?php
session_start();
require '../src/controllers/profile.php';

if (!isset($userData) || !is_array($userData)) {
    die("Erro: Dados do usuário não foram carregados corretamente.");
}

extract($userData);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="../public/css/profile.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <main class="profile-container">
        <section class="info-section">
            <div class="photo-container">
                <img src="../public/img/profile-photo.svg" alt="Foto do Usuário" class="profile-photo">
                <button class="btn-edit">Editar Perfil</button>
            </div>
            <div class="user-info">
                <h1 class="user-name"><?= $userName; ?></h1>
                <div class="stats-container">
                    <span class="following">99 seguindo</span>
                    <span class="followers">99 seguidores</span>
                </div>

                <?php if ($user_id !== $logged_in_user_id): ?>
                    <form method="POST">
                        <input type="hidden" name="acao" value="<?= $estaSeguindo ? 'parar_de_seguir' : 'seguir' ?>">
                        <button type="submit" class="btn-follow">
                            <?= $estaSeguindo ? 'Deixar de seguir' : 'Seguir' ?>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </section>

        <section class="info-section">
            <div class="feed-photo-container">
                <?php if ($userPhoto): ?>
                    <?php
                    $relativePath = "/eng-soft-geral/t06-crud-php/src/uploads/" . htmlspecialchars($userPhoto);
                    ?>
                    <img src="<?= $relativePath; ?>" alt="Imagem do Feed" class="feed-image">
                <?php endif; ?>
            </div>

            <?php if (!$userPhoto && $user_id == $_SESSION['user_id']): ?>
                <div class="upload-container">
                    <form action="../src/controllers/upload-photo-feed.php" method="POST" enctype="multipart/form-data">
                        <label for="photo">
                            <img src="../public/img/add-photo.svg" class="icon"> <br>
                            Adicionar foto
                        </label>
                        <input type="file" id="photo" name="photo" accept="image/*">
                        <button type="submit" class="btn-upload">Enviar</button>
                    </form>
                </div>
            <?php endif; ?>
        </section>
    </main>
    <script src="../public/js/search.js"></script>
</body>
</html>
