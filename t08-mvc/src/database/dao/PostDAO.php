<?php
declare(strict_types=1);

namespace src\database\dao;

use src\database\domain\Post;
use src\database\BaseDAO;
use src\database\mappers\PostMapper;

class PostDAO extends BaseDAO {
    private PostMapper $mapper;

    public function __construct() {
        parent::__construct();
        $this->mapper = new PostMapper();
    }

    public function insertPost(Post $post): bool{
        return $this->insert('posts', $this->mapper->mapToPersistenceArray($post));
    }

    public function getPostByUserAndPhoto(int $userId, string $photoUrl): ?Post {
        $sql= "SELECT * FROM posts WHERE user_id = ? AND photo_url = ? LIMIT 1";
        $result= $this->executeQuery($sql, [$userId, $photoUrl]);

        if (!$result) {return null;}

        return $this->mapper->mapToPost($result[0]);
    }

    public function getPostsByUserId(int $userId): array {

        $sql= "SELECT p.id, p.photo_url, p.upload_date, p.description, u.username, u.profile_pic_url 
               FROM posts p 
               JOIN users u ON p.user_id = u.id 
               WHERE p.user_id = ?
               ORDER BY p.upload_date DESC";

        return $this->executeQuery($sql, [$userId]);
    }

    public function getAllPosts(): array {
        $sql = "SELECT p.id, p.photo_url, p.user_id, p.upload_date, p.description, u.username, u.profile_pic_url 
                FROM posts p 
                JOIN users u ON p.user_id = u.id 
                ORDER BY p.upload_date DESC";

        return $this->executeQuery($sql); 
    }

    public function deleteAllPostsByUserId(int $userId): int {
        $sql= "DELETE FROM posts WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->rowCount(); 
    }

    public function deletePost(int $postId): bool {
        $sql = "DELETE FROM posts WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$postId]);
        return $stmt->rowCount() > 0;
    }

}