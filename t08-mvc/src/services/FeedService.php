<?php
declare(strict_types=1);

namespace src\services;

use src\database\dao\PostDAO;
use src\database\domain\Post;
use src\viewmodels\PostFeedItem;
use src\database\mappers\PostMapper;


class FeedService
{
    private PostDAO $postDAO;
    private PostMapper $postMapper;

    public function __construct(PostDAO $postDAO)
    {
        $this->postDAO = $postDAO;
        $this->postMapper = new PostMapper();
    }

    public function getFeedData(): array {
        $rows = $this->postDAO->getAllPosts();
        $items = [];
        foreach ($rows as $r) {
            $post = $this->postMapper->mapToPost($r);
            $items[] = new PostFeedItem(
                $post,
                (string)($r['username'] ?? 'Usuário'),
                $r['profile_pic_url'] ?? null
            );
        }
        return $items;
    }

    public function createPost(int $userId, array $file, ?string $description = null): Post
    {
        if ($userId <= 0) {
            throw new \InvalidArgumentException('User invalido');
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
            throw new \RuntimeException('Erro ao salvar post');
        }

        $createdPost= $this->postDAO->getPostByUserAndPhoto($userId, $fileUrl);
        if (!$createdPost) {
            throw new \RuntimeException('Erro ao recuperar post criado');
        }
        return $createdPost;
    }

    public function deletePost(int $postId): bool
    {
        if ($postId <= 0) {
            throw new \InvalidArgumentException('Post invalido');
        }

        try {
            $this->postDAO->beginTransaction();
            $result = $this->postDAO->deletePost($postId);
            $this->postDAO->commit();

            return $result;
        } catch (\Throwable $e) {
            $this->postDAO->rollback();
            throw $e;
        }
    }
}