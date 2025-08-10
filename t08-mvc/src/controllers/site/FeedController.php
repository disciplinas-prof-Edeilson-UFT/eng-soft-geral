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

    public function show(): void
    {
        try {
            $loggedUserId= $this->getSession('user_id');
            $posts= $this->feedService->getAllPostsFeed();
            $this->view('feed', ['posts' => $posts,'pageTitle' => 'Feed','loggedUserId' => $loggedUserId,'pageCSS' => 'feed']);
        } catch (\Throwable $e) {
            $this->view('feed', [
                'posts' => [],
                'error' => $e->getMessage(),
                'pageTitle' => 'Feed - erro',
                'pageCSS' => 'feed'
            ]);
        }
    }

    public function store(int $userId): void
    {
        try {
            $file= $this->file('file');
            $description = $this->input('description');

            if ($file && ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
                $this->feedService->createPost($userId, $file, $description);
                $this->redirect('/profile/' . $userId . '?success=Imagem enviada com sucesso');
            } else {
                $this->redirect('/profile/' . $userId . '?error=Falha no upload da imagem');
            }
        } catch (\Throwable $e) {
            $this->redirect('/profile/' . $userId . '?error=' . urlencode($e->getMessage()));
        }
    }
}