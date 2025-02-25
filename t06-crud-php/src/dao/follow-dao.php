<?php
require_once __DIR__ . '/../../database.php';

class FollowDao {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function followUser($followerId, $followingId) {
        $query = "INSERT INTO follow (user_id, following_id) VALUES (:userId, :followingId)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([":userId" => $followerId, ":followingId" => $followingId]);
    }

    public function isFollowing($followerId, $followingId): bool {
        $query = "SELECT * FROM follow WHERE user_id = :sourceId AND following_id = :seekerId";
        $stmt = $this->db->prepare($query);
        $stmt->execute([ ":sourceId"=>$followerId,":seekerId" =>$followingId]);
        return $stmt->fetch() ? true : false;

    }

    public function unfollowUser($followerId, $followingId) {
        $query = "DELETE FROM follow WHERE user_id = :userId AND following_id = :followingId";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([":userId" => $followerId, ":followingId"=>$followingId]);
    }

    private function getCount($userId, $countColumn) {
        $query = "SELECT $countColumn FROM users WHERE id = :userId";
        $stmt = $this->db->prepare($query);
        $stmt->execute([":userId" => $userId]);
        return $stmt->fetchColumn();
    }

    public function getFollowers($userId) {
        return $this->getCount($userId, 'count_followers');
    }

    public function getFollowing($userId) {
        return $this->getCount($userId , 'count_following');
    }
}