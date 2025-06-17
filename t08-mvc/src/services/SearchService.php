<?php
namespace src\services;

use src\database\dao\UserDAO;



class SearchService{
    public UserDAO $userDAO;

    public function __construct(UserDAO $userDAO)
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