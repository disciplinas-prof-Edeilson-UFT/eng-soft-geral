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

    public function show($user_id) {
        try {
            $profile = $this->profileService->getProfileData($user_id);
            $posts = $this->profileService->getProfileFeed($user_id);
            
            $logged_in_user_id = $this->getSession('user_id', 0);
            $isFollowing = $this->followService->isFollowing($user_id, $logged_in_user_id);
            
            $this->view('profile', [
                'user' => $profile,                    
                'user_id' => $user_id,
                'logged_in_user_id' => $logged_in_user_id,
                'isFollowing' => $isFollowing,
                'userPosts' => $posts,
                'profilePhoto' => $profile->getProfilePicUrl()
            ]);

        } catch (\InvalidArgumentException $e) {
            error_log('InvalidArgumentException: ' . $e->getMessage());
            $this->view('profile', ['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            error_log('Exception: ' . $e->getMessage());
            $this->view('profile', ['error' => 'Erro ao carregar perfil: ' . $e->getMessage()]);
        }
    }

    public function follow($user_id) {
        $logged_in_user_id = $this->getSession('user_id', 0);
        
        if (!$logged_in_user_id) {
            Flash::error('Você precisa estar logado');
            $this->redirect('/auth/login');
            exit;
        }
        
        try {
            $action = $this->input('action');
            $success = $this->followService->handleFollow($user_id, $logged_in_user_id, $action);
            
            Flash::success($success ? 'Ação realizada com sucesso' : 'Não foi possível realizar esta ação');
            $this->redirect('/profile/' . $user_id);
            
        } catch (\Exception $e) {
            Flash::error($e->getMessage());
            $this->redirect('/profile/' . $user_id);
        }
        exit;
    }

    public function edit($user_id) {
        $logged_in_user_id = $this->getSession('user_id', 0);
        
        if ((int)$user_id !== (int)$logged_in_user_id) {
            Flash::error('Você só pode editar seu próprio perfil');
            $this->redirect('/profile/' . $user_id);
            exit;
        }
        
        try {
            $user = $this->profileService->getProfileData($user_id);
            $this->view('profile-update', [
                'user_id' => $user_id,
                'user' => $user  
            ]);
        } catch (\Exception $e) {
            Flash::error($e->getMessage());
            $this->redirect('/profile/' . $user_id);
        }
    }

    public function update($user_id) {
        try {
            $logged_in_user_id = $this->getSession('user_id', 0);
            
            if ((int)$user_id !== (int)$logged_in_user_id) {
                Flash::error('Você só pode editar seu próprio perfil');
                $this->redirect('/profile/' . $user_id);
                exit;
            }

            $action = $this->input('action');
            
            switch ($action) {
                case 'delete':
                    $this->profileService->deleteProfile($user_id);
                    $this->destroySession();
                    Flash::success('Conta excluída com sucesso');
                    $this->redirect('/auth/login');
                    break;
                    
                case 'logout':
                    $this->destroySession();
                    Flash::success('Logout realizado com sucesso');
                    $this->redirect('/auth/login');
                    break;
                    
                case 'edit':
                    $phone = $this->input('phone');
                    $username = $this->input('username');
                    $email = $this->input('email'); 
                    $bio = $this->input('bio');

                    $this->profileService->updateProfileData($user_id, $username, $phone, $email, $bio);
                
                    if (isset($_FILES['profile_pic_url']) && $_FILES['profile_pic_url']['error'] === UPLOAD_ERR_OK) {
                        $this->profileService->updateProfilePhoto($user_id, $_FILES['profile_pic_url']);
                    }
                    
                    Flash::success('Perfil atualizado com sucesso');
                    break;
                    
                default:
                    throw new \InvalidArgumentException('Ação inválida');
            }

            $this->redirect('/profile/' . $user_id);

        } catch (\InvalidArgumentException $e) {
            Flash::error($e->getMessage());
            $this->redirect('/profile/' . $user_id . '/edit');
        } catch (\Exception $e) {
            Flash::error('Erro interno: ' . $e->getMessage());
            $this->redirect('/profile/' . $user_id . '/edit');
        }
        exit;
    }
}