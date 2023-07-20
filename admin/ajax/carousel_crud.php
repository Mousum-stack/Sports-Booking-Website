<?php
require('../inc/db_config.php');
require('../inc/essentials.php');
adminLogin();

if(isset($_POST['get_carousel']))
{
  $res = selectAll('carousel');

  while($row = mysqli_fetch_assoc($res))
  {
    $path = CAROUSEL_IMG_PATH;
    echo <<<data
      <div class="col-md-4 mb-3">
        <div class="card bg-dark text-white">
          <img src="$path$row[image]" class="card-img" alt="...">
          <div class="card-img-overlay text-end">
          <button type="button" onclick="rem_image($row[sr_no])" class="btn btn-danger btn-sm shadow-none">
          <i class="bi bi-trash3-fill"></i> Delete
            </button>
          </div>
        </div>
      </div>
    data;
  }
}

if (isset($_POST['rem_image'])) {
  $frm_data = filteration($_POST);
  $imageId = $frm_data['rem_image'];

  $check_q = select('SELECT * FROM `carousel` WHERE `sr_no`=?', [$imageId], 'i');

  if (mysqli_num_rows($check_q) == 1) {
    $q = "DELETE FROM `carousel` WHERE `sr_no`=?";
    $res = delete_from_db($con, $q, 'i', [$imageId]);
    echo $res;
  } else {
    echo 'image_not_found';
  }
}

?>
