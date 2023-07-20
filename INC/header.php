<?php

session_start();
require('admin/inc/db_config.php');
require('admin/inc/essentials.php');

    $contact_q = "SELECT * FROM `contact_details` WHERE `sr_no`=?";
    $settings_q = "SELECT * FROM `settings` WHERE `sr_no`=?";
    $values = [1];
    $contact_r = mysqli_fetch_assoc(select($contact_q,$values,'i')); 
    $settings_r = mysqli_fetch_assoc(select($settings_q,$values,'i'));
    
    if($settings_r['shutdown'])
    {
        echo<<<alertbar
        <div class='bg-danger text-center p-2 fw-bold'>
        <i class="bi bi-exclamation-diamond-fill"></i>
        Booking are temporarily closed!
        </div>
        alertbar;
    }
    
 ?>
<nav id="nav-bar" class="navbar navbar-expand-lg navbar-light bg-white px-lg-3 py-lg-2 shadow-sm sticky-top ">
<div class="container-fluid">
<a class="navbar-brand me-5 fw-bold  fs-3 h-font" href="index.php"><?php echo $settings_r['site_title'] ?></a>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
<span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarSupportedContent">
<ul class="navbar-nav me-auto mb-2 mb-lg-0">
<li class="nav-item">
<a class="nav-link me-2" href="index.php">Home</a>
</li>
<li class="nav-item">
<a class="nav-link me-2"  href="venues.php">Venues</a>
</li>
<li class="nav-item">
<a class="nav-link me-2" href="facilities.php">Facilities</a>
</li>
<li class="nav-item">
<a class="nav-link me-2" href="contact.php">Contact us</a>
</li>
<li class="nav-item">
<a class="nav-link me-2" href="about.php">About</a>
</li>
<li class="nav-item">
<a class="nav-link me-2" href="partner.php">Partnership</a>
</li>
   
  

</ul>
<div class="d-flex">
<?php if(!isset($_SESSION['authenticated'])) : ?>
                    <li class="nav-item">
                    <a  class="btn btn-outline-dark shadow-none me-lg-3 me-2" href="register.php" >
                     Register
                     </a>                    </li>
                    <li class="nav-item">
                    <a class="btn btn-outline-dark shadow-none me-lg-3 me-2" href="login.php" >
                        Login
                     </a>                    </li>
                    <?php endif  ?>
        
                    <?php 
                    $u_exist = select("SELECT * from `users` WHERE `id`=? LIMIT 1",[$_SESSION['uId']],'s');

                    if(mysqli_num_rows($u_exist)==0)
                    {
                        
                    }
                    $u_fetch = mysqli_fetch_assoc($u_exist);
                    if(isset($_SESSION['authenticated'])) : ?>
                    <li class="nav-item"> 
                    <a class="btn btn-outline-dark shadow-none dropdown-toggle" href="logout.php" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                    <?php echo $u_fetch['name'] ?>
                        </a>
                            <ul class="dropdown-menu dropdown-menu-lg-end">
                                <li><a class="dropdown-item" href="Dashboard.php">Profile</a></li>
                                <li><a class="dropdown-item" href="booking.php">Booking</a></li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                                
                            </ul>
                                       
                     </li>
                    <?php endif  ?>

</div> 
</div>
</div>
</nav>
