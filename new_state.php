<?php ob_start(); ?>
<?php $page = 'states'; ?>
<?php $title = "Add New State"; ?>
<?php include "includes/main_sidebar.php"; ?>
<?php
if($isAdmin != 1){
    header('Location: index.php');
}?>

<!-- Main Sidebar Container -->
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">States</h3>
                            <div class="card-tools">
                                <button type="button" name="add" id="addState" class="btn btn-dark btn-sm pull-right">New State</button>
                            </div>
                        </div>
                        <div class="card-body" id="view_roles">
                            <div class="table-responsive">
                                <table id="stateList" class="table table-bordered table-striped">
                                    <thead class="bg-info">
                                        <tr>
                                            <th>Sr#</th>
                                            <th>State Name</th>
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
                <form method="post" id="stateForm">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>State Name</label>
                                <input type="text" name="state_name" id="state_name" class="form-control" placeholder="Enter State Name" required>
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
<script>
    $(document).ready(function() {
        var stateRecords = $('#stateList').DataTable({
            "responsive": true,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "bProcessing": true,
            "order": [],
            "ajax": {
                url: "states/process.php",
                type: "POST",
                data: {
                    action: 'listState'
                },
                dataType: "json"
            },
            "columnDefs": [{
                "targets": [-1], //last column
                "orderable": false, //set not orderable
            }, ],
            "pageLength": 10
        });
        $('#addState').click(function() {
            $('#myModal').modal('show');
            $('#stateForm')[0].reset();
            $('.modal-title').html("<i class='fa fa-plus'></i> Add New State");
            $('#action').val('addState');
            $('#save').val('Add');
        });
        $("#stateList").on('click', '.update', function() {
            var editId = $(this).attr("id");
            var action = 'getState';
            $.ajax({
                url: 'states/process.php',
                method: "POST",
                data: {
                    editId: editId,
                    action: action
                },
                dataType: "json",
                success: function(data) {
                    $('#myModal').modal('show');
                    $('#editId').val(data.state_id);
                    $('#state_name').val(data.state_name);
                    $('.modal-title').html("<i class='fa fa-plus'></i> Edit State");
                    $('#action').val('updateState');
                    $('#save').val('Save');
                }
            })
        });
        $("#myModal").on('submit', '#stateForm', function(event) {
            event.preventDefault();
            $('#save').attr('disabled', 'disabled');
            var formData = $(this).serialize();
            $.ajax({
                url: "states/process.php",
                method: "POST",
                data: formData,
                success: function(data) {
                    $('#stateForm')[0].reset();
                    $('#myModal').modal('hide');
                    $('#save').attr('disabled', false);
                    stateRecords.ajax.reload();
                    $.notify("State Added Successfully", "success");
                }
            })
        });
        $("#stateList").on('click', '.delete', function() {
            var editId = $(this).attr("id");
            var action = "deleteState";
            if (confirm("Are you sure you want to delete this state?")) {
                $.ajax({
                    url: "states/process.php",
                    method: "POST",
                    data: {
                        editId: editId,
                        action: action
                    },
                    success: function(data) {
                        stateRecords.ajax.reload();
                        $.notify("State Deleted Successfully", "danger");
                    }
                })
            } else {
                return false;
            }
        });
    });
</script>
<?php ob_end_flush();?>