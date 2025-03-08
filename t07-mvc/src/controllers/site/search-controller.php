<?php

namespace src\controllers\site;
use src\controllers\BaseController;
use src\services\SearchService;
use src\database\dao\IUserDAO;
use core\mvc\IModelRepository;
use src\database\dao\UserDAO;
use core\mvc\ModelRepository;


require_once __DIR__ . '/../../database/dao/user-dao.php';
require_once __DIR__ . '/../../../core/mvc/model-repository.php';
require_once __DIR__ . '/../../services/search-service.php';

class SearchController extends BaseController {
    public $searchService;
    public IUserDAO $userDAO;
    public IModelRepository $IModelRespot;

    public function __construct() {
        $this->IModelRespot = new ModelRepository();
        $this->userDAO = new UserDAO($this->IModelRespot);
        $this->searchService = new SearchService($this->userDAO);
    }
    
    public function search() {
        $this->searchService->searchUsers($this->query('query'));
        
    }
}