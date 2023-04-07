<?php ob_start(); ?>
<?php $page = 'loan_management'; ?>
<?php $title = "Loan Management"; ?>
<?php include "includes/main_sidebar.php"; ?>
<?php
if ($isAdmin != 1) {
    if ($loan_management_access == FALSE) {
        header('Location: index.php');
    }
}
if(isset($_POST["import"]))
{
    if (is_uploaded_file($_FILES['file']['tmp_name'])) {
        $file = $_FILES['file']['tmp_name'];
        $handle = fopen($file, "r");
        $c = 0;
        while(($getData = fgetcsv($handle,1000,",","'")) !== false)
        {
            $name           =   mysqli_real_escape_string($conn, $getData[0]);
            $mobile_no      =   mysqli_real_escape_string($conn, $getData[1]);
            $address        =   mysqli_real_escape_string($conn, $getData[2]);
            $details        =   mysqli_real_escape_string($conn, $getData[3]);
            $bank_acc_id    =   mysqli_real_escape_string($conn, $getData[4]);
            $invoice_no     =   mysqli_real_escape_string($conn, $getData[5]);
            $credit         =   mysqli_real_escape_string($conn, $getData[6]);
            $debit          =   mysqli_real_escape_string($conn, $getData[7]);
            $payment_type   =   mysqli_real_escape_string($conn, $getData[8]);
            $date           =   date('Y-m-d', strtotime(str_replace('-', '/', mysqli_real_escape_string($conn, $getData[9]))));
            $created_at     =   date('Y-m-d H:i:s');
            $sqlQuery = $conn->query("SELECT person_id FROM person_information WHERE `name` LIKE '$name%'");
            if(mysqli_num_rows($sqlQuery) > 0){
                $sqlRows = mysqli_fetch_assoc($sqlQuery);
                $person_id = $sqlRows['person_id'];
                $sql = $conn->query("INSERT INTO `loans`(`person_id`, `bank_acc_id`, `invoice_no`, `credit`, `debit`, `payment_type`, `date`, `created_at`, `user_id`) VALUES ('$person_id', '$bank_acc_id', '$invoice_no', '$credit', '$debit', '$payment_type', '$date', '$created_at', '$user_id')");                   
                if($sql){
                    echo '<script>
                        setTimeout(function() {
                            swal({
                                title: "Success!",
                                text: "Data addedd successfully!",
                                type: "success"
                            }, function() {
                                window.location = "new_loan.php";
                            });
                        }, 1000);
                    </script>';
                }else{
                    echo '<script>
                        setTimeout(function() {
                            swal({
                                title: "Error!",
                                text: "Data Not Added!",
                                type: "error"
                            }, function() {
                                window.location = "loans";
                            });
                        }, 1000);
                    </script>';
                }
            }else{
                $stmt = $conn->query("INSERT INTO `person_information`(`name`, `mobile_no`, `address`, `details`) VALUES ('$name', '$mobile_no', '$address', '$details')");
                $person_id = $conn->insert_id;
                $sql = $conn->query("INSERT INTO `loans`(`person_id`, `bank_acc_id`, `invoice_no`, `credit`, `debit`, `payment_type`, `date`, `created_at`, `user_id`) VALUES ('$person_id', '$bank_acc_id', '$invoice_no', '$credit', '$debit', '$payment_type', '$date', '$created_at', '$user_id')");  
                    if($sql){
                        $c = $c + 1;
                        echo '<script>
                            setTimeout(function() {
                                swal({
                                    title: "Success!",
                                    text: "Data Imported Successfully!",
                                    type: "success"
                                }, function() {
                                    window.location = "loans";
                                });
                            }, 1000);
                        </script>';
                    } 
                    else
                    {
                        echo '<script>
                            setTimeout(function() {
                                swal({
                                    title: "Error!",
                                    text: "Data Not Imported!",
                                    type: "error"
                                }, function() {
                                    window.location = "loans";
                                });
                            }, 1000);
                        </script>';
                    }
            }
        }
    }
}
?>
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row" id="showLoans">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Loans List</h3>
                            <div class="card-tools">
                                <button type="button" id="import" style="margin-left:10px"
                                    class="btn btn-dark btn-sm pull-right">Import Data</button>
                                <a href="add-loan" class="btn btn-dark btn-sm pull-right">New Loan</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row" style="display:none" id="import_data">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-10">
                                                    <form action="" method="POST" enctype="multipart/form-data">
                                                        <div class="form-group row">
                                                            <label for="firstname" class="control-label col-md-3"
                                                                style="padding-top: 7px;" required="">Import
                                                                Data</label>
                                                            <div class="col-md-6">
                                                                <input type="file" name="file" id="file" accept=".csv"
                                                                    class="form-control">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <button type="submit" id="submit" name="import"
                                                                    class="btn btn-info">Import</button>
                                                                <button type="button" id="cancel" name="cancel"
                                                                    class="btn btn-default">Cancel</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <div id="buttons" class="text-center m-0"></div>
                                <table id="debit" class="table table-bordered table-striped">
                                    <thead class="bg-info">
                                        <tr>
                                            <th>Sr#</th>
                                            <th>Person Name</th>
                                            <th>Bank Name</th>
                                            <th class="text-center">Credit</th>
                                            <th class="text-center">Debit</th>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Balance</th>
                                            <th class="text-center">Generate</th>
                                            <?php if ($isAdmin == 1) { ?>
                                            <th class="text-center"><i class="fa fa-cogs"></i></th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $userQuery = $conn->query("SELECT isAdmin,role_id FROM users WHERE user_id = '$user_id'");
                                        $userRows = mysqli_fetch_array($userQuery);
                                        if ($userRows['isAdmin'] != 1 && $userRows['role_id'] != 3) {
                                            $sqlQuery = $conn->query("SELECT * FROM loans INNER JOIN person_information ON loans.person_id = person_information.person_id INNER JOIN bank_accounts ON bank_accounts.bank_acc_id = loans.bank_acc_id INNER JOIN users ON loans.user_id = users.user_id WHERE loans.user_id = '$user_id' ORDER BY date DESC");
                                        } else {
                                            $sqlQuery = $conn->query("SELECT * FROM loans INNER JOIN person_information ON loans.person_id = person_information.person_id INNER JOIN bank_accounts ON bank_accounts.bank_acc_id = loans.bank_acc_id ORDER BY date DESC");
                                        }
                                        $tbalance = 0;
                                        while ($sqlRows = mysqli_fetch_assoc($sqlQuery)) :
                                            $chkbala = $sqlRows['credit'] - $sqlRows['debit'];
                                        ?>
                                        <tr>
                                            <td><?= $sqlRows['invoice_no']; ?></td>
                                            <td><?= ucfirst($sqlRows['name']); ?></td>
                                            <td><?= $sqlRows['bank_acc_name']; ?></td>
                                            <td class="text-right"><?= number_format($sqlRows['credit'], 2); ?></td>
                                            <td class="text-center"><?= number_format($sqlRows['debit'], 2); ?></td>
                                            <td class="text-center"><?= $sqlRows['date']; ?></td>
                                            <td class="text-center"><?= number_format($tbalance += $chkbala, 2); ?></td>
                                            <td class="text-center"><a
                                                    href="loan-invoice/<?php echo $sqlRows['loan_id']; ?>"
                                                    class="btn btn-primary btn-sm" target="_blank"><i
                                                        class="fa fa-print"></i></a></td>
                                            <?php if ($isAdmin == 1) { ?>
                                            <td class="text-center">
                                                <a href="edit-loan/<?php echo $sqlRows['loan_id']; ?>"
                                                    class="btn btn-warning btn-sm update"><span
                                                        class="fa fa-edit"></span></a>
                                                <button class="btn btn-danger btn-sm delete" id="btn_delete"
                                                    data-id1="<?php echo $sqlRows['loan_id']; ?>"><span
                                                        class="fa fa-trash"></span></button>
                                            </td>
                                            <?php } ?>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                    <tfoot class="bg-info">
                                        <tr>
                                            <td colspan="2" class="text-right">Total</td>
                                            <td colspan="4"></td>
                                            <td class="text-center"><?= number_format($tbalance, 2); ?></td>
                                            <td></td>
                                            <?php if ($isAdmin == 1) { ?>
                                            <td></td>
                                            <?php } ?>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="edit_body"></div>
        </div>
    </section>
</div>
<?php include "includes/footer.php"; ?>
<link rel="stylesheet" href="sweetalert.min.css" />
<script src="sweetalert.min.js"></script>
<script>
var table = $('#debit').DataTable({
    "responsive": true,
    "autoWidth": false,
    "processing": true,
    "serverSide": false,
    "bProcessing": true,
    "order": [],
    "pageLength": 25,
    "lengthChange": true,
    "lengthMenu": [
        [25, 100, 1000, -1],
        [25, 100, 1000, "All"]
    ],
});
var buttons = new $.fn.dataTable.Buttons(table, {
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
$(document).on('click', '#btn_delete', function() {
    var Delete_ID = $(this).attr('data-id1');
    if (confirm("Are you sure you want to delete this payment?")) {
        $.ajax({
            url: "loan_management/delete.php",
            method: "POST",
            data: {
                Del_ID: Delete_ID
            },
            success: function(data) {
                setTimeout(function() {
                    swal({
                        title: "Success!",
                        text: "Data Deleted Successfully!",
                        type: "success"
                    }, function() {
                        window.location = "loans";
                    });
                }, 1000);
            }
        })
    } else {
        return false;
    }
});

function _edit(id) {
    $.post('loan_management/_edit.php', {
            id: id
        },
        function(data) {
            $("#showLoans").hide('fast');
            $('#edit_body').html(data);
        });
}
$("#import").on('click', function() {
    $("#import_data").show('fast');
});
$("#cancel").on('click', function() {
    $("#import_data").hide('fast');
});
</script>
<?php ob_end_flush(); ?>