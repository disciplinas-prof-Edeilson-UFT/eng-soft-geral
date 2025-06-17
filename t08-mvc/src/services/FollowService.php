<?php
namespace src\services;

use src\database\dao\FollowDAO;
use src\database\dao\UserDAO;
use src\database\domain\User;
use src\database\domain\Follow;

class FollowService{
    public FollowDAO $followDAO;
    public User $user;
    public UserDAO $userDAO;
    public Follow $follow;  

    public function __construct(FollowDAO $followDAO, User $user, UserDAO $userDAO)
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