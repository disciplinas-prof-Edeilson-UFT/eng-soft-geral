<?php
namespace src\services;

use src\database\dao\FollowDAO;
use src\database\dao\UserDAO;
use src\database\domain\Follow;

class FollowService{
    private FollowDAO $followDAO;
    private UserDAO $userDAO;


    public function __construct(FollowDAO $followDAO, UserDAO $userDAO)
    {
        $this->followDAO = $followDAO;
        $this->userDAO = $userDAO;
    }
    public function isFollowing($userId, $followerId): bool {
        $follow = $this->followDAO->isFollowing($userId, $followerId);
        return $follow !== null;
    }

    public function follow($followingId, $followerId):bool {
        if ($this->isFollowing($followingId, $followerId)) {
            throw new \InvalidArgumentException("user ja esta sendo seguido");
        }
        if ($followingId === $followerId) {
            throw new \InvalidArgumentException("user nao pode seguir a si mesmo");
        }

        $this->userDAO->beginTransaction();

        $follow = new Follow($followerId, $followingId);
        $result = $this->followDAO->follow($follow);
        
        if (!$result) {
            throw new \Exception("Erro ao seguir user");
        }

        $this->userDAO->incrementFollowers($followingId);      
        $this->userDAO->incrementFollowing($followerId);
        
        $this->userDAO->commit();
        return true;
    }

    public function unfollow($followingId, $followerId):bool {
    
        if ($this->isFollowing($followingId, $followerId)) {
            throw new \InvalidArgumentException("user ja esta sendo seguido");
        }

        $this->userDAO->beginTransaction();
        
        $result = $this->followDAO->unfollow($followerId, $followingId);

        if (!$result) {
            throw new \Exception("Erro ao deixar de seguir user");
        }

        $this->userDAO->decrementFollowers($followerId);
        $this->userDAO->decrementFollowing($followingId);

        $this->userDAO->commit();
        return true;
    }

    public function handleFollow($profileId, $currentUserId, $action): bool {
        switch ($action) {
            case 'follow':
                return $this->follow($profileId, $currentUserId);
            case 'unfollow':
                return $this->unfollow($profileId, $currentUserId);
            default:
                throw new \InvalidArgumentException("acao invalida: $action");
        }
    }

    public function getFollowersCount($userId): int {
        return $this->userDAO->getFollowers($userId);
    }

    public function getFollowingCount($userId): int {
        return $this->userDAO->getFollowing($userId);
    }
}