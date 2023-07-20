<?php
session_start();
$page_title = "Password Change Update";
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
                        <h5>Change Password</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="password-reset-code.php" method="post">
                            <input type="hidden" name="password_token" value="<?php if(isset($_GET['token'])){echo $_GET['token'];}  ?>">
                            <div class="form-group mb-3">
                                <label>Email Address</label>
                                <input type="email" name="email" value="<?php if(isset($_GET['email'])){echo $_GET['email'];}  ?>" class="form-control" placeholder="Enter your email" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>New Password</label>
                                <input type="text" name="new_password" class="form-control" placeholder="Enter new Password" required>
                            </div> <div class="form-group mb-3">
                                <label>Confirm Password</label>
                                <input type="text" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
                            </div>
                          
                            <div class="form-group mb-3">
                                <button type="submit" name="password_update" class="btn btn-success w-100">Update Passsword</button>
                            </div>
                        </form>
                       
                    </div>
                </div>
        </div>
    </div>
</div>
</div>
<?php  include('INC/footer.php');