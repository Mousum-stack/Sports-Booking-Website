<?php
session_start();
include('admin/inc/db_config.php');
include('INC/links.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
//Load Composer's autoloader
require 'vendor/autoload.php';

function sendemail_verify($name, $email, $verify_token) {
    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->SMTPAuth = true; 

    $mail->Host = "smtp.gmail.com";
    $mail->Username = "gogoiraj239@gmail.com";
    $mail->Password = "vbduujyusmvtlrrc";

    $mail->SMTPSecure = "tls";
    $mail->Port = 587; 

    $mail->setFrom("gogoiraj239@gmail.com",$name);
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Email Verification';

    $email_template = "
        <h2>You are registered with email</h2>
        <h5>Verify your email address with the link given below</h5>
        <br/><br/>
        <a href='http://localhost/verify-email.php?token=$verify_token'>Click me</a>
    ";
    $mail->Body = $email_template;
    $mail->send();
}

if (isset($_POST['register_btn'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $address = $_POST['address'];
    $pin = $_POST['pin'];
    $dob = $_POST['dob'];
    $verify_token = md5(rand());
  

        // check_email_query

        $check_email_query = "SELECT  `email` FROM `users` WHERE email='$email' LIMIT 1";
        $check_email_query_run = mysqli_query($con, $check_email_query);
    
        if(mysqli_num_rows($check_email_query_run) > 0)
        {
            $_SESSION['status'] = "Email ID already Exist";
            header("Location: register.php");
            
        }
        else{
        // Insert User / Register User data
        $query = "INSERT INTO `users`(`name`, `phone`, `email`, `password`, `address`, `pin`, `dob`, `verify_token`) VALUES ('$name','$phone','$email','$password','$address','$pin','$dob','$verify_token')";
        $query_run = mysqli_query($con,$query);
    
        if($query_run)
        {
            sendemail_verify("$name","$email","$verify_token");
            $_SESSION['status'] = "Registration Successfull. ! Please verify your Email address";
            header("Location: register.php");
        }
        else{
                $_SESSION['status'] = "Registration Failed";
                header("Location: register.php");
        }
     }
    }
    ?>
