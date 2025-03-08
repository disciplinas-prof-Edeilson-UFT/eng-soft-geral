<?php

namespace src\controllers\site;
use src\controllers\BaseController;
use src\services\ProfileService;
use src\database\dao\IPostDAO;
use src\database\domain\Post;
use core\mvc\IModelRepository;
use src\database\dao\PostDAO;
use core\mvc\ModelRepository;
use src\database\dao\IFollowDAO;
use src\database\domain\User;
use src\database\domain\Follow;
use src\services\FollowService;
use src\database\dao\FollowDAO;
use src\database\dao\IUserDAO;
use src\database\dao\UserDAO;

require_once __DIR__ . '/../../database/dao/post-dao.php';
require_once __DIR__ . '/../../database/domain/post.php';
require_once __DIR__ . '/../../database/domain/user.php';
require_once __DIR__ . '/../../../core/mvc/model-repository.php';
require_once __DIR__ . '/../../services/profile-service.php';
require_once __DIR__ . '/../base-controller.php';
require_once __DIR__ . '/../../services/follow-service.php';
require_once __DIR__ . '/../../database/dao/follow-dao.php';
require_once __DIR__ . '/../../database/dao/user-dao.php';
require_once __DIR__ . '/../../database/domain/follow.php';

class ProfileController extends BaseController{
    public $profileService;
    public $followService;
    public IPostDAO $postDAO;
    public Post $post;
    public IModelRepository $IModelRepo;
    public User $user;
    public Follow $follow;
    public IFollowDAO  $followDAO;
    public IUserDAO $userDAO;

    public function __construct() {
        $this->IModelRepo = new ModelRepository();
        $this->post = new Post(null, null, null, null, null, null, null);
        $this->postDAO = new PostDAO($this->IModelRepo);
        $this->user = new User(null, null, null, null, null, null);
        $this->userDAO = new UserDAO($this->IModelRepo);
        $this->follow = new Follow(null, null);
        $this->followDAO = new FollowDAO($this->IModelRepo);
        $this->profileService = new ProfileService($this->postDAO, $this->post, $this->user);
        $this->followService = new FollowService($this->followDAO, $this->user, $this->userDAO);
    }

    public function show($user_id) {
        try {
            $profile = $this->profileService->getProfileData($user_id);
            $profile_pic_url = $profile->getProfilePicUrl();
            $posts = $this->profileService->getProfileFeed($user_id);
        
            $logged_in_user_id = $this->getSession('user_id', 0);
            
            $isFollowing = $this->followService->isFollowing($user_id, $logged_in_user_id);

            
            $this->view('profile', ['user' => $profile, 'user_id' => $user_id,'logged_in_user_id' => $logged_in_user_id, 'isFollowing' => $isFollowing,'userPosts' => $posts, 'profilePhoto' => $profile_pic_url]);

        } catch (\InvalidArgumentException $e) {
            error_log('InvalidArgumentException: ' . $e->getMessage());
            $this->view('profile', ['error' => $e->getMessage(), ]);
            exit;
        } catch (\Exception $e) {
            error_log('Exception: ' . $e->getMessage());
            $this->view('profile', ['error' => 'Erro ao carregar perfil: ' . $e->getMessage()]);
            exit;
        }
    }

    public function follow($user_id) {
        $logged_in_user_id = $_SESSION['user_id'] ?? 0;
        $action = $this->input('action');
        
        if (!$logged_in_user_id) {
            $this->redirect('/auth/login');
            exit;
        }
        
        try {
            $success = $this->followService->handleFollow($user_id, $logged_in_user_id, $action);
            $this->redirect('/profile/' . $user_id . ($success ? '?success=Ação realizada com sucesso' : '?error=Não foi possível realizar esta ação'));
        } catch (\Exception $e) {
            $this->redirect('/profile/' . $user_id . '?error=' . urlencode($e->getMessage()));
        }
    }


    public function edit($user_id) {
        $logged_in_user_id = $this->getSession('user_id', 0);
        
        if ((int)$user_id !== (int)$logged_in_user_id) {
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
            $this->redirect('/profile/' . $user_id . '?error=' . urlencode($e->getMessage()));
        }
    }

    public function update($user_id) {
        try {
            $phone = $this->input('phone');
            $username = $this->input('username');
            $email = $this->input('email'); 
            $bio = $this->input('bio');
    
            $this->profileService->updateProfileData($user_id, $username, $phone, $email, $bio);
    
            if (isset($_FILES['profile_pic_url']) && $_FILES['profile_pic_url']['error'] === UPLOAD_ERR_OK) {
                $this->profileService->updateProfilePhoto($user_id, $_FILES['profile_pic_url']);
            }

            if(isset($_POST['logout'])){
                $this->destroySession();
                $this->redirect('/auth/login');
            }
    
            $this->redirect('/profile/' . $user_id);
    
        } catch (\InvalidArgumentException $e) {
            $this->redirect('/profile/' . $user_id . '/edit?error=' . urlencode($e->getMessage()));
        }catch(\Exception $e){
            $this->redirect('/profile/' . $user_id . '/edit?error=' . urlencode("Erro interno do server"));

        }
    }
}