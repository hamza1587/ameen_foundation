<?php ob_start(); ?>
<?php $page = 'users'; ?>
<?php $title = "New User"; ?>
<?php include "includes/main_sidebar.php"; ?>
<?php
if($isAdmin != 1){
    if($users_access == FALSE){
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
                            <h3 class="card-title">Users</h3>
                            <div class="card-tools">
                                <button type="button" name="add" id="addUser" class="btn btn-dark btn-sm pull-right">New User</button>
                            </div>
                        </div>
                        <div class="card-body" id="view_users">
                            <div class="table-responsive">
                                <table id="userList" class="table table-bordered table-striped">
                                    <thead class="bg-info">
                                        <tr>
                                            <th>Sr#</th>
                                            <th>Name</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Role</th>
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
                <form method="post" id="userForm">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter name" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="user_name" id="user_name" class="form-control" placeholder="Enter Username" required>
                                <span id="error"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email" required>
                                <span id="email_error"></span>
                            </div>
                        </div>
                        <div class="col-md-12" id="password">
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="user_password" id="user_password" class="form-control" placeholder="Enter Password" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Select Role</label>
                                <select name="role_id" id="role_id" class="form-control" required>
                                    <?php
                                    $fetch_roles = "select * from roles where status != 2";
                                    $run_roles = mysqli_query($conn, $fetch_roles);
                                    if ($run_roles) {
                                        while ($row_roles = mysqli_fetch_array($run_roles)) {
                                            $role_id = $row_roles['role_id'];
                                            $role_name = $row_roles['role_name'];
                                            echo "<option value='$role_id'>$role_name</option>";
                                        }
                                    }
                                    ?>
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
        var userRecords = $('#userList').DataTable({
            "responsive": true,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "bProcessing": true,
            "order": [],
            "ajax": {
                url: "user/process.php",
                type: "POST",
                data: {
                    action: 'listUser'
                },
                dataType: "json"
            },
            "columnDefs": [{
                "targets": [-1], //last column
                "orderable": false, //set not orderable
            }, ],
            "pageLength": 10
        });
        $('#addUser').click(function() {
            $('#myModal').modal('show');
            $('#userForm')[0].reset();
            $('.modal-title').html("<i class='fa fa-plus'></i> Add User");
            $('#action').val('addUser');
            $('#save').val('Add');
        });
        $("#userList").on('click', '.update', function() {
            var editId = $(this).attr("id");
            var action = 'getUser';
            $.ajax({
                url: 'user/process.php',
                method: "POST",
                data: {
                    editId: editId,
                    action: action
                },
                dataType: "json",
                success: function(data) {
                    $('#myModal').modal('show');
                    $('#editId').val(data.user_id);
                    $('#name').val(data.name);
                    $('#user_name').val(data.user_name);
                    $('#email').val(data.email);
                    $('#role_id').val(data.role_id);
                    document.getElementById('password').style.display = 'none';
                    $('.modal-title').html("<i class='fa fa-plus'></i> Edit User");
                    $('#action').val('updateUser');
                    $('#save').val('Save');
                }
            })
        });
        $("#myModal").on('submit', '#userForm', function(event) {
            event.preventDefault();
            $('#save').attr('disabled', 'disabled');
            var formData = $(this).serialize();
            $.ajax({
                url: "user/process.php",
                method: "POST",
                data: formData,
                success: function(data) {
                    $('#userForm')[0].reset();
                    $('#myModal').modal('hide');
                    $('#save').attr('disabled', false);
                    userRecords.ajax.reload();
                    $.notify("User Added Successfully", "success");
                }
            })
        });
        $("#userList").on('click', '.delete', function() {
            var editId = $(this).attr("id");
            var action = "deleteUser";
            if (confirm("Are you sure you want to delete this user?")) {
                $.ajax({
                    url: "user/process.php",
                    method: "POST",
                    data: {
                        editId: editId,
                        action: action
                    },
                    success: function(data) {
                        userRecords.ajax.reload();
                        $.notify("User Deleted Successfully", "danger");
                    }
                })
            } else {
                return false;
            }
        });
    });
</script>

<style>
    .focused {
        border-color: red !important;
    }

    .text {
        color: red !important;
    }
</style>
<?php ob_end_flush();?>