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

    public function getAllPostsFeed(): array{
        $posts = $this->postDAO->getAllPosts();
        error_log(var_export($posts, true));
        return $posts;
    }

    public function createPost($user_id,  $file, $description = null)
    {
        $allowTypes = array( 'png', 'jpeg', 'gif');
        $uploadDir = '/uploads/feed/';
        $createdFileUrl = $this->uploadImageService->handleUpload($file, $uploadDir, $allowTypes);

        if (!$createdFileUrl['success']) {
            throw new \InvalidArgumentException(isset($createdFileUrl['error']) ? $createdFileUrl['error'] : "Erro ao fazer upload do post");
        }

        $File_url = $createdFileUrl['file_name'];
        $post = new Post($user_id, $File_url, $description);
        
        $result = $this->postDAO->create('posts', $post);

        if (!$result) {
            throw new \InvalidArgumentException("Erro ao salvar post no banco ");
        }


        $createdPost= $this->postDAO->find('posts', ['user_id' => $user_id, 'photo_url' => $File_url ], ['upload_date', 'id']);

        //echo var_dump($createdPost);

        if(empty($createdPost)){
            throw new \InvalidArgumentException("Erro ao recuperar post");
        }

        $post->setId($createdPost[0]->id);
        $post->setUploadDate($createdPost[0]->upload_date);
    }
}