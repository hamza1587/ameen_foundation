<?php ob_start(); ?>
<?php $page = 'settings'; ?>
<?php $title = "Settings"; ?>
<?php include "includes/main_sidebar.php"; ?>
<?php
if ($isAdmin != 1) {
    if($settings_access == FALSE){
        header('Location: index.php');
    }
} ?>
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Company Details</h3>
                        </div>
                        <?php
                        if (isset($_POST['update_password'])) {
                            $n_pass_border_color = '';
                            $c_pass_border_color = '';
                            $new_password = $_POST['new_password'];
                            $confirm_password = $_POST['confirm_password'];
                            $user_id = $_POST['id'];
                            if (empty($new_password)) {
                                $n_pass_border_color = 'red !important';
                            } else if (empty($confirm_password)) {
                                $c_pass_border_color = 'red !important';
                            } else if($new_password != $confirm_password){
                                $error = "confirm password not matched!!!";
                            }else {
                                $enctype_password = password_hash($confirm_password, PASSWORD_DEFAULT);
                                $conn->query("UPDATE users SET user_password = '$enctype_password' WHERE user_id = '$user_id' AND isAdmin = '1'");
                                @session_destroy();
                                echo "<script>window.location.href='login'</script>";
                            }
                        }
                        if(isset($_POST['update_email'])){
                            $email_border_color = '';
                            $email = $_POST['email'];
                            $user_id = $_POST['user_id'];
                            if (empty($email)) {
                                $email_border_color = 'red !important';
                            } else {
                                $conn->query("UPDATE users SET email = '$email' WHERE user_id = '$user_id' AND isAdmin = '1'");
                                echo "<script>window.location.href='profile'</script>";
                            }
                        }
                        ?>
                        <style>
                            #new_password {
                                border-color: <?php if (isset($n_pass_border_color)) {
                                                    echo $n_pass_border_color;
                                                } ?>
                            }

                            #confirm_password {
                                border-color: <?php if (isset($c_pass_border_color)) {
                                                    echo $c_pass_border_color;
                                                } ?>
                            }
                            #email {
                                border-color: <?php if (isset($email_border_color)) {
                                                    echo $email_border_color;
                                                } ?>
                            }
                        </style>
                        <div class="card-body">
                            <form class="form-horizontal" method="POST">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-3"></div>
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <input type="hidden" id="user_id" name="user_id" value="<?= $user_id ?>">
                                                <label for="email" class="col-sm-4 control-label">Email<label class="text-danger">*</label></label>
                                                <div class="col-sm-12">
                                                    <input type="email" class="form-control" id="email" name="email" value="<?= $email ?>" placeholder="Enter Email">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-md-5"></div>
                                        <div class="col-md-2">
                                            <button type="submit" id="update_email" name="update_email" class=" btn btn-block btn-info" title="Save Data">Update Email</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <hr>
                            <form class="form-horizontal" method="POST">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-3"></div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="hidden" name="id" value="<?= $user_id ?>">
                                                <label for="new_password" class="col-sm-4 control-label">New Password<label class="text-danger">*</label></label>
                                                <div class="col-sm-12">
                                                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter New Password" value="<?php if (isset($_POST['new_password'])) { echo $_POST['new_password']; } ?>">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="confirm_password" class="col-sm-4 control-label">Confirm Password<label class="text-danger">*</label></label>
                                                <div class="col-sm-12">
                                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm New Password" value="<?php if (isset($_POST['confirm_password'])) { echo $_POST['confirm_password']; } ?>">
                                                    <?php if(isset($error)):?>
                                                        <span class="text-danger"><?= $error ?></span>
                                                    <?php endif;?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-md-5"></div>
                                        <div class="col-md-2">
                                            <button type="submit" id="update_password" name="update_password" class="btn btn-block btn-info" title="Save Data">Update Password</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php include "includes/footer.php"; ?>
<style>
    .focused {
        border-color: red !important;
    }
</style>
<?php ob_end_flush();?>