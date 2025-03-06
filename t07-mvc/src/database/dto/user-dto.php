<?php 

class UserDto{
    private $name;
    private $email;
    private $password;
    private $phone;

    public function __construct($name, $email, $password, $phone){
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->phone = $phone;
    }
}