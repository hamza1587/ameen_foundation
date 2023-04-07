<?php ob_start(); ?>
<?php $page = 'payments'; ?>
<?php $title = "Payments"; ?>
<?php include "includes/main_sidebar.php"; ?>
<?php
if($isAdmin != 1){
    if($payments_access == FALSE){
        header('Location: index.php');
    }
}

?>
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card" id="show_payments">
                        <div class="card-header">
                            <h3 class="card-title">Payments</h3>
                            <div class="card-tools">
                                <button type="button" name="add" id="addPayment" class="btn btn-dark btn-sm pull-right">New Payment</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="paymentList" class="table table-bordered table-striped">
                                    <thead class="bg-info">
                                        <tr>
                                            <th>Sr#</th>
                                            <th>Receipt #</th>
                                            <th>Date</th>
                                            <th>From Account</th>
                                            <th>To Account</th>
                                            <th>Amount Type</th>
                                            <th>Invoice</th>
                                            <th><i class="fa fa-cogs"></i></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-default" style="display: none;" id="add_payment">
            <div class="card-header">
                <h3 class="card-title"><i class="fa fa-plus"></i> &nbsp; Add New Payment</h3>
                <div class="card-tools">
                    <button type="button" name="view" id="viewPayment" class="btn btn-dark btn-sm pull-right">View Payments</button>
                </div>
            </div>
            <?php
            $invoice_code = "SELECT receipt_no FROM payments WHERE payment_id = (SELECT MAX(payment_id) FROM payments)";
            $run_code = mysqli_query($conn, $invoice_code);
            if (mysqli_num_rows($run_code) > 0) {
                $row_code = mysqli_fetch_assoc($run_code);
                $t_code = $row_code['receipt_no'];
                $tran_code = $t_code;
                $tt_code = str_replace("-", "", $tran_code);
                $result = $tt_code[2];
                $result = $result + 1;
                $a = $tt_code[0];
                $b = $tt_code[1];
                $code = $a . $b . "-" . $result;
            } else {
                $code = "Rc-1";
            }
            ?>
            <div class="card-body" id="view_payment">
                <form method="post" id="paymentForm">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="invoice_no" class="control-label">Reciept #</label>
                                                <input type="text" name="receipt_no" class="form-control" id="receipt_no" value="<?= $code; ?>" required="" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3"></div>
                                        <div class="col-md-3"></div>
                                        <div class="col-md-3">
                                            <label for="date" class="control-label">Date</label>
                                            <input type="date" name="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required="" <?php if ($isAdmin != 1) {
                                                                                                                                                        echo "readonly";
                                                                                                                                                    } ?>>
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
                                            <label for="date" class="control-label">Amount</label>
                                            <input type="number" name="amount" id="amount" class="form-control" placeholder="Enter Amount Here" required="">
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="firstname" class="control-label" required="">From Account</label>
                                                <select class="form-control" name="from_account" id="from_account" required>
                                                    <option value="" selected>Choose Cash Account</option>
                                                    <?php
                                                    $fetch_bank_accounts = "select * from bank_accounts";
                                                    $run_bank_accounts = mysqli_query($conn, $fetch_bank_accounts);
                                                    if ($run_bank_accounts) {
                                                        $total_rows = mysqli_num_rows($run_bank_accounts);
                                                        while ($row_bank_accounts = mysqli_fetch_array($run_bank_accounts)) {
                                                            $bank_acc_id = $row_bank_accounts['bank_acc_id'];
                                                            $bank_acc_name = $row_bank_accounts['bank_acc_name'];
                                                    ?>
                                                            <option value="<?php echo $bank_acc_id; ?>" style="<?php if ($bank_acc_name == "Cash" || $bank_acc_name == "Cheque") {
                                                                                                                    echo "display:none";
                                                                                                                } ?>"><?php echo $bank_acc_name; ?></option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="firstname" class="control-label" required="">To Account</label>
                                                <select class="form-control" name="to_account" id="to_account" required>
                                                    <option value="" selected>Choose Cash Account</option>
                                                    <?php
                                                    $fetch_bank_accounts = "select * from bank_accounts";
                                                    $run_bank_accounts = mysqli_query($conn, $fetch_bank_accounts);
                                                    if ($run_bank_accounts) {
                                                        $total_rows = mysqli_num_rows($run_bank_accounts);
                                                        while ($row_bank_accounts = mysqli_fetch_array($run_bank_accounts)) {
                                                            $bank_acc_id = $row_bank_accounts['bank_acc_id'];
                                                            $bank_acc_name = $row_bank_accounts['bank_acc_name'];
                                                    ?>
                                                            <option value="<?php echo $bank_acc_id; ?>" style="<?php if ($bank_acc_name == "Cash" || $bank_acc_name == "Cheque") {
                                                                                                                    echo "display:none";
                                                                                                                } ?>"><?php echo $bank_acc_name; ?></option>
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
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="invoice_no" class="control-label">Amount Type</label>
                                                <select class="form-control" name="amount_type" id="amount_type" required>
                                                    <option value="Cash">Cash</option>
                                                    <option value="Cheque">Cheque</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row" style="display:none" id="show_cheque">
                                        <div class="col-md-2"></div>
                                        <label for="date" class="control-label col-md-1">Cheque #</label>
                                        <div class="col-md-7">
                                            <input type="text" name="cheque_no" id="cheque_no" class="form-control" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="invoice_no" class="control-label">Details</label>
                                        <textarea name="details" id="details" class="form-control" rows="2" placeholder="" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body text-center">
                                    <input type="hidden" name="editId" id="editId" value="" />
                                    <input type="hidden" name="action" id="action" value="" />
                                    <a href="payments.php" class="btn btn-dark">Cancel</a>
                                    <input type="submit" name="submit" value="Save" class="btn bg-info">
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
<script>
    $(document).ready(function() {
        $("#amount_type").change(function() {
            var amount_type = $("#amount_type").val();
            if (amount_type == 'Cheque') {
                $("#show_cheque").show('fast');
                $("#cheque_no").prop('required', true);
            } else {
                $("#show_cheque").hide('fast');
                $("#cheque_no").prop('required', false);
            }
        });
        $("#viewPayment").on('click', function() {
            $("#show_payments").show('fast');
            $("#add_payment").hide('fast');
        });
    });
</script>

<script>
    $(document).ready(function() {
        var paymentRecords = $('#paymentList').DataTable({
            "responsive": true,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "bProcessing": true,
            "order": [],
            "ajax": {
                url: "payments/process.php",
                type: "POST",
                data: {
                    action: 'listPayment'
                },
                dataType: "json"
            },
            "columnDefs": [{
                "targets": [-1], //last column
                "orderable": false, //set not orderable
            }, ],
            "pageLength": 10
        });
        $('#addPayment').click(function() {
            $("#show_payments").hide('fast');
            $("#add_payment").show('fast');
            $('#paymentForm')[0].reset();
            $('#action').val('addPayment');
        });
        $("#paymentList").on('click', '.update', function() {
            var editId = $(this).attr("id");
            var action = 'getPayment';
            $.ajax({
                url: 'payments/process.php',
                method: "POST",
                data: {
                    editId: editId,
                    action: action
                },
                dataType: "json",
                success: function(data) {
                    $('#add_payment').show('fast');
                    $('#show_payments').hide('fast');
                    $('#editId').val(data.receipt_no);
                    $('#receipt_no').val(data.receipt_no);
                    $('#date').val(data.date);
                    $('#from_account').val(data.from_account);
                    $('#to_account').val(data.to_account);
                    $('#amount_type').val(data.amount_type);
                    if (data.amount_type == "Cash") {
                        $("#show_cheque").hide();
                        $("#cheque_no").prop('required', false);
                    } else {
                        $("#show_cheque").show('fast');
                        $("#cheque_no").prop('required', true);
                        $('#cheque_no').val(data.cheque_no);
                    }
                    if(data.account_type == "Credit"){
                        $('#amount').val(data.credit);
                    }else{
                        $('#amount').val(data.debit);
                    }
                    $('#details').val(data.details);
                    $('#account_type').val(data.account_type);
                    $('#action').val('updatePayment');
                    $('#save').val('Save');
                }
            })
        });
        $("#add_payment").on('submit', '#paymentForm', function(event) {
            event.preventDefault();
            $('#save').attr('disabled', 'disabled');
            var formData = $(this).serialize();
            $.ajax({
                url: "payments/process.php",
                method: "POST",
                data: formData,
                success: function(data) {
                    $('#paymentForm')[0].reset();
                    $('#add_payment').hide('fast');
                    $('#show_payments').show('fast');
                    $('#save').attr('disabled', false);
                    window.location = 'add-payments';
                }
            })
        });
        $("#paymentList").on('click', '.delete', function() {
            var editId = $(this).attr("id");
            var action = "deletePayment";
            if (confirm("Are you sure you want to delete this payment?")) {
                $.ajax({
                    url: "payments/process.php",
                    method: "POST",
                    data: {
                        editId: editId,
                        action: action
                    },
                    success: function(data) {
                        window.location = 'add-payments';
                    }
                })
            } else {
                return false;
            }
        });
    });
</script>
<?php ob_end_flush(); ?>