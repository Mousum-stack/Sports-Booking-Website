<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('../inc/db_config.php');
require('../inc/essentials.php');
adminLogin();

if(isset($_POST['booking_analytics']))
{
    $frm_data = filteration($_POST);

    $condition="";

    if($frm_data['period']==1){
        $condition="WHERE datetime BETWEEN NOW() - INTERVAL 30 DAY AND NOW()";
    }
    else if($frm_data['period']==2){
        $condition="WHERE datetime BETWEEN NOW() - INTERVAL 90 DAY AND NOW()";
    }
    else if($frm_data['period']==3){
        $condition="WHERE datetime BETWEEN NOW() - INTERVAL 1 YEAR AND NOW()";
    }

    $result = mysqli_fetch_assoc(mysqli_query($con,"SELECT 

    COUNT(CASE WHEN `status`!='pending' THEN 1 END) AS `total_bookings`,
    SUM(CASE WHEN `status`!='pending' THEN `amount` END) AS `total_amt`,


    COUNT(CASE WHEN `status`='success' AND arrival=1 THEN 1 END) AS `active_bookings`,
    SUM(CASE WHEN `status`='success' AND arrival=1 THEN `amount` END) AS `active_amt`,

    COUNT(CASE WHEN `status`='cancelled' AND refund=1 THEN 1 END) AS `cancelled_bookings`, 
    SUM(CASE WHEN `status`='cancelled' AND refund=1 THEN `amount` END) AS `cancelled_amt`
    FROM `bookingorder` $condition"));

    $output = json_encode($result);

    echo $output;
}

if(isset($_POST['user_analytics']))
{
    $frm_data = filteration($_POST);

    $condition="";

    if($frm_data['period']==1){
        $condition="WHERE datetime BETWEEN NOW() - INTERVAL 30 DAY AND NOW()";
    }
    else if($frm_data['period']==2){
        $condition="WHERE datetime BETWEEN NOW() - INTERVAL 90 DAY AND NOW()";
    }
    else if($frm_data['period']==3){
        $condition="WHERE datetime BETWEEN NOW() - INTERVAL 1 YEAR AND NOW()";
    }
    else if($frm_data['period']==3){
        $condition="WHERE datetime BETWEEN NOW() - INTERVAL 1 YEAR AND NOW()";
    }

    $total_review = mysqli_fetch_assoc(mysqli_query($con,"SELECT COUNT(sr_no) AS `count` 
    FROM `rating_review` $condition"));


    $total_queries = mysqli_fetch_assoc(mysqli_query($con,"SELECT COUNT(sr_no) AS `count`
     FROM `user_queries` $condition"));


    $output = ['total_queries' => $total_queries['count'],
        'total_review' => $total_review['count']
    ];

    $output = json_encode($output);

    echo $output;

}



?>
