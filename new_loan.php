<?php ob_start(); ?>
<?php $page = 'loan_management'; ?>
<?php $title = "New Loan"; ?>
<?php include "includes/main_sidebar.php"; ?>
<?php
if ($isAdmin != 1) {
    if ($loan_management_access == FALSE) {
        header("Location: index.php");
    }
}

?>
<div class="content-wrapper">
    <section class="content">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title"> <i class="fa fa-plus"></i> Add New Loan </h3>
                <div class="card-tools">
                    <a href="loans" class="btn btn-dark btn-sm pull-right">View Loans</a>
                </div>
            </div>
            <?php
            $invoice_code = "SELECT invoice_no FROM loans WHERE loan_id = (SELECT MAX(loan_id) FROM loans)";
            $run_code = mysqli_query($conn, $invoice_code);
            if (mysqli_num_rows($run_code) > 0) {
                $row_code = mysqli_fetch_assoc($run_code);
                $t_code = $row_code['invoice_no'];
                $tran_code = $t_code;
                $tt_code = str_replace("-", "", $tran_code);
                $result = $tt_code[3];
                $result = $result + 1;
                $a = $tt_code[0];
                $b = $tt_code[1];
                $c = $tt_code[2];
                $code = $a . $b . $c . "-" . $result;
            } else {
                $code = "Inv-1";
            }
            ?>
            <?php include "loan_management/process.php"; ?>
            <?php echo $error_message; ?>
            <div class="card-body">
                <form method="post">
                    <input type="hidden" name="user_id" id="user_id" value="<?= $user_id; ?>">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3"></div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label for="firstname" class="control-label col-md-3" style="padding-top: 7px;" required="">Loan Type</label>
                                                <div class="col-md-8">
                                                    <select class="form-control" name="loan_type" id="loan_type">
                                                        <option value="" selected>Choose Loan Type</option>
                                                        <option value="take">Take Loan</option>
                                                        <option value="return">Return Loan</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="display:none" id="loanForm">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="invoice_no" class="control-label">Select Person</label>
                                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name">
                                                <select class="form-control" name="loaner_id" id="loaner_id">
                                                    <option value="" selected>Choose Person</option>
                                                    <?php
                                                    $fetch_person = "SELECT `person_id`,`name` FROM person_information GROUP BY person_id";
                                                    $run_person = mysqli_query($conn, $fetch_person);
                                                    if ($run_person) {
                                                        while ($row_person = mysqli_fetch_array($run_person)) {
                                                            $person_id  = $row_person['person_id'];
                                                            $name       = $row_person['name'];
                                                            echo "<option value='$person_id'>$name</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="invoice_no" class="control-label">Mobile No</label>
                                                <input type="number" name="mobile_no" id="mobile_no" class="form-control" id="mobile_no" required="" placeholder="Enter Mobile No">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="firstname" class="control-label" required="">Bank Account</label>
                                                <select class="form-control" name="bank_acc_id" id="bank_acc_id">
                                                    <option value="" selected>Choose Cash Account</option>
                                                    <?php
                                                    $fetch_bank_accounts = "select * from bank_accounts";
                                                    $run_bank_accounts = mysqli_query($conn, $fetch_bank_accounts);
                                                    if ($run_bank_accounts) {
                                                        $total_rows = mysqli_num_rows($run_bank_accounts);
                                                        while ($row_bank_accounts = mysqli_fetch_array($run_bank_accounts)) {
                                                            $bank_acc_id = $row_bank_accounts['bank_acc_id'];
                                                            $bank_acc_name = $row_bank_accounts['bank_acc_name'];
                                                            echo "<option value='$bank_acc_id'>$bank_acc_name</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="invoice_no" class="control-label">Invoice#</label>
                                                <input type="text" name="invoice_no" class="form-control" id="invoice_no" value="<?= $code; ?>" required="" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="date" class="control-label">Billing Date</label>
                                            <input type="date" name="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required="" <?php if ($isAdmin != 1) {
                                                                                                                                                        echo "readonly";
                                                                                                                                                    } ?>>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="date" class="control-label">Amount</label>
                                            <input type="number" name="amount" id="amount" class="form-control" placeholder="Enter Amount Here" required="">
                                        </div>
                                        <div class="col-md-3" style="display: none;" id="loanAmount">
                                            <label for="date" class="control-label">Paid Amount</label>
                                            <input type="number" name="paid_amount" id="paid_amount" class="form-control" placeholder="Enter Paid Amount Here">
                                            <span id="error" class="text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="invoice_no" class="control-label">Address</label>
                                        <textarea name="address" id="address" class="form-control" rows="2" placeholder=""></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="invoice_no" class="control-label">Details</label>
                                        <textarea name="details" id="details" class="form-control" rows="2" placeholder=""></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body text-center">
                                    <a href="loans" class="btn btn-dark">Cancel</a>
                                    <input type="submit" name="submit" id="save" value="" class="btn bg-info">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
<?php include "includes/footer.php"; ?>
<link rel="stylesheet" href="sweetalert.min.css" />
<script src="sweetalert.min.js"></script>
<script>
    $(function() {
        $('#loan_type').change(function() {
            var loan_type = $("#loan_type").val();
            if (loan_type == "return") {
                $("#loaner_id").show('fast');
                $("#name").hide('fast');
                $("#loanForm").show('fast');
                $("#loanAmount").show('fast');
                $("#save").val('Return');
                $("#loaner_id").prop('required', true);
                $("#paid_amount").prop('required', true);
                $("#paid_amount").prop('readonly', true);
                $("#mobile_no").prop('readonly', true);
                $("#paid_amount").prop('readonly', true);
                $("#amount").prop('readonly', true);
                $("#address").prop('readonly', true);
                $("#details").prop('readonly', true);
            } else if (loan_type == "take") {
                $("#loaner_id").hide('fast');
                $("#name").show('fast');
                $("#loanForm").show('fast');
                $("#loanAmount").hide('fast');
                $("#save").val('Save');
                $("#name").prop('required', true);
            } else {
                $("#loanForm").hide('fast');
            }
        });
        $('#loaner_id').change(function() {
            var loaner_id = $("#loaner_id").val();
            $.ajax({
                url: "loan_management/details.php",
                type: "POST",
                data: {
                    loaner_id: loaner_id
                },
                dataType: "json",
                success: function(data) {
                    $("#mobile_no").val(data.mobile_no);
                    $("#bank_acc_id").val(data.bank_acc_id);
                    $("#amount").val(data.amount);
                    $("#address").val(data.address);
                    $("#details").val(data.details);
                    $("#mobile_no").prop('readonly', true);
                    $("#amount").prop('readonly', true);
                    $("#address").prop('readonly', true);
                    $("#details").prop('readonly', true);
                    $("#paid_amount").prop('readonly', false);
                    if($("#amount").val() == 0 || $("#amount").val() < 0) {
                        $(':input[type="submit"]').prop('disabled', true);
                        setTimeout(function() {
                            swal({
                                title: "Error!",
                                text: "Loan Balance is Zero!",
                                type: "error"
                            }, function() {
                                window.location = "loans";
                            });
                        }, 1000);
                    }
                }
            });
        });
        $("#paid_amount").on("keydown keyup", per);

        function per() {
            if (Number($("#paid_amount").val()) > Number($("#amount").val())) {
                $("#error").text("Paid Amount is not greater than Amount");
                $(':input[type="submit"]').prop('disabled', true);
            }else if(Number($("#paid_amount").val()) == 0){
                $("#error").text("");
                $(':input[type="submit"]').prop('disabled', true);
            }else{
                $("#error").text("");
                $(':input[type="submit"]').prop('disabled', false);
            }
        }
    });
</script>
<?php ob_end_flush(); ?>