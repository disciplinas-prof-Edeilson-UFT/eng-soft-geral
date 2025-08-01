<?php
namespace src\services;

use src\database\dao\UserDAO;



class SearchService{
    public UserDAO $userDAO;

    public function __construct(UserDAO $userDAO)
    {
        $this->userDAO = $userDAO;
    }

    public function searchUsers($username): array {
        return $this->userDAO->searchUsers($username);
    }
}