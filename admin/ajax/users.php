<?php
require('../inc/db_config.php');
require('../inc/essentials.php');
adminLogin();


if(isset($_POST['get_users']))
{
  $res = selectAll('users');



  $i=1;

  $data = "";

  while($row = mysqli_fetch_assoc($res)){
    $del_btn = " <button type='button' onclick='remove_user($row[id])' class='btn btn-danger shadow-none btn-sm'>
    <i class='bi bi-trash'></i>
   </button>";
    $verified = "<span class='badge bg-danger'><i class='bi bi-x-lg'></i></span>";
    
    if($row['verify_status'])
    {
      $verified = "<span class='badge bg-success'><i class='bi bi-check-lg'></i></span>";
      $del_btn = "";
    }

    $status = "<button onclick='toggle_status($row[id],0)' class='btn btn-dark btn-sm shadow-none'>Active</button>";
    if(!$row['status'])
    {
      $status = "<button onclick='toggle_status($row[id],1)' class='btn btn-danger btn-sm shadow-none'>Inactive</button>";

    }

    $date = date("d-m-Y",strtotime($row['created_at']));

    $data.="
      <tr>
        <td>$i</td>
        <td>$row[name]</td>
        <td>$row[email]</td>
        <td>$row[phone]</td>
        <td>$row[address] | $row[pin]</td>
        <td>$row[dob]</td>
        <td>$verified</td>
        <td>$status</td>
        <td>$date</td>
        <td>$del_btn</td>
      </tr>
    ";
    $i++;
  }

  echo $data;
}


if(isset($_POST['toggle_status']))
{
  $frm_data = filteration($_POST);

  $q = "UPDATE `users` SET `status`=? WHERE `id`=?";

  $v = [$frm_data['value'],$frm_data['toggle_status']];

  if(update($q,$v,'ii')){
    echo 1;
  }
  else{
    echo 0;
  }
}


if (isset($_POST['remove_user'])) {
  $frm_data = filteration($_POST);
  $userId = $frm_data['remove_user'];
  

  $check_q = select('SELECT * FROM `users` WHERE `id`=?', [$userId], 'i');

  if (mysqli_num_rows($check_q) == 1) {
    $q = "DELETE FROM `users` WHERE `id`=? AND `verify_status`=?";
    $res = delete_from_db($con, $q, 'ii', [$userId,0]);
    echo $res;
  } else {
    echo 'user_not_found';
  }
}


if(isset($_POST['search_user']))
{

  $frm_data = filteration($_POST);

  $query = "SELECT * FROM `users` WHERE `name` LIKE ?";
  $res = select($query,["%$frm_data[name]%"],'s');

  $i=1;

  $data = "";

  while($row = mysqli_fetch_assoc($res)){
    $del_btn = " <button type='button' onclick='remove_user($row[id])' class='btn btn-danger shadow-none btn-sm'>
    <i class='bi bi-trash'></i>
   </button>";
    $verified = "<span class='badge bg-danger'><i class='bi bi-x-lg'></i></span>";
    
    if($row['verify_status'])
    {
      $verified = "<span class='badge bg-success'><i class='bi bi-check-lg'></i></span>";
      $del_btn = "";
    }

    $status = "<button onclick='toggle_status($row[id],0)' class='btn btn-dark btn-sm shadow-none'>Active</button>";
    if(!$row['status'])
    {
      $status = "<button onclick='toggle_status($row[id],1)' class='btn btn-danger btn-sm shadow-none'>Inactive</button>";

    }

    $date = date("d-m-Y",strtotime($row['created_at']));

    $data.="
      <tr>
        <td>$i</td>
        <td>$row[name]</td>
        <td>$row[email]</td>
        <td>$row[phone]</td>
        <td>$row[address] | $row[pin]</td>
        <td>$row[dob]</td>
        <td>$verified</td>
        <td>$status</td>
        <td>$date</td>
        <td>$del_btn</td>
      </tr>
    ";
    $i++;
  }

  echo $data;
}


?>
