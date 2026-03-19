<?php 
namespace Conex\T07Composer\controllers\site;

use Conex\T07Composer\Database;
use Conex\T07Composer\dao\PostDAO;
use Conex\T07Composer\dao\UserDAO;
use Conex\T07Composer\utils\UploadHandler;

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


    public function uploadPost() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "login");
            exit;
        }
        
        $user_id = $_SESSION['user_id'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
            $uploadDir = __DIR__ . "/../../../uploads/feed/";
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            
            $dbUpdateCallback = function ($photoUrl) use ($user_id) {
                return $this->postDAO->createPost($user_id, $photoUrl);
            };
            
            $uploadResult = UploadHandler::handleUpload($_FILES['photo'], $uploadDir, $allowedTypes, $dbUpdateCallback);
            
            if ($uploadResult['success']) {
                $_SESSION['success'] = "Foto do feed adicionada com sucesso!";
            } else {
                $_SESSION['error'] = $uploadResult['error'];
            }
            
            header("Location: " . BASE_URL . "profile?id=" . $user_id);
            exit;
        }
    }
}