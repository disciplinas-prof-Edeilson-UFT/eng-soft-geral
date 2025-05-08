<?php
namespace Conex\T07Composer\utils;

use Conex\T07Composer\dao\FollowDAO;
use Conex\T07Composer\dao\UserDAO;

require_once __DIR__ . '/../../dir-config.php';

class FollowHandler
{
    private FollowDAO $followDAO;
    private UserDAO $userDAO;

    public function __construct()
    {
        $this->followDAO = new FollowDAO();
        $this->userDAO = new UserDAO();
    }

    public function handleFollow($userId, $loggedInUserId, $isFollowing, $action)
    {
        $users = $this->userDAO->getUserProfileById($userId);
        $loggedInUser = $this->userDAO->getUserProfileById($loggedInUserId);
        $originalFollowerCount = $users['count_followers'];
        $originalFollowingCount = $loggedInUser['count_following'];

        if ($action === 'follow') {
            if (!$isFollowing) {
                try {
                    $this->followDAO->followUser($userId, $loggedInUserId);
                    $users['count_followers']++;
                    $loggedInUser['count_following']++;
                } catch (\Exception $e) {
                    error_log("Error following user: " . $e->getMessage());
                    header("Location: " . BASE_URL . "view/profile.php?id=$userId");
                    exit();
                }
            }
        } else if ($action === 'unfollow') {
            if ($isFollowing) {
                try {
                    $this->followDAO->unfollowUser($userId, $loggedInUserId);
                    $users['count_followers']--;
                    $loggedInUser['count_following']--;
                } catch (\Exception $e) {
                    error_log("Error unfollowing user: " . $e->getMessage());
                    header("Location: " . BASE_URL . "view/profile.php?id=$userId");
                    exit();
                }
            }
        }

        if ($users['count_followers'] !== $originalFollowerCount) {
            try {
                if ($users['count_followers'] < 0) {
                    $users['count_followers'] = 0;
                }
                $this->userDAO->updateUserFollowerCount($userId, $users['count_followers']);
            } catch (\Exception $e) {
                error_log("Error updating follower count: " . $e->getMessage());
                $_SESSION['error_message'] = "Failed to update follower count. Please try again.";
            }
        }

        if ($loggedInUser['count_following'] !== $originalFollowingCount) {
            try {
                if ($loggedInUser['count_following'] < 0) {
                    $loggedInUser['count_following'] = 0;
                }
                $this->userDAO->updateUserFollowingCount($loggedInUserId, $loggedInUser['count_following']);
            } catch (\Exception $e) {
                error_log("Error updating following count: " . $e->getMessage());
                $_SESSION['error_message'] = "Failed to update following count. Please try again.";
            }
        }

        header("Location: " . BASE_URL . "view/profile.php?id=$userId");
        exit();
    }
}
