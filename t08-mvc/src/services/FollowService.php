<?php
declare(strict_types=1);

namespace src\services;

use src\database\dao\FollowDAO;
use src\database\dao\UserDAO;
use src\database\domain\Follow;

class FollowService
{
    private FollowDAO $followDAO;
    private UserDAO $userDAO;

    public function __construct(FollowDAO $followDAO, UserDAO $userDAO){
        $this->followDAO = $followDAO;
        $this->userDAO = $userDAO;
    }

    public function isFollowing(int $targetUserId, int $currentUserId): bool{
        return $this->followDAO->isFollowing($targetUserId, $currentUserId) !== null;
    }

    public function follow(int $targetUserId, int $currentUserId): bool{
        if ($targetUserId === $currentUserId) {
            throw new \InvalidArgumentException('user não pode seguir a si mesmo');
        }
        if ($this->isFollowing($targetUserId, $currentUserId)) {
            throw new \InvalidArgumentException('Você já segue este user');
        }

        $this->userDAO->beginTransaction();
        try {
            $follow = new Follow($currentUserId, $targetUserId);
            $result = $this->followDAO->follow($follow);
            if (!$result) {
                throw new \RuntimeException('Erro ao seguir user');
            }

            $this->userDAO->incrementFollowers($targetUserId);
            $this->userDAO->incrementFollowing($currentUserId);
            $this->userDAO->commit();
            return true;
        } catch (\Throwable $e) {
            $this->userDAO->rollback();
            throw $e;
        }
    }

    public function unfollow(int $targetUserId, int $currentUserId): bool {
        if (!$this->isFollowing($targetUserId, $currentUserId)) {
            throw new \InvalidArgumentException('currentUser não segue este targetUser');
        }
        $this->userDAO->beginTransaction();
        try {
            $result = $this->followDAO->unfollow($currentUserId, $targetUserId);
            if (!$result) {
                throw new \RuntimeException('Erro ao deixar de seguir');
            }

            $this->userDAO->decrementFollowers($targetUserId);
            $this->userDAO->decrementFollowing($currentUserId);
            $this->userDAO->commit();
            return true;
        } catch (\Throwable $e) {
            $this->userDAO->rollback();
            throw $e;
        }
    }

    public function handleFollow(int $profileId, int $currentUserId, string $action): bool
    {
        return match ($action) {
            'follow' => $this->follow($profileId, $currentUserId),
            'unfollow' => $this->unfollow($profileId, $currentUserId),
            default => throw new \InvalidArgumentException('Action invalida: ' . $action)
        };
    }

    public function getFollowersCount(int $userId): int{
        return $this->userDAO->getFollowers($userId);
    }

    public function getFollowingCount(int $userId): int{
        return $this->userDAO->getFollowing($userId);
    }
}