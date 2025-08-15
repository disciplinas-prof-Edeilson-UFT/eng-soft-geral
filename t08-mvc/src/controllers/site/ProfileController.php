<?php
namespace src\controllers\site;

use src\controllers\BaseController;
use src\services\ProfileService;
use src\services\FollowService;
use src\database\dao\PostDAO;
use src\database\dao\FollowDAO;
use src\database\dao\UserDAO;
use Conex\MiniFramework\utils\Flash;

class ProfileController extends BaseController {
    private ProfileService $profileService;
    private FollowService $followService;

    public function __construct() {
        parent::__construct();
        $postDAO = new PostDAO();
        $userDAO = new UserDAO();
        $followDAO = new FollowDAO();

        $this->profileService = new ProfileService($userDAO, $postDAO);
        $this->followService = new FollowService($followDAO, $userDAO);
    }

    public function show(int $userId): void
    {
        try {
            $profile = $this->profileService->getProfileData($userId);
            $posts = $this->profileService->getProfileFeed($userId);
            $loggedUserId = $this->getSession('user_id', 0);
            $isFollowing= $this->followService->isFollowing($userId, $loggedUserId);

            $profileData= $this->prepareProfileData($profile, $userId, $loggedUserId, $isFollowing);
            $postsData= $this->preparePostsData($posts, $profile->getUsername(), $userId, $loggedUserId); 

            $viewData = array_merge($profileData, $postsData, [ 
                'pageTitle' => 'Perfil de ' . htmlspecialchars($profile->getUsername()),
                'pageCSS' => 'profile'
            ]);

            $this->view('profile', $viewData);
        } catch (\Throwable $e) {
            error_log('Profile show error: ' . $e->getMessage());
            $this->view('profile', ['error' => 'Erro ao carregar perfil']);
        }
    }

    public function follow(int $userId): void
    {
        $loggedUserId = $this->getSession('user_id', 0);
        if (!$loggedUserId) {
            Flash::error('Você precisa estar logado');
            $this->redirect('/auth/login');
        }

        try {
            $action = (string)$this->input('action');
            $success = $this->followService->handleFollow($userId, $loggedUserId, $action);
            Flash::success($success ? 'Ação realizada com sucesso' : 'Não foi possível realizar esta ação');
        } catch (\Throwable $e) {
            Flash::error($e->getMessage());
        }
        $this->redirect('/profile/' . $userId);
    }

    public function edit(int $userId): void
    {
        $loggedUserId = $this->getSession('user_id', 0);
        if ($userId !== (int)$loggedUserId) {
            Flash::error('Você só pode editar seu próprio perfil');
            $this->redirect('/profile/' . $userId);
        }
        try {
            $user = $this->profileService->getProfileData($userId);
            $this->view('profile-update', [
                'user_id' => $userId,
                'user' => $user,
                'pageCSS' => 'profile-update'
            ]);
        } catch (\Throwable $e) {
            Flash::error($e->getMessage());
            $this->redirect('/profile/' . $userId);
        }
    }

    public function update(int $userId): void
    {
        $loggedUserId = $this->getSession('user_id', 0);
        if ($userId !== (int)$loggedUserId) {
            Flash::error('Você só pode editar seu próprio perfil');
            $this->redirect('/profile/' . $userId);
        }
        try {
            $action = (string)$this->input('action');
            switch ($action) {
                case 'delete':
                    $this->profileService->deleteProfile($userId);
                    $this->destroySession();
                    Flash::success('Conta excluída com sucesso');
                    $this->redirect('/auth/login');
                    return;
                case 'logout':
                    $this->destroySession();
                    Flash::success('Logout realizado com sucesso');
                    $this->redirect('/auth/login');
                    return;
                case 'edit':
                    $this->profileService->updateProfileData(
                        $userId,
                        (string)$this->input('username'),
                        (string)$this->input('phone'),
                        (string)$this->input('email'),
                        (string)$this->input('bio')
                    );
                    $file = $this->file('profile_pic_url');
                    if ($file && ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
                        $this->profileService->updateProfilePhoto($userId, $file);
                    }
                    Flash::success('Perfil atualizado com sucesso');
                    break;
                default:
                    throw new \InvalidArgumentException('Ação inválida');
            }
            $this->redirect('/profile/' . $userId);
        } catch (\InvalidArgumentException $e) {
            Flash::error($e->getMessage());
            $this->redirect('/profile/' . $userId . '/edit');
        } catch (\Throwable $e) {
            Flash::error('Erro interno: ' . $e->getMessage());
            $this->redirect('/profile/' . $userId . '/edit');
        }
    }

    private function prepareProfileData($profile, int $userId, int $loggedUserId, bool $isFollowing): array{
        return [
            'user' => $profile,
            'user_id' => $userId,
            'loggedUserId' => $loggedUserId,
            'isOwnProfile' => $userId === $loggedUserId,
            'profilePhoto' => $this->getProfilePhotoUrl($profile),
            'username' => htmlspecialchars($profile->getUsername()),
            'bio' => htmlspecialchars($profile->getBio() ?? 'Sem biografia'),
            'followingCount' => htmlspecialchars($profile->getCountFollowing()),
            'followersCount' => htmlspecialchars($profile->getCountFollowers()),
            'isFollowing' => $isFollowing,
            'followButtonText' => $isFollowing ? 'Deixar de seguir' : 'Seguir',
            'followAction' => $isFollowing ? 'unfollow' : 'follow'
        ];
    }

    private function preparePostsData(array $posts, string $username, int $userId, int $loggedUserId): array{
        $preparedPosts = [];
        
        foreach ($posts as $item) {
            $post = $item->getPost();
            $preparedPosts[] = [
                'id' => $post->getId(),
                'photoUrl'=> htmlspecialchars($post->getPhotoUrl() ?? ''),
                'description' => htmlspecialchars($post->getDescription() ?? ''),
                'uploadDate' => $post->getUploadDate(),
                'formattedDate' => $this->formatPostDate($post->getUploadDate()),
                'hasImage' => !empty($post->getPhotoUrl())
            ];
        }

        return [
            'userPosts' => $preparedPosts,
            'hasUserPosts' => !empty($preparedPosts),
            'postsCount' => count($preparedPosts),
            'postsCountText' => count($preparedPosts) . ' post' . (count($preparedPosts) !== 1 ? 's' : ''),
            'uploadText' => empty($preparedPosts) ? 'Adicionar primeira foto' : 'Adicionar nova foto',
            'noPostsTitle' => $userId === $loggedUserId ? 'Você ainda não tem posts' : htmlspecialchars($username) . ' ainda não tem posts',
            'noPostsMessage' => $userId === $loggedUserId ? 'Comece compartilhando sua primeira foto!' : 'Quando ' . htmlspecialchars($username) . ' compartilhar algo, aparecerá aqui.'
        ];
    }

    private function getProfilePhotoUrl($profile): string
    {
        return $profile->getProfilePicUrl() ? 
            '/public/uploads/avatars/' . htmlspecialchars($profile->getProfilePicUrl()) : 
            '/public/img/profile.svg';
    }

    private function formatPostDate(?string $date): string
    {
        return $date ? date('d/m/Y H:i', strtotime($date)) : '';
    }
}