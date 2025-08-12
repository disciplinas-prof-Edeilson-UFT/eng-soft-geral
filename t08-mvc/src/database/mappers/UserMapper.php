<?php
declare(strict_types=1);
namespace src\database\mappers;
use src\database\domain\User;

class UserMapper {
    public function mapToUser(array $data): User {
        $user = new User(
            $data['username'],
            $data['email'],
            $data['password_hash'] ?? null,
            $data['phone'] ?? null,
            $data['bio'] ?? null,
            $data['profile_pic_url'] ?? null
        );
        if (isset($data['id'])) { $user->setId((int)$data['id']); }
        if (isset($data['created_at'])) { $user->setCreatedAt($data['created_at']); }
        if (isset($data['count_followers'])) { $user->setCountFollowers((int)$data['count_followers']); }
        if (isset($data['count_following'])) { $user->setCountFollowing((int)$data['count_following']); }
        return $user;
    }

    public function mapToPersistenceArray(User $user): array {
        $data = [
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'password_hash' => $user->getPasswordHash(),
            'phone' => $user->getPhone(),
            'bio' => $user->getBio(),
            'profile_pic_url' => $user->getProfilePicURL(),
        ];
        if ($user->getId() !== null) { $data['id'] = $user->getId(); }
        if ($user->getCountFollowers() !== null) { $data['count_followers'] = $user->getCountFollowers(); }
        if ($user->getCountFollowing() !== null) { $data['count_following'] = $user->getCountFollowing(); }
        if ($user->getCreatedAt() !== null) { $data['created_at'] = $user->getCreatedAt(); }
        return $data;
    }

    public function mapToPublicArray(User $user): array {
        return [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'phone' => $user->getPhone(),
            'bio' => $user->getBio(),
            'profile_pic_url' => $user->getProfilePicURL(),
            'count_followers' => $user->getCountFollowers(),
            'count_following' => $user->getCountFollowing(),
            'created_at' => $user->getCreatedAt(),
        ];
    }

    public function mapToUserProfile(array $data): User { return $this->mapToUser($data); }

    public function mapToUserAuth(array $data): User {
        $user = new User($data['username'], $data['email'], $data['password_hash'] ?? null, null, null, null);
        if (isset($data['id'])) { $user->setId((int)$data['id']); }
        return $user;
    }
}