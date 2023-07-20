<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');


date_default_timezone_set("Asia/Kolkata");

session_start();

if(isset($_POST['cancel_booking'])) {
    $frm_data = filteration($_POST);

    $query = "UPDATE `bookingorder` SET `status`=?, `refund`=? 
    WHERE `booking_id`=? AND `user_id`=?";


 $values = ['cancelled',0,$frm_data['id'],$_SESSION['uId']];

 $result = update($query,$values,'siii');

 echo $result;
   
}
?>
