<?php ob_start(); ?>
<?php $page = 'expenditure'; ?>
<?php $title = "Expenditure"; ?>
<?php include "includes/main_sidebar.php"; ?>
<?php
if ($isAdmin != 1) {
    if ($expense_access == FALSE) {
        header("Location: index.php");
    }
}

?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card" id="show_expense">
                        <div class="card-header">
                            <h3 class="card-title">Expenses</h3>
                            <div class="card-tools">
                                <button type="button" name="view" id="addExpense" class="btn btn-dark btn-sm pull-right">New Expence</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="expenseList" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr#</th>
                                            <th>Reference No.</th>
                                            <th>Category</th>
                                            <th>Expense For</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>User</th>
                                            <th>Role</th>
                                            <th>Invoice</th>
                                            <?php if ($isAdmin == 1) { ?>
                                                <th><i class="fa fa-cogs"></i></th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card" style="display: none;" id="view_expense">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-plus"></i> &nbsp; Add New Expense</h3>
                            <div class="card-tools">
                                <button type="button" name="view" id="viewExpense" class="btn btn-dark btn-sm pull-right">View Expense</button>
                            </div>
                        </div>
                        <?php
                        $refrence_no = "SELECT refrence_no FROM expense WHERE exp_id = (SELECT MAX(exp_id) FROM expense)";
                        $run_code = mysqli_query($conn, $refrence_no);
                        if (mysqli_num_rows($run_code) > 0) {
                            $row_code = mysqli_fetch_assoc($run_code);
                            $t_code = $row_code['refrence_no'];
                            $s = explode("-", $t_code);
                            unset($s[0]);
                            $s = implode(" ", $s);
                            $result = $s + 1;
                            $code = "RFN-" . $result;
                        } else {
                            $code = "RFN-1";
                        }
                        ?>
                        <form method="post" id="expenseForm">
                            <input type="hidden" name="user_id" id="user_id" value="<?= $user_id; ?>">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="control-label col-md-3" style="padding-top: 7px;">Expense Date: </label>
                                            <div class="col-md-6">
                                                <input type="date" name="expense_date" id="expense_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required <?php if ($isAdmin != 1) {
                                                                                                                                                                                echo "readonly";
                                                                                                                                                                            } ?>>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="control-label col-md-3" style="padding-top: 7px;">Reference No: </label>
                                            <div class="col-md-6">
                                                <input type="text" name="refrence_no" id="refrence_no" class="form-control" value="<?php echo $code; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="control-label col-md-3" style="padding-top: 7px;">Category: </label>
                                            <div class="col-md-6">
                                                <select class="form-control" name="expense_id" id="expense_id" style="width: 100%;" required>
                                                    <option value="">-Select Expense Type-</option>
                                                    <?php
                                                    $fetch_expenses = "select * from expenses";
                                                    $run_expenses = mysqli_query($conn, $fetch_expenses);
                                                    if ($run_expenses) {
                                                        while ($row_expenses = mysqli_fetch_array($run_expenses)) {
                                                            $expense_id = $row_expenses['expense_id'];
                                                            $expense_name = $row_expenses['expense_title'];
                                                            echo "<option value='$expense_id'>$expense_name</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="control-label col-md-3" style="padding-top: 7px;">Amount: </label>
                                            <div class="col-md-6">
                                                <input type="number" step="any" name="expense_amount" id="expense_amount" class="form-control" placeholder="Enter Amount" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="control-label col-md-3" style="padding-top: 7px;">Amount Type: </label>
                                            <div class="col-md-6">
                                                <select class="form-control" name="amount_type" id="amount_type" required>
                                                    <option value="" selected>Choose Amount Type</option>
                                                    <option value="Cash">Cash</option>
                                                    <option value="Cheque">Cheque</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="control-label col-md-3" style="padding-top: 7px;">Bank Name: </label>
                                            <div class="col-md-6">
                                                <select class="form-control" name="bank_acc_id" id="bank_acc_id" style="width: 100%;" required>
                                                    <option value="">-Select Account Type-</option>
                                                    <?php
                                                    $fetch_bank_accounts = "select * from bank_accounts";
                                                    $run_bank_accounts = mysqli_query($conn, $fetch_bank_accounts);
                                                    if ($run_bank_accounts) {
                                                        while ($row_bank_accounts = mysqli_fetch_array($run_bank_accounts)) {
                                                            $bank_acc_id = $row_bank_accounts['bank_acc_id'];
                                                            $bank_acc_name = $row_bank_accounts['bank_acc_name'];
                                                    ?>
                                                            <option value="<?php echo $bank_acc_id; ?>"><?php echo $bank_acc_name; ?></option>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="display: none;" id="show_cheque">
                                        <div class="form-group row">
                                            <label class="control-label col-md-3" style="padding-top: 7px;">Cheque No: </label>
                                            <div class="col-md-6">
                                                <input type="text" name="cheque_no" id="cheque_no" class="form-control" placeholder="Enter Check No">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="control-label col-md-3" style="padding-top: 7px;">Expense For: </label>
                                            <div class="col-md-6">
                                                <input type="text" name="expense_for" id="expense_for" class="form-control" placeholder="Enter Expense For" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-5"></div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="hidden" name="editId" id="editId" value="" />
                                            <input type="hidden" name="action" id="action" value="" />
                                            <button type="submit" name="save" id="save" class="btn btn-info">Save Expense</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php include "includes/footer.php"; ?>
<script>
    $(document).ready(function() {
        $("#viewExpense").on('click', function() {
            $("#view_expense").hide('fast');
            $("#show_expense").show('fast');
        });
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
    });
</script>
<script>
    $(document).ready(function() {
        var expenseRecords = $('#expenseList').DataTable({
            "responsive": true,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "bProcessing": true,
            "order": [],
            "ajax": {
                url: "expenses/process.php",
                type: "POST",
                data: {
                    action: 'listExpense'
                },
                dataType: "json"
            },
            "columnDefs": [{
                "targets": [-1], //last column
                "orderable": false, //set not orderable
            }, ],
            "pageLength": 100
        });
        $('#addExpense').click(function() {
            $("#view_expense").show('fast');
            $("#show_expense").hide('fast');
            $('#expenseForm')[0].reset();
            $('#action').val('addExpense');
        });
        $("#expenseList").on('click', '.update', function() {
            var editId = $(this).attr("id");
            var action = 'getExpense';
            $.ajax({
                url: 'expenses/process.php',
                method: "POST",
                data: {
                    editId: editId,
                    action: action
                },
                dataType: "json",
                success: function(data) {
                    $('#view_expense').show('fast');
                    $('#show_expense').hide('fast');
                    $('#editId').val(data.exp_id);
                    $('#expense_date').val(data.expense_date);
                    $('#refrence_no').val(data.refrence_no);
                    $('#expense_id').val(data.expense_id);
                    $('#amount_type').val(data.amount_type);
                    $('#bank_acc_id').val(data.bank_acc_id);
                    $('#cheque_no').val(data.cheque_no);
                    $('#expense_for').val(data.expense_for);
                    if (data.amount_type == "Cash") {
                        $("#show_cheque").hide();
                        $("#cheque_no").prop('required', false);
                    } else {
                        $("#show_cheque").show('fast');
                        $("#cheque_no").prop('required', true);
                        $('#cheque_no').val(data.cheque_no);
                    }
                    $('#expense_amount').val(data.expense_amount);
                    $('#action').val('updateExpense');
                    $('#save').val('Save');
                }
            })
        });
        $("#view_expense").on('submit', '#expenseForm', function(event) {
            event.preventDefault();
            $('#save').attr('disabled', 'disabled');
            var formData = $(this).serialize();
            $.ajax({
                url: "expenses/process.php",
                method: "POST",
                data: formData,
                success: function(data) {
                    $('#expenseForm')[0].reset();
                    $('#view_expense').hide('fast');
                    $('#show_expense').show('fast');
                    $('#save').attr('disabled', false);
                    window.location.href = 'add-expense';
                }
            })
        });
        $("#expenseList").on('click', '.delete', function() {
            var editId = $(this).attr("id");
            var action = "deleteExpense";
            if (confirm("Are you sure you want to delete this expense?")) {
                $.ajax({
                    url: "expenses/process.php",
                    method: "POST",
                    data: {
                        editId: editId,
                        action: action
                    },
                    success: function(data) {
                        window.location.href = 'add-expense';
                    }
                })
            } else {
                return false;
            }
        });
    });
</script>
<?php ob_end_flush(); ?>