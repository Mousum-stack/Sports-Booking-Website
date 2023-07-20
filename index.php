
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Play Hard</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css"/>
<?php require('inc/links.php'); ?>
<style>

.availability-form{
    margin-top: -50px;
    z-index: 2;
    position: relative;
}
@media screen and (max-width: 575px) {
    .availability-form{
        margin-top: 2px;
        padding: 0 35px;
    }
}
.app-features-section {
    padding: 10% 0;
}

#supportBtn {
  position: fixed; /* or position: absolute; */
  bottom: 20px;
  right: 20px;
  z-index: 9999; /* Set the z-index appropriately */
}


</style>
</head>
<body class="bg-light">
 <?php require('inc/header.php');?>
    <!-- carousel -->
<div class="container-fluid px-lg-4 mt-4">
<div class="swiper swiper-container">
<div class="swiper-wrapper">
    <?php
        $res = selectAll('carousel');
        while($row = mysqli_fetch_assoc($res))
            {
                $path = CAROUSEL_IMG_PATH;
                echo <<<data
                <div class="swiper-slide">
                <img src="$path$row[image]" class="w-100 d-block">
                </div>
                data;
            }


    ?>
</div>
</div>
</div>
    <!--partnership form  -->
    <div class="container availability-form">
    <div class="row">
        <div class="col-lg-12 bg-white shadow p-4 rounded">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-2" style="color:green; font-weight: bolder; font-size: 26px">Your nearest Sports Community</h5>
                    <h4 class="mb-4" style="color:green; font-weight: bolder; font-size: 26px">Just a tap away</h4>
                </div>
                <div class="text-center">
                    <a href="venues.php" class="badge rounded-pill text-light me-5 custom-bg mb-2" style="font-size: 20px; text-decoration: none;">To Book Venue</a>
                    <a href="partner.php" class="badge rounded-pill text-light me-5 custom-bg" style="font-size: 20px; text-decoration: none;">To get Listed</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS for small screens */
    @media (max-width: 576px) {
        .container.availability-form {
            padding: 10px;
        }
        .col-lg-12.bg-white.shadow.p-4.rounded {
            padding: 10px;
        }
        .d-flex.justify-content-between.align-items-center {
            flex-direction: column;
        }
        .text-center {
            margin-top: 20px;
        }
        .badge.rounded-pill.text-light.me-5.custom-bg {
            margin-bottom: 10px;
            display: block;
            width: 100%;
        }
    }
</style>


    <!-- Our venues -->
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Our venues</h2>
    <div class="container">
    <div class="row">

    <?php
        $room_res = select("SELECT * FROM `venues` WHERE `status`=? AND `removed`=? ORDER BY `id` DESC LIMIT 3",[1,0],'ii');

        while($room_data = mysqli_fetch_assoc($room_res))
        {
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
            $book_btn = "<a href='booknow.php?id={$room_data['id']}' class='btn btn-sm text-white custom-bg shadow-none'>Book Now</a>";
        }
        

        $rating_q = "SELECT AVG(rating) AS `avg_rating` FROM `rating_review`
                    WHERE `room_id`='$room_data[id]' ORDER BY `sr_no` DESC LIMIT 20";

        $rating_res = mysqli_query($con,$rating_q);
        $rating_fetch = mysqli_fetch_assoc($rating_res);

        $rating_data = "";

        if($rating_fetch['avg_rating']!=NULL)
        {
            $rating_data = "<div class='rating mb-4'>
            <h6 class='mb-1'>Rating</h6>
            <span class='badge rounded-pill bg-light'>";

            for($i=0; $i < $rating_fetch['avg_rating']; $i++){
                $rating_data .="<i class='bi bi-star-fill text-warning'></i> ";
            }

            $rating_data .= "  </span>
            </div>";
        }


         // print venues card

            echo<<<data

                <div class="col-lg-4 col-md-6 my-3">

                <div class="card border-0 shadow" style="max-width: 350px; margin: auto;">
                    <img src="$room_thumb" class="card-img-top">
                    
                    <div class="card-body">
                        <h5>$room_data[name]</h5>
                        <h6 class="mb-4">₹$room_data[price]Per Day</h6>

                        <div class="features mb-4">
                        <h6 class="mb-1">Featues</h6>
                        $features_data
                        </div>
                        <div class="facilities mb-4">
                        <h6 class="mb-1">Facilities</h6>
                        $facilities_data
                        </div>
                        $rating_data
                        <div class="d-flex justify-content-evenly mb-2">
                        $book_btn
                        <a href="venues_details.php?id=$room_data[id]" class="btn btn-sm btn-outline-dark shadow-none">More details</a>
                     </div>
                   </div>
                </div>
            </div>
        data;
        }
        ?>
       
        <div class="col-lg-12 text-center mt-5">
            <a href="venues.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More venues>>></a>
        </div>
    </div>
    </div>
    <!-- Our Facilities -->
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Our Facilities</h2>
    <div class="container">
    <div class="row justify-content-evenly px-lg-0 px-md-0 px-5 ">
    <?php
            $res = mysqli_query($con,"SELECT * FROM `facilities` ORDER BY `id` DESC LIMIT 5");
            $path = FEATURES_IMG_PATH;

            while($row = mysqli_fetch_assoc($res)){
                echo<<<data
                    <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
                        <img src="$path$row[icon]" width="80px">
                        <h5 class="mt-3">$row[name]</h5>
                    </div>
                data;
            }
        ?>
        <div class="col-lg-12 text-center mt-5">
            <a href="facilities.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More Facilities>>></a>
        </div>
    </div>
    </div>
    <!-- Testimonials -->
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">RATING AND REVIEWS</h2>
    <div class="container mt-5">
    <div class="swiper swiper-testimonials">
        <div class="swiper-wrapper mb-5">

        <?php
            $review_q = "SELECT rr.*,uc.name AS uname, r.name AS
                        rname FROM `rating_review` rr
                        INNER JOIN `users` uc ON rr.user_id = uc.id
                        INNER JOIN `venues` r ON rr.room_id = r.id
                        ORDER BY `sr_no` DESC LIMIT 6";

                        $review_res = mysqli_query($con,$review_q);

                        if(mysqli_num_rows($review_res)==0)
                        {
                            echo 'No reviews yet!';
                        }
                        else{
                            while($row = mysqli_fetch_assoc($review_res))
                            {
                                $stars = "<i class='bi bi-star-fill text-warning'></i> ";
                                for($i=1; $i<$row['rating']; $i++)
                                {
                                    $stars .= " <i class='bi bi-star-fill text-warning'></i>";
                                }

                                echo<<<slides

                                <div class="swiper-slide bg-white p-4">
                                <div class="profile d-flex align-items-center mb-3">
                                    <i class="bi bi-person-circle mb-2"></i>
                                    <h6 class="m=0 ms-2">$row[uname]</h6>
                                </div>
                                <p>
                                   $row[review]
                                </p>
                                <div class="rating">
                                    $stars
                                </div>
                                </div>

                                slides;
                            }
                        }
        ?>

        </div>
        <div class="swiper-pagination"></div>
        </div>
    </div>
    <div class="col-lg-12 text-center mt-5">
        <a href="about.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">Know More>>></a>
    </div>
    </div>

    <section class="app-features-section">
    <div class="container-fluid" style="padding: 0 5%;">
        <div class="row">
	                <div class="col-md-4">
                <div class="feature-wrap wow  fadeIn slow animated" style="visibility: visible; animation-name: fadeIn;">
                    <img src="https://www.playspots.in/wp-content/uploads/2020/02/search.png" class="img-fluid wow  bounceIn slower" alt="Playspots" style="visibility: visible; animation-name: bounceIn;">
                    <h4 class="wow  fadeInUp slow" style="visibility: visible; animation-name: fadeInUp;">Search</h4>
                    <div class="content">
                    <p>Are you looking to play after work, organize your Sunday Five's football match? Explore the largest network of sports facilities whole over the India</p>
                    </div>
                </div>
            </div>
	            <div class="col-md-4">
                <div class="feature-wrap wow  fadeIn slow animated" style="visibility: visible; animation-name: fadeIn;">
                    <img src="https://www.playspots.in/wp-content/uploads/2020/02/book.png" class="img-fluid wow  bounceIn slower" alt="Playspots" style="visibility: visible; animation-name: bounceIn;">
                    <h4 class="wow  fadeInUp slow" style="visibility: visible; animation-name: fadeInUp;">Book</h4>
                    <div class="content">
                    <p>Once you’ve found the perfect ground, court or gym, Connect with the venue through the Book Now Button to make online booking &amp; secure easier payment
</p>
                    </div>
                </div>
            </div>
	            <div class="col-md-4">
                <div class="feature-wrap wow  fadeIn slow animated" style="visibility: visible; animation-name: fadeIn;">
                    <img src="https://www.playspots.in/wp-content/uploads/2020/02/play.png" class="img-fluid wow  bounceIn slower" alt="Playspots" style="visibility: visible; animation-name: bounceIn;">
                    <h4 class="wow  fadeInUp slow" style="visibility: visible; animation-name: fadeInUp;">Play</h4>
                    <div class="content">
                    <p>You’re the hero, you’ve found a stunning turf or court, booked with ease and now its time to play. The scene is set for your epic match.</p>
                    </div>
                </div>
            </div>
	            </div>
    </div>
</section>

    <!-- Reach Us -->

    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Reach US</h2>
    <div class="container">
    <div class="row">
        <div class="col-lg-8 col-md-8 p-4 mb-lg-0 mb-3 bg-white rounded">
        <iframe class="w-100 rounded" height="320px" src="<?php echo $contact_r['iframe'] ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>  
        </div>
        <div class="col-lg-4 col-md-4">
        <div class="bg-white p-4 rounded mb-4">
            <h5>Call Us</h5>
            <a href="Tel: +<?php echo $contact_r['pn1'] ?>" class="d-inline-block mb-2 text-decoration-none text-dark">
                <i class="bi bi-telephone-fill"></i> +<?php echo $contact_r['pn1'] ?></a>
                <br>
                <?php
                    if($contact_r['pn2']!=''){
                      echo<<<data
                      <a href="Tel: +$contact_r[pn2]" class="d-inline-block text-decoration-none text-dark">
                         <i class="bi bi-telephone-fill"></i> +$contact_r[pn2]
                      </a>
                      data;  
                    }
                ?>
        </div>
        <div class="bg-white p-4 rounded mb-4">
            <h5>Follow Us</h5>
            <?php
            if($contact_r['tw']!=''){
                echo<<<data
                <a href="$contact_r[tw]" class="d-inline-block mb-3">
                    <span class="badge bg-light text-dark fs-6 p-2">
                        <i class="bi bi-twitter me-1"></i> Twitter
                    </span>
                </a>
                <br>
                data;
                 }
                 ?>  
                <a href="<?php echo $contact_r['fb'] ?>" class="d-inline-block mb-3">
                <span class="badge bg-light text-dark fs-6 p-2">
                    <i class="bi bi-facebook me-1"></i> Facebook
                </span>
                </a>
                <br>
                <a href="<?php echo $contact_r['insta'] ?>"class="d-inline-block">
                    <span class="badge bg-light text-dark fs-6 p-2">
                        <i class="bi bi-instagram me-1"></i> Instagram
                    </span>
                    </a>
                <br>

        </div>
        </div>
    </div>
    </div>
   
    <button id="supportBtn" class="btn-md custom-bg btn-outline-dark rounded fw-bold shadow-none " onclick="redirectSupport('mousumgogoi392@gmail.com')">
  <i class="bi bi-question-circle"></i> Support
</button>

   
    <?php require('inc/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

<script>


  function redirectSupport(email) {
    window.location.href = 'mailto:' + email;
  }

    var swiper = new Swiper(".swiper-container", {
    slidesPerView: 1,
    spaceBetween: 30,
    keyboard: {
    enabled: true,
    },
    loop: true,
    autoplay: {
    delay: 3500,
    disableOnIntercation: false
    }
    });
    var swiper = new Swiper(".swiper-testimonials", {
    effect: "coverflow",
    grabCursor: true,
    centeredSlides: true,
    slidesPerView: "auto",
    slidesPerView: "3",
    loop: true,
    coverflowEffect: {
    rotate: 50,
    stretch: 0,
    depth: 100,
    modifier: 1,
    slideShadows: false,
    },
    pagination: {
    el: ".swiper-pagination",
    },
    breakpoints: {
    320: {
        slidesPerView: 1,
    },
    640: {
        slidesPerView: 1,
    },
    768: {
        slidesPerView: 2,
    },
    1024: {
        slidesPerView: 3,
    },
    }
});
</script>
</body>
</html>
