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
    @session_start();
    include "../includes/db.php";
    // If the user is not logged in redirect to the login page...
    if (!isset($_SESSION['loggedin'])) {
        @header('Location: login.php');
        exit;
    } else {
        $status =  $_POST['status'];
        $id =  $_POST['id'];
        $role =  $_POST['role'];
        if(!empty($id) && !empty($status) && !empty($role)){
            $sql = $conn->query("SELECT * FROM permission WHERE role_id = '$role' && module_id = '$id'");
            if(mysqli_num_rows($sql) > 0){
                $conn->query("UPDATE `permission` SET `module_id` = '$id', `permissions` = '$status', `role_id` = '$role' WHERE role_id = '$role' && module_id = '$id'");
            }else{
                $conn->query("INSERT INTO `permission`(`module_id`, `permissions`, `role_id`) VALUES ('$id', '$status', '$role')");
            }
        }else{
            $conn->query("UPDATE `permission` SET `module_id` = '$id', `permissions` = '$status', `role_id` = '$role' WHERE role_id = '$role' && module_id = '$id'");
        }
    }
}

?>