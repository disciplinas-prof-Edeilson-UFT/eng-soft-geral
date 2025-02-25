<?php
require_once __DIR__ . "/../dir-config.php";
require_once __DIR__ . '/../src/controllers/users/profile-user.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/profile.css">
</head>

<body>
    <?php include __DIR__ . '/components/side-bar.php'; ?>

    <main class="profile-container">
        <!-- Seção de informações do usuário -->
        <section class="info-section">
            <div class="photo-container">
                
                <img src="<?= $profilePhoto; ?>" alt="Foto de Perfil" class="profile-picture">

                <!-- Botão de edição de perfil (apenas para o próprio usuário) -->
                <?php if ((int)$user_id === (int)$logged_in_user_id): ?>
                    <button class="btn-edit">
                        <a href="<?= BASE_URL ?>view/profile-update.php?id=<?= $user_id; ?>">Editar Perfil</a>
                    </button>
                <?php endif; ?>
            </div>

            <div class="user-info">
                <h1 class="user-name"><?php echo $userName; ?></h1>
                <div class="stats-container">
                    <span class="following"><?= $following; ?> seguindo</span>
                    <span class="followers"><?= $followers; ?> seguidores</span>
                </div>

                <!-- Formulário para seguir/deixar de seguir (apenas para outros usuários) -->
                <?php if ((int)$user_id !== (int)$logged_in_user_id): ?>
                    <form method="POST">
                        <input type="hidden" name="acao" value="<?= $isFollowing ? 'parar_de_seguir' : 'seguir' ?>">
                        <button type="submit" class="btn-follow">
                            <?= $isFollowing ? 'Deixar de seguir' : 'Seguir' ?>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </section>

        <!-- Seção da foto do feed -->
        <section class="info-section">
            <div class="feed-photo-container">
                <?php if ($userPosts): ?>
                    <?php
                    if (isset($userPosts[0]['photo_url'])) {
                        $relativePath = BASE_URL . "uploads/feed/" . htmlspecialchars($userPosts[0]['photo_url']);
                        ?>
                        <img src="<?= $relativePath; ?>" alt="Imagem do Feed" class="feed-image">
                    <?php } ?>
                <?php endif; ?>
            </div>

            <!-- Formulário de upload de foto (apenas para o próprio usuário e se não houver foto) -->
            <?php if (!$profilePhoto && $user_id == $_SESSION['user_id']): ?>
                <div class="pai-do-upload-container">
                    <div class="upload-container">
                        <form action="<?= BASE_URL ?>src/controllers/posts/upload-feed-photo.php" method="POST" enctype="multipart/form-data">
                            <label for="photo">
                                <img src="<?= BASE_URL ?>public/img/add-photo.svg" class="icon"> <br>
                                Adicionar foto
                            </label>
                            <input type="file" id="photo" name="photo" accept="image/*">
                            <button type="submit" class="btn-upload">Enviar</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>

</html>