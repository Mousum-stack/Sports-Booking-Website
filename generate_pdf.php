<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require('admin/inc/essentials.php');
require('admin/inc/db_config.php');
require('admin/inc/mpdf/vendor/autoload.php');

session_start();

if (isset($_GET['gen_pdf']) && isset($_GET['id'])) {

    $frm_data = filteration($_GET);

    $query = "SELECT bo.*, bd.*, uc.email FROM `bookingorder` bo
    INNER JOIN `bookingdetails` bd ON bo.booking_id = bd.booking_id
    INNER JOIN `users` uc ON bo.user_id = uc.id
    WHERE ((bo.status = 'success' AND bo.arrival = 1)
     OR (bo.status = 'cancelled' AND bo.refund = 1)
     OR (bo.status = 'pending'))
    AND bo.booking_id = '$frm_data[id]'";

    $res = mysqli_query($con, $query);


    $total_rows = mysqli_num_rows($res);

    if ($total_rows == 0) {
        header('loaction: index.php');
        exit;
    }


    $data = mysqli_fetch_assoc($res);
    $date = date("h:ia | d-m-Y", strtotime($data['datetime']));
    $check_in = date("d-m-Y", strtotime($data['check_in']));
    $check_out = date("d-m-Y", strtotime($data['check_out']));

    // PDF design styles
    $style = '
    <style>
        h2 {
            color: #333333;
            font-size: 18px;
            margin-bottom: 10px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        td {
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }
        
        .title-row td {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        
        .order-details {
            margin-bottom: 20px;
        }
        
        .status-pending {
            color: #ff9900;
            font-weight: bold;
        }
        
        .status-success {
            color: #00cc00;
            font-weight: bold;
        }
        
        .status-cancelled {
            color: #cc0000;
            font-weight: bold;
        }
        
        .amount-refunded {
            color: #ff0000;
            font-weight: bold;
        }
        
        .amount-paid {
            color: #009900;
            font-weight: bold;
        }
        
        .background-text {
            position: absolute;
            top: 5.1%;
            right: 38.5%;
            transform: translate(50%, -50%) rotate(-30deg);
            font-size: 23px;
            font-weight: bold;
            color: #05fa57;
            opacity: 0.2;
            z-index: -1;
            white-space: nowrap;
        }
    </style>
';


    $table_data = "
        $style
        <h2>BOOKING RECEIPT</h2>
        <div class='background-text'>Play Hard</div>
        <div class='order-details'>
            <table>
                <tr class='title-row'>
                    <td>Order ID:</td>
                    <td>Booking Date:</td>
                </tr>
                <tr>
                    <td>{$data['order_id']}</td>
                    <td>$date</td>
                </tr>
                <tr class='title-row'>
                    <td colspan='2'>Payment Status:</td>
                </tr>
                <tr>
                    <td colspan='2' class='status-{$data['status']}'>{$data['status']}</td>
                </tr>
                <tr class='title-row'>
                    <td>Name:</td>
                    <td>Email:</td>
                </tr>
                <tr>
                    <td>{$data['user_name']}</td>
                    <td>{$data['email']}</td>
                </tr>
                <tr class='title-row'>
                    <td>Phone Number:</td>
                    <td>Address:</td>
                </tr>
                <tr>
                    <td>{$data['phonenum']}</td>
                    <td>{$data['address']}</td>
                </tr>
                <tr class='title-row'>
                    <td>Venue Name:</td>
                    <td>Cost:</td>
                </tr>
                <tr>
                    <td>{$data['room_name']}</td>
                    <td>₹{$data['price']} per day</td>
                </tr>
                <tr class='title-row'>
                    <td>Booking From:</td>
                    <td>Booking To:</td>
                </tr>
                <tr>
                    <td>$check_in</td>
                    <td>$check_out</td>
                </tr>
    ";

    if ($data['status'] == 'cancelled') {
        $refund = ($data['refund']) ? "Amount Refunded" : "Not Yet Refunded";

        $table_data .= "
                <tr class='title-row'>
                    <td>Amount Paid:</td>
                    <td>Refund:</td>
                </tr>
                <tr>
                    <td>₹{$data['amount']}</td>
                    <td class='amount-{$data['refund']}'>$refund</td>
                </tr>
        ";
    } else if ($data['status'] == 'pending') {
        $table_data .= "
                <tr class='title-row'>
                    <td>Transaction Amount:</td>
                </tr>
                <tr>
                    <td>₹{$data['amount']}</td>
                </tr> 
        ";
    } else {
        $table_data .= "
                <tr class='title-row'>
                    <td>Venue Name:</td>
                    <td>Amount Paid:</td>
                </tr>
                <tr>
                    <td>{$data['room_no']}</td>
                    <td>₹{$data['amount']}</td>
                </tr> 
        ";
    }

    $table_data .= "</table></div>";

    $mpdf = new \Mpdf\Mpdf();

    $mpdf->WriteHTML($table_data);

    $mpdf->Output($data['order_id'] . '.pdf', 'D');
} else {
    header('location: index.php');
}
?>
