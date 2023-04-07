<?php ob_start(); ?>
<?php $page = 'donation'; ?>
<?php $title = "Donations"; ?>
<?php include "includes/main_sidebar.php"; ?>
<?php
if ($isAdmin != 1) {
    if ($donation_access == FALSE) {
        header('Location: index.php');
    }
}
?>
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card" id="show_donation">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-plus"></i> &nbsp; Donations</h3>
                            <div class="card-tools">
                                <button type="button" name="add" id="addDonation" class="btn btn-dark btn-sm pull-right">New Donation</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <div id="buttons" class="text-center m-0"></div>
                                <table id="donationList" class="table table-bordered table-striped">
                                    <thead class="bg-info">
                                        <tr>
                                            <th>Sr#</th>
                                            <th>Donator Name</th>
                                            <th>Phone Number</th>
                                            <th>Account Type</th>
                                            <th>Total</th>
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
                    <div class="card" id="view_donation" style="display: none;">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-plus"></i> &nbsp; Add New Donation</h3>
                            <div class="card-tools">
                                <button type="button" name="view" id="viewDonation" class="btn btn-dark btn-sm pull-right">View Donation</button>
                            </div>
                        </div>
                        <?php
                        $receipt_no = "SELECT receipt_no FROM donations WHERE donation_id = (SELECT MAX(donation_id) FROM donations)";
                        $run_code = mysqli_query($conn, $receipt_no);
                        if (mysqli_num_rows($run_code) > 0) {
                            $row_code = mysqli_fetch_assoc($run_code);
                            $t_code = $row_code['receipt_no'];
                            $s = explode("-", $t_code);
                            unset($s[0]);
                            $s = implode(" ", $s);
                            $result = $s + 1;
                            $code = "RCN-" . $result;
                        } else {
                            $code = "RCN-1";
                        }
                        ?>
                        <form method="post" id="donationForm">
                            <input type="hidden" name="user_id" id="user_id" value="<?= $user_id; ?>">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Receipt No</label>
                                            <input type="text" name="receipt_no" id="receipt_no" class="form-control" readonly value="<?php echo $code; ?>">
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
                                            <label for="">Donator Name</label>
                                            <input type="text" name="donator_name" id="donator_name" class="form-control" placeholder="Enter Donator Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="">Phone Number</label>
                                            <input type="tel" name="phone_number" id="phone_number" class="form-control" placeholder="Enter Phone Number" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="">Total Amount (In numbers)</label>
                                            <input type="number" name="total_amount_num" id="total_amount_num" class="form-control" placeholder="Enter Amount In Numbers" required>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="">Total Amount (In words)</label>
                                            <input type="text" name="total_amount_words" id="total_amount_words" class="form-control" placeholder="Enter Amount In Words" required>
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
                                    <div class="col-md-3">
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
                                    <div class="col-md-1">
                                        <label for="">Tax %</label>
                                        <div class="form-group">
                                            <input type="number" class="form-control" name="Tax" id="Tax" value="0" readonly />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">Donation Purpose</label>
                                        <div class="form-group">
                                            <select class="form-control" name="income_source_id" id="income_source_id" required>
                                                <option value="" selected>Choose Donation Type</option>
                                                <?php
                                                $fetch_income_sources = "select * from income_source";
                                                $run_income_sources = mysqli_query($conn, $fetch_income_sources);
                                                if ($run_income_sources) {
                                                    while ($row_income_sources = mysqli_fetch_array($run_income_sources)) {
                                                        $income_source_id = $row_income_sources['income_source_id'];
                                                        $income_source_title = $row_income_sources['income_source_title'];
                                                        echo "<option value='$income_source_id'>$income_source_title</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row" style="display:none" id="show_cheque">
                                            <div class="col-md-2"></div>
                                            <label for="date" class="control-label col-md-1">Cheque No: </label>
                                            <div class="col-md-7">
                                                <input type="text" name="cheque_no" id="cheque_no" class="form-control" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Address</label>
                                            <textarea name="address" id="address" cols="30" rows="3" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Details</label>
                                            <textarea name="description" id="description" cols="30" rows="3" class="form-control"></textarea>
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
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <div class="col-md-3"></div>
                                            <label class="control-label col-md-3" style="padding-top: 7px;">Total: </label>
                                            <div class="col-md-6">
                                                <input type="text" name="total" id="total" class="form-control" readonly>
                                            </div>
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
        var donationRecords = $('#donationList').DataTable({
            "responsive": true,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "bProcessing": true,
            "order": [],
            "ajax": {
                url: "donation/process.php",
                type: "POST",
                data: {
                    action: 'listDonation'
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
        var buttons = new $.fn.dataTable.Buttons(donationRecords, {
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

        $('#addDonation').click(function() {
            $("#view_donation").show('fast');
            $("#show_donation").hide('fast');
            $('#donationForm')[0].reset();
            $('#action').val('addDonation');
        });
        $("#donationList").on('click', '.update', function() {
            var editId = $(this).attr("id");
            var action = 'getDonation';
            $.ajax({
                url: 'donation/process.php',
                method: "POST",
                data: {
                    editId: editId,
                    action: action
                },
                dataType: "json",
                success: function(data) {
                    $('#view_donation').show('fast');
                    $('#show_donation').hide('fast');
                    $('#editId').val(data.donation_id);
                    $('#receipt_no').val(data.receipt_no);
                    $('#donator_name').val(data.donator_name);
                    $('#phone_number').val(data.phone_number);
                    $('#total_amount_num').val(data.total_amount_num);
                    $('#total_amount_words').val(data.total_amount_words);
                    $('#amount_type').val(data.amount_type);
                    $('#bank_acc_id').val(data.bank_acc_id);
                    $('#income_source_id').val(data.income_source_id);
                    $('#total').val(data.total);
                    $('#date').val(data.date);
                    $('#address').val(data.address);
                    $('#description').val(data.description);
                    if (data.amount_type == "Cash") {
                        $("#show_cheque").hide();
                        $("#cheque_no").prop('required', false);
                    } else {
                        $("#show_cheque").show('fast');
                        $("#cheque_no").prop('required', true);
                        $('#cheque_no').val(data.cheque_no);
                    }
                    $('#action').val('updateDonation');
                    $('#save').val('Save');
                }
            })
        });
        $("#view_donation").on('submit', '#donationForm', function(event) {
            event.preventDefault();
            $('#save').attr('disabled', 'disabled');
            var formData = $(this).serialize();
            $.ajax({
                url: "donation/process.php",
                method: "POST",
                data: formData,
                success: function(data) {
                    $('#donationForm')[0].reset();
                    $('#view_donation').hide('fast');
                    $('#show_donation').show('fast');
                    $('#save').attr('disabled', false);
                    window.location.href = 'add-donation';
                }
            })
        });
        $("#donationList").on('click', '.delete', function() {
            var editId = $(this).attr("id");
            var action = "deleteDonation";
            if (confirm("Are you sure you want to delete this donation?")) {
                $.ajax({
                    url: "donation/process.php",
                    method: "POST",
                    data: {
                        editId: editId,
                        action: action
                    },
                    success: function(data) {
                        window.location.href = 'add-donation';
                    }
                })
            } else {
                return false;
            }
        });
    });
</script>
<script src="num-to-words.js" type="text/javascript"></script>
<script>
    //Enter Only Numbers
    $(".numbers").keypress(function(e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //display error message
            return false;
        }
    });

    var words = "";
    $(function() {
        $("#total_amount_num").on("keydown keyup", per);

        function per() {
            var totalamount = (
                Number($("#total_amount_num").val())
            );
            words = toWords(totalamount);
            $("#total_amount_words").val(words);
        }
    });
</script>
<script>
    $(document).ready(function() {
        $("#viewDonation").on('click', function() {
            $("#view_donation").hide('fast');
            $("#show_donation").show('fast');
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
    $('#total_amount_num').keyup(function() {
        var amount = $("#total_amount_num").val();
        $("#total").val(amount);
    });
    $('#total_amount_words').keydown(function(e) {
        if (e.shiftKey || e.ctrlKey || e.altKey) {
            e.preventDefault();
        } else {
            var key = e.keyCode;
            if (!((key == 8) || (key == 32) || (key == 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90))) {
                e.preventDefault();
            }
        }
    });
</script>
<script>
    $("#phone_number").on('change', function() {
        var phone_number = $(this).val();
        $.ajax({
            url: 'donation/get_details.php',
            method: "POST",
            data: {
                phone_number: phone_number
            },
            dataType: "json",
            success: function(data) {
                if (data != null) {
                    $('#donator_name').val(data.donator_name);
                    $('#address').val(data.address);
                }
            }
        });
    });

    function minusPercent(n, p) {
        return n - (n * (p / 100));
    }

    $("#bank_acc_id").on('change', function() {
        if ($(this).val() == 24) {
            $("#Tax").val(1);
        } else {
            $("#Tax").val(0);
        }
        let deduction = 0;
        var amount = $("#total_amount_num").val();
        var tax = $("#Tax").val();
        if (tax == 1) {
            deduction = minusPercent(amount, tax);
        } else {
            deduction = amount;
        }
        $("#total").val(deduction);
    });
</script>
<?php ob_end_flush(); ?>