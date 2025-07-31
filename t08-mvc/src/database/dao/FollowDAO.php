<?php
namespace src\database\dao;
use src\database\domain\Follow;
use src\database\BaseDAO;
use src\database\mappers\FollowMapper;

class FollowDAO extends BaseDAO {
    private FollowMapper $mapper;

    public function __construct() {
        parent::__construct();
        $this->mapper = new FollowMapper();
    }

    public function follow(Follow $follow)
    {
        return $this->insert('follow', $follow->toArray());
    }

    public function isFollowing($followingId, $followerId): ?Follow {
        $sql = "SELECT * FROM follow WHERE following_id = :following_id AND follower_id = :follower_id";
        $result= $this->executeQuery($sql, [":following_id" => $followingId, ":follower_id" => $followerId]);

        if (empty($result)) {
            return null;
        }
        return $this->mapper->mapToFollow($result[0]);
    }

    public function unfollow($followingId, $followerId)
    {
        $query = "DELETE FROM follow WHERE following_id = :following_id AND follower_id = :follower_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([":following_id" => $followingId, ":follower_id" => $followerId]);
    }
}