<?php
    @session_start();
    if (isset($_SESSION['loggedin'])) {
        echo "<script>window.open('dashboard', '_self')</script>";
    }else{
        echo "<script>window.open('login.php', '_self')</script>";   
    }
?>