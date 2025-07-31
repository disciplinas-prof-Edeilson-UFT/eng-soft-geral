<?php
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

    public function insertUser(User $user): bool {
        return $this->insert('users', $user->toArray());
    }

    public function searchUsers($username): array {
        $sql= "SELECT id, username, email, profile_pic_url FROM users WHERE username LIKE ?";
        return $this->executeQuery($sql, ["%{$username}%"]);
    }

    public function checkEmailExists($email): bool {
        $sql= "SELECT id FROM users WHERE email = ?";
        $result = $this->executeQuery($sql, [$email]);
        return !empty($result);
    }

    public function getUserAuthDataByEmail($email): ?array {
        $sql = "SELECT id, username, email, password_hash FROM users WHERE email = ?";
        $result = $this->executeQuery($sql, [$email]);
        return !empty($result) ? $result[0] : null;
    }

    public function getUserProfileById($id): ?array {
        $sql = "SELECT id, username, email, phone, bio, profile_pic_url, count_followers, count_following FROM users WHERE id = ?";
        $result = $this->executeQuery($sql, [$id]);
        return !empty($result) ? $result[0] : null;
    }

    public function getUserNameById($id): ?string {
        $sql= "SELECT username FROM users WHERE id = ?";
        $result= $this->executeQuery($sql, [$id]);
        return !empty($result) ? $result[0]['username'] : null;
    }

    public function getUserProfilePhotoById($id): ?string {
        $sql= "SELECT profile_pic_url FROM users WHERE id = ?";
        $result= $this->executeQuery($sql, [$id]);
        return !empty($result) ? $result[0]['profile_pic_url'] : null;
    }

    public function getUserById($id): ?User {
        $userData = $this->find('users', $id);
        return $userData ? $this->mapper->mapToUser($userData) : null;
    }

    public function findByEmail($email): ?User {
        $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
        $result= $this->executeQuery($sql, [$email]);
        
        if (empty($result)) {
            return null;
        }
        
        return $this->mapper->mapToUser($result[0]);
    }

    public function updateUser($username, $email, $bio, $phone, $id): bool {
        $data= ['username' => $username,'email' => $email,'bio'=> $bio,'phone' => $phone];
        return $this->update('users', $data, $id);
    }

    public function updateProfilePic($profilePicUrl, $id): bool {
        $data= ['profile_pic_url' => $profilePicUrl];
        return $this->update('users', $data, $id);
    }

    public function updateUserFollowerCount($userId, $followers_count): bool {
        $data= ['count_followers' => $followers_count];
        return $this->update('users', $data, $userId);
    }

    public function updateUserFollowingCount($userId, $count): bool {
        $data = ['count_following' => $count];
        return $this->update('users', $data, $userId);
    }

    public function deleteUser($id): bool {
        return $this->delete('users', $id);
    }

    private function getCount($userId, $countColumn)
    {
        $allowedColumns = ['count_followers', 'count_following'];
        
        if (!in_array($countColumn, $allowedColumns)) {
            throw new \InvalidArgumentException("Invalid column name: $countColumn");
        }

        $query = "SELECT $countColumn FROM users WHERE id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([":user_id" => $userId]);
        return $stmt->fetchColumn();
    }

    public function getFollowers($userId)
    {
        return $this->getCount($userId, 'count_followers');
    }

    public function getFollowing($userId)
    {
        return $this->getCount($userId, 'count_following');
    }

    public function incrementFollowers($userId)
    {
        return $this->update('users', ['id' => $userId], ['count_followers' => 'count_followers + 1']);
    }

    public function incrementFollowing($userId)
    {
        return $this->update('users', ['id' => $userId], ['count_following' => 'count_following + 1']);
    }

    public function decrementFollowing($userId): bool {
        return $this->update('users', ['id' => $userId], ['count_following' => 'count_following - 1']);
    }

    public function decrementFollowers($userId): bool {
        return $this->update('users', ['id' => $userId], ['count_followers' => 'count_followers - 1']);
    }

    public function createUserWithPassword($username, $email, $password, $confirm_password, $phone): bool {
        $user = new User($username, $email, null, $phone);
        $user->setPassword($password, $confirm_password); 
        
        return $this->insert('users', $user->toArray());
    }

}