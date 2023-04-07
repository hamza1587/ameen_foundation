<?php ob_start(); ?>
<?php $page = 'project_expense'; ?>
<?php $title = "Helping Categories"; ?>
<?php include "includes/main_sidebar.php"; ?>
<?php
if($isAdmin != 1){
    if($project_expense_access == FALSE){
        header('Location: index.php');
    }
}

?>
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card" id="show_expense">
                        <div class="card-header">
                            <h3 class="card-title">Helping Categories</h3>
                            <div class="card-tools">
                                <button type="button" name="add" id="addExpense" class="btn btn-dark btn-sm pull-right">New Helping Category</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="expenseList" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr#</th>
                                            <th>Person Name</th>
                                            <th>CNIC No.</th>
                                            <th>Bank Name</th>
                                            <th>Amount Type</th>
                                            <th>Amount</th>
                                            <th>User</th>
                                            <th>Role</th>
                                            <th>Invoice</th>
                                            <?php if($isAdmin == 1){ ?>
                                            <th><i class="fa fa-cogs"></i></th>
                                            <?php }?>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card" id="view_expense" style="display: none;">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-plus"></i> &nbsp; Add New Helping Category</h3>
                            <div class="card-tools">
                                <button type="button" name="view" id="viewExpense" class="btn btn-dark btn-sm pull-right">View Helping Categories</button>
                            </div>
                        </div>
                        <?php
                            $receipt_no = "SELECT receipt_no FROM project_expense WHERE project_expense_id = (SELECT MAX(project_expense_id) FROM project_expense)";
                            $run_code = mysqli_query($conn, $receipt_no);
                            if (mysqli_num_rows($run_code) > 0) {
                                $row_code = mysqli_fetch_assoc($run_code);
                                $t_code = $row_code['receipt_no'];
                                $s = explode("-",$t_code);
                                unset($s[0]);
                                $s = implode(" ",$s);
                                $result = $s + 1;
                                $code = "RCN-" . $result;
                            } else {
                                $code = "RCN-1";
                            }
                        ?>
                        <form method="post" id="expenseForm">
                            <input type="hidden" name="user_id" id="user_id" value="<?= $user_id; ?>">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Receipt No</label>
                                            <input type="text" name="receipt_no" id="receipt_no" class="form-control" value="<?php echo $code; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Date</label>
                                            <input type="date" name="date" id="date" class="form-control datetimepicker-input" value="<?php echo date('Y-m-d'); ?>" required <?php if ($isAdmin != 1) { echo "readonly"; }?>/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Person Name</label>
                                            <input type="text" name="person_name" id="person_name" class="form-control" placeholder="Enter Person Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">CNIC No.</label>
                                            <input type="number" name="person_cnic" id="person_cnic" class="form-control" placeholder="Enter CNIC No." required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Phone No</label>
                                            <input type="number" name="person_phone_no" id="person_phone_no" class="form-control" placeholder="Enter Phone No" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="">Donated Amount</label>
                                            <input type="number" name="donate_amount" id="donate_amount" class="form-control" placeholder="Enter Donate Amount" required>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="">Total Amount (In words)</label>
                                            <input type="text" name="amount_in_words" id="amount_in_words" class="form-control" placeholder="Enter Amount In Words" required>
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
                                            <label>Bank Account</label>

                                            <select class="form-control" name="bank_acc_id" id="bank_acc_id" required>
                                                <option value="" selected>Choose Cash Account</option>
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
                                    <div class="col-md-4">
                                        <label for="">Service</label>
                                        <div class="form-group">
                                            <select class="form-control" name="service_id" id="service_id" required>
                                                <option value="" selected>Choose Service</option>
                                                <?php
                                                $fetch_services = "select * from services";
                                                $run_services = mysqli_query($conn, $fetch_services);
                                                if ($run_services) {
                                                    while ($row_services = mysqli_fetch_array($run_services)) {
                                                        $service_id = $row_services['service_id'];
                                                        $service_name = $row_services['service_name'];
                                                        echo "<option value='$service_id'>$service_name</option>";
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
                                                <input type="text" name="cheque_no" id="cheque_no" class="form-control" value="" >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
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
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">State</label>
                                            <select class="form-control" name="state_id" id="state_id" required>
                                                <option value="" selected>Choose State</option>
                                                <?php
                                                $fetch_states = "select * from states";
                                                $run_states = mysqli_query($conn, $fetch_states);
                                                if ($run_states) {
                                                    $total_rows = mysqli_num_rows($run_states);
                                                    while ($row_states = mysqli_fetch_array($run_states)) {
                                                        $state_id = $row_states['state_id'];
                                                        $state_name = $row_states['state_name'];
                                                        echo "<option value='$state_id'>$state_name</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">City</label>
                                            <select class="form-control" name="city_id" id="city_id" required>
                                                <option value="" selected>Choose City</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">Address</label>
                                            <textarea name="person_address" id="person_address" cols="30" rows="4" class="form-control" required></textarea>
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
        $("#viewExpense").on('click', function() {
            $("#view_expense").hide('fast');
            $("#show_expense").show('fast');
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
    $('#donate_amount').keyup(function() {
        var amount = $("#donate_amount").val();
        $("#total").val(amount);
    });
    $(document).on('change','#state_id', function(){
        var stateID = $(this).val();
        if(stateID){
            $.ajax({
                type:'POST',
                url:'project_expense/backend-script.php',
                data:{'state_id':stateID},
                success:function(result){
                    $('#city_id').html(result);

                }
            });
        }else{
            $('#city_id').html('<option value="">Select City</option>');
        }
    });
    function get_city(stateID, cityID)
    {
        if(stateID){
            $.ajax({
                type:'POST',
                url:'project_expense/backend-script.php',
                data:{'state_id':stateID, 'city_id': cityID},
                success:function(result){
                    $('#city_id').html(result);
                }
            });
        }else{
            $('#city_id').html('<option value="">Select City</option>');
        }
    }
</script>
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
                url: "project_expense/process.php",
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
            $("#view_expense").show('fast');
            $("#show_expense").hide('fast');
            $('#expenseForm')[0].reset();
            $('#action').val('addExpense');
        });
        $("#expenseList").on('click', '.update', function() {
            var editId = $(this).attr("id");
            var action = 'getExpense';
            $.ajax({
                url: 'project_expense/process.php',
                method: "POST",
                data: {
                    editId: editId,
                    action: action
                },
                dataType: "json",
                success: function(data) {
                    $('#view_expense').show('fast');
                    $('#show_expense').hide('fast');
                    $('#editId').val(data.project_expense_id);
                    $('#receipt_no').val(data.receipt_no);
                    $('#person_name').val(data.person_name);
                    $('#person_cnic').val(data.person_cnic);
                    $('#person_phone_no').val(data.person_phone_no);
                    $('#state_id').val(data.state_id);
                    $('#city_id').val(data.city_id);
                    $('#person_address').val(data.person_address);
                    $('#service_id').val(data.service_id);
                    $('#income_source_id').val(data.income_source_id);
                    $('#amount_type').val(data.amount_type);
                    $('#bank_acc_id').val(data.bank_acc_id);
                    get_city(data.state_id, data.city_id);
                    if(data.amount_type == "Cash") {
                        $("#show_cheque").hide();
                        $("#cheque_no").prop('required', false);
                    } else {
                        $("#show_cheque").show('fast');
                        $("#cheque_no").prop('required', true);
                        $('#cheque_no').val(data.cheque_no);
                    }
                    $('#date').val(data.date);
                    $('#donate_amount').val(data.donate_amount);
                    $('#amount_in_words').val(data.amount_in_words);
                    $("#total").val(data.donate_amount);
                    $('#action').val('updateExpense');
                    $('#save').val('Save');
                }
            })
        });
        $("#person_phone_no").on('change', function() {
            var person_phone_no = $(this).val();
            $.ajax({
                url: 'project_expense/get_details.php',
                method: "POST",
                data: {
                    person_phone_no: person_phone_no
                },
                dataType: "json",
                success: function(data) {
                    if (data != null) {
                        $('#receipt_no').val(data.receipt_no);
                        $('#person_name').val(data.person_name);
                        $('#person_cnic').val(data.person_cnic);
                        $('#person_phone_no').val(data.person_phone_no);
                        $('#state_id').val(data.state_id);
                        $('#city_id').val(data.city_id);
                        $('#person_address').val(data.person_address);
                        $('#service_id').val(data.service_id);
                        $('#income_source_id').val(data.income_source_id);
                        $('#amount_type').val(data.amount_type);
                        $('#bank_acc_id').val(data.bank_acc_id);
                        get_city(data.state_id, data.city_id);
                        if (data.amount_type == "Cash") {
                            $("#show_cheque").hide();
                            $("#cheque_no").prop('required', false);
                        } else {
                            $("#show_cheque").show('fast');
                            $("#cheque_no").prop('required', true);
                            $('#cheque_no').val(data.cheque_no);
                        }
                        $('#date').val(data.date);
                        $('#donate_amount').val(data.donate_amount);
                        $('#amount_in_words').val(data.amount_in_words);
                        $("#total").val(data.donate_amount);
                    } else {
                        $('#donationForm')[0].reset();
                    }
                }
            });
        });
        $("#view_expense").on('submit', '#expenseForm', function(event) {
            event.preventDefault();
            $('#save').attr('disabled', 'disabled');
            var formData = $(this).serialize();
            $.ajax({
                url: "project_expense/process.php",
                method: "POST",
                data: formData,
                success: function(data) {
                    $('#expenseForm')[0].reset();
                    $('#view_expense').hide('fast');
                    $('#show_expense').show('fast');
                    $('#save').attr('disabled', false);
                    window.location.href = 'add-helping-category';
                }
            })
        });
        $("#expenseList").on('click', '.delete', function() {
            var editId = $(this).attr("id");
            var action = "deleteExpense";
            if (confirm("Are you sure you want to delete this project expense?")) {
                $.ajax({
                    url: "project_expense/process.php",
                    method: "POST",
                    data: {
                        editId: editId,
                        action: action
                    },
                    success: function(data) {
                        window.location.href = 'add-helping-category';
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
        $("#donate_amount").on("keydown keyup", per);

        function per() {
            var totalamount = (
                Number($("#donate_amount").val())
            );
            words = toWords(totalamount);
            $("#amount_in_words").val(words);
        }
    });
</script>
<?php ob_end_flush();?>