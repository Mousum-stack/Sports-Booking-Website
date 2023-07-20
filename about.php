<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Play Hard- ABOUT</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css"/>
<?php require('inc/links.php'); ?>
<style>
    .box{
        border-top-color: var(--teal) !important;
    }
    h2span{
        text-decoration: underline solid rgb(217, 246, 167) 40%;
    }

</style>
</head>
<body class="bg-light">
   <?php require('inc/header.php');?>
   <div class="my-5 px-4">
    <h2 class="fw-bold h-font text-center">About Us</h2>
    <div class="h-line bg-dark"></div>
    <br>
    <h3 class="text-center mt-3 fw-bold" style="color:green;">Play Hard, India's Leading Sports Facility Booking Platform.</h3>  
 </div>
   <div class="container">
    <div class="row justify-content-between align-items-center">
        <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-md-1 order-2">
            <p class="mb-3 fw-bold" style="font-size: 19px; text-align:justify;">Welcome to Play Hard! We offer an online 
                experience for sports enthusiasts, ensuring you never miss out on your favorite 
                events. Our user-friendly platform provides hassle-free ticket booking for a wide 
                range of sports worldwide. From football to basketball, tennis to gym, swimming to lawn-tennis we have it
                 all. With authorized vendors and a secure transaction process, we prioritize your 
                 satisfaction and security. Our dedicated support team is always ready to assist 
                 you. Immerse yourself in the world of sports with Play Hard and 
                 witness the electrifying moments that make sports extraordinary. Join us today 
                 and let the games begin!</p>
          
        </div>
    <div class="col-lg-5 col-md-5 mb-4 order-lg-2 order-md-2 order-1">
    <img src="images/background/tag.png" class="w-100">
    </div>

    </div>
   </div>
    <div class="container mt-5">
        <div class="row">
            <h2span class="my-5 fw-bold h-font" style="font-size: 50px;">Do more with <span style="color:  rgb(0, 169, 79);">Play Hard</span>
          </h2span>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/features/team.png" width="80px" height="80px">
                    <h4>Get Easy Bookings</h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/features/badminton.png" width="80px" height="80px">
                    <h4>Discover Venues</h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/features/rate.png" width="80px" height="80px">
                    <h4>Rate & Review</h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/features/Profile.png" width="80px" height="80px">
                    <h4>Build Profile</h4>
                </div>
            </div>
            
        </div>
    </div>
    <h3 class="my-5 fw-bold h-font text-center" style="color: rgb(0, 169, 79);">One Solution For Sports Activity</h3>
    <div class="container px-4">
        <div class="swiper mySwiper">
            <div class="swiper-wrapper mb-5">
                <?php
                $about_r = selectAll('aboutimg');
                $path = ABOUT_IMG_PATH;
                while($row = mysqli_fetch_assoc($about_r)){
                    echo<<<data
                         <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                         <img src="$path$row[image]" class="w-100">
                         <h5 class="mt-2">$row[name]</h5>
                         </div>
                    data;
                }
                ?>
                  
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-scrollbar"></div>
            <div class="swiper-pagination"></div>
          </div>
    </div>

    <?php require('inc/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

<script>
    var swiper = new Swiper(".mySwiper", {
    effect: "coverflow",
    grabCursor: true,
    centeredSlides: true,
    slidesPerView: "auto",
    coverflowEffect: {
        rotate: 50,
        stretch: 0,
        depth: 100,
        modifier: 1,
        slideShadows: true,
    },
    slidesPerView: 3,
    spaceBetween: 40,
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
