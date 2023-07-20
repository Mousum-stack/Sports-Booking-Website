<?php

require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');

date_default_timezone_set("Asia/Kolkata");

if (isset($_POST['info_form'])) {
    $data = filteration($_POST);
    session_start();

    $u_exist = select("SELECT * FROM `users` WHERE `phone` = ? AND `id`!= ? LIMIT 1",
    [$data['phone'], $_SESSION['uId']], "si");

    if (mysqli_num_rows($u_exist) != 0) {
        echo 'phone_already';
        exit;
    }

    $query = "UPDATE `users` SET `name`=?, `phone`=?, `address`=?, `pin`=?, `dob`=? WHERE `id`=?";
    $values = [$data['name'], $data['phone'], $data['address'], $data['pin'], $data['dob'], $_SESSION['uId']];

    if (update($query, $values, 'sssisi')) {
        echo 1;
    } else {
        echo 0;
    }
}


if (isset($_POST['pass_form'])) {
    $newPass = $_POST['new_pass'];
    session_start();

    if ($newPass != $_POST['confirm_pass']) {
        echo 'mismatch';
        exit;
    }

    $query = "UPDATE `users` SET `password` = ? WHERE `id` = ?";
    $values = [$newPass, $_SESSION['uId']];

    if (update($query, $values, 'si')) {
        echo '1'; // Password changed successfully
    } else {
        echo '0'; // Failed to change password
    }
}

?>
