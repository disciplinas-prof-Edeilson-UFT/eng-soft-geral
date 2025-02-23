<?php
require_once __DIR__ . '/../../dao/follow-dao.php';
require_once __DIR__ . '/../../../config.php';

class FollowController {
    private $followDao;

    public function __construct() {
        $this->followDao = new FollowDao();
    }

    public function handleFollow($userId, $loggedInUserId, $isFollowing, $action) {
        if ($action === 'follow') {
            if (!$isFollowing) {
                $this->followDao->followUser($userId, $loggedInUserId);
            }
        } elseif ($action === 'unfollow') {
            if ($isFollowing) {
                $this->followDao->unfollowUser($userId, $loggedInUserId);
            }
        }
        header("Location: " . BASE_URL . "view/profile.php?id=$userId");
        exit();

    }
}