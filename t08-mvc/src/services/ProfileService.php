<?php
namespace src\services;

use src\database\dao\UserDAO;
use src\database\dao\PostDAO;
use src\database\domain\User;
use src\database\mappers\UserMapper;

class ProfileService {
    private UserDAO $userDAO;
    private PostDAO $postDAO;
    private UserMapper $userMapper;

    public function __construct(UserDAO $userDAO, PostDAO $postDAO) {
        $this->userDAO = $userDAO;
        $this->postDAO = $postDAO;
        $this->userMapper = new UserMapper();
    }

    public function getProfileData(int $userId): User {
        if($userId <= 0) {
            throw new \InvalidArgumentException('ID de user invalido');
        }

        $userData = $this->userDAO->getUserProfileById($userId);
        
        if(!$userData) {
            throw new \InvalidArgumentException('user nao encontrado');
        }

        return $this->userMapper->mapToUserProfile($userData);
    }

    public function getProfileFeed(int $userId): array {
        return $this->postDAO->getPostsByUserId($userId);
    }

    public function updateProfileData(int $userId, string $username, string $phone, string $email, string $bio): bool {
        if(empty($username) || strlen($username) < 3) {
            throw new \InvalidArgumentException('Username deve ter pelo menos 3 caracteres');
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Email inválido');
        }

        if($this->userDAO->checkEmailExists($email, $userId)) {
            throw new \InvalidArgumentException('Email já está em uso');
        }
        return $this->userDAO->updateUser($username, $email, $bio, $phone, $userId);
    }

    public function updateProfilePhoto(int $userId, array $file): bool {
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        
        $uploadResult = UploadImageService::handleUpload($file, 'avatars', $allowedTypes);

        if (!$uploadResult['success']) {
            throw new \InvalidArgumentException($uploadResult['error']);
        }

        return $this->userDAO->updateProfilePic($userId, $uploadResult['file_name']);
    }

    public function deleteProfile(int $userId): bool {
        try {
            $this->userDAO->beginTransaction();
            
            $this->postDAO->deleteAllPostsByUserId($userId);
            $result = $this->userDAO->deleteUser($userId);
            
            $this->userDAO->commit();
            return $result;
            
        } catch (\Exception $e) {
            $this->userDAO->rollback();
            throw $e;
        }
    }
}