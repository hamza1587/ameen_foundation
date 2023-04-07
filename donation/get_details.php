<?php

require_once '../includes/db.php';
if(!empty($_POST['phone_number'])) {
    $phone_no = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $sqlQuery = $conn->query("SELECT *, SUM(total)'total' FROM donations WHERE phone_number = '$phone_no'  GROUP BY phone_number");
    $row = mysqli_fetch_array($sqlQuery);
    echo json_encode($row);
}