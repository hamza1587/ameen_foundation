<?php ob_start(); ?>
<?php $page = 'report'; ?>
<?php $title = "Payment Report"; ?>
<?php include "includes/main_sidebar.php"; ?>
<?php
if($isAdmin != 1){
    if($payments_access == FALSE){
        header('Location: index.php');
    }
}
?>

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
                                                <label for="date" class="control-label col-md-4" style="padding: 6px;">From Date :</label>
                                                <div class="col-md-8">
                                                    <input type="date" name="from_date" id="from_date" class="form-control" value="<?php if(isset($_POST['from_date'])){ echo $_POST['from_date'];} else{ echo date('Y-m-d'); } ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2"></div>
                                        <div class="col-md-4">
                                            <div class="form-group row">
                                                <label for="date" class="control-label col-md-4" style="padding: 6px;">To Date :</label>
                                                <div class="col-md-8">
                                                    <input type="date" name="to_date" id="to_date" class="form-control" value="<?php if(isset($_POST['to_date'])){ echo $_POST['to_date'];} else{ echo date('Y-m-d'); } ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-1"></div>
                                        <div class="col-md-4">
                                            <div class="form-group row">
                                                <label for="date" class="control-label col-md-4" style="padding: 6px;">From Account</label>
                                                <div class="col-md-8">
                                                    <select class="form-control" name="from_account" id="from_account">
                                                        <option value="">Choose Bank Account</option>
                                                        <?php
                                                        $fetch_bank_accounts = "SELECT * FROM bank_accounts";
                                                        $run_bank_accounts = mysqli_query($conn, $fetch_bank_accounts);
                                                        if ($run_bank_accounts) {
                                                            while ($row_bank_accounts = mysqli_fetch_array($run_bank_accounts)) {
                                                                $bank_acc_id  = $row_bank_accounts['bank_acc_id'];
                                                                $bank_acc_name       = $row_bank_accounts['bank_acc_name'];
                                                                echo "<option value='$bank_acc_id'>$bank_acc_name</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2"></div>
                                        <div class="col-md-4">
                                            <div class="form-group row">
                                                <label for="date" class="control-label col-md-4" style="padding: 6px;">To Account</label>
                                                <div class="col-md-8">
                                                    <select class="form-control" name="to_account" id="to_account">
                                                        <option value="">Choose Bank Account</option>
                                                        
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-1"></div>
                                        <div class="col-md-4">
                                            <div class="form-group row">
                                                <label for="date" class="control-label col-md-4" style="padding: 6px;">Payment Type</label>
                                                <div class="col-md-8">
                                                    <select class="form-control" name="amount_type" id="amount_type">
                                                        <option value="">Choose Amount Type</option>
                                                        <option value="Cash">Cash</option>
                                                        <option value="Cheque">Cheque</option>
                                                    </select>
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
                    <div class="col-md-12" id="payment-report">
                        
                    </div>
                </div>
            </div>
        </section>
    </div>
<?php include "includes/footer.php"; ?>
    <script type="text/javascript">
        $("#search").on('click', function() {
            var from_date       = $("#from_date").val();
            var to_date         = $("#to_date").val();
            var from_account    = $("#from_account").val();
            var to_account      = $("#to_account").val();
            var amount_type     = $("#amount_type").val();
            $.ajax({
                url: "reports/get-payments.php",
                method: "POST",
                data: {
                    from_date: from_date,
                    to_date: to_date,
                    from_account: from_account,
                    to_account: to_account,
                    amount_type: amount_type
                },
                success: function(data) {
                    $('#payment-report').html(data);
                }
            })
        });
        function PrintDiv() {
            var contents = $("#debit").html();
            var frame1 = $('<iframe />');
            frame1[0].name = "frame1";
            frame1.css({ "position": "absolute", "top": "-1000000px" });
            $("body").append(frame1);
            var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
            frameDoc.document.open();
            //Create a new HTML document.
            frameDoc.document.write('<html><head><title>Payment Report</title>');
            frameDoc.document.write('</head><body>');
            //Append the external CSS file.
            frameDoc.document.write('<link rel="stylesheet" href="file.css" type="text/css">');
            //Append the DIV contents.
            frameDoc.document.write(contents);
            frameDoc.document.write('</body></html>');
            frameDoc.document.close();
            setTimeout(function () {
                window.frames["frame1"].focus();
                window.frames["frame1"].print();
                frame1.remove();
            }, 500);
        }
        $('#from_account').on('change', function(){
            var from_account = $("#from_account").val();
            if(from_account){
                $.ajax({
                    type:'POST',
                    url:'payments/ajaxpro.php',
                    data:'from_account='+from_account,
                    success:function(html){
                        $('#to_account').html(html);
                    }
                }); 
            }else{
                $('#to_account').html('<option value="">Select from account first</option>');
            }
        });
    </script>
<?php ob_end_flush();?>