<?php 
namespace src\controllers\site;

use src\controllers\BaseController;
use src\services\FeedService;
use src\database\dao\IPostDAO;
use src\database\domain\Post;
use core\mvc\IModelRepository;
use src\database\dao\PostDAO;
use core\mvc\ModelRepository;
use src\services\ProfileService;
use src\database\domain\User;

require_once __DIR__ . '/../../database/dao/post-dao.php';
require_once __DIR__ . '/../../database/domain/post.php';
require_once __DIR__ . '/../../../core/mvc/model-repository.php';
require_once __DIR__ . '/../../services/feed-service.php';
require_once __DIR__ . '/../base-controller.php';
require_once __DIR__ . '/../../services/profile-service.php';
require_once __DIR__ . '/../../database/domain/user.php';

class FeedController extends BaseController{
    public $feedService;
    public IPostDAO $postDAO;
    public Post $post;
    public IModelRepository $IModelRepo;
    public ProfileService $profileService;
    public User $user;

    public function __construct() {
        $this->IModelRepo = new ModelRepository();
        $this->post = new Post(null, null, null, null, null, null, null);
        $this->postDAO = new PostDAO($this->IModelRepo);
        $this->feedService = new FeedService($this->postDAO, $this->post);
        $this->user = new User(null, null, null, null, null, null);
        $this->profileService = new ProfileService($this->postDAO, $this->post, $this->user);
    }

    public function show() {
        $posts = [];
        try {
            $posts = $this->feedService->getAllPostsFeed();
            $this->view('feed', ['posts' => $posts]);
        } catch (\InvalidArgumentException $e) {
            $this->view('feed', ['posts' => $posts, 'error' => $e->getMessage()]);
            exit;
        }
    }


    public function store($user_id) {
        try {
            if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
                $file = $_FILES['file'];
                $description = $_POST['description'] ?? null;
                
                error_log("Recebendo upload para usuário $user_id: " . print_r($file, true));
                
                $this->feedService->createPost($user_id, $file, $description);
                
                $this->redirect('/profile/' . $user_id . '?success=Imagem enviada com sucesso');
            } else {
                error_log("Erro no upload: " . ($_FILES['file']['error'] ?? 'Arquivo não enviado'));
                $this->redirect('/profile/' . $user_id . '?error=Falha no upload da imagem');
            }
        } catch (\Exception $e) {
            error_log("Exceção no upload: " . $e->getMessage());
            $this->redirect('/profile/' . $user_id . '?error=' . urlencode($e->getMessage()));
        }
    }

}