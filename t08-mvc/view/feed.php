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
                <?php foreach ($posts as $item): ?>
                    <?php 
                        $post = $item->getPost();
                        $username = htmlspecialchars($item->getUsername());
                        $profilePicUrl = $item->getProfilePicUrl();
                        $profilePhoto = $profilePicUrl 
                            ? '/public/uploads/avatars/' . htmlspecialchars($profilePicUrl)
                            : '/public/img/profile.svg';
                        $photoUrl = $post->getPhotoUrl();
                        $description = $post->getDescription();
                        $uploadDate = $post->getUploadDate();
                        $userId = $post->getUserId();
                    ?>
                    <article class="post">
                        <!-- Cabeçalho do Post: Informações do Usuário -->
                        <header class="user-info">
                            <!-- Container da Foto de Perfil -->
                            <div class="avatar" aria-label="Foto do Usuário">
                                <img src="<?= $profilePhoto; ?>" alt="Foto de Perfil de <?= $username ?>" class="profile-picture">
                            </div>

                            <a href="/profile/<?= htmlspecialchars((string)$userId) ?>" class="username">
                                <?= $username ?>
                            </a>
                        </header>

                        <div class="image-container" aria-label="Imagem do Post">
                            <?php if (!empty($photoUrl)): ?>
                                <img src="/public/uploads/feed/<?= htmlspecialchars($photoUrl) ?>" 
                                     alt="Post de <?= $username ?>"
                                     class="post-image">
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($description)): ?>
                            <div class="post-description">
                                <p><?= htmlspecialchars($description) ?></p>
                            </div>
                        <?php endif; ?>

                        <div class="post-meta">
                            <time datetime="<?= $uploadDate ?? '' ?>">
                                Publicado em: <?= $uploadDate ? date('d/m/Y H:i', strtotime($uploadDate)) : '' ?>
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