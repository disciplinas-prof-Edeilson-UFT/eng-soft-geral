<?php 
namespace src\services;

use src\database\dao\IUserDAO;
use src\database\domain\User;

require_once __DIR__ . '/../database/domain/user.php';
require_once __DIR__ . '/../database/dao/user-dao.php';


class AuthService {

    public IUserDAO $userDAO;
    public User $user;

    public function __construct(IUserDAO $userDAO, User $user)
    {
        $this->userDAO = $userDAO;
        $this->user = $user;
    }

    public function register($username, $email, $password, $confirm_password,$phone, $bio = null, $profile_pic_url = null){
        $user = new User($username, $email, null, $phone, $bio,$profile_pic_url);
        
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPasswordHash($password, $confirm_password);
        $user->setPhone($phone);
        $user->setBio($bio);
        $user->setProfilePicUrl($profile_pic_url);
        
        $result = $this->userDAO->create('users', $user);
        if(!$result){
            throw new \InvalidArgumentException("Erro ao criar usuário");
        }

        $createdUser =$this->userDAO->find('users', ['email' => $email], ['id']);

        if(empty($createdUser)){
            throw new \InvalidArgumentException("Erro ao recuperar usuário");
        }

        $user->setId($createdUser[0]->id);
    }
    
    public function login($email, $password)
    {
        $user = $this->userDAO->find('users', ['email' => $email], ['email', 'password_hash', 'id']);
        
        //var_dump($user);
        
        if($user){
            $userObject = $user[0];
            if(password_verify($password, $userObject->password_hash)){
                unset($userObject->password_hash);
                return $userObject;
            }
        }

        return false;
    }

}