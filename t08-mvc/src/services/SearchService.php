<?php
declare(strict_types=1);

namespace src\services;

use src\database\dao\UserDAO;


class SearchService
{
    private UserDAO $userDAO;

    public function __construct(UserDAO $userDAO)
    {
        $this->userDAO = $userDAO;
    }

    public function searchUsers(string $username): array
    {
        $term = trim($username);
        if ($term === '') {
            return [];
        }
        return $this->userDAO->searchUsers($term);
    }
}