<?php
require_once __DIR__ . '/../../Database.php';

class SearchDAO
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function SearchUsers($username)
    {
        $sql = "SELECT id, username, email, profile_pic_url FROM users WHERE username LIKE :username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username' => $username]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

