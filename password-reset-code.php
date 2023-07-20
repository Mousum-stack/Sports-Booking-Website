<?php
session_start();

include('admin/inc/db_config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';


function send_password_reset($get_name,$get_email,$token)
{
    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->SMTPAuth = true; 

    $mail->Host = "smtp.gmail.com";
    $mail->Username = "gogoiraj239@gmail.com";
    $mail->Password = "vbduujyusmvtlrrc";

    $mail->SMTPSecure = "tls";
    $mail->Port = 587; 

    $mail->setFrom("gogoiraj239@gmail.com",$get_name);
    $mail->addAddress($get_email);

    $mail->isHTML(true);
    $mail->Subject = 'Resend Email Verification';

    $email_template = "
        <h2>You are registered with email</h2>
        <h5>Verify your email address with the link given below</h5>
        <br/><br/>
        <a href='http://localhost/mp2023/password-change.php?token=$token&email=$get_email'>Click me</a>
    ";
    $mail->Body = $email_template;
    $mail->send(); 
}



if(isset($_POST['password_reset_link']))
{
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $token = md5(rand());

    $check_email = "SELECT * FROM `users` WHERE email='$email' LIMIT 1";
    $check_email_run = mysqli_query($con, $check_email);

    if(mysqli_num_rows($check_email_run) >0)
    {
        $row = mysqli_fetch_array($check_email_run);
        $get_name = $row['name'];
        $get_email = $row['email'];


        $update_token = "UPDATE `users` SET `verify_token`='$token' WHERE email='$get_email' LIMIT 1";
        $update_token_run = mysqli_query($con,$update_token);

        if($update_token_run)
        {   
            send_password_reset($get_name,$get_email,$token);
            $_SESSION['status'] = "A Password reset link has been sent to your mail!";
            header("Location: password-reset.php");
            exit(0);

        }
        else
        {
            $_SESSION['status'] = "Something Went Wrong. #1";
            header("Location: password-reset.php");
            exit(0);
        }

    }
    else{
        $_SESSION['status'] = "No Email Found";
        header("Location: password-reset.php");
        exit(0);
    }

}


if(isset($_POST['password_update']))
{
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $new_password = mysqli_real_escape_string($con, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);
    $token = mysqli_real_escape_string($con, $_POST['password_token']);

    if(!empty($token))
    {
        if(!empty($email) && !empty($new_password) && !empty($confirm_password))
        {
                // checking token vlidation
                $check_token = "SELECT `verify_token` FROM `users` WHERE verify_token='$token' LIMIT 1";
                $check_token_run = mysqli_query($con, $check_token);
                
                if(mysqli_num_rows($check_token_run) > 0)
                {
                        if($new_password == $confirm_password)
                        {
                            $update_password = "UPDATE `users` SET `password`='$new_password' WHERE verify_token='$token' LIMIT 1";
                            $update_password_run = mysqli_query($con, $update_password);


                            if($update_password_run)
                            {
                                $new_token = md5(rand())."funda"; 
                                $update_to_new_token = "UPDATE `users` SET `verify_token`='$new_token' WHERE verify_token='$token' LIMIT 1";
                                $update_to_new_token_run = mysqli_query($con, $update_to_new_token);
                                $_SESSION['status'] = "Password changed Successfully";
                                header("Location: login.php");
                                exit(0);
                            }
                            else
                            {
                                $_SESSION['status'] = "Password not updated";
                                header("Location: password-change.php?token=$token&email=$email");
                                exit(0);
                            }
                        }
                        else
                        {
                            $_SESSION['status'] = "Password and confirm password doesnot match";
                            header("Location: password-change.php?token=$token&email=$email");
                            exit(0);
                        }
                }
                else
                {
                    $_SESSION['status'] = "Invalid Token";
                    header("Location: password-change.php?token=$token&email=$email");
                    exit(0);
                }
        }
        else
        {
            $_SESSION['status'] = "ALL fields are mandetory";
            header("Location: password-change.php?token=$token&email=$email");
            exit(0);
        }
    }
    else
    {
        $_SESSION['status'] = "No Token Available";
        header("Location: password-change.php");
        exit(0);
    }

}

?>