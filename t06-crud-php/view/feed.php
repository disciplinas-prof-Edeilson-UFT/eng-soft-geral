<?php
require_once __DIR__ . '/../dir-config.php';
require_once __DIR__ . '/../src/controllers/posts/get-all-posts.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/home.css">
</head>

<body class="home-page">
    <?php include __DIR__ . '/components/side-bar.php'; ?>

    <!-- Conteúdo Principal -->
    <main class="container">
        <!-- Seção de Feed de Posts -->
        <section class="feed">
            <?php if ($posts): ?>
                <!-- Itera sobre cada post -->
                <?php foreach ($posts as $post): ?>
    
                    <article class="post">
                        <!-- Cabeçalho do Post: Informações do Usuário -->
                        <header class="user-info">
                            <!-- Container da Foto de Perfil -->
                            <div class="avatar" aria-label="Foto do Usuário">
                                <?php
                                // Define a foto de perfil do usuário
                                $profilePhoto = !empty($post['profile_pic_url'])
                                    ? BASE_URL . 'uploads/' . htmlspecialchars($post['profile_pic_url'])
                                    : BASE_URL . 'public/img/profile.svg'; 
                                ?>
                                <img src="<?= $profilePhoto; ?>" alt="Foto de Perfil" class="profile-picture">
                            </div>

                            <!-- Link para o Perfil do Usuário -->
                            <a href="<?= BASE_URL ?>view/profile.php?id=<?= htmlspecialchars($post['user_id'] ?? '') ?>" class="username">
                                <?= htmlspecialchars($post['username'] ?? '') ?>
                            </a>
                        </header>

                        <!-- Container da Imagem do Post -->
                        <div class="image-placeholder" aria-label="Imagem do Post">
                            <?php if (!empty($post['photo_url'])): ?>
                                <!-- Exibe a imagem do post, se houver -->
                                <img src="<?= BASE_URL . 'uploads/feed/' . htmlspecialchars($post['photo_url']) ?>" alt="Imagem do post">
                            <?php endif; ?>
                        </div>
                    </article>

                    <!-- Divisor entre os posts -->
                    <hr class="divider">
                <?php endforeach; ?>
            <?php else: ?>
                <p>0 postagens no seu feed</p>
            <?php endif; ?>
        </section>
    </main>
</body>

</html>