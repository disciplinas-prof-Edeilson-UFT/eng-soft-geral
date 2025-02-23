<?php
require __DIR__ . '/../../../database.php';
require __DIR__ . '/../../follow-dao.php';
require __DIR__ . '/../../user-dao.php';
require_once __DIR__ . '/../../../config.php';

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

$userName = $userDao->getUserNameById($logged_in_user_id);
$userPhoto = $userDao->getUserProfilePhotoById($logged_in_user_id);

$isFollowing = isFollowing($user_id, $logged_in_user_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    $followController = new FollowController();
    $followController->handleFollow($user_id, $logged_in_user_id, $isFollowing, $action);
}

$userProfileData = [
    'username' => htmlspecialchars($user['username']),
    'user_id' => $user_id,
    'logged_in_user_id' => $logged_in_user_id,
    'profile_Pic_Url' => htmlspecialchars($user['profile_pic_url']),
    'is_Following' => $isFollowing,
    'followers' => getFollowers($user_id),
    'following' => getFollowing($user_id),
    'bio' => htmlspecialchars($user['bio'])
];

?>