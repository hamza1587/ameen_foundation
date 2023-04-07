<?php ob_start(); ?>
<?php $page = 'cities'; ?>
<?php $title = "Add New City"; ?>
<?php include "includes/main_sidebar.php"; ?>
<?php
if ($isAdmin != 1) {
    header('Location: index.php');
} ?>

<!-- Main Sidebar Container -->
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Cities</h3>
                            <div class="card-tools">
                                <button type="button" name="add" id="addCity" class="btn btn-dark btn-sm pull-right">New City</button>
                            </div>
                        </div>
                        <div class="card-body" id="view_roles">
                            <div class="table-responsive">
                                <table id="cityList" class="table table-bordered table-striped">
                                    <thead class="bg-info">
                                        <tr>
                                            <th>Sr#</th>
                                            <th>City Name</th>
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
                <form method="post" id="cityForm">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>State</label>
                                <select name="state_id" id="state_id" class="form-control" required>
                                    <option value="">Select State</option>
                                    <?php
                                    $state_sql = $conn->query("SELECT * FROM `states`");
                                    while ($state_rows = mysqli_fetch_assoc($state_sql)) {
                                        $state_id = $state_rows['state_id'];
                                        $state_name = $state_rows['state_name'];
                                        echo "<option value='$state_id'>$state_name</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>City Name</label>
                                <input type="text" name="city_name" id="city_name" class="form-control" placeholder="Enter City Name" required>
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
        var cityRecords = $('#cityList').DataTable({
            "responsive": true,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "bProcessing": true,
            "order": [],
            "ajax": {
                url: "cities/process.php",
                type: "POST",
                data: {
                    action: 'listCity'
                },
                dataType: "json"
            },
            "columnDefs": [{
                "targets": [-1], //last column
                "orderable": false, //set not orderable
            }, ],
            "pageLength": 10
        });
        $('#addCity').click(function() {
            $('#myModal').modal('show');
            $('#cityForm')[0].reset();
            $('.modal-title').html("<i class='fa fa-plus'></i> Add New City");
            $('#action').val('addCity');
            $('#save').val('Add');
        });
        $("#CityList").on('click', '.update', function() {
            var editId = $(this).attr("id");
            var action = 'getCity';
            $.ajax({
                url: 'cities/process.php',
                method: "POST",
                data: {
                    editId: editId,
                    action: action
                },
                dataType: "json",
                success: function(data) {
                    $('#myModal').modal('show');
                    $('#editId').val(data.City_id);
                    $('#City_name').val(data.City_name);
                    $('.modal-title').html("<i class='fa fa-plus'></i> Edit City");
                    $('#action').val('updateCity');
                    $('#save').val('Save');
                }
            })
        });
        $("#myModal").on('submit', '#cityForm', function(event) {
            event.preventDefault();
            $('#save').attr('disabled', 'disabled');
            var formData = $(this).serialize();
            $.ajax({
                url: "cities/process.php",
                method: "POST",
                data: formData,
                success: function(data) {
                    $('#cityForm')[0].reset();
                    $('#myModal').modal('hide');
                    $('#save').attr('disabled', false);
                    cityRecords.ajax.reload();
                    $.notify("City Added Successfully", "success");
                }
            })
        });
        $("#CityList").on('click', '.delete', function() {
            var editId = $(this).attr("id");
            var action = "deleteCity";
            if (confirm("Are you sure you want to delete this city?")) {
                $.ajax({
                    url: "cities/process.php",
                    method: "POST",
                    data: {
                        editId: editId,
                        action: action
                    },
                    success: function(data) {
                        cityRecords.ajax.reload();
                        $.notify("City Deleted Successfully", "danger");
                    }
                })
            } else {
                return false;
            }
        });
    });
</script>
<?php ob_end_flush();?>