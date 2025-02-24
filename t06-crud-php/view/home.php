<?php
require __DIR__ . '/../src/dao/fetch-posts.php';

// Busca os posts dos usuários que o usuário logado segue
$posts = fetchPosts();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
    <link rel="stylesheet" href="../public/css/home.css"> 
</head>

<body class="home-page">
    <!-- Inclui a barra lateral -->
    <?php include './sidebar.php'; ?>

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
                                $profilePhoto = !empty($post['profile_pic'])
                                    ? 'http://localhost/eng-soft-geral/t06-crud-php/src/uploads/' . htmlspecialchars($post['profile_pic'])
                                    : 'http://localhost/eng-soft-geral/t06-crud-php/public/img/default-avatar.png'; // Foto padrão
                                ?>
                                <img src="<?= $profilePhoto; ?>" alt="Foto de Perfil" class="profile-picture">
                            </div>

                            <!-- Link para o Perfil do Usuário -->
                            <a href="/eng-soft-geral/t06-crud-php/view/profile.php?id=<?= htmlspecialchars($post['id']) ?>" class="username">
                                <?= htmlspecialchars($post['name']) ?> <!-- Nome do Usuário -->
                            </a>
                        </header>

                        <!-- Container da Imagem do Post -->
                        <div class="image-placeholder" aria-label="Imagem do Post">
                            <?php if (!empty($post['photo_url'])): ?>
                                <!-- Exibe a imagem do post, se houver -->
                                <img src="<?= 'http://localhost/eng-soft-geral/t06-crud-php/src/uploads/' . htmlspecialchars($post['photo_url']) ?>" alt="Imagem do post">
                            <?php endif; ?>
                        </div>
                    </article>

                    <!-- Divisor entre os posts -->
                    <hr class="divider">
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhuma postagem disponível.</p>
            <?php endif; ?>
        </section>
    </main>
    <script src="../public/js/search.js"></script>
</body>

</html>