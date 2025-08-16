<?php 
namespace src\controllers\site;

use src\controllers\BaseController;
use src\services\FeedService;
use src\database\dao\PostDAO;
use src\viewmodels\PostFeedItem;
use Conex\MiniFramework\utils\Flash;

class FeedController extends BaseController {
    private FeedService $feedService;

    public function __construct() {
        parent::__construct();
        $postDAO = new PostDAO();
        $this->feedService = new FeedService($postDAO); 
    }

    public function show(): void {
        try {
            $feedData = $this->feedService->getFeedData();
            
            $preparedPosts = [];
            foreach ($feedData as $item) {
                $postViewModel = new PostFeedItem($item['post'],$item['username'],$item['profile_pic_url']);
                $preparedPosts[] = $postViewModel->getFormattedData();
            }

            $this->view('feed', ['posts' => $preparedPosts, 'pageTitle' => 'Feed', 'pageCSS' => 'feed']);

        } catch (\Throwable $e) {
            //error_log("Erro ao carregar feed: " . $e->getMessage());
            Flash::error('Erro ao carregar feed');
        }
    }    
    
    public function store(int $userId): void{
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