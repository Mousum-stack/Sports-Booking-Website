<?php
session_start();
include('admin/inc/db_config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
//Load Composer's autoloader
require 'vendor/autoload.php';

function resend_email_verify($name, $email, $verify_token)
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

    $mail->setFrom("gogoiraj239@gmail.com",$name);
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Resend Email Verification';

    $email_template = "
        <h2>You are registered with email</h2>
        <h5>Verify your email address with the link given below</h5>
        <br/><br/>
        <a href='http://localhost/mp2023/verify-email.php?token=$verify_token'>Click me</a>
    ";
    $mail->Body = $email_template;
    $mail->send();  
}
if(isset($_POST['resend_email_verify_btn']))
{
  
        if(!empty(trim($_POST['email'])))
        {
            $email = mysqli_real_escape_string($con,$_POST['email']);

            $checkemail_query = "SELECT * FROM `users` WHERE email='$email' LIMIT 1";
            $checkemail_query_run = mysqli_query($con, $checkemail_query);

            if(mysqli_num_rows($checkemail_query_run) >0)
            {
                $row = mysqli_fetch_array($checkemail_query_run);
                if($row['verify_status'] == "0")
                {
                        $name = $row['name'];
                        $email = $row['email'];
                        $verify_token = $row['verify_token'];

                        resend_email_verify($name,$email,$verify_token);

                        
                        $_SESSION['status'] = "Verification Email link has been sent to your email adress";
                        header("Location: login.php");
                        exit(0); 
                }
                else{
                    $_SESSION['status'] = "Email already verified please Login";
                    header("Location: resend-email-verification.php");
                    exit(0); 
                }
            }
            else{
                $_SESSION['status'] = "Email is not register please register now";
                header("Location: register.php");
                exit(0);
            }
        }
        else
        {
            $_SESSION['status'] = "Please enter the email field";
            header("Location: resend-email-verification.php");
            exit(0);
        }
    }

?>