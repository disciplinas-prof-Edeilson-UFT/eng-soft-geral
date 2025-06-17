<?php 
namespace src\controllers\site;

use src\controllers\BaseController;
use src\services\FeedService;
use src\database\dao\PostDAO;

class FeedController extends BaseController {
    private FeedService $feedService;

    public function __construct() {
        $postDAO = new PostDAO();
        $this->feedService = new FeedService($postDAO); 
    }

    public function show() {
        try {
            $posts= $this->feedService->getAllPostsFeed();
            $this->view('feed', ['posts' => $posts]);
        } catch (\Exception $e) {
            $this->view('feed', ['posts' => [], 'error' => $e->getMessage()]);
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