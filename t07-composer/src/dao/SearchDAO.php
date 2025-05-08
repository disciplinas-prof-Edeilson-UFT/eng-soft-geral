<?php
//require_once __DIR__ . '/../../database.php';

namespace Conex\T07Composer\dao;

use PDO;
use Conex\T07Composer\database;

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

