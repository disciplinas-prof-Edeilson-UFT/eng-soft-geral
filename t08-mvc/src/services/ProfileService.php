<?php

namespace src\services;
use src\database\dao\PostDAO;
use src\database\domain\Post;
use src\services\UploadImageService;
use src\database\domain\User;

class ProfileService{
    public PostDAO $postDAO;
    public Post $post;
    public UploadImageService $uploadImageService;
    public User $user;

    public function __construct(PostDAO $postDAO, Post $post, User $user)
    {
        $this->postDAO = $postDAO;
        $this->post = $post;
        $this->uploadImageService = new UploadImageService();
        $this->user = $user;
    }

    public function getProfileFeed($user_id){
        $profileFeed = $this->postDAO->getPostsById($user_id);
        
        return $profileFeed;
    }



    public function getProfileData($user_id) {
        $userData = $this->postDAO->find('users', ['id' => $user_id], ['*']);
        
        if (empty($userData)) {
            throw new \InvalidArgumentException("Perfil não encontrado");
        }
        
        $userObj = is_array($userData) ? $userData[0] : $userData;
        
        $userArray = [];
        if (is_object($userObj)) {
            $userArray = get_object_vars($userObj);
        } else if (is_array($userObj)) {
            $userArray = $userObj;
        } else {
            throw new \InvalidArgumentException("Formato de dados de usuário inválido");
        }
        
        $user = new User(
            $userArray['username'] ?? null,
            $userArray['email'] ?? null, 
            $userArray['password_hash'] ?? null,
            $userArray['phone'] ?? null,
            $userArray['bio'] ?? null,
            $userArray['profile_pic_url'] ?? null
        );
        
        /*foreach ($userArray as $key => $value) {
            echo $key . ' => ' . $value . '<br>';
        }*/
        if (isset($userArray['id'])) {
            $user->setId($userArray['id']);
        }
        
        $user->setCountFollowers($userArray['count_followers'] ?? 0);
        $user->setCountFollowing($userArray['count_following'] ?? 0);
        
        return $user;
    }

    public function getUserProfile($user_id){
        $profileFeed = $this->getProfileFeed($user_id);   
        $profileData = $this->getProfileData($user_id);
        return ['profileFeed' => $profileFeed, 'profileData' => $profileData];
    }

    public function updateProfilePhoto($id, $file)
    {
        if (!isset($file) || !is_uploaded_file($file['tmp_name'])) {
            throw new \InvalidArgumentException("Arquivo inválido ou upload falhou");
        }

        $allowTypes = array('jpeg', 'png');
        $uploadDir = '/uploads/avatars/';
        $result = $this->uploadImageService->handleUpload($file, $uploadDir, $allowTypes);
        
        if ( !$result['success']) {
            throw new \InvalidArgumentException("Erro ao fazer upload da foto de perfil");
        }
        
        $fileUrl = $result['file_name'];
        $this->user->setProfilePicUrl($fileUrl);
        $updated = $this->postDAO->update('users', ['id' => $id], ['profile_pic_url' => $fileUrl]);
        
        if (!$updated) {
            throw new \InvalidArgumentException("Erro ao atualizar foto de perfil no banco de dados");
        }

        return $fileUrl;
    }

    public function updateProfileData($user_id, $username, $phone, $email, $bio){
        $registeredUser = $this->postDAO->find('users', ['id' => $user_id], ['*']);
        if (empty($registeredUser)) {
            throw new \InvalidArgumentException("Usuário não encontrado");
        }
        
        $updatedUser = $this->postDAO->update('users', ['id' => $user_id], ['username' => $username,'email' => $email,'bio' => $bio, 'phone' => $phone]);
        
        if (!$updatedUser) {
            throw new \InvalidArgumentException("Erro ao atualizar user no banco");
        }
        $this->user->setId($user_id);
        $this->user->setUsername($username);
        $this->user->setEmail($email);
        $this->user->setBio($bio);
        
        if (isset($registeredUser[0]->profile_pic_url)) {
            $this->user->setProfilePicUrl($registeredUser[0]->profile_pic_url);
        }
    }
}