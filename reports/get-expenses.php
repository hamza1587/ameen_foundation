<?php
/* at the top of 'check.php' */
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    /* 
           Up to you which header to send, some prefer 404 even if 
           the files does exist for security
        */
    header('HTTP/1.0 403 Forbidden', TRUE, 403);

    /* choose the appropriate page to redirect users */
    die(header('location: ../404.php'));
} else {
    @session_start();
    include "../includes/db.php";
    // If the user is not logged in redirect to the login page...
    if (!isset($_SESSION['loggedin'])) {
        @header('Location: login.php');
        exit;
    } else {
        $sqlQuery = "SELECT expense_title, expense_date, amount_type, bank_acc_name, expense_for, expense_amount, cheque_no FROM `expense` INNER JOIN expenses ON expenses.expense_id = expense.expense_id INNER JOIN bank_accounts ON bank_accounts.bank_acc_id = expense.bank_acc_id ";
        if (!empty($_POST["from_date"]) && !empty($_POST["to_date"]) && empty($_POST['amount_type']) && empty($_POST["expense_id"])) {
            $sqlQuery .= "WHERE expense_date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "' ";
        } else if (!empty($_POST["from_date"]) && !empty($_POST["to_date"]) && !empty($_POST["amount_type"]) && empty($_POST["expense_id"])) {
            $sqlQuery .= "WHERE expense_date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "' AND amount_type = '" . $_POST['amount_type'] . "' ";
        } else if (!empty($_POST["from_date"]) && !empty($_POST["to_date"]) && empty($_POST["amount_type"]) && !empty($_POST["expense_id"])) {
            $sqlQuery .= "WHERE expense_date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "' AND expense.expense_id = '" . $_POST['expense_id'] . "' ";
        } else if (!empty($_POST["from_date"]) && !empty($_POST["to_date"]) && !empty($_POST["amount_type"]) && !empty($_POST["expense_id"])) {
            $sqlQuery .= "WHERE expense_date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "' AND amount_type = '" . $_POST['amount_type'] . "' AND expense.expense_id = '" . $_POST['expense_id'] . "' ";
        }
        $sqlQuery .= 'ORDER BY expense_date DESC';
        $runQuery = mysqli_query($conn, $sqlQuery);
?>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Expense Report</h3>
                <div class="card-tools">
                    <button type="button" value="print" onclick="PrintDiv();"><i class="fa fa-print"></i></button>
                </div>
            </div>
            <div class="card-body" id="debit">
                <div class="row">
                    <div class="col-md-12">
                        <h4>
                            <?php if (!empty($_POST['from_date']) && !empty($_POST['to_date'])) {
                                echo "<span style='float: right;'>" . $_POST['from_date'] . " to " . $_POST['to_date'] . "</span>";
                            } ?>
                        </h4>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="expenses" class="table table-bordered table-striped">
                        <thead class="bg-info">
                            <tr>
                                <th>Sr#</th>
                                <th>Expense Type</th>
                                <th>Amount Type</th>
                                <th>Bank Name</th>
                                <th>Date</th>
                                <th>Expense For</th>
                                <th class="text-right">Cheque No</th>
                                <th>Total Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            $total_amount = 0;
                            while ($debit = mysqli_fetch_assoc($runQuery)) :
                                $total_amount += $debit['expense_amount'];
                                if ($debit['cheque_no'] == "") {
                                    $cheques_no = "-------";
                                } else {
                                    $cheques_no = $debit['cheque_no'];
                                }
                            ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= ucfirst($debit['expense_title']); ?></td>
                                    <td><?= $debit['amount_type']; ?></td>
                                    <td><?= $debit['bank_acc_name']; ?></td>
                                    <td><?= $debit['expense_date']; ?></td>
                                    <td><?= $debit['expense_for']; ?></td>
                                    <td><?= $cheques_no; ?></td>
                                    <td class="text-right"><?= number_format($debit['expense_amount'], 2); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot class="text-right bg-info">
                            <tr>
                                <td colspan="7" class="text-center">Total</td>
                                <td><?php echo number_format($total_amount, 2); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <script>
            var expenses = $('#expenses').DataTable({
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
            var buttons = new $.fn.dataTable.Buttons(expenses, {
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
        </script>
<?php }
} ?>