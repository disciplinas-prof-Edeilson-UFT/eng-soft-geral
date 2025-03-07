<?php
class Users
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db->getConnection();
    }

    public function createUser($username, $email, $password, $phone)
    {
        $sql = "INSERT INTO users (username, email, password_hash, phone) VALUES (:username, :email, :password_hash, :phone)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password_hash' => $password,  // Alterado para 'password_hash'
            ':phone' => $phone
        ]);
    }

    public function checkEmailExists($email)
    {
        $sql = "SELECT id FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch() ? true : false;
    }
}
