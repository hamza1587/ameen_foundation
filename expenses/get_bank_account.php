<?php
    @session_start();
    include "../includes/db.php";
    // If the user is not logged in redirect to the login page...
    if (!isset($_SESSION['loggedin'])) {
        @header('Location: login.php');
        exit;
    } else {
        $id =  $_POST['id'];
        if(!empty($id)){
            $sqlQuery = "SELECT bank_acc_name FROM bank_accounts WHERE bank_acc_id = '$id'";
			$result = mysqli_query($conn, $sqlQuery);	
			$row = mysqli_fetch_assoc($result);
			echo json_encode($row);
        }
    }

?>