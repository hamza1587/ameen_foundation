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
            $Del_Id = $_POST['Del_ID'];
            $query = "DELETE FROM loans WHERE loan_id = '$Del_Id' ";
            $result = mysqli_query($conn, $query);
            if($result){
                echo json_encode($result);
            }else{
                echo json_encode($result);
            }
        }
    }
?>