<?php 
    session_start();
    

    if(!isset($_SESSION['authenticated']))
    {
        $_SESSION['status'] = "Please Login to book venues and access your profile";
        header('Location: login.php');
        exit(0);
    }
?>