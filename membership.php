<?php ob_start(); ?>
<?php $page = 'membership'; ?>
<?php $title = "Membership"; ?>
<?php include "includes/main_sidebar.php"; ?>
<?php
if ($isAdmin != 1) {
    if ($membership_system_access == FALSE) {
        header('Location: index.php');
    }
}
$months = array(
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July ',
    'August',
    'September',
    'October',
    'November',
    'December',
);
?>
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card" id="show_membership">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fa fa-plus"></i> &nbsp; Membership</h3>
                                <div class="card-tools">
                                    <button type="button" name="add" id="addMembership" class="btn btn-dark btn-sm pull-right">New Membership</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <div id="buttons" class="text-center m-0"></div>
                                    <table id="membershipList" class="table table-bordered table-striped">
                                        <thead class="bg-info">
                                        <tr>
                                            <th>Sr#</th>
                                            <th>Name</th>
                                            <th>Father Name</th>
                                            <th>Fee</th>
                                            <th>Fee Type</th>
                                            <th>Account Type</th>
                                            <th>Amount Type</th>
                                            <th>Fee Month</th>
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
                        <div class="card" id="view_membership" style="display: none;">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fa fa-plus"></i> &nbsp; Add New Membership</h3>
                                <div class="card-tools">
                                    <button type="button" name="view" id="viewMembership" class="btn btn-dark btn-sm pull-right">View Membership</button>
                                </div>
                            </div>
                            <?php
                            $invoice_no = $conn->query("SELECT invoice_no FROM membership WHERE membership_id = (SELECT MAX(membership_id) FROM membership)");
                            if (mysqli_num_rows($invoice_no) > 0) {
                                $row_code = mysqli_fetch_assoc($invoice_no);
                                $t_code = $row_code['invoice_no'];
                                $s = explode("-", $t_code);
                                unset($s[0]);
                                $s = implode(" ", $s);
                                $result = $s + 1;
                                $code = "INC-" . $result;
                            } else {
                                $code = "INC-1";
                            }
                            ?>
                            <form method="post" id="membershipForm">
                                <input type="hidden" name="user_id" id="user_id" value="<?= $user_id; ?>">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Invoice No</label>
                                                <input type="text" name="invoice_no" id="invoice_no" class="form-control" readonly value="<?php echo $code; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Date</label>
                                                <input type="date" name="date" id="date" class="form-control datetimepicker-input" value="<?php echo date('Y-m-d'); ?>" required <?php if ($isAdmin != 1) {
                                                    echo "readonly";
                                                } ?> />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <label for="">Name</label>
                                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="">Father Name</label>
                                                <input type="text" name="father_name" id="father_name" class="form-control" placeholder="Enter Father Name" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Phone No</label>
                                                <input type="number" name="phone_no" id="phone_no" class="form-control" placeholder="Enter Phone No" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Fee</label>
                                                <input type="number" name="fee" id="fee" class="form-control" placeholder="Enter Fee" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Fee Type</label>
                                                <select class="form-control" name="fee_type" id="fee_type" required>
                                                    <option value="" selected>Choose Fee Type</option>
                                                    <option value="Registration">Registration</option>
                                                    <option value="Monthly">Monthly</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Amount Type</label>

                                                <select class="form-control" name="amount_type" id="amount_type" required>
                                                    <option value="" selected>Choose Amount Type</option>
                                                    <option value="Cash">Cash</option>
                                                    <option value="Cheque">Cheque</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Account Type</label>

                                                <select class="form-control" name="bank_acc_id" id="bank_acc_id" required>
                                                    <option value="" selected>Choose Bank Account</option>
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
                                            <label for="">Fee Month</label>
                                            <div class="form-group">
                                                <select class="form-control" name="fee_month" id="fee_month" required>
                                                    <option value="" selected>Choose Fee Month</option>
                                                    <?php foreach($months as $month){ ?>
                                                        <option value="<?= $month ?>"><?= $month ?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group row" style="display:none" id="show_cheque">
                                                <div class="col-md-1"></div>
                                                <label for="date" class="control-label col-md-2">Cheque No: </label>
                                                <div class="col-md-6">
                                                    <input type="text" name="cheque_no" id="cheque_no" class="form-control" value="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Designation</label>
                                                <input name="designation" id="designation" class="form-control" placeholder="Enter Designation">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">City</label>
                                                <input type="text" name="city" id="city" class="form-control" placeholder="Enter City">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Level</label>
                                                <input type="text" name="level" id="level" class="form-control" placeholder="Enter Level">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="hidden" name="editId" id="editId" value="" />
                                                <input type="hidden" name="action" id="action" value="" />
                                                <button type="submit" name="save" id="save" class="btn bg-info">Save</button>
                                            </div>
                                        </div>
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4"></div>
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
    <link rel="stylesheet" href="sweetalert.min.css" />
    <script src="sweetalert.min.js"></script>
    <script>
        $(document).ready(function() {
            var membershipRecords = $('#membershipList').DataTable({
                "responsive": true,
                "autoWidth": false,
                "processing": true,
                "serverSide": true,
                "bProcessing": true,
                "order": [],
                "ajax": {
                    url: "membership/process.php",
                    type: "POST",
                    data: {
                        action: 'listMembership'
                    },
                    dataType: "json"
                },
                "pageLength": 25,
                "lengthChange": true,
                "lengthMenu": [
                    [25, 100, 1000, -1],
                    [25, 100, 1000, "All"]
                ],
            });
            var buttons = new $.fn.dataTable.Buttons(membershipRecords, {
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Excel',
                    text: 'Export as excel',
                    className: 'bg-info btn-xs border-0 m-1',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },
                    {
                        extend: 'pdfHtml5',
                        title: 'PDF',
                        text: 'Export as PDF',
                        className: 'bg-info btn-xs border-0 m-1',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        title: 'CSV',
                        text: 'Export as CSV',
                        className: 'bg-info btn-xs border-0 m-1',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    },
                ]
            }).container().appendTo($('#buttons'));

            $('#addMembership').click(function() {
                $("#view_membership").show('fast');
                $("#show_membership").hide('fast');
                $('#membershipForm')[0].reset();
                $('#action').val('addMembership');
            });
            $("#membershipList").on('click', '.update', function() {
                var editId = $(this).attr("id");
                var action = 'getMembership';
                $.ajax({
                    url: 'membership/process.php',
                    method: "POST",
                    data: {
                        editId: editId,
                        action: action
                    },
                    dataType: "json",
                    success: function(data) {
                        $('#view_membership').show('fast');
                        $('#show_membership').hide('fast');
                        $('#editId').val(data.membership_id );
                        $('#receipt_no').val(data.receipt_no);
                        $('#name').val(data.name);
                        $('#father_name').val(data.father_name);
                        $('#fee').val(data.fee);
                        $('#fee_type').val(data.fee_type);
                        $('#amount_type').val(data.amount_type);
                        $('#bank_acc_id').val(data.bank_acc_id);
                        $('#fee_month').val(data.fee_month);
                        $('#designation').val(data.designation);
                        $('#city').val(data.city);
                        $('#level').val(data.level);
                        $('#phone_no').val(data.phone_no);
                        if (data.amount_type == "Cash") {
                            $("#show_cheque").hide();
                            $("#cheque_no").prop('required', false);
                        } else {
                            $("#show_cheque").show('fast');
                            $("#cheque_no").prop('required', true);
                            $('#cheque_no').val(data.cheque_no);
                        }
                        $('#action').val('updateMembership');
                        $('#save').val('Save');
                    }
                })
            });
            $("#view_membership").on('submit', '#membershipForm', function(event) {
                event.preventDefault();
                $('#save').attr('disabled', 'disabled');
                var formData = $(this).serialize();
                $.ajax({
                    url: "membership/process.php",
                    method: "POST",
                    data: formData,
                    success: function(data) {
                        $('#membershipForm')[0].reset();
                        $('#view_membership').hide('fast');
                        $('#show_membership').show('fast');
                        $('#save').attr('disabled', false);
                        window.location = "add-membership";
                    }
                })
            });
            $("#membershipList").on('click', '.delete', function() {
                var editId = $(this).attr("id");
                var action = "deleteMembership";
                if (confirm("Are you sure you want to delete this membership?")) {
                    $.ajax({
                        url: "membership/process.php",
                        method: "POST",
                        data: {
                            editId: editId,
                            action: action
                        },
                        success: function(data) {
                            setTimeout(function() {
                                swal({
                                    title: "Success!",
                                    text: "Data Deleted Successfully!",
                                    type: "success"
                                }, function() {
                                    window.location = "add-membership";
                                });
                            }, 1000);
                        }
                    })
                } else {
                    return false;
                }
            });
        });
        $(document).ready(function() {
            $("#viewMembership").on('click', function() {
                $("#view_membership").hide('fast');
                $("#show_membership").show('fast');
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

        $("#phone_no").on('change', function() {
            var phone_no = $(this).val();
            $.ajax({
                url: 'membership/get_details.php',
                method: "POST",
                data: {
                    phone_no: phone_no
                },
                dataType: "json",
                success: function(data) {
                    if (data != null) {
                        $('#receipt_no').val(data.receipt_no);
                        $('#name').val(data.name);
                        $('#father_name').val(data.father_name);
                        $('#fee').val(data.fee);
                        $('#fee_type').val(data.fee_type);
                        $('#amount_type').val(data.amount_type);
                        $('#bank_acc_id').val(data.bank_acc_id);
                        $('#fee_month').val(data.fee_month);
                        $('#designation').val(data.designation);
                        $('#city').val(data.city);
                        $('#level').val(data.level);
                        $('#phone_no').val(data.phone_no);
                    }
                }
            });
        });
    </script>
<?php ob_end_flush(); ?>