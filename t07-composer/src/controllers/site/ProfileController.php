<?php 
namespace Conex\T07Composer\controllers\site;

use Conex\T07Composer\Database;
use Conex\T07Composer\dao\PostDAO;
use Conex\T07Composer\dao\UserDAO;
use Conex\T07Composer\dao\FollowDAO;
use Conex\T07Composer\utils\FollowHandler;
use Conex\T07Composer\utils\UploadHandler;

class ProfileController {
    private PostDAO $postDAO;
    private UserDAO $userDAO;
    private FollowDAO $followDAO;
    private FollowHandler $followHandler;

    public function __construct(){
        $database = Database::getInstance();
        $this->postDAO = new PostDAO($database);
        $this->userDAO = new UserDAO($database);
        $this->followDAO = new FollowDAO($database);
        $this->followHandler = new FollowHandler($this->followDAO, $this->userDAO);
    }

    public function show($userId){
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "view/login.php");
            exit;
        }

        $loggedInUserId = $_SESSION['user_id'];

        $user = $this->userDAO->getUserProfileById($userId);

        if (!$user) {
            $_SESSION['error'] = "Usuário não encontrado.";
            header("Location: " . BASE_URL . "feed");
            exit;
        }

        $profilePhoto = !empty($user['profile_pic_url'])
            ? BASE_URL . 'uploads/avatars/' . htmlspecialchars($user['profile_pic_url'])
            : BASE_URL . 'public/img/profile.svg';

        $isFollowing = $this->followDAO->isFollowing($userId, $loggedInUserId);

        $followers = $this->followDAO->getFollowers($userId);
        $following = $this->followDAO->getFollowing($userId);

        $userPosts = $this->postDAO->getPostsById($userId);

        $userProfileData = [
            'username' => htmlspecialchars($user['username']),
            'user_id' => $userId,
            'logged_in_user_id' => $loggedInUserId,
            'profile_Pic_Url' => htmlspecialchars($profilePhoto ?? ''),
            'is_Following' => $isFollowing,
            'followers' => $followers,
            'following' => $following,
            'bio' => htmlspecialchars($user['bio'] ?? ''),
            'photos' => $userPosts,
        ];

        $userName = $userProfileData['username'];
        $user_id = $userProfileData['user_id'];
        $logged_in_user_id = $userProfileData['logged_in_user_id'];
        $isFollowing = $userProfileData['is_Following'];
        $followers = $userProfileData['followers'];
        $following = $userProfileData['following'];
        $profilePhoto = $profilePhoto;

        $editProfileUrl = BASE_URL . "profile/update?id=" . $userId;

        require_once __DIR__ . '/../../../view/profile.php';
    }

    public function follow($userId) {
        $loggedInUserId = $_SESSION['user_id'];
        $isFollowing = $this->followDAO->isFollowing($userId, $loggedInUserId);

        if (!$loggedInUserId) {
            header("Location: " . BASE_URL . "view/login.php");
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $action = $_POST['action'];

            $this->followHandler->handleFollow($userId, $loggedInUserId, $isFollowing, $action);
        }
    }


    public function edit($userId) {
        $loggedInUserId = $_SESSION['user_id'];

        $this->validateUserPermission($userId);

        $user = $this->userDAO->getUserProfileById($userId);

        if (!$user) {
            $_SESSION['error'] = "Usuário não encontrado.";
            header("Location: " . BASE_URL . "feed");
            exit;
        }

        $profilePhoto = !empty($user['profile_pic_url'])
            ? BASE_URL . 'uploads/avatars/' . htmlspecialchars($user['profile_pic_url'])
            : BASE_URL . 'public/img/profile.svg';

        $userProfileData = [
            'username' => htmlspecialchars($user['username']),
            'user_id' => $userId,
            'logged_in_user_id' => $loggedInUserId,
            'profile_Pic_Url' => htmlspecialchars($profilePhoto ?? ''),
            'bio' => htmlspecialchars($user['bio'] ?? ''),
        ];

        require_once __DIR__ . '/../../../view/profile-update.php';
    }

    public function update($userId) {
        $this->validateUserPermission($userId);
        
        $user = $this->userDAO->getUserProfileById($userId);
        
        if (!$user) {
            $_SESSION['error'] = "Usuário não encontrado.";
            header("Location: " . BASE_URL . "feed");
            exit;
        }
        
        if (isset($_POST['edit'])) {
            $name = trim($_POST['username']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $bio = trim($_POST['bio']);
    
            if (isset($_FILES['profile_pic_url']) && $_FILES['profile_pic_url']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . "/../../../uploads/avatars/";
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    
                $dbUpdateCallback = function ($photoUrl) use ($userId) {
                    return $this->userDAO->updateProfilePic($photoUrl, $userId);
                };
    
                $uploadResult = UploadHandler::handleUpload($_FILES['profile_pic_url'], $uploadDir, $allowedTypes, $dbUpdateCallback);
    
                if (!$uploadResult['success']) {
                    $_SESSION['error'] = "Usuário não encontrado.";
                    header("Location: " . BASE_URL . "feed?id=$userId&error=" . urlencode($uploadResult['error']));
                    exit;
                }
            }
    
            try {
                $this->userDAO->updateUser($name, $email, $bio, $phone, $userId);
                $_SESSION['success'] = "Perfil atualizado com sucesso!";
                header("Location: " . BASE_URL . "profile?id=" . $userId);
                exit;
            } catch (\Exception $e) {
                $_SESSION['error'] = "Erro ao atualizar perfil: " . $e->getMessage();
                header("Location: " . BASE_URL . "profile/edit?id=" . $userId);
                exit;
            }
        }
        header("Location: " . BASE_URL . "profile/edit?id=" . $userId);
        exit;
    }

    public function delete($userId) {
        $this->validateUserPermission($userId);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->userDAO->deleteUser($userId);
                session_destroy();
                header("Location: " . BASE_URL . "login");
                exit;
            } catch (\Exception $e) {
                echo 'Erro: ' . $e->getMessage();
            }
        }
    }

    private function validateUserPermission($userId) {
        if ((int)$userId !== (int)$_SESSION['user_id']) {
            $_SESSION['error'] = "Você não tem permissão para editar este perfil";
            header("Location: " . BASE_URL . "profile?id=$userId");
            exit;
        }
    }
}