<?php
namespace src\services;

use src\database\dao\IUserDAO;

require_once __DIR__ . '/../database/domain/user.php';
require_once __DIR__ . '/../database/dao/user-dao.php';

class SearchService{
    public IUserDAO $userDAO;

    public function __construct(IUserDAO $userDAO)
    {
        $this->userDAO = $userDAO;
    }

    public function searchUsers($username)
    {
        $table = 'users';
        $conditions = ['username' => $username];
        $data = ['id', 'username','email', 'profile_pic_url'];
        
        return $this->userDAO->find($table, $conditions, $data);
    }
}