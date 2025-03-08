<?php 

namespace src\services;

use src\database\dao\IPostDAO;
use src\database\domain\Post;
use src\services\UploadImageService;

require_once __DIR__ . '/../database/domain/post.php';
require_once __DIR__ . '/../database/dao/post-dao.php';
require_once __DIR__ . '/upload-image-service.php';

class FeedService{
    private $table = 'posts';
    public IPostDAO $postDAO;
    public Post $post;
    public UploadImageService $uploadImageService;

    public function __construct(IPostDAO $postDAO, Post $post)
    {
        $this->postDAO = $postDAO;
        $this->post = $post;
        $this->uploadImageService = new UploadImageService();
    }

    public function getAllPostsFeed(): array{
        $posts = $this->postDAO->getAllPosts();
        //echo var_dump($posts);
        return $posts;
    }

    public function createPost($user_id,  $file, $description = null)
    {
        $allowTypes = array( 'png', 'jpeg', 'gif');
        $createdFileUrl = $this->uploadImageService->handleUpload($file, '/feed', $allowTypes);

        if($createdFileUrl){
            throw new \InvalidArgumentException("Erro ao criar post");
        }

        $File_url = $createdFileUrl['file_name'];
        $post = new Post($user_id, $File_url, $description);
        
        $result = $this->postDAO->create('posts', $post);

        if (!$result) {
            throw new \InvalidArgumentException("Erro ao salvar post no banco ");
        }


        $createdPost= $this->postDAO->find('posts', ['user_id' => $user_id, 'photo_url' => $File_url ], ['upload_date', 'id']);

        if(empty($createdPost)){
            throw new \InvalidArgumentException("Erro ao recuperar post");
        }

        $post->setId($createdPost[0]->id);
        $post->setUploadDate($createdPost[0]->upload_date);
    }
}