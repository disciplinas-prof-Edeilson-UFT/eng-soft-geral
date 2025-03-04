<?php
class Users
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db->getConnection();
    }

    public function createUser($name, $email, $password, $phone)
    {
        $sql = "INSERT INTO users (name, email, password_hash, phone) VALUES (:name, :email, :password, :phone)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $password,
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