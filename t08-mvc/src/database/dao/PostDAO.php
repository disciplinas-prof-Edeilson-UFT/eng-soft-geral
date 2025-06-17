<?php
namespace src\database\dao;

use src\database\domain\Post;
use src\database\BaseDAO;
use PDO;

class PostDAO extends BaseDAO {

    public function insertPost(Post $post): bool {
        return $this->insert('posts', $post);
    }

    public function getPostByUserAndPhoto($userID, $photoURL): ?Post {
        $sql= "SELECT * FROM posts WHERE user_id = ? AND photo_url = ? LIMIT 1";
        $result= $this->executeQuery($sql, [$userID, $photoURL]);

        if (empty($result)) {
            return null;
        }

        return $this->mapToPost($result[0]);
    }

    public function getPostsByUserID($userID) {

        $sql= "SELECT p.id, p.photo_url, p.upload_date, p.description, u.username, u.profile_pic_url 
                FROM posts p 
                JOIN users u ON p.user_id = u.id 
                WHERE p.user_id = ?
                ORDER BY p.upload_date DESC";

        return $this->executeQuery($sql, [$userID]);
    }

    public function getAllPosts() {
        $sql = "SELECT p.id, p.photo_url, p.user_id, p.upload_date, p.description, u.username, u.profile_pic_url 
                FROM posts p 
                JOIN users u ON p.user_id = u.id 
                ORDER BY p.upload_date DESC";

        return $this->executeQuery($sql);
    }

    public function mapToPost(array $data): Post {
        $post= new Post($data['user_id'], $data['photo_url'], $data['description']);
        
        if (isset($data['id'])) {
            $post->setId($data['id']);
        }
        if (isset($data['upload_date'])) {
            $post->setUploadDate($data['upload_date']);
        }
        return $post;
    }

}