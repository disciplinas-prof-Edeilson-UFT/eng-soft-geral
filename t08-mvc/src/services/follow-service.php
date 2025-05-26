<?php
namespace src\services;

use src\database\dao\IFollowDAO;
use src\database\dao\IUserDAO;
use src\database\domain\User;
use src\database\domain\Follow;

require_once __DIR__ . '/../database/domain/user.php';
require_once __DIR__ . '/../database/dao/user-dao.php';
require_once __DIR__ . '/../database/domain/follow.php';
require_once __DIR__ . '/../database/dao/follow-dao.php';

class FollowService{
    public IFollowDAO $followDAO;
    public User $user;
    public IUserDAO $userDAO;
    public Follow $follow;  

    public function __construct(IFollowDAO $followDAO, User $user, IUserDAO $userDAO)
    {
        $this->followDAO = $followDAO;
        $this->user = $user;
        $this->userDAO = $userDAO;
        $this->follow = new Follow(null, null);
    }
    public function isFollowing($profileId, $currentUserId):bool {
        $result = $this->followDAO->find('follow', ['user_id' => $profileId,'follower_id' => $currentUserId], ['id']);
        if (empty($result)) {
            return false;
        }
        return true;
    }

    public function follow($profileId, $currentUserId):bool {
        $follow = new Follow($currentUserId, $profileId);
        
        $result = $this->followDAO->create('follow', $follow->toArray());
        
        if (!$result) {
            return false;
        }

        $this->userDAO->update('users', ['id' => $profileId], ['count_followers' => 'count_followers + 1']);
        $this->userDAO->update('users', ['id' => $currentUserId], ['count_following' => 'count_following + 1']);
        
        return true;
    }

    public function unfollow($profileId, $currentUserId):bool {
        $result = $this->followDAO->delete('follow', [
            'user_id' => $profileId,
            'follower_id' => $currentUserId
        ]);
        
        if (!$result) {
            return false;
        }

        $this->userDAO->update('users', ['id' => $profileId], ['count_followers' => 'count_followers - 1']);
        $this->userDAO->update('users', ['id' => $currentUserId], ['count_following' => 'count_following - 1']);
        
        return true;
    }

    public function handleFollow($profileId, $currentUserId, $action):bool {
        if ($action === 'follow') {
            return $this->follow($profileId, $currentUserId);
        } else if ($action === 'unfollow') {
            return $this->unfollow($profileId, $currentUserId);
        }
        return false;
    }



}