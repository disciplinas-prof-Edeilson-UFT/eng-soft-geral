<?php
declare(strict_types=1);

namespace src\database\dao;

use src\database\domain\User;
use src\database\BaseDAO;
use src\database\mappers\UserMapper;

class UserDAO extends BaseDAO {
    private UserMapper $mapper;

    public function __construct() {
        parent::__construct();
        $this->mapper = new UserMapper();
    }

    public function insertUser(User $user): bool{ 
        return $this->insert('users', $this->mapper->mapToPersistenceArray($user)); 
    }

    public function searchUsers(string $username): array {
        $sql = "SELECT id, username, email, profile_pic_url FROM users WHERE username LIKE ?";
        return $this->executeQuery($sql, ["%{$username}%"]);
    }

    public function checkEmailExists(string $email, ?int $excludeUserId = null): bool {
        if ($excludeUserId !== null) {
            $sql = "SELECT 1 FROM users WHERE email = ? AND id <> ? LIMIT 1";
            $result = $this->executeQuery($sql, [$email, $excludeUserId]);
        } else {
            $sql = "SELECT 1 FROM users WHERE email = ? LIMIT 1";
            $result = $this->executeQuery($sql, [$email]);
        }
        return !empty($result);
    }

    public function getUserAuthDataByEmail(string $email): ?array {
        $sql = "SELECT id, username, email, password_hash FROM users WHERE email = ?";
        $result = $this->executeQuery($sql, [$email]);
        return $result[0] ?? null;
    }

    public function getUserProfileById(int $id): ?array {
        $sql = "SELECT id, username, email, phone, bio, profile_pic_url, count_followers, count_following FROM users WHERE id = ?";
        $result = $this->executeQuery($sql, [$id]);
        return $result[0] ?? null;
    }

    public function getUserNameById(int $id): ?string {
        $sql = "SELECT username FROM users WHERE id = ? LIMIT 1";
        $result = $this->executeQuery($sql, [$id]);
        return $result[0]['username'] ?? null;
    }

    public function getUserProfilePhotoById(int $id): ?string {
        $sql = "SELECT profile_pic_url FROM users WHERE id = ? LIMIT 1";
        $result = $this->executeQuery($sql, [$id]);
        return $result[0]['profile_pic_url'] ?? null;
    }

    public function getUserById(int $id): ?User {
        $userData = $this->find('users', $id);
        return $userData ? $this->mapper->mapToUser($userData) : null;
    }

    public function findByEmail(string $email): ?User {
        $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
        $result = $this->executeQuery($sql, [$email]);
        if (!$result) {return null; }

        return $this->mapper->mapToUser($result[0]);
    }

    public function updateUser(string $username, string $email, string $bio, string $phone, int $id): bool {
        $data = [
            'username' => $username,
            'email' => $email,
            'bio' => $bio,
            'phone' => $phone
        ];
        return $this->update('users', $data, $id);
    }

    public function updateProfilePic(int $id, string $profilePicUrl): bool {
        return $this->update('users', ['profile_pic_url' => $profilePicUrl], $id);
    }

    public function updateUserFollowerCount(int $userId, int $followersCount): bool {
        return $this->update('users', ['count_followers' => $followersCount], $userId);
    }

    public function updateUserFollowingCount(int $userId, int $count): bool {
        return $this->update('users', ['count_following' => $count], $userId);
    }

    public function deleteUser(int $id): bool { 
        return $this->delete('users', $id); 
    }

    private function getCount(int $userId, string $countColumn): int {
        $allowedColumns = ['count_followers', 'count_following'];
        if (!in_array($countColumn, $allowedColumns, true)) {
            throw new \InvalidArgumentException("Invalid column name: {$countColumn}");
        }

        $sql = "SELECT {$countColumn} FROM users WHERE id = ? LIMIT 1";
        $result = $this->executeQuery($sql, [$userId]);
        return isset($result[0][$countColumn]) ? (int)$result[0][$countColumn] : 0;
    }

    public function getFollowers(int $userId): int { return $this->getCount($userId, 'count_followers');}
    public function getFollowing(int $userId): int { return $this->getCount($userId, 'count_following');}

    public function incrementFollowers(int $userId): bool {
        $stmt = $this->db->prepare('UPDATE users SET count_followers = count_followers + 1 WHERE id = ?');
        return $stmt->execute([$userId]);
    }
    public function incrementFollowing(int $userId): bool {
        $stmt = $this->db->prepare('UPDATE users SET count_following = count_following + 1 WHERE id = ?');
        return $stmt->execute([$userId]);
    }
    public function decrementFollowing(int $userId): bool {
        $stmt = $this->db->prepare('UPDATE users SET count_following = GREATEST(count_following - 1, 0) WHERE id = ?');
        return $stmt->execute([$userId]);
    }
    public function decrementFollowers(int $userId): bool {
        $stmt = $this->db->prepare('UPDATE users SET count_followers = GREATEST(count_followers - 1, 0) WHERE id = ?');
        return $stmt->execute([$userId]);
    }

    public function createUserWithPassword(string $username, string $email, string $password, string $confirmPassword, string $phone): bool {
        $user = new User($username, $email, null, $phone);
        $user->setPassword($password, $confirmPassword);
        return $this->insert('users', $this->mapper->mapToPersistenceArray($user));
    }
}