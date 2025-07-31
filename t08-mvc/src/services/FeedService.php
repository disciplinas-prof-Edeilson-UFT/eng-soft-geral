<?php 

namespace src\services;

use src\database\dao\PostDAO;
use src\database\domain\Post;
use src\services\UploadImageService;

class FeedService{
    private PostDAO $postDAO;

    public function __construct(PostDAO $postDAO)
    {
        $this->postDAO = $postDAO;
    }

    public function getAllPostsFeed(): array {
        return $this->postDAO->getAllPosts();
    }

    public function createPost($userId,  $file, $description = null){
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $uploadResult = UploadImageService::handleUpload($file, 'feed', $allowedTypes);

        if (!$uploadResult['success']) {
            throw new \InvalidArgumentException($uploadResult['error'] ?? "Erro ao fazer upload do post");
        }

        $fileUrl= $uploadResult['file_name'];
        $post= new Post($userId, $fileUrl, $description);

        if (!$this->postDAO->insertPost($post)) {
            if (file_exists($uploadResult['file_path'])) {
                unlink($uploadResult['file_path']);
            }
            throw new \InvalidArgumentException("Erro ao salvar post no banco ");
        }

        $createdPost= $this->postDAO->getPostByUserAndPhoto($userId, $fileUrl);
        //echo var_dump($createdPost);
        if(empty($createdPost)){
            throw new \InvalidArgumentException("Erro ao recuperar post");
        }

        return $createdPost;
    }
}