<?php 
require_once __DIR__ . "/../dir-config.php";
//echo "userID = " . $user_id;
//echo "logged in user ID = " . $logged_in_user_id;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="/css/profile.css">
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

    <main class="profile-container">
        <!-- Seção de informações do usuário -->
        <section class="info-section">
            <div class="photo-container">
                <?php if ($user->getProfilePicUrl()): ?>
                    <img src="<?= htmlspecialchars($user->getProfilePicUrl()) ?>" alt="Foto de Perfil" class="profile-picture">
                <?php else: ?>
                    <img src="/img/profile.svg" alt="Foto de Perfil" class="profile-picture">
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
                <div class="stats-container">
                    <span class="following"><?= htmlspecialchars($user->getCountFollowers()) ?> seguindo</span>
                    <span class="followers"><?= htmlspecialchars($user->getCountFollowing()) ?> seguidores</span>
                </div>

                <!-- Formulário para seguir/deixar de seguir (apenas para outros usuários) -->
                <?php if ((int)$user_id !== (int)$logged_in_user_id): ?>
                    <form method="POST">
                        <input type="hidden" name="action" value="<?= $isFollowing ? 'unfollow' : 'follow' ?>">
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
                        $relativePath = "/uploads/feed/" . htmlspecialchars($userPosts[0]['photo_url']);
                        ?>
                        <img src="<?= $relativePath; ?>" alt="Imagem do Feed" class="feed-image">
                    <?php } ?>
                <?php endif; ?>
            </div>

            <!-- Formulário de upload de foto (apenas para o próprio usuário e se não houver foto) -->
            <?php if (!$profilePhoto && $user_id == $_SESSION['user_id']): ?>
                <div class="pai-do-upload-container">
                    <div class="upload-container">
                        <form action="/feed/<?= $user_id ?>" method="POST" enctype="multipart/form-data">
                            <label for="photo">
                                <img src="/img/add-photo.svg" class="icon"> <br>
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