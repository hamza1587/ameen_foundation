<?php ob_start(); ?>
<?php $page = 'report'; ?>
<?php $title = "Expense Report"; ?>
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
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Please Enter Valid Information</h3>
                        </div>
                        <div class="card-body">
                            <form action="" method="post" id="form">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label for="date" class="control-label col-md-4" style="padding: 6px;">From
                                                Date :</label>
                                            <div class="col-md-8">
                                                <input type="date" name="from_date" id="from_date" class="form-control"
                                                    value="<?php if(isset($_POST['from_date'])){ echo $_POST['from_date'];} else{ echo date('Y-m-d'); } ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label for="date" class="control-label col-md-4" style="padding: 6px;">To
                                                Date :</label>
                                            <div class="col-md-8">
                                                <input type="date" name="to_date" id="to_date" class="form-control"
                                                    value="<?php if(isset($_POST['to_date'])){ echo $_POST['to_date'];} else{ echo date('Y-m-d'); } ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label for="date" class="control-label col-md-4"
                                                style="padding: 6px;">Payment Type</label>
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
                                            <button type="button" name="submit" id="search"
                                                class="btn bg-primary">Filter <i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-12" id="helping-category-report">

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
    var amount_type = $("#amount_type").val();
    $.ajax({
        url: "reports/get-helping-categories.php",
        method: "POST",
        data: {
            from_date: from_date,
            to_date: to_date,
            amount_type: amount_type
        },
        success: function(data) {
            $('#helping-category-report').html(data);
        }
    })
});
var helping_categories = $('#helping_categories').DataTable({
    "responsive": true,
    "autoWidth": false,
    "processing": false,
    "serverSide": false,
    "bProcessing": false,
    "order": [],
    "lengthChange": false,
    "pageLength": 25,
    "paging": false,
    "info": false,
    'searching': false,
});
var buttons = new $.fn.dataTable.Buttons(helping_categories, {
    buttons: [{
            extend: 'excelHtml5',
            title: 'Excel',
            text: 'Export as excel',
            className: 'bg-info btn-xs border-0 m-1',
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
            }
        },
        {
            extend: 'pdfHtml5',
            title: 'PDF',
            text: 'Export as PDF',
            className: 'bg-info btn-xs border-0 m-1',
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
            }
        },
        {
            extend: 'csvHtml5',
            title: 'CSV',
            text: 'Export as CSV',
            className: 'bg-info btn-xs border-0 m-1',
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
            }
        },
    ]
}).container().appendTo($('#buttons'));
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
    var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0]
        .contentDocument.document : frame1[0].contentDocument;
    frameDoc.document.open();
    //Create a new HTML document.
    frameDoc.document.write('<html><head><title>Loan Report</title>');
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
<?php ob_end_flush();?>