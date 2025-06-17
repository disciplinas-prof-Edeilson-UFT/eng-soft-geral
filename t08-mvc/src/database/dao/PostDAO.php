<?php
namespace src\database\dao;

use Database;
use PDO;

class PostDAO {

    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getPostsById($userId) {

        $sql = "SELECT p.id, p.photo_url, p.upload_date, p.description, u.username, u.profile_pic_url 
                FROM posts p 
                JOIN users u ON p.user_id = u.id 
                WHERE p.user_id = :userId 
                ORDER BY p.upload_date DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllPosts() {
        $sql = "SELECT p.id, p.photo_url, p.user_id, p.upload_date, p.description, u.username, u.profile_pic_url 
                FROM posts p 
                JOIN users u ON p.user_id = u.id 
                ORDER BY p.upload_date DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}