<?php
require_once __DIR__ . '/../../../database.php';
require_once __DIR__ . '/../../dao/follow-dao.php';
require_once __DIR__ . '/../../dao/user-dao.php';
require_once __DIR__ . '/../../../dir-config.php';
require_once __DIR__ . '/../../dao/posts-dao.php';
require_once __DIR__ . "/../../utils/follow-handler.php";

session_start();
if (!isset($_SESSION['user_id'] )) {
    header("Location: " . BASE_URL . "view/login.php");
    exit;
}

$user_id = isset($_GET['id']) ? intval($_GET['id']) : $_SESSION['user_id'];
$logged_in_user_id = $_SESSION['user_id'];

$userDao = new UserDao();
$user = $userDao->getUserProfileById($user_id);

if (!$user) {
    die("<p>Usuário não encontrado.</p>");
}


$userName = $user['username']; 
$profilePhoto = !empty($user['profile_pic_url'])
    ? BASE_URL . 'uploads/avatars/' . htmlspecialchars($user['profile_pic_url'])
    : BASE_URL . 'public/img/profile.svg';

$followDao = new FollowDao();
$isFollowing = $followDao->isFollowing($user_id, $logged_in_user_id);

$followers = $followDao->getFollowers($user_id);
$following = $followDao->getFollowing($user_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    $followHandler = new FollowHandler();
    $followHandler->handleFollow($user_id, $logged_in_user_id, $isFollowing, $action);
}

$postsDao = new PostsDao();
$userPosts = $postsDao->getPostsById($user_id);

$userProfileData = [
    'username' => htmlspecialchars($user['username']),
    'user_id' => $user_id,
    'logged_in_user_id' => $logged_in_user_id,
    'profile_Pic_Url' => htmlspecialchars($profilePhoto ?? ''),
    'is_Following' => $isFollowing,
    'followers' => $followers,
    'following' => $following,
    'bio' => htmlspecialchars($user['bio'] ?? ''),
    'photos' => $userPosts,
];

?>