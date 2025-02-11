<?php
    require_once 'database.php';
    require_once 'users.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $users = new Users();
        $users->createUser($name, $email, $password, $phone);
}