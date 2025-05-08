<?php 
namespace Conex\T07Composer\controllers\site;

use Conex\T07Composer\Database;
use Conex\T07Composer\dao\PostDAO;

class FeedController{
    private PostDAO $postDAO;

    public function __construct(){
        $database = Database::getInstance();
        $this->postDAO = new PostDao($database);
    }

    public function show(){
        $posts = $this->postDAO->getAllPosts();
        require_once __DIR__ . '/../../../view/feed.php';
    }

}