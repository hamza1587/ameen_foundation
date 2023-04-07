<?php
    // Database connection
    include('includes/db.php');

    global $wrongPwdErr, $accountNotExistErr, $emailPwdErr, $verificationRequiredErr, $email_empty_err, $pass_empty_err;

    if(isset($_POST['login'])) {
        $email_signin        = $_POST['email_signin'];
        $password_signin     = $_POST['password_signin'];

        // clean data 
        $user_email = filter_var($email_signin, FILTER_SANITIZE_EMAIL);
        $pswd = mysqli_real_escape_string($conn, $password_signin);

        // Query if email exists in db
        $sql = "SELECT * From users WHERE email = '{$email_signin}' ";
        $query = mysqli_query($conn, $sql);
        $rowCount = mysqli_num_rows($query);

        // If query fails, show the reason 
        if(!$query){
           die("SQL query failed: " . mysqli_error($conn));
        }

        if(!empty($email_signin) && !empty($password_signin)){
            // Check if email exist
            if($rowCount <= 0) {
                $accountNotExistErr = '<div class="alert alert-danger">
                        User account does not exist.
                    </div>';
            } else {
                // Fetch user data and store in php session
                while($row = mysqli_fetch_array($query)) {
                    $user_id     = $row['user_id'];
                    $name        = $row['name'];
                    $email       = $row['email'];
                    $pass_word   = $row['user_password'];
                    $isAdmin     = $row['isAdmin'];
                }

                // Verify password
                $password = password_verify($password_signin, $pass_word);

                // Allow only verified user
                if($isAdmin == '1') {
                    if($email_signin == $email && $password_signin == $password) {
                       header("Location: ./dashboard");
                       session_regenerate_id();
                       $_SESSION['loggedin'] = TRUE;
                       $_SESSION['user_id'] = $user_id;
                       $_SESSION['name'] = $name;
                       $_SESSION['email'] = $email;

                    } else {
                        $emailPwdErr = '<div class="alert alert-danger">
                                Either email or password is incorrect.
                            </div>';
                    }
                } else if($isAdmin == '0') {
                    if($email_signin == $email && $password_signin == $password) {
                       header("Location: ./dashboard");
                       session_regenerate_id();
                       $_SESSION['loggedin'] = TRUE;
                       $_SESSION['user_id'] = $user_id;
                       $_SESSION['name'] = $name;
                       $_SESSION['email'] = $email;

                    } else {
                        $emailPwdErr = '<div class="alert alert-danger">
                                Either email or password is incorrect.
                            </div>';
                    }
                } else {
                    $verificationRequiredErr = '<div class="alert alert-danger">
                            Either email or password is incorrect.
                        </div>';
                }

            }

        } else {
            if(empty($email_signin)){
                $email_empty_err = "<div class='alert alert-danger email_alert'>
                            Email not provided.
                    </div>";
            }
            
            if(empty($password_signin)){
                $pass_empty_err = "<div class='alert alert-danger email_alert'>
                            Password not provided.
                        </div>";
            }            
        }

    }

?>