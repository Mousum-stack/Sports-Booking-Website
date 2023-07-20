<?php
  session_start();
    require('inc/essentials.php');
    require('inc/db_config.php');
    adminLogin();

    ?>
    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Pannel - Features & Facilities</title>
    <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">
   <?php
    require('inc/header.php');
   ?>
    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">FEATURES & FACILITIES</h3>

      <div class="card border-0 shadow mb-4" >
        <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="card-title m-0">Features</h5>
                <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#feature-s">
                  <i class="bi bi-plus-square"></i>Add
                </button>
            </div>
           <div class="table-responsive-md" style="height: 350px; overflow-y: scroll;">
           <table class="table table-hover border">
                <thead class="sticky-top">
                    <tr class="bg-dark text-light">
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="features-data">
                  
                </tbody>
                </table>
           </div>
        
        
        </div>
        </div>



      <div class="card border-0 shadow mb-4" >
        <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="card-title m-0">Facilities</h5>
                <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#facility-s">
                  <i class="bi bi-plus-square"></i>Add
                </button>
            </div>
           <div class="table-responsive-md" style="height: 350px; overflow-y: scroll;">
           <table class="table table-hover border">
                <thead>
                    <tr class="bg-dark text-light">
                    <th scope="col">#</th>
                    <th scope="col">Icon</th>
                    <th scope="col">Name</th>
                    <th scope="col" width="40%">Description</th>
                    <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="facilities-data">
                  
                </tbody>
                </table>
           </div>
        
        
        </div>
        </div>


            </div>
        </div>
    </div>
       <!-- Feature modal -->
<div class="modal fade" id="feature-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="feature_s_form">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Features</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Name</label>
                        <input type="text" name="feature_name" id="site_title_inp" class="form-control shadow-none" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn custom-bg text-white shadow-none">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>


 <!-- Sports settings modal -->

 <div class="modal fade" id="facility-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <?php
            if(isset($_SESSION['status']) && $_SESSION != '')
            {
                ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Hey!</strong> <?php echo $_SESSION['status']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php
                unset($_SESSION['status']);
            }
            ?>
            <form action="../admin/ajax/features_facilities.php" method="post" enctype="multipart/form-data">

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Facility</h5>
                        </div>
                <div class="modal-body">
               <div class="mb-3">
                    <label  class="form-label fw-bold">name</label>
                    <input type="text" name="name" id="name" class="form-control shadow-none" required>
                </div>
                <div class="mb-3">
                    <label  class="form-label fw-bold">Icon</label>
                    <input type="file" accept=".svg"  name="picture" id="picture" class="form-control shadow-none" required>

                 </div>
                 <div class="mb-3">
                            <label  class="form-label">Description</label>
                            <textarea name="facility_desc" id="facility_desc" class="form-control shadow-none" rows="3"></textarea>
                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn text-secondary shadow-none" onclick="document.getElementsByName('name')[0].value=''; document.getElementsByName('picture')[0].value=''; document.getElementsByName('facility_desc')[0].value='';"
                    data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="save_stud_image" class="btn custom-bg text-white shadow-none">Save</button>
                </div>
                </div>
            </form>
        </div>
        </div>



<?php require('inc/scripts.php'); ?>
<script src="scripts/features_facilities.js"></script>
</body>
</html>
