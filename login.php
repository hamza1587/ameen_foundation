<?php
include "includes/db.php";
@session_start();
if (isset($_SESSION['loggedin'])) {
    echo "<script>window.open('dashboard', '_self')</script>";
}
?>
<!-- Login script -->
<?php include('./authentication/check_login.php'); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="">
                <b>Ameen Foundation</b>
            </a>
            <img src="images/logo.jpeg" style="height:100px">
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to access</p>
                <?php echo $accountNotExistErr; ?>
                <?php echo $emailPwdErr; ?>
                <?php echo $verificationRequiredErr; ?>
                <?php echo $email_empty_err; ?>
                <?php echo $pass_empty_err; ?>
                <form action="" method="post">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" name="email_signin" placeholder="Email">
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password_signin" placeholder="Password">
                    </div>
                    <div class="row">
                        <div class="col">
                            <button type="submit" name="login" id="sign_in" class="btn btn-primary btn-block" name="submit">Sign In</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>

</body>

</html>
