<?php
declare(strict_types=1);

namespace src\services;

use src\database\dao\UserDAO;
use src\database\dao\PostDAO;
use src\database\domain\User;
use src\database\mappers\UserMapper;
use src\database\mappers\PostMapper;
use src\viewmodels\PostFeedItem;

class ProfileService
{
    private UserDAO $userDAO;
    private PostDAO $postDAO;
    private UserMapper $userMapper;
    private PostMapper $postMapper;

    public function __construct(UserDAO $userDAO, PostDAO $postDAO){
        $this->userDAO = $userDAO;
        $this->postDAO = $postDAO;
        $this->userMapper = new UserMapper();
        $this->postMapper = new PostMapper();
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

    /**
     * Retorna posts do perfil como PostFeedItem[] (mesma abordagem do feed principal)
     * @return PostFeedItem[]
     */
    public function getProfileFeed(int $userId): array
    {
        if ($userId <= 0) { return []; }
        $rows = $this->postDAO->getPostsByUserId($userId); 
        $items = [];
        foreach ($rows as $r) {
            $post = $this->postMapper->mapToPost([
                'id' => $r['id'],
                'user_id' => $userId,
                'photo_url' => $r['photo_url'],
                'upload_date' => $r['upload_date'] ?? null,
                'description' => $r['description'] ?? null,
            ]);
            $items[] = new PostFeedItem(
                $post,
                (string)($r['username'] ?? 'Usuário'),
                $r['profile_pic_url'] ?? null
            );
        }
        return $items;
    }

    public function updateProfileData(int $userId, string $username, string $phone, string $email, string $bio): bool{
        if ($userId <= 0) { throw new \InvalidArgumentException('Usuário inválido'); }
        
        $user = new User($username, $email, null, $phone, $bio, null);
        if($this->userDAO->checkEmailExists($user->getEmail(), $userId)) {
            throw new \InvalidArgumentException('Email já está em uso');
        }
        return $this->userDAO->updateUser($user->getUsername(), $user->getEmail(), $bio, $phone, $userId);
    }

    /**
     * @param array $file Estrutura do arquivo (como em $_FILES['...'])
     */
    public function updateProfilePhoto(int $userId, array $file): bool
    {
        if ($userId <= 0) {
            throw new \InvalidArgumentException('Usuário inválido');
        }
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $uploadResult = UploadImageService::handleUpload($file, 'avatars', $allowedTypes);
        if (!$uploadResult['success']) {
            throw new \InvalidArgumentException($uploadResult['error']);
        }
        return $this->userDAO->updateProfilePic($userId, $uploadResult['file_name']);
    }

    public function deleteProfile(int $userId): bool{
        if ($userId <= 0) {
            throw new \InvalidArgumentException('Usuário inválido');
        }
        try {
            $this->userDAO->beginTransaction();
            $this->postDAO->deleteAllPostsByUserId($userId);
            $result = $this->userDAO->deleteUser($userId);
            $this->userDAO->commit();

            return $result;
        } catch (\Throwable $e) {
            $this->userDAO->rollback();
            throw $e;
        }
    }
}