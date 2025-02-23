<?php 
require_once __DIR__ . '/../../../database.php';
class PostsDao {

    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function createPost($userId, $photo_url, $description) {
        $sql = "INSERT INTO posts (user_id, photo_url, description) VALUES (:userId, :photo_url, :description)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':userId' => $userId,
            ':photo_url' => $photo_url,
            ':description' => $description
        ]);
    }

    public function getPosts($userId) {
        $sql = "SELECT p.id, p.photo_url, p.upload_date, p.description, u.name, u.profile_pic FROM posts p JOIN users u ON p.user_id = u.id WHERE p.user_id = :userId ORDER BY p.upload_date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deletePost($postId) {
        $sql = "DELETE FROM posts WHERE id = :postId";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':postId' => $postId]);
    }
}