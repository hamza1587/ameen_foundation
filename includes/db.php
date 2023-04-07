<?php
/* at the top of 'check.php' */
if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
    header( 'HTTP/1.0 403 Forbidden', TRUE, 403 );

    /* choose the appropriate page to redirect users */
    die( header( 'location: ../404.php' ) );

}else{
    $connect_error = 'Sorry, we\' re experiencing connection problems. ';
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ngo";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
}
?>
