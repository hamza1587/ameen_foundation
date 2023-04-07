<?php
include '../includes/db.php';
if(!empty($_POST['email'])) {
    $email = $_POST['email'];
    $user_id = $_POST['user_id'];
    $select_email = $conn->query("SELECT email FROM users WHERE email = '$email' AND isAdmin = '0'");
    if(mysqli_num_rows($select_email) > 0){
        echo "exists";
    }else{
        $email_query = $conn->query("UPDATE users SET email = '$email' WHERE user_id = '$user_id' AND isAdmin = '1'");
    }
}

?>