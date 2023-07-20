<?php
session_start();
require('../inc/db_config.php');
require('../inc/essentials.php');
adminLogin();


if(isset($_POST['add_room']))
{
  $features = filteration(json_decode($_POST['features']));
  $facilities = filteration(json_decode($_POST['facilities']));

  $frm_data = filteration($_POST);
  $flag = 0;

  $q1 = "INSERT INTO `venues` (`name`, `area`, `price`, `description`) VALUES (?,?,?,?)";
  $values = [$frm_data['name'], $frm_data['area'], $frm_data['price'], $frm_data['desc']];

  if(insert($q1, $values, 'siis')){
    $flag = 1;
  }

  $room_id = mysqli_insert_id($con);

  $q2 = "INSERT INTO `venues_facilities`(`venues_id`, `facilities_id`) VALUES (?,?)";

  if($stmt = mysqli_prepare($con, $q2)){
    foreach($facilities as $f){
      mysqli_stmt_bind_param($stmt, 'ii', $room_id, $f);
      mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);
  }
  else{
    $flag = 0;
    die('query cannot be prepared - insert');
  }


  $q3 = "INSERT INTO `venues_features`(`venues_id`, `features_id`) VALUES (?,?)";

  if($stmt = mysqli_prepare($con, $q3)){
    foreach($features as $f){
      mysqli_stmt_bind_param($stmt, 'ii', $room_id, $f);
      mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);
  }
  else{
    $flag = 0;
    die('query cannot be prepared - insert');
  }

  if($flag){
    echo 1;
  }
  else{
    echo 0;
  }

}


if(isset($_POST['get_all_rooms']))
{
  $res = select("SELECT * FROM `venues` WHERE `removed`=?",[0],'i');

  $i=1;

  $data = "";

  while($row = mysqli_fetch_assoc($res)){
    if ($row['status'] == 1) {
    $status = "<button onclick='toggle_status($row[id],0)' class='btn btn-dark btn-sm shadow-none'>active</button>";
  } else {
    $status = "<button onclick='toggle_status($row[id],1)' class='btn btn-warning btn-sm shadow-none'>inactive</button>";
  }
  

    $data.="
    <tr class='align-middle'>
    <td>$i</td>
    <td>$row[name]</td>
    <td>$row[area] sq. ft.</td>
    <td>₹$row[price]</td>
    <td>$status</td>
    <td>
      <button type='button' onclick='edit_details($row[id])' class='btn btn-primary shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#edit-room'>
                  <i class='bi bi-pencil-square'></i>
      </button>
      <button type='button' onclick=\"room_images($row[id],'$row[name]')\" class='btn btn-info shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#room-images'>
        <i class='bi bi-images'></i>
       </button>
       <button type='button' onclick='remove_room($row[id])' class='btn btn-danger shadow-none btn-sm'>
       <i class='bi bi-trash'></i>
      </button>
    </td>
    </tr>
    ";
    $i++;
  }

  echo $data;
}

if(isset($_POST['get_room']))
{
  $frm_data = filteration($_POST);
  
  $res1 = select("SELECT * FROM `venues` WHERE `id`=?",[$frm_data['get_room']],'i');
  $res2 = select("SELECT * FROM `venues_features` WHERE `venues_id`=?",[$frm_data['get_room']],'i');
  $res3 = select("SELECT * FROM `venues_facilities` WHERE `venues_id`=?",[$frm_data['get_room']],'i');

  $roomdata = mysqli_fetch_assoc($res1);
  $features = [];
  $facilities = [];


  if(mysqli_num_rows($res2)>0){
    while($row = mysqli_fetch_assoc($res2)){
      array_push($features,$row['features_id']);
    }
  }

  if(mysqli_num_rows($res3)>0){
    while($row = mysqli_fetch_assoc($res3)){
      array_push($facilities,$row['facilities_id']);
    }
  }

  $data = ["roomdata" => $roomdata, "features" => $features, "facilities" => $facilities];

  $data = json_encode($data);

  echo $data;
}

if (isset($_POST['edit_room']))
 {
  $features = filteration(json_decode($_POST['features']));
  $facilities = filteration(json_decode($_POST['facilities']));

  $frm_data = filteration($_POST);

  $q1 = "UPDATE `venues` SET `name`=?, `area`=?, `price`=?, `description`=? WHERE `id`=?";
  $values = [$frm_data['name'], $frm_data['area'], $frm_data['price'], $frm_data['desc'], $frm_data['room_id']];

  $q2 = "INSERT INTO `venues_facilities`(`venues_id`, `facilities_id`) VALUES (?,?)";
  $q3 = "INSERT INTO `venues_features`(`venues_id`, `features_id`) VALUES (?,?)";

  $con->begin_transaction();
  try {
    // Update the venue
    $stmt1 = $con->prepare($q1);
    $stmt1->bind_param('ssisi', ...$values);
    if (!$stmt1->execute()) {
      throw new Exception("Failed to update venue: " . $stmt1->error);
    }

    // Delete existing features and facilities
    $stmt2 = $con->prepare("DELETE FROM `venues_features` WHERE `venues_id`=?");
    $stmt2->bind_param('i', $frm_data['room_id']);
    if (!$stmt2->execute()) {
      throw new Exception("Failed to delete features: " . $stmt2->error);
    }
    $stmt3 = $con->prepare("DELETE FROM `venues_facilities` WHERE `venues_id`=?");
    $stmt3->bind_param('i', $frm_data['room_id']);
    if (!$stmt3->execute()) {
      throw new Exception("Failed to delete facilities: " . $stmt3->error);
    }

    // Insert new features and facilities
    $stmt4 = $con->prepare($q2);
    $stmt4->bind_param('ii', $frm_data['room_id'], $f);
    foreach ($facilities as $f) {
      if (!$stmt4->execute()) {
        throw new Exception("Failed to insert facility: " . $stmt4->error);
      }
    }

    $stmt5 = $con->prepare($q3);
    $stmt5->bind_param('ii', $frm_data['room_id'], $f);
    foreach ($features as $f) {
      if (!$stmt5->execute()) {
        throw new Exception("Failed to insert feature: " . $stmt5->error);
      }
    }

    // Commit the transaction
    $con->commit();
    echo 1;
  } catch (Exception $e) {
    // Roll back the transaction and report the error
    $con->rollback();
    echo "Error: " . $e->getMessage();
  }
}


if(isset($_POST['toggle_status']))
{
  $frm_data = filteration($_POST);

  $q = "UPDATE `venues` SET `status`=? WHERE `id`=?";

  $v = [$frm_data['value'],$frm_data['toggle_status']];

  if(update($q,$v,'ii')){
    echo 1;
  }
  else{
    echo 0;
  }
}

if(isset($_POST['get_room_images']))
{
  $frm_data = filteration($_POST);
  $res = select("SELECT * FROM `venue_images` WHERE `venues_id`=?",[$frm_data['get_room_images']],'i');

  $path = VENUES_IMG_PATH;

  while($row = mysqli_fetch_assoc($res))
  {
    if ($row['thumb'] == 1) {
      $thumb_btn = "<i class='bi bi-check-lg text-light bg-success px-2 py-1 rounded fs-5'></i>";
    }
    else{
      $thumb_btn = " <button onclick='thumb_image({$row['sr_no']},{$row['venues_id']})' class='btn btn-secondary shadow-none'>
      <i class='bi bi-check-lg'></i>
    </button>";
    }
    echo <<<data
    <tr class='align-middle'> 
      <td><img src='{$path}{$row['image']}' class='img-fluid'></td>
      <td>{$thumb_btn}</td>
      <td>
        <button onclick='rem_image({$row['sr_no']},{$row['venues_id']})' class='btn btn-danger shadow-none'>
          <i class='bi bi-trash'></i>
        </button>
      </td>
    </tr>
    data;
    
  }
}

if (isset($_POST['rem_image']))
 {
  $frm_data = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_BACKTICK);
  $values = [$frm_data['image_id'], $frm_data['room_id']];

  $check_q = select('SELECT * FROM `venue_images` WHERE `sr_no`=? AND `venues_id`=?', $values, 'ii');

  if (mysqli_num_rows($check_q) == 1) {
    $q = "DELETE FROM `venue_images` WHERE `sr_no`=? AND `venues_id`=?";
    $res = delete_from_db($con, $q, 'ii', $values);
    echo $res;
  } else {
    echo 'image_not_found';
  }
}

if (isset($_POST['thumb_image']))
 {
  $frm_data = filteration($_POST);

  $pre_q = "UPDATE `venue_images` SET `thumb`=? WHERE `venues_id`=?";
  $pre_v = [0,$frm_data['room_id']];
  $pre_res = update($pre_q,$pre_v,'ii');

  $q = "UPDATE `venue_images` SET `thumb`=? WHERE `sr_no`=? AND `venues_id`=?";
  $v = [1,$frm_data['image_id'],$frm_data['room_id']];
  $res = update($q,$v,'iii');

  echo $res;
}

if(isset($_POST['remove_room']))
{
  $frm_data = filteration($_POST);

  $res1 = select("SELECT * FROM `venue_images` WHERE `venues_id`=?",[$frm_data['room_id']],'i');

  while($row = mysqli_fetch_assoc($res1)){
    deleteImage($row['image'],VENUES_FOLDER);
  }

  $res2 = delete_from_db($con,"DELETE FROM `venue_images` WHERE `venues_id`=?",'i',[$frm_data['room_id']]);
  $res3 = delete_from_db($con,"DELETE FROM `venues_features` WHERE `venues_id`=?",'i',[$frm_data['room_id']]);
  $res4 = delete_from_db($con,"DELETE FROM `venues_facilities` WHERE`venues_id`=?",'i',[$frm_data['room_id']]);
  $res5 = update("UPDATE `venues` SET `removed`=? WHERE `id`=?",[1,$frm_data['room_id']],'ii');


  if($res2 || $res3 || $res4 || $res5){
    echo 1;
  }
  else{
    echo 0;
  }



}


?>
