<?php $page = 'dashboard'; ?>
<?php $title = "Dashboard"; ?>
<?php include "includes/main_sidebar.php"; ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <?php
            if($isAdmin == 1){
                include 'includes/admin_dashboard.php';
            }else{
                include 'includes/user_dashboard.php';
            }?>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<?php include "includes/footer.php"; ?>
