<?php ob_start(); ?>
<?php $page = 'service'; ?>
<?php $title = "Service"; ?>
<?php include "includes/main_sidebar.php"; ?>
<?php
if($isAdmin != 1){
    if($services_access == false){
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
                            <h3 class="card-title">Services</h3>
                            <div class="card-tools">
                                <button type="button" name="add" id="addService" class="btn btn-dark btn-sm pull-right">New Service</button>
                            </div>
                        </div>
                        <div class="card-body" id="view_services">
                            <div class="table-responsive">
                                <table id="serviceList" class="table table-bordered table-striped">
                                    <thead class="bg-info">
                                        <tr>
                                            <th>Sr#</th>
                                            <th>Service Name</th>
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
                <form method="post" id="serviceForm">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Service Name</label>
                                <input type="text" name="service_name" id="service_name" class="form-control" placeholder="Enter Service Name" required>
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
        var serviceRecords = $('#serviceList').DataTable({
            "responsive": true,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "bProcessing": true,
            "order": [],
            "ajax": {
                url: "service/process.php",
                type: "POST",
                data: {
                    action: 'listService'
                },
                dataType: "json"
            },
            "columnDefs": [{
                "targets": [-1], //last column
                "orderable": false, //set not orderable
            }, ],
            "pageLength": 10
        });
        $('#addService').click(function() {
            $('#myModal').modal('show');
            $('#serviceForm')[0].reset();
            $('.modal-title').html("<i class='fa fa-plus'></i> Add Service");
            $('#action').val('addService');
            $('#save').val('Add');
        });
        $("#serviceList").on('click', '.update', function() {
            var editId = $(this).attr("id");
            var action = 'getService';
            $.ajax({
                url: 'service/process.php',
                method: "POST",
                data: {
                    editId: editId,
                    action: action
                },
                dataType: "json",
                success: function(data) {
                    $('#myModal').modal('show');
                    $('#editId').val(data.service_id);
                    $('#service_name').val(data.service_name);
                    $('.modal-title').html("<i class='fa fa-plus'></i> Edit Service");
                    $('#action').val('updateService');
                    $('#save').val('Save');
                }
            })
        });
        $("#myModal").on('submit', '#serviceForm', function(event) {
            event.preventDefault();
            $('#save').attr('disabled', 'disabled');
            var formData = $(this).serialize();
            $.ajax({
                url: "service/process.php",
                method: "POST",
                data: formData,
                success: function(data) {
                    $('#serviceForm')[0].reset();
                    $('#myModal').modal('hide');
                    $('#save').attr('disabled', false);
                    serviceRecords.ajax.reload();
                    $.notify("Service Added Successfully", "success");
                }
            })
        });
        $("#serviceList").on('click', '.delete', function() {
            var editId = $(this).attr("id");
            var action = "deleteService";
            if (confirm("Are you sure you want to delete this service?")) {
                $.ajax({
                    url: "service/process.php",
                    method: "POST",
                    data: {
                        editId: editId,
                        action: action
                    },
                    success: function(data) {
                        serviceRecords.ajax.reload();
                        $.notify("Service Deleted Successfully", "danger");
                    }
                })
            } else {
                return false;
            }
        });
    });
</script>
<?php ob_end_flush();?>