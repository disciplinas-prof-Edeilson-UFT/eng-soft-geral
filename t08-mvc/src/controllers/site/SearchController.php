<?php

namespace src\controllers\site;
use src\controllers\BaseController;
use src\services\SearchService;
use src\database\dao\UserDAO;


class SearchController extends BaseController {
    public $searchService;
    public UserDAO $userDAO;

    public function __construct() {
        $this->userDAO = new UserDAO();
        $this->searchService = new SearchService($this->userDAO);
    }
    
    public function search() {
        try {
            $query = $this->input('query');
            
            
            $results = $this->searchService->searchUsers($query);
            
            if (empty($results)) {
                return $this->redirect('/feed');
            }
            
            return $this->redirect("/profile/{$results[0]['id']}");
            
        } catch (\Exception $e) {
            return $this->view('search', ['error' => $e->getMessage()]);
        }
    }
}