<?php
    // Database connection
    include('includes/db.php');
    
    global $error_message;

    if(isset($_POST['submit'])) {

        if($_POST['submit'] == "Save") {

            $name              =   $_POST['name'];
            $mobile_no         =   $_POST['mobile_no'];
            $bank_acc_id       =   $_POST['bank_acc_id'];

            $invoice_no        =   $_POST['invoice_no'];
            $date              =   $_POST['date'];
            $amount            =   $_POST['amount'];

            $address           =   $_POST['address'];
            $details           =   $_POST['details'];
            $created_at        =   date('Y-m-d H:i:s');
            $user_id           =   $_POST['user_id'];

            if(!empty($name) && !empty($mobile_no) && !empty($bank_acc_id) && !empty($invoice_no) && !empty($date) && !empty($amount)){
                $sqlQuery = $conn->query("SELECT person_id FROM person_information WHERE `name` LIKE '$name%'");
                if(mysqli_num_rows($sqlQuery) > 0){
                    $sqlRows = mysqli_fetch_assoc($sqlQuery);
                    $person_id = $sqlRows['person_id'];
                    $sql = $conn->query("INSERT INTO `loans`(`person_id`, `bank_acc_id`, `invoice_no`, `credit`, `payment_type`, `date`, `created_at`, `user_id`) VALUES ('$person_id', '$bank_acc_id', '$invoice_no', '$amount', 'Credit', '$date', '$created_at', '$user_id')");                   
                    if($sql){
                        echo '<script>
                            setTimeout(function() {
                                swal({
                                    title: "Success!",
                                    text: "Data addedd successfully!",
                                    type: "success"
                                }, function() {
                                    window.location = "add-loan";
                                });
                            }, 1000);
                        </script>';
                    }else{
                        echo '<script>
                            setTimeout(function() {
                                swal({
                                    title: "Error!",
                                    text: "Data Not Added!",
                                    type: "error"
                                }, function() {
                                    window.location = "add-loan";
                                });
                            }, 1000);
                        </script>';
                    }
                } else{
                    $stmt = $conn->query("INSERT INTO `person_information`(`name`, `mobile_no`, `address`, `details`) VALUES ('$name', '$mobile_no', '$address', '$details')");
                    $person_id = $conn->insert_id;
                    $sql = $conn->query("INSERT INTO `loans`(`person_id`, `bank_acc_id`, `invoice_no`, `credit`, `payment_type`, `date`, `created_at`, `user_id`) VALUES ('$person_id', '$bank_acc_id', '$invoice_no', '$amount', 'Credit', '$date', '$created_at', '$user_id')");  
                    if($sql){
                        echo '<script>
                            setTimeout(function() {
                                swal({
                                    title: "Success!",
                                    text: "Data addedd successfully!",
                                    type: "success"
                                }, function() {
                                    window.location = "add-loan";
                                });
                            }, 1000);
                        </script>';
                    } else{
                        echo '<script>
                            setTimeout(function() {
                                swal({
                                    title: "Error!",
                                    text: "Data Not Added!",
                                    type: "error"
                                }, function() {
                                    window.location = "add-loan";
                                });
                            }, 1000);
                        </script>';
                    }
                }
            } else {
                $error_message = "<div class='alert alert-danger email_alert'>
                                Data Not Added.
                        </div>";          
            }
        }

        if($_POST['submit'] == "Return") {

            $loaner_id          =   $_POST['loaner_id'];
            $bank_acc_id        =   $_POST['bank_acc_id'];
            $invoice_no         =   $_POST['invoice_no'];
            $paid_amount        =   $_POST['paid_amount'];
            $payment_type       =   'Debit';
            $date               =   $_POST['date'];
            $created_at         =   date('Y-m-d H:i:s');
            $user_id            =   $_POST['user_id'];

            if(!empty($loaner_id) && !empty($invoice_no) && !empty($paid_amount) && !empty($date)){
                $sqlQuery = $conn->query("INSERT INTO `loans`(`person_id`, `bank_acc_id`, `invoice_no`, `debit`, `payment_type`, `date`, `created_at`, `user_id`) VALUES ('$loaner_id', '$bank_acc_id', '$invoice_no', '$paid_amount', '$payment_type', '$date', '$created_at', '$user_id')");
                if($sqlQuery){
                    echo '<script>
                        setTimeout(function() {
                            swal({
                                title: "Success!",
                                text: "Data addedd successfully!",
                                type: "success"
                            }, function() {
                                window.location = "add-loan";
                            });
                        }, 1000);
                    </script>';
                }else{
                    echo '<script>
                        setTimeout(function() {
                            swal({
                                title: "Error!",
                                text: "Data Not Added!",
                                type: "error"
                            }, function() {
                                window.location = "add-loan";
                            });
                        }, 1000);
                    </script>';
                }
            } else {
                $error_message = "<div class='alert alert-danger email_alert'>
                               Data Not Added.
                        </div>";          
            }
        }
    }

?>