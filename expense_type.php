<?php ob_start(); ?>
<?php $page = 'expense_type'; ?>
<?php $title = "Expense"; ?>
<?php include "includes/main_sidebar.php"; ?>
<?php
if($isAdmin != 1){
    if($expense_type_access == false){
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
                            <h3 class="card-title">Expense Types</h3>
                            <div class="card-tools">
                                <button type="button" name="add" id="addExpense"
                                    class="btn btn-dark btn-sm pull-right">New Expense</button>
                            </div>
                        </div>
                        <div class="card-body" id="view_expense">
                            <div class="table-responsive">
                                <table id="expenseList" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr#</th>
                                            <th>Expense Title</th>
                                            <?php if($isAdmin == 1){?>
                                            <th><i class="fa fa-cogs"></i></th>
                                            <?php }?>
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
                <form method="post" id="expenseForm">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="expense_title">Expense Type</label>
                                <input type="text" name="expense_title" id="expense_title" class="form-control"
                                    placeholder="Enter Expense Type" required>
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
    var expenseRecords = $('#expenseList').DataTable({
        "responsive": true,
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "bProcessing": true,
        "order": [],
        "ajax": {
            url: "expense/process.php",
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
        "pageLength": 10
    });
    $('#addExpense').click(function() {
        $('#myModal').modal('show');
        $('#expenseForm')[0].reset();
        $('.modal-title').html("<i class='fa fa-plus'></i> Add Expense");
        $('#action').val('addExpense');
        $('#save').val('Add');
    });
    $("#expenseList").on('click', '.update', function() {
        var editId = $(this).attr("id");
        var action = 'getExpense';
        $.ajax({
            url: 'expense/process.php',
            method: "POST",
            data: {
                editId: editId,
                action: action
            },
            dataType: "json",
            success: function(data) {
                $('#myModal').modal('show');
                $('#editId').val(data.expense_id);
                $('#expense_title').val(data.expense_title);
                $('.modal-title').html("<i class='fa fa-plus'></i> Edit Expense");
                $('#action').val('updateExpense');
                $('#save').val('Save');
            }
        })
    });
    $("#myModal").on('submit', '#expenseForm', function(event) {
        event.preventDefault();
        $('#save').attr('disabled', 'disabled');
        var formData = $(this).serialize();
        $.ajax({
            url: "expense/process.php",
            method: "POST",
            data: formData,
            success: function(data) {
                $('#expenseForm')[0].reset();
                $('#myModal').modal('hide');
                $('#save').attr('disabled', false);
                expenseRecords.ajax.reload();
                $.notify("Expense Type Added Successfully", "success");
            }
        })
    });
    $("#expenseList").on('click', '.delete', function() {
        var editId = $(this).attr("id");
        var action = "deleteExpense";
        if (confirm("Are you sure you want to delete this expense?")) {
            $.ajax({
                url: "expense/process.php",
                method: "POST",
                data: {
                    editId: editId,
                    action: action
                },
                success: function(data) {
                    expenseRecords.ajax.reload();
                    $.notify("Expense Type Deleted Successfully", "danger");
                }
            })
        } else {
            return false;
        }
    });
});
</script>
<?php ob_end_flush();?>