<?php
// Database connection
include('includes/db.php');

global $error_message;

if(isset($_POST['submit'])) {

    if($_POST['submit'] == "Save") {

        $person_id         =   $_POST['person_id'];
        $name              =   $_POST['name'];
        $mobile_no         =   $_POST['credit_mobile_no'];
        $bank_acc_id       =   $_POST['credit_bank_acc_id'];

        $loan_id           =   $_POST['loan_id'];
        $date              =   $_POST['credit_date'];
        $amount            =   $_POST['credit'];

        $address           =   $_POST['credit_address'];
        $details           =   $_POST['credit_details'];

        if(!empty($name) && !empty($mobile_no) && !empty($bank_acc_id) && !empty($date) && !empty($amount)){
            
            $personSQL = $conn->query("UPDATE `person_information` SET `name`='$name', `mobile_no`='$mobile_no', `address`='$address', `details`='$details' WHERE person_id = '$person_id'");
            $loanSQL = $conn->query("UPDATE `loans` SET `person_id` = '$person_id', `bank_acc_id` = '$bank_acc_id', `credit` = '$amount', `date` = '$date' WHERE loan_id = '$loan_id'");
            if($loanSQL){
                echo '<script>
                            setTimeout(function() {
                                swal({
                                    title: "Success!",
                                    text: "Data addedd successfully!",
                                    type: "success"
                                }, function() {
                                    window.location = "../loans";
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
                                    window.location = "../loans";
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

    if($_POST['submit'] == "Return") {

        $loan_id            =   $_POST['loan_id'];
        $loaner_id          =   $_POST['loaner_id'];
        $bank_acc_id        =   $_POST['debit_bank_acc_id'];
        $paid_amount        =   $_POST['paid_amount'];
        $date               =   $_POST['debit_date'];
        $updated_at         =   date('Y-m-d H:i:s');

        if(!empty($loaner_id) && !empty($paid_amount) && !empty($date)){
            $sqlQuery = $conn->query("UPDATE `loans` SET `person_id` = '$loaner_id', `bank_acc_id` = '$bank_acc_id', `debit` = '$paid_amount', `date` = '$date' WHERE loan_id = '$loan_id'");
            if($sqlQuery){
                echo '<script>
                        setTimeout(function() {
                            swal({
                                title: "Success!",
                                text: "Data addedd successfully!",
                                type: "success"
                            }, function() {
                                window.location = "../loans";
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
                                window.location = "../loans";
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