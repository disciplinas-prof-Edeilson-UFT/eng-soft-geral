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
        $posts = $this->feedService->getUsersPostsFeed($user_id);
        
        $this->view('feed', ['posts' => $posts]);
    }

}