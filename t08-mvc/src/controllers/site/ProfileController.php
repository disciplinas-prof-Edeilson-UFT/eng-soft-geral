<?php
namespace src\controllers\site;

use src\controllers\BaseController;
use src\services\ProfileService;
use src\services\FollowService;
use src\viewmodels\ProfileData;
use src\viewmodels\ProfilePostsData;
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

            $profileViewModel = new ProfileData($profile, $userId, $loggedUserId, $isFollowing, $profile->getUsername());
            $postsViewModel = new ProfilePostsData($posts, $profile->getUsername(), $userId, $loggedUserId);

            $viewData = array_merge(
                $profileViewModel->getFormattedData(),
                $postsViewModel->getFormattedData(),
                [
                    'pageTitle' => 'Perfil de ' . htmlspecialchars($profile->getUsername()),
                    'pageCSS' => 'profile'
                ]
            );

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
}