<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');
date_default_timezone_set("Asia/Kolkata");
session_start();

if(isset($_GET['fetch_rooms']))
{
    $chk_avail = json_decode($_GET['chk_avail'],true);

    if($chk_avail['checkin']!='' && $chk_avail['checkout']!=''){
        $today_date = new DateTime(date("Y-m-d"));
        $checkin_date = new DateTime($chk_avail['checkin']);
        $checkout_date = new DateTime($chk_avail['checkout']);
    
        if($checkin_date == $checkout_date) {
             echo "<h3 class='text-center text-danger'>Booking From And Booking To Cannot be Same. <br>
             Because the booking period starts at 12 am on the Booking From date and ends at 12 am on the Booking To date.
             <br>Note!: You and go anytime between the opening time of the venue. </h3>";
             exit;
        } else if($checkout_date < $checkin_date) {
            echo "<h3 class='text-center text-danger'>Booking to date cannot be before the booking from date!.</h3>";
            exit;
        } else if($checkin_date < $today_date) {
            echo "<h3 class='text-center text-danger'>Booking from date cannot be before todays date</h3>";
             exit;
        }
    }


    //venues data decode

    $facility_list = json_decode($_GET['facility_list'],true);

    //get venues with filters
    //count no of venues and output variable to store
    $count_rooms = 0; 
    $output = "";
    
    //fetching settings table for checking shutdown
    $settings_q = "SELECT * FROM `settings` WHERE `sr_no`=1";
    $settings_r = mysqli_fetch_assoc(mysqli_query($con,$settings_q));
    
    //query for venue cards
    $room_res = select("SELECT * FROM `venues` WHERE `status`=? AND `removed`=? ORDER BY `id` DESC",[1,0],'ii');

    while($room_data = mysqli_fetch_assoc($room_res))
    {
            if($chk_avail['checkin']!='' && $chk_avail['checkout']!='')
            {

            }
            // get rooms with filters
            $fac_count = 0;
            $veu_q = mysqli_query($con,"SELECT `name`, `id` FROM `venues`
            WHERE id = '$room_data[id]'");
        
            $venues_data = "";
            while($vac_row = mysqli_fetch_assoc($veu_q)){
                if(in_array($vac_row['id'],$facility_list['facilities']))
                {
                    $fac_count++;
                }
                $venues_data .=" <span class='badge rounded-pill bg-light text-dark  text-wrap me-1 mb-1'>
                $vac_row[name]
            </span>";
            }

            if(count($facility_list['facilities'])!=$fac_count){
                continue;
            }

        //Get features of venues

        $fea_q = mysqli_query($con,"SELECT f.name FROM `features` f
     INNER JOIN `venues_features` rfea ON f.id = rfea.features_id WHERE 
     rfea.venues_id = '$room_data[id]'");

    $features_data = "";
     while($fea_row = mysqli_fetch_assoc($fea_q)){
        $features_data .=" <span class='badge rounded-pill bg-light text-dark  text-wrap me-1 mb-1'>
        $fea_row[name]
    </span>";

}

    //get facilities fo room

    $fac_q = mysqli_query($con,"SELECT f.name FROM `facilities` 
    f INNER JOIN `venues_facilities` rfac ON f.id = rfac.facilities_id 
    WHERE rfac.venues_id = '$room_data[id]'");

    $facilities_data = "";
    while($fac_row = mysqli_fetch_assoc($fac_q)){
        $facilities_data .=" <span class='badge rounded-pill bg-light text-dark  text-wrap me-1 mb-1'>
        $fac_row[name]
    </span>";

}
// get thumbnail of image
    $room_thumb = VENUES_IMG_PATH."thumbnail.jpg";
    $thumb_q = mysqli_query($con,"SELECT * FROM `venue_images` 
    WHERE `venues_id`='$room_data[id]'
     AND `thumb`='1'");

     if(mysqli_num_rows($thumb_q)>0){
        $thumb_res = mysqli_fetch_assoc($thumb_q);
        $room_thumb = VENUES_IMG_PATH.$thumb_res['image'];
     }

     $book_btn= "";

     if(!$settings_r['shutdown']){
      $book_btn = "<a href='booknow.php?id={$room_data['id']}' class='btn btn-sm w-100 mb-2 text-white custom-bg shadow-none'>Book Now</a>";
    }

     // print venues card

        $output.="
        <div class='card mb-4 border-0 shadow'>
            <div class='row g-0 p-3 align-items-center'>
              <div class='col-md-5 mb-lg-0 mb-md-0 mb-3'>
                <img src='$room_thumb' class='img-fluid rounded'>
              </div>
              <div class='col-md-5 px-lg-3 px-md-3 px-0'>
               <h5 class='mb-3'>$room_data[name]</h5>
                <div class='features mb-3'>
                    <h6 class='mb-1'>Featues</h6>
                      $features_data
                    </div>
                    <div class='facilities'>
                        <h6 class='mb-1'>Facilities</h6>
                            $facilities_data
                        </div>
              </div>
              <div class='col-md-2 mt-lg-0 mt-md-0 mt-4 text-center'>
                <h6 class='mb-4'>₹$room_data[price] Per Day</h6>
                $book_btn
                <a href='venues_details.php?id=$room_data[id]' class='btn btn-sm w-100 btn-outline-dark shadow-none'>More details</a>
              </div>
            </div>
          </div>

        ";
        $count_rooms++;
    }
    if($count_rooms>0){
        echo $output;
    }
    else{
        echo "<h3 class='text-center text-danger'>No Venues Found!</h3>";
    }
}
?> 