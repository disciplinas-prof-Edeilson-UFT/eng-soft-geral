<?php 
namespace src\controllers\site;

use src\controllers\BaseController;
use src\services\FeedService;
use src\database\dao\IPostDAO;
use src\database\domain\Post;
use core\mvc\IModelRepository;
use src\database\dao\PostDAO;
use core\mvc\ModelRepository;

require_once __DIR__ . '/../../database/dao/post-dao.php';
require_once __DIR__ . '/../../database/domain/post.php';
require_once __DIR__ . '/../../../core/mvc/model-repository.php';
require_once __DIR__ . '/../../services/feed-service.php';
require_once __DIR__ . '/../base-controller.php';

class FeedController extends BaseController{
    public $feedService;
    public IPostDAO $postDAO;
    public Post $post;
    public IModelRepository $IModelRepo;

    public function __construct() {
        $this->IModelRepo = new ModelRepository();
        $this->post = new Post(null, null, null, null, null, null, null);
        $this->postDAO = new PostDAO($this->IModelRepo);
        $this->feedService = new FeedService($this->postDAO, $this->post);
    }

    public function show($user_id) {
        $posts = [];
        try {
            $posts = $this->feedService->getAllPostsFeed($user_id);
        } catch (\InvalidArgumentException $e) {
            $this->view('feed', ['posts' => $posts, 'error' => $e->getMessage()]);
            exit;
        }
        
        $this->view('feed', ['posts' => $posts]);
    }


    public function store($user_id) {
        session_start();
        
        if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
            $file = $_FILES['file'];
            try {
                $this->feedService->createPost($user_id, $file, $description = null);
            } catch (\InvalidArgumentException $e) {
                $this->redirect('/feed/' . $user_id . '?error=' . $e->getMessage());
                exit;
            }
            $this->redirect('/feed/' . $user_id . '?success=Post created');
        } else {
            $this->redirect('/feed/' . $user_id . '?error=Nenhum arquivo foi enviado.');
        }
    }

}