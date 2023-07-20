<?php
  session_start();
    require('inc/essentials.php');
    adminLogin();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Pannel - Carousel</title>
    <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">
   <?php
    require('inc/header.php');
   ?>
    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">Carousel</h3>

                      <!-- Carousel section -->
      <div class="card border-0 shadow mb-4" >
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="card-title m-0">Images</h5>
                <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#carousel-s">
                  <i class="bi bi-plus-square"></i>Add
                </button>
            </div>
            <div class="row" id="carousel-data">
             </div>
        
        
        </div>
        </div>

         <!-- Carousel modal -->

        <div class="modal fade" id="carousel-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
            <form action="inc/img.php" method="post" enctype="multipart/form-data">

                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">add sports</h5>
                </div>
                <div class="modal-body">
                <div class="mb-3">
                    <label  class="form-label fw-bold">image</label>
                    <input type="file" name="picture" id="picture" class="form-control shadow-none" required>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn text-secondary shadow-none" onclick="document.getElementsByName('picture')[0].value='';"
                    data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="stud_image" class="btn custom-bg text-white shadow-none">Save</button>
                </div>
                </div>
            </form>
        </div>
        </div>


            </div>
        </div>
    </div>

<?php require('inc/scripts.php'); ?>
<script src="scripts/carousel.js"></script>
</body>
</html>
