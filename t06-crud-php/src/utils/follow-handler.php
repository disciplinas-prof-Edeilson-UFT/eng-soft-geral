<?php
require_once __DIR__ . '/../dao/follow-dao.php';
require_once __DIR__ . '/../../dir-config.php';

class FollowHandler {
    private $followDao;
    private $userDao;

    public function __construct() {
        $this->followDao = new FollowDao();
        $this->userDao = new UserDao();

    }

    public function handleFollow($userId, $loggedInUserId, $isFollowing, $action) {
        $users = $this->userDao->getUserProfileById($userId);
        $originalFollowerCount = $users['count_followers'];

        if ($action === 'follow') {
            if (!$isFollowing) {
                try {
                    $this->followDao->followUser($userId, $loggedInUserId);
                    $users['count_followers']++;
                } catch (Exception $e) {
                    error_log("Error following user: " . $e->getMessage());
                    header("Location: " . BASE_URL . "view/profile.php?id=$userId");
                    exit();
                }
            }

        } elseif ($action === 'unfollow') {
            if ($isFollowing) {
                try {
                    $this->followDao->unfollowUser($userId, $loggedInUserId);
                    $users['count_followers']--;
                } catch (Exception $e) {
                    error_log("Error unfollowing user: " . $e->getMessage());
                    header("Location: " . BASE_URL . "view/profile.php?id=$userId");
                    exit();
                }
            }
        }

        if ($users['count_followers'] !== $originalFollowerCount) {
            try {
                if($users['count_followers'] < 0) {
                    $users['count_followers'] = 0;
                }
                $this->userDao->updateUserFollowerCount($userId, $users['count_followers']);
            } catch (Exception $e) {
                error_log("Error updating follower count: " . $e->getMessage());
                $_SESSION['error_message'] = "Failed to update follower count. Please try again.";
            }
        }
        header("Location: " . BASE_URL . "view/profile.php?id=$userId");
        exit();

    }
}