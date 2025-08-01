<?php
namespace src\controllers\site;

use src\controllers\BaseController;
use src\services\SearchService;
use src\database\dao\UserDAO;

class SearchController extends BaseController {
    private SearchService $searchService;

    public function __construct() {
        parent::__construct();
        $userDAO = new UserDAO();
        $this->searchService = new SearchService($userDAO);
    }

    public function search() {
        $query = $_GET['query'] ?? '';
        $response = ['users' => []];
        
        if (!empty($query)) {
            $users = $this->searchService->searchUsers($query);
            foreach ($users as $user) {
                $response['users'][] = [
                    'id' => $user['id'],
                    'name' => $user['username'],
                    'photo' => !empty($user['profile_pic_url'])
                        ?  "/public/uploads/avatars/" . $user['profile_pic_url']
                        :  "/public/img/profile.svg"
                ];
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
} 