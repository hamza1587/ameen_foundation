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
        $loaner_id =  $_POST['loaner_id'];
        if(!empty($loaner_id)){
            $sql = $conn->query("SELECT mobile_no,bank_acc_id,(SUM(credit)-SUM(debit))'amount',address,details FROM person_information INNER JOIN loans ON loans.person_id = person_information.person_id WHERE loans.person_id  = '$loaner_id' GROUP BY person_information.name");
            if(mysqli_num_rows($sql) > 0){
                $data = mysqli_fetch_assoc($sql);
                echo json_encode($data);
            }
        }
    }
}

?>