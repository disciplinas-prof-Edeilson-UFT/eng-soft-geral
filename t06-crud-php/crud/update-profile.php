<?php

include('../database.php');
include('repository/profile-repository.php');

$db = new Database();
$profileRepository = new ProfileRepository();

$name  = $_POST['name'];
$email = $_POST['email'];
$bio   = $_POST['bio'];
$phone = $_POST['phone']; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = $_GET['id'];
    try {
        $profileRepository->updateProfile($db, $name, $email, $bio, $phone, $id);
        header('Location: profile.php?id=$id');
        exit;
    } catch(Exception $e) { 
        echo 'Erro: ' . $e->getMessage();
    }
} else {
    echo "Dados inválidos";
}