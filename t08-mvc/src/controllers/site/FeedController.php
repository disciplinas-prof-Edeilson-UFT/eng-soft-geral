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
            $posts= $this->feedService->getAllPostsFeed();
            $preparedPosts= $this->prepareFeedData($posts);
    
            $this->view('feed', ['posts' => $preparedPosts,'pageTitle' => 'Feed','pageCSS' => 'feed']);
        } catch (\Throwable $e) {
            $this->view('feed', ['posts' => [],'error' => $e->getMessage(),'pageTitle' => 'Feed - erro','pageCSS' => 'feed']);
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

    private function prepareFeedData(array $posts): array{
        $preparedPosts = [];
        
        foreach ($posts as $item) {
            $post = $item->getPost();
            $profilePicUrl = $item->getProfilePicUrl();
            
            $preparedPosts[] = [
                'username' => htmlspecialchars($item->getUsername()),
                'profilePhoto' => $profilePicUrl 
                    ? '/public/uploads/avatars/' . htmlspecialchars($profilePicUrl)
                    : '/public/img/profile.svg',
                'profileUrl' => '/profile/' . htmlspecialchars((string)$post->getUserId()),
                'postImageUrl' => $post->getPhotoUrl() 
                    ? '/public/uploads/feed/' . htmlspecialchars($post->getPhotoUrl())
                    : null,
                'description' => htmlspecialchars($post->getDescription() ?? ''),
                'formattedDate' => $this->formatPostDate($post->getUploadDate()),
                'uploadDate' => $post->getUploadDate() ?? '',
                'hasImage' => !empty($post->getPhotoUrl()),
                'hasDescription' => !empty($post->getDescription()),
                'userId' => $post->getUserId()
            ];
        }
        
        return $preparedPosts;
    }

    private function formatPostDate(?string $uploadDate): string{
        return $uploadDate ? 'Publicado em: ' . date('d/m/Y H:i', strtotime($uploadDate)) : '';
    }
}