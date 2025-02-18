<?php
require_once __DIR__ . '../../../database.php';

class UserRepository
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function buscarUsuarios($nome)
    {
        $sql = "SELECT name, email FROM users WHERE name LIKE :nome";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['nome' => "%$nome%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
