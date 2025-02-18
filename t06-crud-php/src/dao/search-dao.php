<?php
require_once __DIR__ . '../../../database.php';

class UserRepository
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function buscarUsuarios($name)
    {
        $sql = "SELECT id, name, email, profile_pic FROM users WHERE name LIKE :name";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['name' => "%$name%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
