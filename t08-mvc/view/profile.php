<?php 
require_once __DIR__ . "/../dirconfig.php";
?>

<div class="profile-container">
    <!-- Seção de informações do usuário -->
    <section class="profile-header">
        <div class="photo-container">
            <img src="<?= $profilePhoto ?>" 
                 alt="Foto de Perfil de <?= $username ?>" 
                 class="profile-picture">

            <!-- Botão de edição de perfil (apenas para o próprio usuário) -->
            <?php if ($isOwnProfile): ?>
                <a href="/profile/<?= $user_id; ?>/edit" class="btn-edit">
                    Editar Perfil
                </a>
            <?php endif; ?>
        </div>
                
        <div class="user-info">
            <h1 class="user-name"><?= $username ?></h1>
            <p class="user-bio"><?= $bio ?></p>
            
            <div class="stats-container">
                <span class="following"><?= $followingCount ?> seguindo</span>
                <span class="followers"><?= $followersCount ?> seguidores</span>
            </div>
                
            <!-- Formulário para seguir/deixar de seguir (apenas para outros usuários) -->
            <?php if (!$isOwnProfile): ?>
                <form method="POST" action="/profile/<?= $user_id ?>/follow" class="follow-form">
                    <input type="hidden" name="action" value="<?= $followAction ?>">
                    <button type="submit" class="btn-follow"><?= $followButtonText ?></button>
                </form>
            <?php endif; ?>
        </div>
    </section>

    <!-- Seção de upload (apenas para o próprio usuário) -->
    <?php if ($isOwnProfile): ?>
        <section class="upload-section">
            <div class="upload-container">
                <form action="/feed/<?= $user_id ?>/store" method="POST" enctype="multipart/form-data" class="upload-form">
                    <div class="upload-area">
                        <label for="photo" class="upload-label">
                            <img src="/public/img/add-photo.svg" class="upload-icon" alt="Adicionar foto">
                            <span><?= $uploadText ?></span>
                        </label>
                        <input type="file" id="photo" name="file" accept="image/*" required>
                    </div>
                    
                    <div class="description-area">
                        <textarea name="description" placeholder="Descrição (opcional)..." maxlength="500" rows="3"></textarea>
                    </div>
                    
                    <button type="submit" class="btn-upload">Publicar</button>
                </form>
            </div>
        </section>
    <?php endif; ?>

    <!-- Seção de posts (todos os posts do user) -->
    <section class="posts-section">
        <?php if ($hasUserPosts): ?>
            <div class="posts-header">
                <h2>Posts de <?= $username ?></h2>
                <span class="posts-count"><?= $postsCountText ?></span>
            </div>
            
            <div class="posts-grid">
                <?php foreach ($userPosts as $post): ?>
                    <article class="post-item">
                        <div class="post-image-container">
                            <?php if ($post['hasImage']): ?>
                                <img src="/public/uploads/feed/<?= $post['photoUrl'] ?>" 
                                     alt="Post de <?= $username ?>" 
                                     class="post-image">
                            <?php else: ?>
                                <div class="post-no-image">
                                    <img src="/public/img/add-photo.svg" alt="Sem imagem">
                                </div>
                            <?php endif; ?>
                            <div class="post-overlay">
                                <div class="post-info">
                                    <?php if ($post['description']): ?>
                                        <p class="post-description"><?= $post['description'] ?></p>
                                    <?php endif; ?>
                                    <time class="post-date" datetime="<?= $post['uploadDate'] ?>">
                                        <?= $post['formattedDate'] ?>
                                    </time>
                                </div>
                            </div>
                        </div>
                        <?php if ($isOwnProfile): ?>
                            <form method="POST" action="/feed/<?= $post['id'] ?>/delete" class="delete-form" onsubmit="return confirm('Tem certeza que deseja deletar este post?')">
                                <button type="submit" class="btn-delete" title="Deletar post">x</button>
                            </form>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-posts">
                <div class="no-posts-content">
                    <img src="/public/img/add-photo.svg" alt="Sem posts" class="no-posts-icon">
                    <h3><?= $noPostsTitle ?></h3>
                    <p><?= $noPostsMessage ?></p>
                    
                    <?php if ($isOwnProfile): ?>
                        <p class="upload-hint">Use o formulário acima para fazer seu primeiro post.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </section>
</div>