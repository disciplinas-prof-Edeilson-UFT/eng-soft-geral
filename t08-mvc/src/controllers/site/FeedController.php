<?php 
namespace src\controllers\site;

use src\controllers\BaseController;
use src\services\FeedService;
use src\database\dao\PostDAO;
use Conex\MiniFramework\utils\Flash;

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
                Flash::success('Postagem criada com sucesso');
            } else {
                Flash::error('Falha no upload da imagem');
            }
        } catch (\Throwable $e) {
            Flash::error('Erro ao criar post: ' . $e->getMessage());
        }
        $this->redirect('/profile/' . $userId);
    }

    public function delete(int $postId): void
    {
        try {
            $this->feedService->deletePost($postId);
            Flash::success('Postagem deletada com sucesso');
        } catch (\Throwable $e) {
            Flash::error('Erro ao deletar post: ' . $e->getMessage());
        }
        $this->redirect('/');
    }
}