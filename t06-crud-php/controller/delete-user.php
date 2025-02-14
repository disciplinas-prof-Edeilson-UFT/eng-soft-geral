<?php 
include('../database.php');
include('repository/profile-repository.php');

$db = new Database();
$profileRepository = new ProfileRepository();

if (isset($_GET['id'])) {
    
    $id = $_GET['id'];
    try {
        $profileRepository->deleteProfile($db, $id);
        header("Location: profile.php?id=$id");
        exit;
    } catch(Exception $e) { 
        echo 'Erro: ' . $e->getMessage();
    }
} else {
    echo "ID inválido";
}