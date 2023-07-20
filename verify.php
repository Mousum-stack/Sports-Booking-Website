<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!function_exists('filterationr')) {
    function filterationr($data) {
        foreach($data as $key => $value) {
            $value = trim($value);
            $value = stripslashes($value);
            $value = strip_tags($value);
            $value = htmlspecialchars($value);
            $data[$key] = $value;
        }
        return $data;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('INC/links.php'); ?>
    <title>BOOKING STATUS</title>
</head>
<body class="bg-light">
    <?php require('INC/header.php'); ?>
    
    <div class="container">
        <div class="row">
            <div class="col-12 my-5 mb-3 px-4">
                <h2 class="fw-bold">Payment Status</h2>
            </div>

            <?php  
            $frm_data = filterationr($_GET);

            $booking_q = "SELECT * FROM `bookingorder` bo 
            INNER JOIN `bookingdetails` bd ON bo.booking_id=bd.booking_id
            WHERE bo.booking_id=? AND bo.user_id=? AND bo.status!=?";

            $booking_res = select($booking_q,[$frm_data['booking_id'],$_SESSION['uId'],'pending'],'sis');

            if(mysqli_num_rows($booking_res) == 0){
                echo <<<data
                <div class="col-12 px-4">
                    <p class="fw-bold alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i>
                        Booking not found or already processed.
                        <br><br>
                        <a href='index.php'>Go to Home</a>
                    </p>
                </div>
                data;
            } else {
                $booking_fetch = mysqli_fetch_assoc($booking_res);

            
                if ($booking_fetch['status'] == "success") 
                    {
                    echo <<<data
                    <div class="col-12 px-4">
                        <p class="fw-bold alert alert-success">
                            <i class="bi bi-check2-circle"></i>
                            Payment done! Booking Successful.
                            <br><br>
                            <a href='booking.php'>Go to Bookings</a>
                        </p>
                    </div>
                    data;
                } else {
                    echo <<<data
                    <div class="col-12 px-4">
                        <p class="fw-bold alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i>
                            Payment failed! {$booking_fetch['status']}
                            <br><br>
                            <a href='bookings.php'>Go to Bookings</a>
                        </p>
                    </div>
                    data;   
                }
            }
            ?>
        </div>
    </div>
    <?php require('INC/footer.php'); ?>
</body>
</html>
