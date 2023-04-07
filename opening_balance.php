<?php ob_start(); ?>
<?php $page = 'opening_balance'; ?>
<?php $title = "Opening Balance"; ?>
<?php include "includes/main_sidebar.php"; ?>
<?php
if ($isAdmin != 1) {
    if ($opening_balance_access == FALSE) {
        header('Location: index.php');
    }
}
?>
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Opening Balance</h3>
                            <div class="card-tools">
                                <button type="button" name="add" id="addOB" class="btn btn-dark btn-sm pull-right">New Opening Balanace</button>
                            </div>
                        </div>
                        <div class="card-body" id="view_opening_balance">
                            <div class="table-responsive">
                                <table id="obList" class="table table-bordered table-striped">
                                    <thead class="bg-info">
                                        <tr>
                                            <th style="width: 20px !important;">Sr#</th>
                                            <th>Date</th>
                                            <th>Account Type</th>
                                            <th>Amount</th>
                                            <th>Details</th>
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
    </section>
</div>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <form method="post" id="obForm">
                    <input type="hidden" name="user_id" id="user_id" value="<?= $user_id; ?>">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Date</label>
                                <input type="date" name="ob_date" id="ob_date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Account Type</label>
                                <select class="form-control" name="amount_type" id="amount_type" style="width: 100%;" required>
                                    <option value="" selected>Choose Amount Type</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Cheque">Cheque</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12" style="display: none;" id="show_cheque">
                            <div class="form-group">
                                <label for="">Cheque No</label>
                                <input type="text" name="cheque_no" id="cheque_no" class="form-control" placeholder="Enter Check No">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Account Type</label>
                                <select class="form-control" name="bank_acc_id" id="bank_acc_id" style="width: 100%;" required>
                                    <option value="" selected>Choose Bank Account</option>
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
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Balance Amount</label>
                                <input type="number" name="ob_amount" id="ob_amount" class="form-control" placeholder="Enter Balance Amount" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Details</label>
                                <textarea name="ob_details" id="ob_details" cols="10" rows="5" class="form-control" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <input type="hidden" name="editId" id="editId" value="" />
                            <input type="hidden" name="action" id="action" value="" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" name="save" id="save" class="btn bg-info"> Save </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include "includes/footer.php"; ?>
<style>
    .focused {
        border-color: red !important;
    }

    .text {
        color: red !important;
    }
</style>
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
        });
    </script>
<script>
    $(document).ready(function() {
        var onRecords = $('#obList').DataTable({
            "responsive": true,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "bProcessing": true,
            "order": [],
            "ajax": {
                url: "opening_balance/process.php",
                type: "POST",
                data: {
                    action: 'listOB'
                },
                dataType: "json"
            },
            "columnDefs": [{
                "targets": [-1], //last column
                "orderable": false, //set not orderable
            }, ],
            "pageLength": 10
        });
        $('#addOB').click(function() {
            $('#myModal').modal('show');
            $('#obForm')[0].reset();
            $('.modal-title').html("<i class='fa fa-plus'></i> Add Opening Balance");
            $('#action').val('addOB');
            $('#save').val('Add');
        });
        $("#obList").on('click', '.update', function() {
            var editId = $(this).attr("id");
            var action = 'getOB';
            $.ajax({
                url: 'opening_balance/process.php',
                method: "POST",
                data: {
                    editId: editId,
                    action: action
                },
                dataType: "json",
                success: function(data) {
                    $('#myModal').modal('show');
                    $('#editId').val(data.ob_id);
                    $('#ob_date').val(data.ob_date);
                    $('#amount_type').val(data.amount_type);
                    if(data.amount_type == "Cash") {
                        $("#show_cheque").hide();
                        $("#cheque_no").prop('required', false);
                    } else {
                        $("#show_cheque").show('fast');
                        $("#cheque_no").prop('required', true);
                        $('#cheque_no').val(data.cheque_no);
                    }
                    $('#bank_acc_id').val(data.bank_acc_id);
                    $('#ob_amount').val(data.ob_amount);
                    $('#ob_details').val(data.ob_details);
                    $('.modal-title').html("<i class='fa fa-plus'></i> Edit Opening Balance");
                    $('#action').val('updateOB');
                    $('#save').val('Save');
                }
            })
        });
        $("#myModal").on('submit', '#obForm', function(event) {
            event.preventDefault();
            $('#save').attr('disabled', 'disabled');
            var formData = $(this).serialize();
            $.ajax({
                url: "opening_balance/process.php",
                method: "POST",
                data: formData,
                success: function(data) {
                    $('#obForm')[0].reset();
                    $('#myModal').modal('hide');
                    $('#save').attr('disabled', false);
                    onRecords.ajax.reload();
                    $.notify("Status Changed Successfully", "success");
                }
            })
        });
        $("#obList").on('click', '.delete', function() {
            var editId = $(this).attr("id");
            var action = "deleteOB";
            if (confirm("Are you sure you want to delete this opening balanace?")) {
                $.ajax({
                    url: "opening_balance/process.php",
                    method: "POST",
                    data: {
                        editId: editId,
                        action: action
                    },
                    success: function(data) {
                        onRecords.ajax.reload();
                        $.notify("Status Deleted Successfully", "danger");
                    }
                })
            } else {
                return false;
            }
        });
    });
</script>
<?php ob_end_flush();?>