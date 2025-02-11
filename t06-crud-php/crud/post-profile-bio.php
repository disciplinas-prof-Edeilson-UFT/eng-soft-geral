<?php

include('../database.php');
include('repository/profile-repository.php');

$db = new Database();
$profileRepository = new ProfileRepository();

$bio = $_POST['bio'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bio = trim($_POST['bio']);
    $id  = $_GET['id'];

    try {
        $profileRepository->updateBio($db, $bio, $id);
        header('Location: profile.php?id=$id');
        exit;
    } catch(Exception $e) { 
        echo 'Erro: ' . $e->getMessage();
    }
} else {
    echo "Dados inválidos";
}