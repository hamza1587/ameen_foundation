<?php ob_start(); ?>
<?php $page = 'bank_account'; ?>
<?php $title = "Bank Accounts"; ?>
<?php include "includes/main_sidebar.php"; ?>
<?php
if($isAdmin != 1){
    if($cash_accounts_access == false){
        header('Location: index.php');
    } 
}?>
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Cash Accounts</h3>
                            <div class="card-tools">
                                <button type="button" name="add" id="addBank" class="btn btn-dark btn-sm pull-right">New
                                    Cash Account</button>
                            </div>
                        </div>
                        <div class="card-body" id="view_banks">
                            <div class="table-responsive">
                                <table id="bankList" class="table table-bordered table-striped">
                                    <thead class="bg-info">
                                        <tr>
                                            <th>Sr#</th>
                                            <th>Bank Name</th>
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
                <form method="post" id="bankForm">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Bank Name</label>
                                <input type="text" name="bank_acc_name" id="bank_acc_name" class="form-control"
                                    placeholder="Enter Bank Name" required>
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
    var bankRecords = $('#bankList').DataTable({
        "responsive": true,
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "bProcessing": true,
        "order": [],
        "ajax": {
            url: "bank/process.php",
            type: "POST",
            data: {
                action: 'listBank'
            },
            dataType: "json"
        },
        "columnDefs": [{
            "targets": [-1], //last column
            "orderable": false, //set not orderable
        }, ],
        "pageLength": 10
    });
    $('#addBank').click(function() {
        $('#myModal').modal('show');
        $('#bankForm')[0].reset();
        $('.modal-title').html("<i class='fa fa-plus'></i> Add New Cash Account");
        $('#action').val('addBank');
        $('#save').val('Add');
    });
    $("#bankList").on('click', '.update', function() {
        var editId = $(this).attr("id");
        var action = 'getBank';
        $.ajax({
            url: 'bank/process.php',
            method: "POST",
            data: {
                editId: editId,
                action: action
            },
            dataType: "json",
            success: function(data) {
                $('#myModal').modal('show');
                $('#editId').val(data.bank_acc_id);
                $('#bank_acc_name').val(data.bank_acc_name);
                $('.modal-title').html("<i class='fa fa-plus'></i> Edit Cash Account");
                $('#action').val('updateBank');
                $('#save').val('Save');
            }
        })
    });
    $("#myModal").on('submit', '#bankForm', function(event) {
        event.preventDefault();
        $('#save').attr('disabled', 'disabled');
        var formData = $(this).serialize();
        $.ajax({
            url: "bank/process.php",
            method: "POST",
            data: formData,
            success: function(data) {
                $('#bankForm')[0].reset();
                $('#myModal').modal('hide');
                $('#save').attr('disabled', false);
                bankRecords.ajax.reload();
                $.notify("Bank Account Added Successfully", "success");
            }
        })
    });
    $("#bankList").on('click', '.delete', function() {
        var editId = $(this).attr("id");
        var action = "deleteBank";
        if (confirm("Are you sure you want to delete this bank?")) {
            $.ajax({
                url: "bank/process.php",
                method: "POST",
                data: {
                    editId: editId,
                    action: action
                },
                success: function(data) {
                    bankRecords.ajax.reload();
                    $.notify("Bank Account Deleted Successfully", "danger");
                }
            })
        } else {
            return false;
        }
    });
});
</script>
<?php ob_end_flush();?>