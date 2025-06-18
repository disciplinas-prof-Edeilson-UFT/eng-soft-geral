<?php
session_start();

$_SESSION = array();

session_destroy();
header("Location: ../view/login.php");
exit();
?>