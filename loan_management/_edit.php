<?php
ob_start();
include '../includes/db.php';
$loan = mysqli_real_escape_string($conn, $_POST['id']);
$stmt1 = $conn->prepare('SELECT payment_type FROM loans WHERE loan_id = ?');
$stmt1->bind_param('s', $loan);
$stmt1->execute();
$stmt1->store_result();
if ($stmt1->num_rows > 0) {
    $stmt1->bind_result($payment_type);
    $stmt1->fetch();
    if($payment_type == "Credit"){
        $stmt2 = $conn->prepare('SELECT loans.person_id, user_id, name, mobile_no, details, address, invoice_no, bank_acc_id, credit, date FROM loans INNER JOIN person_information ON loans.person_id = person_information.person_id WHERE loan_id = ?');
        $stmt2->bind_param('s', $loan);
        $stmt2->execute();
        $stmt2->store_result();
        $stmt2->bind_result($person_id, $user_id, $name, $credit_mobile_no, $credit_details, $credit_address, $credit_invoice_no, $credit_bank_acc_id, $credit, $credit_date);
        $stmt2->fetch();
        $stmt2->close();
    }else{
        $stmt3 = $conn->prepare('SELECT loans.person_id, user_id, mobile_no, details, address, invoice_no, bank_acc_id, credit, debit, date FROM loans INNER JOIN person_information ON loans.person_id = person_information.person_id WHERE loan_id = ?');
        $stmt3->bind_param('s', $loan);
        $stmt3->execute();
        $stmt3->store_result();
        $stmt3->bind_result($person_id, $user_id, $debit_mobile_no, $debit_details, $debit_address, $debit_invoice_no, $debit_bank_acc_id, $credit, $debit, $debit_date);
        $stmt3->fetch();
        $stmt3->close();
    }
}
$stmt1->close();
?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Edit Loan</h3>
                                <div class="card-tools">
                                    <a href="loan_management.php" class="btn btn-dark btn-sm pull-right">View Loan</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="post">
                                    <input type="hidden" name="user_id" id="user_id" value="<?= $user_id; ?>">
                                    <input type="hidden" name="loan_id" id="loan_id" value="<?= $loan; ?>">

                                    <?php if($payment_type == "Credit"){?>
                                        <input type="hidden" name="person_id" id="person_id" value="<?= $person_id; ?>">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="invoice_no" class="control-label">Person Name</label>
                                                                    <input type="text" name="name" class="form-control" value="<?= $name; ?>" id="name" placeholder="Enter Name">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="invoice_no" class="control-label">Mobile No</label>
                                                                    <input type="number" name="mobile_no" id="mobile_no" value="<?= $credit_mobile_no; ?>" class="form-control" required="" placeholder="Enter Mobile No">
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
                                                                                ?>
                                                                                <option value="<?= $row_bank_accounts['bank_acc_id']; ?>" <?php if($credit_bank_acc_id == $row_bank_accounts['bank_acc_id']){ echo "selected";}?>><?= $row_bank_accounts['bank_acc_name']; ?></option>
                                                                                <?php
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
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="invoice_no" class="control-label">Invoice#</label>
                                                                    <input type="text" name="invoice_no" class="form-control" id="invoice_no" value="<?= $credit_invoice_no; ?>" required="" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="date" class="control-label">Billing Date</label>
                                                                <input type="date" name="date" class="form-control" value="<?= $credit_date; ?>" required="">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="date" class="control-label">Amount</label>
                                                                <input type="number" name="amount" id="amount" class="form-control" value="<?= $credit; ?>" placeholder="Enter Amount Here" required="">
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
                                                            <textarea name="address" id="address" class="form-control" rows="2" placeholder=""><?= $credit_address ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label for="invoice_no" class="control-label">Details</label>
                                                            <textarea name="details" id="details" class="form-control" rows="2" placeholder=""><?= $credit_details ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="card">
                                                    <div class="card-body text-center">
                                                        <a href="loan_management.php" class="btn btn-dark">Cancel</a>
                                                        <input type="submit" name="submit" id="save" value="Save" class="btn bg-info">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }?>
                                    <?php if($payment_type == "Debit"){?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="invoice_no" class="control-label">Select Person</label>
                                                                    <select class="form-control" name="loaner_id" id="loaner_id">
                                                                        <option value="" selected>Choose Person</option>
                                                                        <?php
                                                                        $fetch_person = "SELECT `person_id`,`name` FROM person_information";
                                                                        $run_person = mysqli_query($conn, $fetch_person);
                                                                        if ($run_person) {
                                                                            while ($row_person = mysqli_fetch_array($run_person)) {
                                                                                ?>
                                                                                <option value="<?= $row_person['person_id']; ?>" <?php if($person_id == $row_person['person_id']){ echo "selected";}?>><?= $row_person['name']; ?></option>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="invoice_no" class="control-label">Mobile No</label>
                                                                    <input type="number" name="mobile_no" id="mobile_no" value="<?= $debit_mobile_no; ?>" class="form-control" required="" placeholder="Enter Mobile No">
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
                                                                                ?>
                                                                                <option value="<?= $row_bank_accounts['bank_acc_id']; ?>" <?php if($debit_bank_acc_id == $row_bank_accounts['bank_acc_id']){ echo "selected";}?>><?= $row_bank_accounts['bank_acc_name']; ?></option>
                                                                                <?php
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
                                                                    <input type="text" name="invoice_no" class="form-control" id="invoice_no" value="<?= $debit_invoice_no; ?>" required="" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="date" class="control-label">Billing Date</label>
                                                                <input type="date" name="date" class="form-control" value="<?= $debit_date; ?>" required="">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label for="date" class="control-label">Amount</label>
                                                                <input type="number" name="amount" id="amount" class="form-control" value="<?= $credit; ?>" placeholder="Enter Amount Here" required="">
                                                            </div>
                                                            <div class="col-md-3" style="display: none;" id="loanAmount">
                                                                <label for="date" class="control-label">Paid Amount</label>
                                                                <input type="number" name="paid_amount" id="paid_amount" class="form-control" value="<?= $debit; ?>" placeholder="Enter Paid Amount Here">
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
                                                            <textarea name="address" id="address" class="form-control" rows="2" placeholder=""><?= $debit_address ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label for="invoice_no" class="control-label">Details</label>
                                                            <textarea name="details" id="details" class="form-control" rows="2" placeholder=""><?= $debit_details ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="card">
                                                    <div class="card-body text-center">
                                                        <a href="loan_management.php" class="btn btn-dark">Cancel</a>
                                                        <input type="submit" name="submit" id="save" value="Return" class="btn bg-info">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }?>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
    <script>
        $(function() {
            <?php if($payment_type == "Debit"){ ?>
            $("#loanAmount").show('fast');
            $("#save").val('Return');
            $("#loaner_id").prop('required', true);
            $("#paid_amount").prop('required', true);
            $("#paid_amount").prop('readonly', true);
            $("#mobile_no").prop('readonly', true);
            $("#amount").prop('readonly', true);
            $("#address").prop('readonly', true);
            $("#details").prop('readonly', true);
            $("#paid_amount").prop('readonly', false);
            var loaner_id = $("#loaner_id").val();
            if(loaner_id != ""){
                $.ajax({
                    url: "loan_management/details.php",
                    type: "POST",
                    data: {
                        loaner_id: loaner_id
                    },
                    dataType: "json",
                    success: function(data) {
                        $("#amount").val(data.amount);
                        $("#mobile_no").prop('readonly', true);
                        $("#amount").prop('readonly', true);
                        $("#address").prop('readonly', true);
                        $("#details").prop('readonly', true);
                        $("#paid_amount").prop('readonly', false);
                        if ($("#amount").val() == 0) {
                            $(':input[type="submit"]').prop('disabled', true);
                        }
                    }
                });
            }
            <?php } ?>
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
                        if ($("#amount").val() == 0) {
                            $(':input[type="submit"]').prop('disabled', true);
                        }
                    }
                });
            });
        });
    </script>
<?php ob_end_flush(); ?>