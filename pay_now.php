<?php
// ini_set('display_errors', 1);
require('admin/inc/db_config.php');
require('admin/inc/essentials.php');
require('razorpay-php/Razorpay.php');
require("gateway-config.php");

date_default_timezone_set("Asia/Kolkata");

session_start();

if (isset($_POST['pay_now'])) {
    $checksum = "";

    // Debug statement to check the user ID
    echo "User ID: " . $_SESSION['uId'];

    $ORDER_ID = 'ORD_' . $_SESSION['uId'] . random_int(11111, 999999);
    $CUST_ID = $_SESSION['uId'];
    $TXN_AMOUNT = $_SESSION['room']['payment'];
    $webtitle = "Play Hard";
    
    $title = $_SESSION['name'];

    $frm_data = filteration($_POST);

    $query1 = "INSERT INTO `bookingorder`(`user_id`, `room_id`, `check_in`, `check_out`, `order_id`, `amount`) 
               VALUES (?,?,?,?,?,?)";

    insert($query1, [$CUST_ID, $_SESSION['room']['id'], $frm_data['checkin'], $frm_data['checkout'], $ORDER_ID, $TXN_AMOUNT], 'issssd');

    $booking_id = mysqli_insert_id($con);

    $query2 = "INSERT INTO `bookingdetails`(`booking_id`, `room_name`, `price`, `total_pay`,`user_name`, `phonenum`, `address`) 
               VALUES (?,?,?,?,?,?,?)";
    insert($query2, [$booking_id, $_SESSION['room']['name'], $_SESSION['room']['price'], $TXN_AMOUNT, $frm_data['name'], $frm_data['phone'], $frm_data['address']], 'issssss');

    // Retrieve the booking ID from the database
    $query = "SELECT `booking_id` FROM `bookingorder` WHERE `order_id` = ?";
    $result = select($query, [$ORDER_ID], 's');

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $booking_id = $row['booking_id'];
    } else {
        // Handle the case when no matching booking is found
        echo "Invalid booking ID. No matching booking found.";
        exit;
    }

    // Create a new instance of the Razorpay API with your API key and secret
    $api = new Razorpay\Api\Api($keyId, $keySecret);

    $orderData = [
        'receipt'         => $ORDER_ID, // Generate a unique receipt number for each order
        'amount'          => $TXN_AMOUNT * 100, // Convert the amount to paise (Indian currency is in paise)
        'currency'        => 'INR', // Set the currency to INR
        'payment_capture' => 1 // Enable auto-capture of payment
    ];

    // Create a new Razorpay order with the order data
    $razorpayOrder = $api->order->create($orderData);
    $razorpayOrderId = $razorpayOrder['id']; // Get the ID of the created order
    $_SESSION['razorpay_order_id'] = $razorpayOrderId; // Store the order ID in the session

    $data = [
        "key"          => $keyId, // Razorpay API key
        "amount"       => $orderData['amount'], // Amount in paise
        "name"         => $webtitle, // Website title
        "description"  => $title, // Product title
        "image"        => "https://s29.postimg.org/r6dj1g85z/logo.png", // URL of the image/logo
        "prefill"      => [
            "name"              => $frm_data['name'], // Payer's name
            "email"             => $frm_data['email'], // Payer's email address
            "contact"           => $frm_data['phone'], // Payer's contact number
            "address"           => $frm_data['address'], // Payer's address
            "merchant_order_id" => $ORDER_ID, // Custom merchant order ID
        ],
        "theme"        => [
            "color" => "#F37254" // Theme color for the Razorpay checkout form
        ],
        "order_id"     => $razorpayOrderId, // Razorpay order ID
        "booking_id"   => $booking_id, // Booking ID
    ];

    $json = json_encode($data); // Convert the data array to JSON
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <!-- Include the Razorpay checkout script -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <h1>Payment</h1>
    <!-- Create a button to initiate the Razorpay checkout -->
    <button onclick="startRazorpayPayment()">Pay Now</button>

    <form id="payment-form" method="POST" action="update-booking-status.php" style="display: none;">
    <input type="hidden" name="payment_id" id="payment_id">
    <input type="hidden" name="order_id" id="order_id">
    <input type="hidden" name="booking_id" id="booking_id" value="<?php echo $booking_id; ?>">
    <input type="hidden" name="STATUS" value="TXN_SUCCESS">
</form>

<script>
    // Function to initiate the Razorpay payment
    function startRazorpayPayment() {
        var options = <?php echo $json; ?>; // Get the payment options JSON

        options.handler = function(response) {
            // Handle the payment success response
            alert('Payment successful! Payment ID: ' + response.razorpay_payment_id);

            // Set the payment ID and order ID in the form
            document.getElementById("payment_id").value = response.razorpay_payment_id;
            document.getElementById("order_id").value = options.order_id;
            document.getElementById("booking_id").value = "<?php echo $booking_id; ?>";

            // Submit the form
            document.getElementById("payment-form").submit();
        };

        options.modal = {
            ondismiss: function() {
                // Handle the payment failure or cancellation
                alert('Payment cancelled or failed.');
                // Redirect to the failure page or perform any additional actions
            }
        };

        var rzp = new Razorpay(options); // Create an instance of Razorpay
        rzp.open(); // Open the Razorpay checkout form
    }
</script>

</body>

</html>
