<?php
require_once __DIR__ . '/../../../database.php';

class SearchDao
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function SearchUsers($name)
    {
        $sql = "SELECT id, username, email, profile_pic_url FROM users WHERE name LIKE :name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['name' => $name]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

