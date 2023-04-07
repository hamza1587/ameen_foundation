<?php

require_once '../includes/db.php';
if(!empty($_POST['person_phone_no'])) {
    $person_phone_no = mysqli_real_escape_string($conn, $_POST['person_phone_no']);
    $sqlQuery = $conn->query("SELECT * FROM project_expense WHERE person_phone_no = '$person_phone_no'");
    $row = mysqli_fetch_array($sqlQuery);
    echo json_encode($row);
}