<?php ob_start(); ?>
<?php $page = 'settings'; ?>
<?php $title = "System Settings"; ?>
<?php include "includes/main_sidebar.php"; ?>
<?php
if ($isAdmin != 1) {
    header('Location: index.php');
} ?>
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">System Details</h3>
                        </div>
                        <?php
                        $get_setting = $conn->query("SELECT * FROM `system_settings` LIMIT 1");
                        $fetch_setting = mysqli_fetch_assoc($get_setting);
                        if(isset($_POST['update'])){
                            $registration_no_border_color = '';
                            $registration_no = $_POST['registration_no'];
                            $id = $_POST['id'];
                            if (empty($registration_no)) {
                                $registration_no_border_color = 'red !important';
                            } else {
                                $conn->query("UPDATE system_settings SET registration_no = '$registration_no' WHERE id = '$id'");
                                echo "<script>window.location.href='system-settings'</script>";
                            }
                        }
                        if(isset($_POST['save'])){
                            $registration_no_border_color = '';
                            $registration_no = $_POST['registration_no'];
                            if (empty($registration_no)) {
                                $registration_no_border_color = 'red !important';
                            } else {
                                $conn->query("INSERT INTO `system_settings`(`registration_no`) VALUES ('$registration_no')");
                                echo "<script>window.location.href='system-settings'</script>";
                            }
                        }
                        ?>
                        <style>
                            #registration_no {
                                border-color: <?php if (isset($registration_no_border_color)) {
                                                    echo $registration_no_border_color;
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
                                                <?php if(mysqli_num_rows($get_setting) > 0){?>
                                                    <input type="hidden" id="id" name="id" value="<?= $fetch_setting['id']; ?>">
                                                <?php } ?>
                                                <label for="email" class="col-sm-4 control-label">Registration No<label class="text-danger">*</label></label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control" id="registration_no" name="registration_no" value="<?= $fetch_setting['registration_no']; ?>" placeholder="Enter Registration No">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-md-5"></div>
                                        <div class="col-md-2">
                                        <?php if(mysqli_num_rows($get_setting) > 0){?>
                                            <button type="submit" id="update" name="update" class=" btn btn-block bg-info" title="Update Data">Update Settings</button>
                                        <?php }else{?>
                                            <button type="submit" id="save" name="save" class=" btn btn-block btn-success" title="Save Data">Save Settings</button>
                                        <?php }?>
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