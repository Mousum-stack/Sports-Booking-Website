<?php
session_start();
require('../inc/db_config.php');
require('../inc/essentials.php');
adminLogin();

// Check if form is submitted
if (isset($_POST['save_stud_image']))
 {

  // Get input values
  $venue_id = mysqli_real_escape_string($con, $_POST['room_id']);
  $image = $_FILES['image']['name'];

  // Set allowed file extensions
  $allowed_extensions = array('gif', 'png', 'jpeg', 'jpg');

  // Get file extension
  $filename = $_FILES['image']['name'];
  $file_extension = pathinfo($filename, PATHINFO_EXTENSION);

  // Check if file extension is allowed
  if (!in_array($file_extension, $allowed_extensions)) {
    $_SESSION['status'] = "You are allowed with only jpg png jpeg and gif";
    header('Location: ../venues.php');
    exit();
  } else {

    // Check if file upload was successful
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {

      // Check if file already exists
      if (file_exists("images/venues/" . $_FILES['image']['name'])) {
        $filename = $_FILES['image']['name'];
        $_SESSION['status'] = "Image Already exist" . $filename;
        header('Location: ../venues.php');
        exit();
      } else {

        // Insert record into database
        $q = "INSERT INTO `venue_images`(`venues_id`, `image`) VALUES ('$venue_id','$image')";
        $q_run = mysqli_query($con, $q);

        if ($q_run) {
          // Upload file
          move_uploaded_file($_FILES["image"]["tmp_name"], "images/venues/" . $_FILES["image"]["name"]);
          $_SESSION['status'] = "Image stored successfully";
          $modal_message = "Image stored successfully";
          $modal_status = "success";
        } else {
          $_SESSION['status'] = "Image not stored successfully!";
          $modal_message = "Image not stored successfully!";
          $modal_status = "danger";
        }
      }
    } else {
      $_SESSION['status'] = "File upload failed: " . $_FILES['image']['error'];
      $modal_message = "File upload failed: " . $_FILES['image']['error'];
      $modal_status = "danger";
    }
  }
}

// Redirect to modal with message
header("Location: ../venues.php?message={$modal_message}&status={$modal_status}#room-images");

?>