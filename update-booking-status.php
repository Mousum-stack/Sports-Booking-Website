<?php
require('admin/inc/db_config.php');
require('admin/inc/essentials.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set("Asia/Kolkata");

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $trans_id = $_POST['payment_id'];
    $booking_id = $_POST['booking_id'];
    $status = $_POST['STATUS'];

    // Add debug statements
    echo "Booking ID from form: " . $booking_id . "<br>";

    // Retrieve the actual booking ID from the database
    $query = "SELECT `booking_id` FROM `bookingorder` WHERE `booking_id` = ?";
    $result = select($query, [$booking_id], 'i');

    if ($result && $result->num_rows > 0)
    {
        $row = $result->fetch_assoc();
        $actualBookingId = $row['booking_id'];
        echo "Actual Booking ID from database: " . $actualBookingId . "<br>";

        // Compare the booking IDs
        if ($booking_id == $actualBookingId) {
            // The booking IDs match
            echo "Booking ID matches. Proceed with updating the database.<br>";

            // Update the booking status based on the transaction status
            if ($status === "TXN_SUCCESS") {
                // Transaction success
                $query = "UPDATE `bookingorder` SET `status` = 'success', `trans_id` = ? WHERE `booking_id` = ?";
                $affectedRows = update($query, [$trans_id, $booking_id], 'si');

                if ($affectedRows > 0) {
                    echo "Payment successful! Thank you for your booking.";
                    $redirectUrl = "verify.php?booking_id=" . $booking_id;
                    header("Location: " . $redirectUrl);
                    exit();
                } else {
                    echo "Error updating booking status in the database.";
                }
            } else {
                // Payment failure
                $query = "UPDATE `bookingorder` SET `status` = 'failure' WHERE `booking_id` = ?";
                $affectedRows = update($query, [$booking_id], 'i');

                if ($affectedRows > 0) {
                    echo "Payment failed. Please try again.";
                } else {
                    echo "Error updating booking status in the database.";
                }
            }
        } else {
            // The booking IDs do not match
            echo "Booking ID does not match. Check the booking ID value being passed.";
        }
    }
     else 
    {
        // No matching booking found in the database
        echo "Invalid booking ID. No matching booking found.";
    }

    
}

?>
