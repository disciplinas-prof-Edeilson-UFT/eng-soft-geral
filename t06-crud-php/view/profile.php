<?php
// Inicia a sessão
session_start();

// Inclui o arquivo de configuração e o controller
require_once __DIR__ . "/../configs.php"; 
require '../src/controllers/profile.php';  
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/profile.css"> <!-- Usa BASE_URL -->
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <main class="profile-container">
        <!-- Seção de informações do usuário -->
        <section class="info-section">
            <div class="photo-container">
                <img src="<?= $profilePhoto; ?>" alt="Foto de Perfil" class="profile-picture">

                <!-- Botão de edição de perfil (apenas para o próprio usuário) -->
                <?php if ((int)$user_id === (int)$logged_in_user_id): ?>
                    <button class="btn-edit">
                        <a href="<?= BASE_URL ?>view/profile-update.php?id=<?= $userId; ?>">Editar Perfil</a>
                    </button>
                <?php endif; ?>
            </div>

            <div class="user-info">
                <h1 class="user-name"><?= $userName; ?></h1>
                <div class="stats-container">
                    <span class="following"><?= $seguindo; ?> seguindo</span>
                    <span class="followers"><?= $seguidores; ?> seguidores</span>
                </div>

                <!-- Formulário para seguir/deixar de seguir (apenas para outros usuários) -->
                <?php if ((int)$user_id !== (int)$logged_in_user_id): ?>
                    <form method="POST">
                        <input type="hidden" name="acao" value="<?= $estaSeguindo ? 'parar_de_seguir' : 'seguir' ?>">
                        <button type="submit" class="btn-follow">
                            <?= $estaSeguindo ? 'Deixar de seguir' : 'Seguir' ?>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </section>

        <!-- Seção da foto do feed -->
        <section class="info-section">
            <div class="feed-photo-container">
                <?php if ($userPhoto): ?>
                    <?php
                    $relativePath = BASE_URL . "src/uploads/" . htmlspecialchars($userPhoto); // Usa BASE_URL
                    ?>
                    <img src="<?= $relativePath; ?>" alt="Imagem do Feed" class="feed-image">
                <?php endif; ?>
            </div>

            <!-- Formulário de upload de foto (apenas para o próprio usuário e se não houver foto) -->
            <?php if (!$userPhoto && $user_id == $_SESSION['user_id']): ?>
                <div class="pai-do-upload-container">
                    <div class="upload-container">
                        <form action="<?= BASE_URL ?>src/controllers/upload-photo-feed.php" method="POST" enctype="multipart/form-data">
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