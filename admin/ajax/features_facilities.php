<?php
session_start();
require('../inc/db_config.php');
require('../inc/essentials.php');
adminLogin();

if(isset($_POST['add_feature']))
{
    // Check if the form data is set
    
        $frm_data = filteration($_POST);

        // Insert the data into the database
        $q = "INSERT INTO `features`(`name`) VALUES (?)";
        $values = [$frm_data['name']];
        $res = insert($q,$values,'s');
        echo $res;
}

if(isset($_POST['get_features']))
{
  $res = selectAll('features');
  $i=1;

  while($row = mysqli_fetch_assoc($res))
  {
    echo <<<data
    <tr>
        <td>$i</td>
        <td>$row[name]</td>
        <td>
            <button type="button" onclick="rem_feature($row[id])" class="btn btn-danger btn-sm shadow-none">
            <i class="bi bi-trash3-fill"></i>  Delete
            </button>
        </td>
     </tr>
 data;
 $i++;
  }
}

if(isset($_POST['rem_feature']))
{
  $frm_data = filteration(($_POST));
  $values = [$frm_data['rem_feature']];

  $check_q = select('SELECT * FROM `venues_features` WHERE `features_id`=?',[$frm_data['rem_feature']],'i');

  if(mysqli_num_rows($check_q)==0){
  
  $q = "DELETE FROM `features` WHERE `id`=?";
  $res = delete_from_db($con,$q,'i',$values,);
  echo $res;
}
else{
  echo 'room_added';
}
}


if(isset($_POST['save_stud_image']))
{
  $name = $_POST['name'];
  $image = $_FILES['picture']['name'];
  $desc = $_POST['facility_desc'];



  $allowed_exttension = array('svg');
  $filename = $_FILES['picture']['name'];
  $file_extension = pathinfo($filename, PATHINFO_EXTENSION);
  if(!in_array($file_extension, $allowed_exttension))
  {
    $_SESSION['status'] = "You are allowed with only svg";
    header('Location: ../features_facilities.php');
  }
  else{
  
    if(file_exists("images/about/" . $_FILES['picture']['name']))
    {
        $filename = $_FILES['picture']['name'];
        $_SESSION['status'] = "Image Already exist".$filename;
        header('Location: ../features_facilities.php');
        }
        else{
            $q = "INSERT INTO `facilities`(`icon`, `name`, `description`) VALUES ('$image','$name','$desc')";
            $q_run = mysqli_query($con,$q);
            
            if($q_run){
              move_uploaded_file($_FILES["picture"]["tmp_name"],"images/features/".$_FILES["picture"]["name"]);
              $_SESSION['status'] = "image stored successfully";
              header('Location: ../features_facilities.php');
               // call the get_facilities() function here
          }
          
    else{
        $_SESSION['status'] = "image not stored successfully!";
        header('Location: ../features_facilities.php');
    }
    }
    }
}

if(isset($_POST['get_facilities']))
{
  $res = selectAll('facilities');
  $i=1;
  $path = FEATURES_IMG_PATH;

  while($row = mysqli_fetch_assoc($res))
  {
    echo <<<data
    <tr class="align-middle">
        <td>$i</td>
        <td><img src="$path$row[icon]" width="100px"></td>
        <td>$row[name]</td>
        <td>$row[description]</td>
        <td>
            <button type="button" onclick="rem_facility($row[id])" class="btn btn-danger btn-sm shadow-none">
            <i class="bi bi-trash3-fill"></i>  Delete
            </button>
        </td>
     </tr>
 data;
 $i++;
  }
}

if(isset($_POST['rem_facility']))
{
  $frm_data = filteration(($_POST));
  $values = [$frm_data['rem_facility']];

  $check_q = select('SELECT * FROM `venues_facilities` WHERE `facilities_id`=?',[$frm_data['rem_facility']],'i');

  if(mysqli_num_rows($check_q)==0){
  
  $q = "DELETE FROM `facilities` WHERE `id`=?";
  $res = delete_from_db($con,$q,'i',$values);
  echo $res;
  }
  else{
    echo 'room_added';
  }
}

  

?>
