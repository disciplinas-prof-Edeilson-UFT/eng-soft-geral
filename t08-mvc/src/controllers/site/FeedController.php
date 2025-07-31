<?php 
namespace src\controllers\site;

use src\controllers\BaseController;
use src\services\FeedService;
use src\database\dao\PostDAO;

class FeedController extends BaseController {
    private FeedService $feedService;

    public function __construct() {
        parent::__construct();
        $postDAO = new PostDAO();
        $this->feedService = new FeedService($postDAO); 
    }

    public function show() {
        try {
            $loggedUserId = $this->getSession('user_id');
            $posts= $this->feedService->getAllPostsFeed();
            $this->view('feed', ['posts' => $posts, 'pageTitle' => 'Feed', 'loggedUserId' => $loggedUserId, 'pageCSS' => 'feed']);
        } catch (\Exception $e) {
            $this->view('feed', ['posts' => [], 'error' => $e->getMessage(), 'pageTitle' => 'Feed - error', 'pageCSS' => 'feed']);
        }
    }

    public function store($user_id) {
        try {
            if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
                $file = $_FILES['file'];
                $description = $_POST['description'] ?? null;
                
                $this->feedService->createPost($user_id, $file, $description);
                $this->redirect('/profile/' . $user_id . '?success=Imagem enviada com sucesso');
            } else {
                $this->redirect('/profile/' . $user_id . '?error=Falha no upload da imagem');
            }
        } catch (\Exception $e) {
            $this->redirect('/profile/' . $user_id . '?error=' . urlencode($e->getMessage()));
        }
    }
}