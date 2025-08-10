<?php
declare(strict_types=1);

namespace src\services;

use src\database\dao\PostDAO;
use src\database\domain\Post;

/**
 * Service responsável pelas regras de criação e listagem de posts no feed
 */
class FeedService
{
    private PostDAO $postDAO;

    public function __construct(PostDAO $postDAO)
    {
        $this->postDAO = $postDAO;
    }

    public function getAllPostsFeed(): array {
        return $this->postDAO->getAllPosts();
    }

    /**
     * Cria um novo post: valida upload, persiste e retorna o Domain Post criado
     * @param array $file Estrutura do arquivo (como em $_FILES['...'])
     */
    public function createPost(int $userId, array $file, ?string $description = null): Post
    {
        if ($userId <= 0) {
            throw new \InvalidArgumentException('Usuário inválido');
        }
        if (!$file || !isset($file['tmp_name'])) {
            throw new \InvalidArgumentException('Arquivo inválido');
        }

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $uploadResult = UploadImageService::handleUpload($file, 'feed', $allowedTypes);
        if (!$uploadResult['success']) {
            throw new \InvalidArgumentException($uploadResult['error'] ?? 'Erro ao fazer upload');
        }

        $fileUrl= $uploadResult['file_name'];
        $post= new Post($userId, $fileUrl, $description);

        if (!$this->postDAO->insertPost($post)) {
            if (!empty($uploadResult['file_path']) && file_exists($uploadResult['file_path'])) {
                unlink($uploadResult['file_path']);
            }
            throw new \InvalidArgumentException('Erro ao salvar post');
        }

        $createdPost= $this->postDAO->getPostByUserAndPhoto($userId, $fileUrl);
        if (!$createdPost) {
            throw new \InvalidArgumentException('Erro ao recuperar post criado');
        }
        return $createdPost;
    }
}