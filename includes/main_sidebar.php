<?php
/* at the top of 'check.php' */
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    /*
           Up to you which header to send, some prefer 404 even if
           the files does exist for security
        */
    header('HTTP/1.0 403 Forbidden', TRUE, 403);

    /* choose the appropriate page to redirect users */
    die(header('location: ../404.php'));
} else {
    
    $redirect = "";
    if($page == "user"){
        if($sub_page == "permissions"){
            $redirect = "../";
        }
    }
    if($page == "loan_management"){
        if(isset($sub_page) == "edit_loan"){
            $redirect = "../";
        }
    }
    ?>
    <?php include "header.php"; ?>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="javascript:void(0);" class="brand-link">
        <center>
            <span class="brand-text font-weight-light">
                <b class="pl-0" style="font-size: xx-large;"> Ameen</b>
                Foundation
            </span>
        </center>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="<?= $redirect; ?>dashboard" class="nav-link <?php if($page == "dashboard"){ echo "active";} ?>">
                        <i class="nav-icon fa fa-tachometer"></i>
                        <p>
                            Dashboard
                            <span class="right badge badge-danger"></span>
                        </p>
                    </a>
                </li>
                <?php 
                    if($isAdmin == 1){
                        include 'includes/admin_sidebar.php';
                    }else{
                        include 'includes//user_sidebar.php';
                    }
                ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<?php }?>