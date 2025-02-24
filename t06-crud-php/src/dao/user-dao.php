<?php

require_once __DIR__ . '/../../../database.php';

class UserDao
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function createUser($username, $email, $password_hash, $phone)
    {
        $query = "INSERT INTO users (username, email, password_hash, phone) VALUES (:username, :email, :password_hash, :phone)";
        $stmt = $this->db->prepare($query);
        
        return $stmt->execute([':username' => $username,':email' => $email,':password_hash' => $password_hash, ':phone' => $phone]);
    }

    public function checkEmailExists($email): bool
    {
        $query = "SELECT id FROM users WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch() ? true : false;
    }

    public function getUserAuthDataByEmail($email)
    {
        $query = "SELECT id, username, email, password_hash FROM users WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public function getUserProfileById($id)
    {

        $query = "SELECT id, username, email, phone, bio, profile_pic_url FROM users WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function getUserNameById($id)
    {

        $query = "SELECT username FROM users WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function getUserProfilePhotoById($id)
    {
        $query = "SELECT profile_pic_url FROM users WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function updateUser($name, $email, $bio, $phone, $id): bool
    {
        $sql = "UPDATE users SET name = :name, email = :email, bio = :bio, phone = :phone WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ":name"  => $name,
            ":email" => $email,
            ":bio"   => $bio,
            ":phone" => $phone,
            ":id"    => $id
        ]);
        return true;
    }
    public function deleteUser($id): bool
    {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return true;
    }

    public function updateProfileImage($id, $profile_pic_url): bool {
        $query = "UPDATE users SET profile_pic_url = :profile_pic_url WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([":profile_pic_url" =>$profile_pic_url,":id" => $id ]);
        return true;
    }


    public function updateProfilePic($profilePic, $id): bool {
        $sql = "UPDATE users SET profile_pic = :profile_pic WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ":profile_pic" => $profilePic,
            ":id" => $id
        ]);
    }
}
