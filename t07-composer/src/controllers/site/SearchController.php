<?php 
namespace Conex\T07Composer\controllers\site;

use Conex\T07Composer\Database;
use Conex\T07Composer\dao\SearchDAO;


class SearchController {
    public SearchDAO $searchDao;

    public function __construct() {
        $database = Database::getInstance();
        $this->searchDao = new SearchDAO($database);
    }

    public function search() {
        $query = $_GET['query'] ?? '';
        $response = ['users' => []];
        
        if (!empty($query)) {
            $users = $this->searchDao->SearchUsers($query);
            foreach ($users as $user) {
                $response['users'][] = [
                    'id' => $user['id'],
                    'name' => $user['username'],
                    'photo' => !empty($user['profile_pic_url'])
                        ? BASE_URL . "uploads/avatars/" . $user['profile_pic_url']
                        : BASE_URL . "public/img/profile.svg"
                ];
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
} 