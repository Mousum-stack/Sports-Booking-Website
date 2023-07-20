<?php

session_start();

require('db_config.php');

if(isset($_POST['stud_image']))
{
  $image = $_FILES['picture']['name'];

  $allowed_extensions = array('gif','png','jpeg','jpg');
  $filename = $_FILES['picture']['name'];
  $file_extension = pathinfo($filename, PATHINFO_EXTENSION);

  if(!in_array($file_extension, $allowed_extensions))
  {
    $_SESSION['status'] = "You are allowed with only jpg, png, jpeg and gif extensions.";
    header('Location: ../carousel.php');
    exit(); // Exit the script after redirection
  }
  else
  {
    if(file_exists("images/carousel/" . $_FILES['picture']['name']))
    {
        $filename = $_FILES['picture']['name'];
        $_SESSION['status'] = "Image already exists: ".$filename;
        header('Location: ../carousel.php');
        exit(); // Exit the script after redirection
    }
    else
    {
        $q = "INSERT INTO `carousel`(`image`) VALUES (?)";
        $q_prepare = mysqli_prepare($con,$q);

        if ($q_prepare) {
          mysqli_stmt_bind_param($q_prepare, "s", $image);
          $q_execute = mysqli_stmt_execute($q_prepare);

          if ($q_execute) {
              move_uploaded_file($_FILES["picture"]["tmp_name"],"images/carousel/".$_FILES["picture"]["name"]);
              $_SESSION['status'] = "Image stored successfully";
              header('Location: ../carousel.php');
              exit(); // Exit the script after redirection
          }
          else{
              $_SESSION['status'] = "Image not stored successfully!";
              header('Location: ../carousel.php');
              exit(); // Exit the script after redirection
          }
        }
        else {
          $_SESSION['status'] = "Failed to prepare query!";
          header('Location: ../carousel.php');
          exit(); // Exit the script after redirection
        }
    }
  }
}

?>