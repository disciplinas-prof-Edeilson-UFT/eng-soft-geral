<?php 

namespace src\services;

use src\database\dao\IPostDAO;
use src\database\domain\Post;
use src\database\domain\User;
use src\database\dao\IUserDAO;

require_once __DIR__ . '/../database/domain/post.php';
require_once __DIR__ . '/../database/dao/post-dao.php';


class FeedService{

    public IPostDAO $postDAO;
    public Post $post;


    public function __construct(IPostDAO $postDAO, Post $post)
    {
        $this->postDAO = $postDAO;
        $this->post = $post;
    }

    public function getUsersPostsFeed($user_id): array
    {
        return $this->postDAO->getPostsById($user_id);     
    }

    public function getAllPostsFeed(): array
    {
        if (empty($this->postDAO->getAllPosts())) {
            throw new \InvalidArgumentException("Não há posts para exibir");
        }

        return $this->postDAO->getAllPosts();
    }
}