<?php
class UserDao
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db->getConnection();
    }

    public function createUser($name, $email, $password, $phone)
    {
        $sql = "INSERT INTO users (name, email, password, phone) VALUES (:name, :email, :password, :phone)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $password,
            ':phone' => $phone
        ]);
    }

    public function checkEmailExists($email): bool
    {
        $sql = "SELECT id FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch() ? true : false;
    }

    public function getUserByEmail($email)
    {
        $sql = "SELECT id, name, email, password, phone FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public function getUserById($id)
    {
        $sql = "SELECT id, name, email, phone, bio FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
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


    public function updateProfilePic($profilePic, $id): bool {
        $sql = "UPDATE users SET profile_pic = :profile_pic WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ":profile_pic" => $profilePic,
            ":id" => $id
        ]);
    }
}
