<?php
require('../inc/db_config.php');
require('../inc/essentials.php');
adminLogin();

if(isset($_POST['get_general']))
{
    $q = "SELECT * FROM `settings` WHERE `sr_no`=?";
    $values = [1];
    $res = select($q,$values,"i");
    $data = mysqli_fetch_assoc($res);
    $json_data = json_encode($data);
    echo $json_data;
}
if(isset($_POST['upd_general']))
{
    $frm_data = filteration($_POST);

    $q = "UPDATE `settings` SET `site_title`=?, `site_about`=? WHERE `sr_no`=?";
    $values = [$frm_data['site_title'],$frm_data['site_about'],1];
    $res = update($q,$values,'ssi');
    echo $res;
}

if(isset($_POST['upd_shutdown']))
{
    $frm_data = ($_POST['upd_shutdown']==0) ? 1 : 0;

    $q = "UPDATE `settings` SET `shutdown`=? WHERE `sr_no`=?";
    $values = [$frm_data,1];
    $res = update($q,$values,'ii');
    echo $res;
}

if(isset($_POST['get_contacts']))
{
    $q = "SELECT * FROM `contact_details` WHERE `sr_no`=?";
    $values = [1];
    $res = select($q,$values,"i");
    $data = mysqli_fetch_assoc($res);
    $json_data = json_encode($data);
    echo $json_data;
}

if(isset($_POST['upd_contacts']))
{
    $frm_data = filteration($_POST);

    $q = "UPDATE `contact_details` SET `address`=?,`gmap`=?,`pn1`=?,`pn2`=?,`email`=?,`fb`=?,`insta`=?,`tw`=?,`iframe`=? WHERE `sr_no`=?";
    $values = [$frm_data['address'],$frm_data['gmap'],$frm_data['pn1'],$frm_data['pn2'],$frm_data['email'],$frm_data['fb'],$frm_data['insta'],$frm_data['tw'],$frm_data['iframe'],1];
    $res = update($q,$values,'sssssssssi');
    echo $res;
}

if(isset($_POST['get_members']))
{
  $res = selectAll('aboutimg');

  while($row = mysqli_fetch_assoc($res))
  {
    $path = ABOUT_IMG_PATH;
    echo <<<data
      <div class="col-md-2 mb-3">
        <div class="card bg-dark text-white">
          <img src="$path$row[image]" class="card-img" alt="...">
          <div class="card-img-overlay text-end">
              <button type="button" onclick="rem_member($row[id])" class="btn btn-danger btn-sm shadow-none">
              <i class="bi bi-trash3-fill"></i>  Delete
              </button>
          </div>
          <p class="card-text text-white">$row[name]</p>
         </div>
      </div>
    data;
  }
}

if (isset($_POST['rem_member'])) {
  $frm_data = filteration($_POST);
  $values = [$frm_data['rem_member']];

  $check_q = select('SELECT * FROM `aboutimg` WHERE `id`=?', [$frm_data['rem_member']], 'i');

  if (mysqli_num_rows($check_q) == 1) {
    $q = "DELETE FROM `aboutimg` WHERE `id`=?";
    $res = delete_from_db($con, $q, 'i', $values);
    echo $res;
  } else {
    echo 'room_added';
  }
}


?>