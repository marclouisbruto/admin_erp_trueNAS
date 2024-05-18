<?php
include '../connect.php';
session_start();

$adminID = $_GET['adminID'];
if(isset($adminID)){
    $_SESSION = array();
    session_destroy();
    header('Location: ../LogIn_SignUp_Admin/Login/login.php');
    exit;
}