<?php ob_start(); ?>
<?php $page = 'roles'; ?>
<?php $title = "Add User Role"; ?>
<?php include "includes/main_sidebar.php"; ?>
<?php
if($isAdmin != 1){
    if($roles_access == FALSE){
        header('Location: index.php');
    }
}?>

<!-- Main Sidebar Container -->
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">User Roles</h3>
                            <div class="card-tools">
                                <button type="button" name="add" id="addRole" class="btn btn-dark btn-sm pull-right">New User Role</button>
                            </div>
                        </div>
                        <div class="card-body" id="view_roles">
                            <div class="table-responsive">
                                <table id="userRole" class="table table-bordered table-striped">
                                    <thead class="bg-info">
                                        <tr>
                                            <th>Sr#</th>
                                            <th>Role Name</th>
                                            <th>Status</th>
                                            <?php if($isAdmin == 1){?>
                                            <th>Permissions</th>
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
                <form method="post" id="userForm">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Role Name</label>
                                <input type="text" name="role_name" id="role_name" class="form-control" placeholder="Enter name" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">InActive</option>
                                </select>
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
        var roleRecords = $('#userRole').DataTable({
            "responsive": true,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "bProcessing": true,
            "order": [],
            "ajax": {
                url: "user_roles/process.php",
                type: "POST",
                data: {
                    action: 'listRole'
                },
                dataType: "json"
            },
            "columnDefs": [{
                "targets": [-1], //last column
                "orderable": false, //set not orderable
            }, ],
            "pageLength": 10
        });
        $('#addRole').click(function() {
            $('#myModal').modal('show');
            $('#userForm')[0].reset();
            $('.modal-title').html("<i class='fa fa-plus'></i> Add User Role");
            $('#action').val('addRole');
            $('#save').val('Add');
        });
        $("#userRole").on('click', '.update', function() {
            var editId = $(this).attr("id");
            var action = 'getRole';
            $.ajax({
                url: 'user_roles/process.php',
                method: "POST",
                data: {
                    editId: editId,
                    action: action
                },
                dataType: "json",
                success: function(data) {
                    $('#myModal').modal('show');
                    $('#editId').val(data.role_id);
                    $('#role_name').val(data.role_name);
                    $('#status').val(data.status);
                    $('.modal-title').html("<i class='fa fa-plus'></i> Edit User Role");
                    $('#action').val('updateRole');
                    $('#save').val('Save');
                }
            })
        });
        $("#myModal").on('submit', '#userForm', function(event) {
            event.preventDefault();
            $('#save').attr('disabled', 'disabled');
            var formData = $(this).serialize();
            $.ajax({
                url: "user_roles/process.php",
                method: "POST",
                data: formData,
                success: function(data) {
                    $('#userForm')[0].reset();
                    $('#myModal').modal('hide');
                    $('#save').attr('disabled', false);
                    roleRecords.ajax.reload();
                    $.notify("User Role Added Successfully", "success");
                }
            })
        });
        $("#userRole").on('click', '.delete', function() {
            var editId = $(this).attr("id");
            var action = "deleteRole";
            if (confirm("Are you sure you want to delete this user role?")) {
                $.ajax({
                    url: "user_roles/process.php",
                    method: "POST",
                    data: {
                        editId: editId,
                        action: action
                    },
                    success: function(data) {
                        roleRecords.ajax.reload();
                        $.notify("User Role Deleted Successfully", "danger");
                    }
                })
            } else {
                return false;
            }
        });
    });
</script>
<?php ob_end_flush();?>