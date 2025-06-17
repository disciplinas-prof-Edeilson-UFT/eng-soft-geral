<?php 

namespace src\services;

use src\database\dao\PostDAO;
use src\database\domain\Post;
use src\services\UploadImageService;

class FeedService{
    public PostDAO $postDAO;
    public UploadImageService $uploadImageService;

    public function __construct(PostDAO $postDAO)
    {
        $this->postDAO = $postDAO;
        $this->uploadImageService = new UploadImageService();
    }

    public function getAllPostsFeed(): array {
        return $this->postDAO->getAllPosts();
    }

    public function createPost($userID,  $file, $description = null){
        $allowTypes= array( 'png', 'jpeg', 'gif');
        $uploadDir= '/uploads/feed/';
        $createdFileUrl= $this->uploadImageService->handleUpload($file, $uploadDir, $allowTypes);

        if (!$createdFileUrl['success']) {
            throw new \InvalidArgumentException(isset($createdFileUrl['error']) ? $createdFileUrl['error'] : "Erro ao fazer upload do post");
        }

        $fileUrl= $createdFileUrl['file_name'];
        $post= new Post($userID, $fileUrl, $description);

        if (!$this->postDAO->insertPost($post)) {
            throw new \InvalidArgumentException("Erro ao salvar post no banco ");
        }

        $createdPost= $this->postDAO->getPostByUserAndPhoto($userID, $fileUrl);
        //echo var_dump($createdPost);

        if(empty($createdPost)){
            throw new \InvalidArgumentException("Erro ao recuperar post");
        }

        return $createdPost;
    }
}