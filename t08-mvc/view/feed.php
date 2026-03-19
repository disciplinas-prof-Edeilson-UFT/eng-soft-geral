<?php 
require_once __DIR__ . "/../dirconfig.php";
?>

<div class="feed-container">
    <header class="feed-header">
        <h1>Feed</h1>
        <p>Veja as últimas postagens</p>
    </header>

    <main class="feed-content">
        <section class="feed">
            <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $post): ?>
                    <article class="post">
                        <!-- Cabeçalho do Post: Informações do Usuário -->
                        <header class="user-info">
                            <div class="avatar" aria-label="Foto do Usuário">
                                <img src="<?= $post['profilePhoto'] ?>" 
                                     alt="Foto de Perfil de <?= $post['username'] ?>" 
                                     class="profile-picture">
                            </div>

                            <a href="<?= $post['profileUrl'] ?>" class="username">
                                <?= $post['username'] ?>
                            </a>
                        </header>

                        <?php if ($post['hasImage']): ?>
                            <div class="image-container" aria-label="Imagem do Post">
                                <img src="<?= $post['postImageUrl'] ?>" 
                                     alt="Post de <?= $post['username'] ?>"
                                     class="post-image">
                            </div>
                        <?php endif; ?>

                        <?php if ($post['hasDescription']): ?>
                            <div class="post-description">
                                <p><?= $post['description'] ?></p>
                            </div>
                        <?php endif; ?>

                        <div class="post-meta">
                            <time datetime="<?= $post['uploadDate'] ?>">
                                <?= $post['formattedDate'] ?>
                            </time>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-posts">
                    <img src="/public/img/no-posts.svg" alt="Sem posts" class="no-posts-icon">
                    <h3>Nenhum post encontrado</h3>
                    <p>Seja o primeiro a compartilhar algo!</p>
                    <?php if ($isAuthenticated ?? false): ?>
                        <a href="/profile/<?= $loggedUserId ?? '' ?>" class="btn-create-post">
                            Criar primeiro post
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
</div>