<?php 
require_once __DIR__ . "/../dirconfig.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="/../public/css/profile.css">
</head>

<body>
    <main class="profile-container">
        <!-- Seção de informações do usuário -->
        <section class="info-section">
            <div class="photo-container">
                <?php if ($user->getProfilePicUrl()): ?>
                    <img src="/public/uploads/avatars/<?= htmlspecialchars($user->getProfilePicUrl()) ?>" alt="Foto de Perfil" class="profile-picture">
                <?php else: ?>
                    <img src="/public/img/profile.svg" alt="Foto de Perfil" class="profile-picture">
                <?php endif; ?>

                <!-- Botão de edição de perfil (apenas para o próprio usuário) -->
                <?php if ((int)$user_id === (int)$logged_in_user_id): ?>
                    <button class="btn-edit">
                        <a href="/profile/<?= $logged_in_user_id; ?>/edit">Editar Perfil</a>
                    </button>
                <?php endif; ?>
            </div>
                    
            <div class="user-info">
                <h1 class="user-name"><?php echo htmlspecialchars($user->getUsername()) ?></h1>
                <p class="user-bio"><?php echo htmlspecialchars($user->getBio() ?? 'adicione uma bio') ?></p>
                <div class="stats-container">
                    <span class="following"><?= htmlspecialchars($user->getCountFollowing()) ?> seguindo</span>
                    <span class="followers"><?= htmlspecialchars($user->getCountFollowers()) ?> seguidores</span>
                </div>
                    
                <!-- Formulário para seguir/deixar de seguir (apenas para outros usuários) -->
                <?php if ((int)$user_id !== (int)$logged_in_user_id): ?>
                    <form method="POST" action="/profile/<?= $user_id ?>/follow">
                        <input type="hidden" name="action" value="<?= $isFollowing ? 'unfollow' : 'follow' ?>">
                        <button type="submit" class="btn-follow">
                            <?= $isFollowing ? 'Deixar de seguir' : 'Seguir' ?>
                        </button>
                    </form>
                <?php endif; ?>
            </div>

            <!-- exibir botão de postagem caso o usuario ja tenha postado algo -->
            <?php if (!empty($userPosts)): ?>
                <div class="add-more-posts-button">
                    <div class="upload-more-photos">
                        <div class="upload-container">
                            <form action="/feed/<?= $user_id ?>/store" method="POST" enctype="multipart/form-data">
                                <label for="photo">
                                    Adicionar foto 
                                </label>
                                <input type="file" id="photo" name="file" accept="image/*">
                                <button type="submit" class="btn-upload">Enviar</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </section>

        <!-- Seção da foto do feed -->
        <section class="info-section">
            <div class="feed-photo-container">
                <?php if ($userPosts): ?>
                    <?php
                    if (isset($userPosts[0]['photo_url'])) {
                        $relativePath = "/uploads/feed/" . htmlspecialchars($userPosts[0]['photo_url']);
                        ?>
                        <img src="/public/uploads/feed/<?= htmlspecialchars($userPosts[0]['photo_url']) ?>" alt="Post" class="feed-image">
                    <?php } ?>
                <?php endif; ?>
            </div>

            <!-- Formulário de upload de foto (apenas para o próprio usuário e se não houver foto) -->
            <?php if (empty($userPosts) && (int)$user_id === (int)$_SESSION['user_id']): ?>
                <div class="pai-do-upload-container">
                    <div class="upload-container">
                        <form action="/feed/<?= $user_id ?>/store" method="POST" enctype="multipart/form-data">
                            <label for="photo">
                                <img src="/public/img/add-photo.svg" class="icon"> <br>
                                Adicionar foto
                            </label>
                            <input type="file" id="photo" name="file" accept="image/*">
                            <button type="submit" class="btn-upload">Enviar</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

        </section>
    </main>
</body>

</html>