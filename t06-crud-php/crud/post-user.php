<?php

include('../database.php');
include('repository/profile-repository.php');

$db = new Database();
$profileRepository = new ProfileRepository();

$bio = $_POST['bio'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
    $bio = trim($_POST['bio']);
    try {
        if($_GET["id"]){
            $id = $_GET["id"];
            
            $profileRpeository->updateUser($name, $email, $bio, $phone, $id);
            header("Location: perfil.php?id=$id");
            exit;
        }
    } catch(Exception $e) { 
        echo 'Erro: ' . $e->getMessage();
    }

} else {
    header('Location: index.php');
    exit;
}