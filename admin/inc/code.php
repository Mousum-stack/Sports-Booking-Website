<?php

session_start();

require('db_config.php');




if(isset($_POST['save_stud_image']))
{
  $name = $_POST['name'];
  $image = $_FILES['picture']['name'];

  $allowed_exttension = array('gif','png','jpeg','jpg');
  $filename = $_FILES['picture']['name'];
  $file_extension = pathinfo($filename, PATHINFO_EXTENSION);
  if(!in_array($file_extension, $allowed_exttension))
  {
    $_SESSION['status'] = "You are allowed with only jpg png jpeg and gif";
    header('Location: ../settings.php');
  }
  else{
  
    if(file_exists("images/about/" . $_FILES['picture']['name']))
    {
        $filename = $_FILES['picture']['name'];
        $_SESSION['status'] = "Image Already exist".$filename;
        header('Location: ../settings.php');
        }
        else{
            $q = "INSERT INTO `aboutimg`(`name`, `image`) VALUES ('$name','$image')";
            $q_run = mysqli_query($con,$q);
            
    if($q_run){
        move_uploaded_file($_FILES["picture"]["tmp_name"],"images/about/".$_FILES["picture"]["name"]);
        $_SESSION['status'] = "image stored successfully";
        header('Location: ../settings.php');
    }
    else{
        $_SESSION['status'] = "image not stored successfully!";
        header('Location: ../settings.php');
    }
    }
    }
}

?> 