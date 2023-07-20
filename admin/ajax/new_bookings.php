<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('../inc/db_config.php');
require('../inc/essentials.php');
adminLogin();

if (isset($_POST['get_bookings'])) {
    $frm_data = filteration($_POST);

    $query = "SELECT bo.*, bd.* FROM `bookingorder` bo
    INNER JOIN `bookingdetails` bd ON bo.booking_id = bd.booking_id
    WHERE (bo.order_id LIKE ? OR bd.phonenum LIKE ? OR bd.user_name LIKE ?)
    AND (bo.status = ?) AND (bo.arrival != ?) ORDER BY bo.booking_id ASC";

    $res = select($query, ["%$frm_data[search]%", "%$frm_data[search]%", "%$frm_data[search]%", "success", 1], 'ssssi');
    $i = 1;
    $table_data = "";

    
    if(mysqli_num_rows($res)==0)
    {
      echo "<b> No data found!</b>";
      exit;
    }


    while($data = mysqli_fetch_assoc($res))
    {
        $date = date("d-m-Y", strtotime($data['datetime']));
        $check_in = date("d-m-Y", strtotime($data['check_in']));
        $check_out = date("d-m-Y", strtotime($data['check_out']));

        $table_data .= "
        <tr>
            <td>$i</td>
            <td>
                <span class='badge bg-primary'>
                    Order ID: {$data['order_id']}
                </span>
                <br>
                <b>Name :</b> {$data['user_name']}
                <br>
                <b>Phone No :</b> {$data['phonenum']}
                <br>
            </td>
            <td>
                <b>Venue:</b> {$data['room_name']}
                <br>
                <b>Price:</b> ₹{$data['price']}
            </td>
            <td>
                <b>Check in:</b> $check_in
                <br>
                <b>Check-out:</b> $check_out
                <br>
                <b>Paid:</b> ₹{$data['amount']}
                <br>
                <b>Date:</b> $date
            </td>
            <td>
                <button type='button' onclick='assign_room($data[booking_id])' class='btn text-dark btn-sm fw-bold custom-bg shadow-none' data-bs-toggle='modal' data-bs-target='#assign-room'>
                    <i class='bi bi-check2-square text-dark'></i>
                    Assign Venues
                </button>
                <br>
                <button type='button' onclick='cancel_booking($data[booking_id])' class='mt-2 btn btn-outline-danger btn-sm fw-bold shadow-none'>
                    <i class='bi bi-trash'></i>
                    Cancel Booking
                </button>
            </td>
        </tr>
        ";

        $i++;
    }

    echo $table_data;
}

if(isset($_POST['assign_room']))
{
    $frm_data = filteration($_POST);

    $query = "UPDATE `bookingorder` bo INNER JOIN `bookingdetails` bd
              ON bo.booking_id = bd.booking_id
              SET bo.arrival = ? ,bo.rate_review=?, bd.room_no = ?
              WHERE bo.booking_id = ?";

    $values = [1,0,$frm_data['room_no'], $frm_data['booking_id']];

    $res = update($query, $values, 'iisi'); //it will update 2 rows so it will return 2

    echo ($res==2) ? 1 : 0;  
}








if(isset($_POST['cancel_booking']))
{
  $frm_data = filteration($_POST);

  $query = "UPDATE `bookingorder` SET `status`=?, `refund`=? WHERE `booking_id`= ?";
  $values = ['cancelled',0,$frm_data['booking_id']];
  $res = update($query,$values,'sii'); 

  echo $res;

}



?>
