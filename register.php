<?php
session_start();
$page_title = "Registration Form";
include('INC/header.php');
include('INC/links.php');
?>

<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">.
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

            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header">
                    <h5 class="modal-title d-flex align-items-center" >
                    <i class="bi bi-person-add fs-3 me-2"></i>User Registration</h5>
                    </div>
                    <div class="card-body">
                            <form action="code.php" method="POST">
                            <span class="badge rounded-pill bg-light text-dark mb-3 text-wrap lh-base">
                        Note: Your details must match with your Id(Aadhaar card, passport, driving lisence, etc.) 
                        that will be required during entry.
                        </span>
                        <div class="row">
                                 <div class="col-md-4 mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" required name="name" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" required name="phone" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" required name="email" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" required name="password" class="form-control">
                                </div>
                                <div class="col-md-8 mb-4">
                                    <label class="form-label">Address</label>
                                   <textarea name="address" required class="form-control" rows="1"></textarea>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Pin Code</label>
                                    <input type="number" required name="pin" class="form-control">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" required name="dob" class="form-control">
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" name="register_btn" class="btn text-white custom-bg shadow-none">Register now</button>
                                </div>
                        </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('INC/footer.php');
?>

