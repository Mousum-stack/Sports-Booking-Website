<?php
session_start();


if(isset($_SESSION['authenticated']))
    {
        $_SESSION['status'] = "You are already logged in";
        header('Location: dashboard.php');
        exit(0);
    }

$page_title = "Login Form";
include('INC/header.php');
include('INC/links.php');
?>

<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
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

                <div class="card shadow">
                    <div class="card-header">
                        <h5><i class="bi bi-person-circle"></i>  Login form</h5>
                    </div>
                    <div class="card-body">
                        <form action="logincode.php" method="post">
                            <div class="form-group mb-3">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="login_now_btn"  class="btn text-white custom-bg shadow-none">Login now</button>
                                <a href="password-reset.php" class="float-end">Forgot your Password</a>
                            </div>
                        </form>
                        <hr>
                        <h5>
                            Did not recive your verification email
                            <a href="resend-email-verification.php">Resend</a>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('INC/footer.php');
?>
