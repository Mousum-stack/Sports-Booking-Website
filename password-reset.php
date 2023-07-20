<?php
session_start();
// $page_title = "Password_Reset_Form";
include('INC/header.php');
include('INC/links.php');
?>
<div class="py-5">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">

        <div class="alert">
          <?php
                    if(isset($_SESSION['status']))
                    {
                        ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Hey!</strong> <?php echo $_SESSION['status']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php
                        unset($_SESSION['status']);
                    }
                ?>
            </div>
        <div class="card shadow">
                    <div class="card-header">
                        <h5>Reset Password</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="password-reset-code.php" method="post">
                            <div class="form-group mb-3">
                                <label>Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                            </div>
                          
                            <div class="form-group mb-3">
                                <button type="submit" name="password_reset_link" class="btn btn-primary">Send Password Reset LInk</button>
                            </div>
                        </form>
                       
                    </div>
                </div>
        </div>
    </div>
</div>
</div>
<?php  include('INC/footer.php');