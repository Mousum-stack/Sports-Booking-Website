<?php
// frontend purpose data

  define('SITE_URL' ,'http://127.0.0.1/mp2023/');
  define('ABOUT_IMG_PATH',SITE_URL.'images/about/');
  define('CAROUSEL_IMG_PATH',SITE_URL.'images/carousel/');
  define('FEATURES_IMG_PATH',SITE_URL.'images/features/');
  define('VENUES_IMG_PATH',SITE_URL.'images/venues/');

  // sendgrid api key
  define('SENDGRID_API_KEY',"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");


  define('UPLOAD_IMAGE_PATH',$_SERVER['DOCUMENT_ROOT'].'/mp2023/images/');
  define('ABOUT_FOLDER','about/');
  define('CAROUSEL_FOLDER','carousel/');
  define('FEATURES_FOLDER','features/');
  define('VENUES_FOLDER','venues/');
  define('USERS_FOLDER','users/');


  function adminLogin(){
    session_start();
     if(!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin']==true)){
        echo"
        <script>
        window.location.href='index.php';
        </script>";
        exit;
        }
  }
  function redirect($url){
    echo"
    <script>
       window.location.href='$url';
    </script>";
    exit;
  }

function alert($type,$msg){

      $bs_class= ($type == "success") ? "alert-success" : "alert-danger";

    echo <<<alert
    <div class="alert $bs_class alert-dismissible fade show custom-alert" role="alert">
      <strong class="me-3">$msg</strong>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
 alert;
}

function uploadImage($image, $folder)
 {
    // Check that the folder exists
    if (!is_dir(UPLOAD_IMAGE_PATH . $folder)) {
        mkdir(UPLOAD_IMAGE_PATH . $folder, 0777, true);
    }

    // Validate the image file
    $valid_mime = ['image/jpeg', 'image/png', 'image/jpg'];
    $img_mime = $image['type'];
    $img_size = $image['size'] / (1024*1024);
    switch (true) {
        case !in_array($img_mime, $valid_mime):
            return 'Invalid image type';
        case $img_size > 2:
            return 'Image size exceeds 2 MB';
        case !move_uploaded_file($image['tmp_name'], UPLOAD_IMAGE_PATH . $folder . '/IMG_' . random_int(11111, 99999) . '.' . pathinfo($image['name'], PATHINFO_EXTENSION)):
            return 'Failed to upload image';
        default:
            return true;
    }
}


function deleteImage($image, $folder)
{
  if (unlink(UPLOAD_IMAGE_PATH.$folder.$image)) {
    return true;
  } else {
    return false;
  }
}











?>