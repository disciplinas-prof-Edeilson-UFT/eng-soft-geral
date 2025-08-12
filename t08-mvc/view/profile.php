<?php 
require_once __DIR__ . "/../dirconfig.php";
?>

<div class="profile-container">
    <!-- Seção de informações do usuário -->
    <section class="profile-header">
        <div class="photo-container">
            <?php if ($user->getProfilePicUrl()): ?>
                <img src="/public/uploads/avatars/<?= htmlspecialchars($user->getProfilePicUrl()) ?>" 
                     alt="Foto de Perfil de <?= htmlspecialchars($user->getUsername()) ?>" 
                     class="profile-picture">
            <?php else: ?>
                <img src="/public/img/profile.svg" alt="Foto de Perfil Padrão" class="profile-picture">
            <?php endif; ?>

            <!-- Botão de edição de perfil (apenas para o próprio usuário) -->
            <?php if ((int)$user_id === (int)$logged_in_user_id): ?>
                <a href="/profile/<?= $user_id; ?>/edit" class="btn-edit">
                    Editar Perfil
                </a>
            <?php endif; ?>
        </div>
                
        <div class="user-info">
            <h1 class="user-name"><?= htmlspecialchars($user->getUsername()) ?></h1>
            <p class="user-bio"><?= htmlspecialchars($user->getBio() ?? 'Sem biografia') ?></p>
            
            <div class="stats-container">
                <span class="following">
                    <?= htmlspecialchars($user->getCountFollowing()) ?> seguindo
                </span>
                <span class="followers">
                    <?= htmlspecialchars($user->getCountFollowers()) ?> seguidores
                </span>
            </div>
                
            <!-- Formulário para seguir/deixar de seguir (apenas para outros usuários) -->
            <?php if ((int)$user_id !== (int)$logged_in_user_id): ?>
                <form method="POST" action="/profile/<?= $user_id ?>/follow" class="follow-form">
                    <input type="hidden" name="action" value="<?= $isFollowing ? 'unfollow' : 'follow' ?>">
                    <button type="submit" class="btn-follow">
                        <?= $isFollowing ? 'Deixar de seguir' : 'Seguir' ?>
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </section>

    <!-- Seção de upload (apenas para o próprio usuário) -->
    <?php if ((int)$user_id === (int)$logged_in_user_id): ?>
        <section class="upload-section">
            <div class="upload-container">
                <form action="/feed/<?= $user_id ?>/store" method="POST" enctype="multipart/form-data" class="upload-form">
                    <div class="upload-area">
                        <label for="photo" class="upload-label">
                            <img src="/public/img/add-photo.svg" class="upload-icon" alt="Adicionar foto">
                            <span><?= empty($userPosts) ? 'Adicionar primeira foto' : 'Adicionar nova foto' ?></span>
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
        <?php if (!empty($userPosts)): ?>
            <div class="posts-header">
                <h2>Posts de <?= htmlspecialchars($user->getUsername()) ?></h2>
                <span class="posts-count"><?= count($userPosts) ?> post<?= count($userPosts) !== 1 ? 's' : '' ?></span>
            </div>
            
            <div class="posts-grid">
                <?php foreach ($userPosts as $post): ?>
                    <article class="post-item">
                        <div class="post-image-container">
                            <?php 
                            $photoUrl = is_array($post) ? ($post['photo_url'] ?? null) : (method_exists($post, 'getPhotoUrl') ? $post->getPhotoUrl() : null);
                            if (!empty($photoUrl)): ?>
                                <img src="/public/uploads/feed/<?= htmlspecialchars($photoUrl) ?>" 
                                     alt="Post de <?= htmlspecialchars($user->getUsername()) ?>" 
                                     class="post-image">
                            <?php else: ?>
                                <div class="post-no-image">
                                    <img src="/public/img/add-photo.svg" alt="Sem imagem">
                                </div>
                            <?php endif; ?>
                            
                            <div class="post-overlay">
                                <div class="post-info">
                                    <?php 
                                    $description = is_array($post) ? ($post['description'] ?? null) : (method_exists($post, 'getDescription') ? $post->getDescription() : null);
                                    if (!empty($description)): ?>
                                        <p class="post-description"><?= htmlspecialchars($description) ?></p>
                                    <?php endif; ?>
                                    <?php $uploadDate = is_array($post) ? ($post['upload_date'] ?? $post['created_at'] ?? null) : (method_exists($post,'getUploadDate') ? $post->getUploadDate() : null); ?>
                                    <time class="post-date" datetime="<?= $uploadDate ?? '' ?>">
                                        <?= $uploadDate ? date('d/m/Y H:i', strtotime($uploadDate)) : '' ?>
                                    </time>
                                </div>
                            </div>
                        </div>

                        <?php if ((int)$user_id === (int)$logged_in_user_id): ?>
                            <?php $postId = is_array($post) ? ($post['id'] ?? '') : (method_exists($post,'getId') ? $post->getId() : ''); ?>
                            <form method="POST" action="/feed/<?= $postId ?>/delete" class="delete-form" onsubmit="return confirm('Tem certeza que deseja deletar este post?')">
                                <button type="submit" class="btn-delete" title="Deletar post">
                                    x
                                </button>
                            </form>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-posts">
                <div class="no-posts-content">
                    <img src="/public/img/add-photo.svg" alt="Sem posts" class="no-posts-icon">
                    <h3>
                        <?php if ((int)$user_id === (int)$logged_in_user_id): ?>
                            Você ainda não tem posts
                        <?php else: ?>
                            <?= htmlspecialchars($user->getUsername()) ?> ainda não tem posts
                        <?php endif; ?>
                    </h3>
                    <p>
                        <?php if ((int)$user_id === (int)$logged_in_user_id): ?>
                            Comece compartilhando sua primeira foto!
                        <?php else: ?>
                            Quando <?= htmlspecialchars($user->getUsername()) ?> compartilhar algo, aparecerá aqui.
                        <?php endif; ?>
                    </p>
                    
                    <?php if ((int)$user_id === (int)$logged_in_user_id): ?>
                        <p class="upload-hint">Use o formulário acima para fazer seu primeiro post.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </section>
</div>