<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Play Hard- book</title>
    <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">
    <?php require('inc/header.php'); ?>

    <?php include('authentication.php'); ?>
    <?php
        if(isset($_SESSION['status'])) {
    ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Hey!</strong> <?php echo $_SESSION['status']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
    <?php
            unset($_SESSION['status']);
        }
    ?> 

<?php
// define and set the value of $settings_r['shutdown']
$settings_r = array('shutdown' => false);

// add this line for debugging purposes
// var_dump($_GET['id']);

/*
    check room_id from url is present or not
    shutdownmode is active or not
    user is logged in or not
*/

if(isset($_GET['id']) && $settings_r['shutdown'] == false) {
    $data = filteration($_GET);

    $room_res = select("SELECT * FROM `venues` WHERE `id`=? AND `status`=? AND `removed`=?", [$data['id'], 1, 0], 'iii');

    if(mysqli_num_rows($room_res) == 0) {
        redirect('venues.php');
    }

    $room_data = mysqli_fetch_assoc($room_res);
    $_SESSION['room'] = [
        "id" => $room_data['id'],
        "name" => $room_data['name'],
        "price" => $room_data['price'],
        "payment" => null,
        "available" => false,
    ];
    

    // debug the session variable for the room
    if(isset($_SESSION['uId']) && !empty($_SESSION['uId'])) {
        $user_res = select("SELECT * FROM `users` WHERE `id`=? LIMIT 1", [$_SESSION['uId']], "i");  
        if(mysqli_num_rows($user_res) == 0) {
            echo "No user data found!";
        } else {
            $user_data = mysqli_fetch_assoc($user_res);
            // var_dump($user_data);
        }
    } else {
        echo "User not logged in!";
    }



} 
?>



      <div class="container">
        <div class="row">
            <div class="col-12 my-5 mb-4 px-4">
            <h2 class="fw-bold">Confirm Booking</h2>
            <div style="font-size: 14px;">
        <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
        <span class="text-secondary"> > </span>
        <a href="venues.php" class="text-secondary text-decoration-none">VENUES</a>
        <span class="text-secondary"> > </span>
        <a href="#" class="text-secondary text-decoration-none">Confirm</a>
       </div>
      </div>

        <div class="col-lg-7 col-md-12 px-4">
        <?php
    $room_thumb = VENUES_IMG_PATH."thumbnail.jpg";
    $thumb_q = mysqli_query($con,"SELECT * FROM `venue_images` 
    WHERE `venues_id`='$room_data[id]'
    AND `thumb`='1'");

    if(mysqli_num_rows($thumb_q)>0){
        $thumb_res = mysqli_fetch_assoc($thumb_q);
        $room_thumb = VENUES_IMG_PATH.$thumb_res['image'];
    }

    echo<<<data
    <div class="card p-3 shadow-sm rounded">
        <img src="$room_thumb" class="img-fluid rounded mb-3">
        <h5>$room_data[name]</h5>
        <h6>₹$room_data[price] Per Day</h6>
    </div>
    data;
?>


        </div>



        <div class="col-lg-5 col-md-12 px-4">
        <div class="card mb-4 border-0 shadow-sm rounded-3">
          <div class="card-body">
            <form action="pay_now.php" method="post" id="booking_form">
                <h6 class="mb-3">Booking Details</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" value="<?php echo $user_data['name'] ?>" class="form-control shadow-none" required>
                    </div>
                    <div class="col-md-6 mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="number" name="phone" value="<?php echo $user_data['phone'] ?>" class="form-control shadow-none" required>
                    </div>
                    <div class="col-md-12 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="<?php echo $user_data['email'] ?>" class="form-control shadow-none" required>
                    </div>
                    <div class="col-md-12 mb-3">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control shadow-none" rows="1"><?php echo $user_data['address'] ?></textarea>
                    </div>

                        <h5 class="mb-3">Select Game Dates</h5>
                  
                    
                    <div class="col-md-6 mb-3">
                    <label class="form-label">Booking From</label>
                    <input type="date" name="checkin" onchange="check_availability()" class="form-control shadow-none" required>
                    </div>
                    <div class="col-md-6 mb-4">
                    <label class="form-label">Booking To</label>
                    <input type="date" name="checkout" onchange="check_availability()" class="form-control shadow-none" required>
                    </div>
                    <div class="col-12">
                    <div class="spinner-border text-info mb-3 d-none" id="info_loader" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                        <h6 class="mb-3 text-danger" id="pay_info">Provide Game date!</h6>
                        <button type="submit" name="pay_now" class="btn w-100 text-white custom-bg shadow-none mb-1" disabled>Pay Now</button>
                    </div>
                </div>
            </form>
          </div>      
        </div>
        </div>
      
       
    </div>
   </div>
    
    <?php require('inc/footer.php'); ?>
    <script>
   let booking_form = document.getElementById('booking_form');
let info_loader = document.getElementById('info_loader');
let pay_info = document.getElementById('pay_info');
let xhr; // Define xhr as a global variable

function check_availability() {
    let checkin_val = booking_form.elements['checkin'].value;
    let checkout_val = booking_form.elements['checkout'].value;

    booking_form.elements['pay_now'].setAttribute('disabled', true);

    if (checkin_val != '' && checkout_val != '') {
        pay_info.classList.add('d-none');
        pay_info.classList.replace('text-dark', 'text-danger');
        info_loader.classList.remove('d-none');

        let data = new FormData();
        data.append('check_availability', '');
        data.append('checkin', checkin_val);
        data.append('checkout', checkout_val);

        xhr = new XMLHttpRequest(); // Assign value to xhr
        xhr.open("POST", "ajax/confirm_booking.php", true);

        xhr.onload = function () {
            console.log(this.responseText);
                // output the responseText to the console
                if (this.responseText.trim() !== '') {
                    let data = JSON.parse(this.responseText);
                    if (data.status == 'check_in_out_equal') {
                        pay_info.innerText = "Booking From And Booking To Cannot be Same.Because the booking period starts at 12 am on the Booking From date and ends at 12 am on the Booking To date.You and go anytime between the opening time of the venue.!";
                    } else if (data.status == 'check_out_earlier') {
                        pay_info.innerText = "Booking from date is later than Booking to date!";
                    } else if (data.status == 'check_in_earlier') {
                        pay_info.innerText = "Booking from date is earlier than today's date!";
                    } else if (data.status == 'unavailable') {
                        pay_info.innerText = "Booking not available for these dates!";
                    } else {
                        pay_info.innerHTML = "No. of days: " + data.days + "<br>Total Amount to Pay: ₹" + data.payment;
                        pay_info.classList.replace('text-danger', 'text-dark');
                        booking_form.elements['pay_now'].removeAttribute('disabled');
                    }
                } else {
                    pay_info.innerText = "Error: Empty response from server!";
                }

                pay_info.classList.remove('d-none');
                info_loader.classList.add('d-none');
            };

            xhr.send(data);
        }
    }
</script>




</body>
</html>

