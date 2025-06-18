<?php
require_once __DIR__ . '/../../Database.php';

class FollowDAO {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function followUser($user_id, $logged_in_user_id) {
        $sql = "INSERT INTO follow (user_id, follower_id) VALUES (:user_id, :follower_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $user_id, ':follower_id' => $logged_in_user_id]);
        return true;
    }

    public function isFollowing($user_id, $follower_id): bool {
        $query = "SELECT * FROM follow WHERE user_id = :user_id AND follower_id = :follower_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([ ":user_id"=>$user_id, ":follower_id" =>$follower_id]);
        return $stmt->fetch() ? true : false;

    }

    public function unfollowUser($user_id, $follower_id) {
        $query = "DELETE FROM follow WHERE user_id = :user_id AND follower_id = :follower_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([":user_id" => $user_id, ":follower_id"=>$follower_id]);
    }

    private function getCount($user_id, $countColumn) {
        $query = "SELECT $countColumn FROM users WHERE id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([":user_id" => $user_id]);
        return $stmt->fetchColumn();
    }

    public function getFollowers($user_id) {
        return $this->getCount($user_id, 'count_followers');
    }

    public function getFollowing($user_id) {
        return $this->getCount($user_id , 'count_following');
    }
}