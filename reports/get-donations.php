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
        $display = "none";
        $sqlQuery = "SELECT donator_name, date, total_amount_num, amount_type, bank_acc_name, cheque_no FROM `donations` INNER JOIN bank_accounts ON bank_accounts.bank_acc_id = donations.bank_acc_id ";
        if (!empty($_POST["from_date"]) && !empty($_POST["to_date"]) && empty($_POST['amount_type'])) {
            $sqlQuery .= "WHERE date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "' ";
            $display = "";
        } else if (!empty($_POST["from_date"]) && !empty($_POST["to_date"]) && !empty($_POST["amount_type"])) {
            $sqlQuery .= "WHERE date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "' AND amount_type = '" . $_POST['amount_type'] . "' ";
            $display = "";
        }
        $sqlQuery .= 'ORDER BY date DESC';
        $runQuery = mysqli_query($conn, $sqlQuery);
?>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Donation Report</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-info btn-sm" value="print" onclick="PrintDiv();"><i class="fa fa-print"></i></button>
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
                    <table id="donations" class="table table-bordered table-striped">
                        <thead class="bg-info">
                            <tr>
                                <th>Sr#</th>
                                <th>Name</th>
                                <th>Amount Type</th>
                                <th>Bank Name</th>
                                <th>Date</th>
                                <th>Cheque No</th>
                                <th class="text-right">Total Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            $total_amount = 0;
                            while ($debit = mysqli_fetch_assoc($runQuery)) :
                                $total_amount += $debit['total_amount_num'];
                                if ($debit['cheque_no'] == "") {
                                    $cheques_no = "-------";
                                } else {
                                    $cheques_no = $debit['cheque_no'];
                                }
                            ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= ucfirst($debit['donator_name']); ?></td>
                                    <td class="text-left"><?= $debit['amount_type']; ?></td>
                                    <td class="text-left"><?= $debit['bank_acc_name']; ?></td>
                                    <td class="text-left"><?= $debit['date']; ?></td>
                                    <td class="text-left"><?= $cheques_no; ?></td>
                                    <td class="text-right"><?= number_format($debit['total_amount_num'], 2); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot class="text-right bg-info">
                            <tr>
                                <td colspan="6" class="text-center">Total</td>
                                <td><?php echo number_format($total_amount, 2); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
<?php }
} ?>