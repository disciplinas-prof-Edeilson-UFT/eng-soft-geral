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
            <?php if ($posts): ?>
                <!-- Itera sobre cada post -->
                <?php foreach ($posts as $post): ?>
                    <article class="post">
                        <!-- Cabeçalho do Post: Informações do Usuário -->
                        <header class="user-info">
                            <!-- Container da Foto de Perfil -->
                            <div class="avatar" aria-label="Foto do Usuário">
                                <?php
                                $profilePhoto = !empty($post['profile_pic_url'])
                                    ? '/public/uploads/avatars/' . htmlspecialchars($post['profile_pic_url'])
                                    : '/public/img/profile.svg';
                                ?>
                                <img src="<?= $profilePhoto; ?>" alt="Foto de Perfil de <?= htmlspecialchars($post['username'] ?? '') ?>" class="profile-picture">
                            </div>

                            <a href="/profile/<?= htmlspecialchars($post['user_id'] ?? '') ?>" class="username">
                                <?= htmlspecialchars($post['username'] ?? 'Usuário') ?>
                            </a>
                        </header>

                        <div class="image-container" aria-label="Imagem do Post">
                            <?php if (!empty($post['photo_url'])): ?>
                                <img src="/public/uploads/feed/<?= htmlspecialchars($post['photo_url']) ?>" 
                                     alt="Post de <?= htmlspecialchars($post['username'] ?? '') ?>"
                                     class="post-image">
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($post['description'])): ?>
                            <div class="post-description">
                                <p><?= htmlspecialchars($post['description']) ?></p>
                            </div>
                        <?php endif; ?>

                        <div class="post-meta">
                            <time datetime="<?= $post['upload_date'] ?? '' ?>">
                                Postado em: <?= isset($post['upload_date']) ? date('d/m/Y H:i', strtotime($post['upload_date'])) : '' ?>
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
                        <a href="/profile/<?= $loggedInUserID ?? '' ?>" class="btn-create-post">
                            Criar primeiro post
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
</div>