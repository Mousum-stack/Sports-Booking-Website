<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Play Hard- Facilities</title>
<?php require('inc/links.php'); ?>
<style>
    .pop:hover{
        border-top-color: var(--teal) !important;
        transform: scale(1.03);
        transition: all 0.3s ;
    }
</style>
</head>
<body class="bg-light">
   <?php require('inc/header.php');?>
   <div class="my-5 px-4">
    <h2 class="fw-bold h-font text-center">Our Facilities</h2>
    <div class="h-line bg-dark"></div>
    <br>
    <p class="fw-bold p-font mt-3" style="font-size: 19px; color: green; text-align:justify;">
    We take pride in providing state-of-the-art facilities to ensure a seamless 
    and enjoyable experience for all sports enthusiasts. Our meticulously designed
    venues are equipped with top-notch amenities to cater to a wide range of sports
    activities. Whether you're a professional athlete or a casual player, our 
    facilities offer the perfect environment to engage in your favorite sports. 
    From well-maintained fields and courts to modern training equipment, we have
    everything you need to unleash your athletic potential. Our spacious and 
    comfortable changing rooms provide convenience and privacy, while our on-site 
    sports shops offer a wide selection of sporting goods and equipment. 
    Additionally, our friendly and knowledgeable staff are always on hand to assist
    you with any queries or requirements. Join us at Play Hard and 
    experience unparalleled facilities that will elevate your sports journey to 
    new heights.</p>
    <br>
   <div class="container">
    <div class="row">
        <?php
            $res = selectAll('facilities');
            $path = FEATURES_IMG_PATH;

            while($row = mysqli_fetch_assoc($res)){
                echo<<<data
                <div class="col-lg-4 col-md-6 mb-5 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
                    <div class="d-flex align-items-center mb-2">
                        <img src="$path$row[icon]" width="40px">
                        <h5 class="m-0 ms-3">$row[name]</h5>
                    </div>
                    <p>$row[description]</p>
                </div>
                </div>

                data;
            }
        ?>

     
        </div>
    </div>

    
    <?php require('inc/footer.php'); ?>


</body>
</html>
