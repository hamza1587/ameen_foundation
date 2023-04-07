<?php

require_once '../includes/db.php';
if(!empty($_POST['phone_no'])) {
    $phone_no = mysqli_real_escape_string($conn, $_POST['phone_no']);
    $sqlQuery = $conn->query("SELECT * FROM membership WHERE phone_no = '$phone_no'  GROUP BY phone_no");
    $row = mysqli_fetch_array($sqlQuery);
    echo json_encode($row);
}