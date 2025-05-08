<?php
/*session_start();
require_once __DIR__ . '/dir-config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "view/login.php");
    exit;
}

$userName = isset($_SESSION['user_name']);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
    <link rel="stylesheet" href="./public/css/feed.css">
</head>

<body>
    <?php require_once __DIR__ . '/view/feed.php'; ?>
</body>

</html>*/

session_start();
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/dir-config.php';

use Conex\T07Composer\controllers\site\FeedController;
use Conex\T07Composer\controllers\site\AuthController;
use Conex\T07Composer\Controllers\site\ProfileController;
use Conex\T07Composer\Controllers\site\SearchController;
use Conex\T07Composer\middlewares\AuthMiddleware;


$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$route = trim($request_uri, '/') ?: 'home';

$authMiddleware = new AuthMiddleware();

switch ($route) {
    case 'feed':
        $authMiddleware->handle(function () {
            $controller = new FeedController();
            $controller->show();
        });

        break;
    case 'login':
        $controller = new AuthController();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login($_POST);
        } else {
            $controller->showLoginForm();
        }
        break;
    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;
    case 'register':
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->register($_POST);
        } else {
            $controller->showRegisterForm();
        }
        break;
    case 'profile':
        $authMiddleware->handle(function () {
            $userId = isset($_GET['id']) ? intval($_GET['id']) : $_SESSION['user_id'];
            $controller = new ProfileController();
            $controller->show($userId);
        });

        break;
    case 'profile/update':
        $authMiddleware->handle(function () {
            $userId = isset($_GET['id']) ? intval($_GET['id']) : $_SESSION['user_id'];
            $controller = new ProfileController();
            $controller->edit($userId);
        });

        break;
    case 'profile/update/save':
        $authMiddleware->handle(function () {
            $userId = isset($_GET['id']) ? intval($_GET['id']) : $_SESSION['user_id'];
            $controller = new ProfileController();
            $controller->update($userId);
        });

        break;
    case 'profile/follow':
        $authMiddleware->handle(function () {
            $userId = isset($_GET['id']) ? intval($_GET['id']) : $_SESSION['user_id'];
            $controller = new ProfileController();
            $controller->follow($userId);
        });

        break;
    case 'profile/delete':
        $authMiddleware->handle(function () {
            $userId = isset($_GET['id']) ? intval($_GET['id']) : $_SESSION['user_id'];
            $controller = new ProfileController();
            $controller->delete($userId);
        });

        break;
        
    case 'search':
        $controller = new SearchController();
        $controller->search();
        break;
    default:
        http_response_code(404);
        echo "Página não encontrada.";
        exit;
}