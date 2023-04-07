<?php ob_start(); ?>
<?php $page = 'report'; ?>
<?php $title = "Cash Accounts Report"; ?>
<?php include "includes/main_sidebar.php"; ?>


<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Please Enter Valid Information</h3>
                        </div>
                        <div class="card-body">
                            <form action="" method="post" id="form">
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label for="date" class="control-label col-md-4" style="padding: 6px;">Select Date</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="select_by" id="select_by" style="width: 100%;" required>
                                                    <option value="">-Select By-</option>
                                                    <option value="DAY">Today</option>
                                                    <option value="WEEK">Weekly</option>
                                                    <option value="MONTH">Monthly</option>
                                                    <option value="CUSTOM">Custom</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1"></div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label for="date" class="control-label col-md-4" style="padding: 6px;">Cash Account</label>
                                            <div class="col-md-8">
                                                <select class="form-control" name="bank_acc_id" id="bank_acc_id" style="width: 100%;" required>
                                                    <option value="">-Select Account Type-</option>
                                                    <?php
                                                    $fetch_bank_accounts = $conn->query("SELECT * FROM bank_accounts");
                                                    while ($row_bank_accounts = mysqli_fetch_array($fetch_bank_accounts)) {
                                                        $bank_acc_id = $row_bank_accounts['bank_acc_id'];
                                                        $bank_acc_name = $row_bank_accounts['bank_acc_name'];
                                                    ?>
                                                        <option value="<?= $bank_acc_id; ?>"><?= $bank_acc_name; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="dates" style="display:none">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label for="date" class="control-label col-md-4" style="padding: 6px;">From Date :</label>
                                            <div class="col-md-8">
                                                <input type="date" name="from_date" id="from_date" class="form-control" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1"></div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label for="date" class="control-label col-md-4" style="padding: 6px;">To Date :</label>
                                            <div class="col-md-8">
                                                <input type="date" name="to_date" id="to_date" class="form-control" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row" style="margin-bottom: -20px;">
                                    <div class="col-md-12 text-right">
                                        <div class="form-group">
                                            <button type="button" name="submit" id="search" class="btn bg-primary">Filter <i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12" id="account-report">
            </div>
        </div>
</div>
</section>
</div>
<?php include "includes/footer.php"; ?>
<script type="text/javascript">
    $("#search").on('click', function() {
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var bank_acc_id = $("#bank_acc_id").val();
        var select_by = $("#select_by").val();
        $.ajax({
            url: "reports/get-account-report.php",
            method: "POST",
            data: {
                from_date: from_date,
                to_date: to_date,
                bank_acc_id: bank_acc_id,
                select_by: select_by
            },
            success: function(data) {
                $('#account-report').html(data);
            }
        })
    });
    $("#select_by").on('change', function() {
        var select_by = $("#select_by").val();
        if(select_by == "CUSTOM"){
            $("#dates").show('fast');
            $("#from_date").datepicker({ dateFormat: "yy-mm-dd"}).datepicker("setDate", new Date());
            $("#to_date").datepicker({ dateFormat: "yy-mm-dd"}).datepicker("setDate", new Date());
        }else{
            $("#dates").hide('fast');
            $("#from_date").val('');
            $("#to_date").val('');
        }
    });
</script>
<script type="text/javascript">
    function PrintDiv() {
        var contents = $("#debit").html();
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({
            "position": "absolute",
            "top": "-1000000px"
        });
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html><head><title>Cash Accounts Report</title>');
        frameDoc.document.write('</head><body>');
        //Append the external CSS file.
        frameDoc.document.write('<link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css" type="text/css"><link rel="stylesheet" href="dist/css/adminlte.css" type="text/css">');
        //Append the DIV contents.
        frameDoc.document.write(contents);
        frameDoc.document.write('</body></html>');
        frameDoc.document.close();
        setTimeout(function() {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);
    }
</script>
<?php ob_end_flush(); ?>