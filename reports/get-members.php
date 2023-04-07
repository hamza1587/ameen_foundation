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
        $sqlQuery = "SELECT * FROM membership INNER JOIN bank_accounts ON membership.bank_acc_id = bank_accounts.bank_acc_id ";
        if (!empty($_POST["from_date"]) && !empty($_POST["to_date"]) && empty($_POST['name']) && empty($_POST['amount_type'])) {
            $sqlQuery .= "WHERE date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "' ";
        } else if (!empty($_POST["from_date"]) && !empty($_POST["to_date"]) && empty($_POST['name']) && !empty($_POST['amount_type'])) {
            $sqlQuery .= "WHERE date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "' AND amount_type = '" . $_POST['amount_type'] . "' ";
        } else if (!empty($_POST["from_date"]) && !empty($_POST["to_date"]) && !empty($_POST['name']) && empty($_POST['amount_type'])) {
            $sqlQuery .= "WHERE date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "' AND name = '" . $_POST['name'] . "' ";
        } else if (!empty($_POST["from_date"]) && !empty($_POST["to_date"]) && !empty($_POST['name']) && !empty($_POST['amount_type'])) {
            $sqlQuery .= "WHERE date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "' AND name = '" . $_POST['name'] . "' AND amount_type = '" . $_POST['amount_type'] . "' ";
        }
        $sqlQuery .= 'ORDER BY date DESC ';
        $runQuery = mysqli_query($conn, $sqlQuery);
?>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Membership Report</h3>
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
                    <table class="table table-bordered table-striped">
                        <thead class="bg-info">
                            <tr>
                                <th>Sr#</th>
                                <th>Name</th>
                                <th>Father Name</th>
                                <th>Date</th>
                                <th>Fee</th>
                                <th>Fee Type</th>
                                <th>Fee Month</th>
                                <th>Bank Name</th>
                                <th>Amount Type</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            $tbalance = 0;
                            while ($debit = mysqli_fetch_assoc($runQuery)) :
                                $tbalance += $debit['fee'];
                            ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $debit['name']; ?></td>
                                    <td><?= $debit['father_name']; ?></td>
                                    <td><?= $debit['date']; ?></td>
                                    <td><?= $debit['fee']; ?></td>
                                    <td><?= $debit['fee_type']; ?></td>
                                    <td><?= $debit['fee_month']; ?></td>
                                    <td><?= $debit['bank_acc_name']; ?></td>
                                    <td><?= $debit['amount_type']; ?></td>
                                    <td class="text-center"><?= number_format($debit['fee'], 2); ?></td>
                                </tr>
                            <?php
                            endwhile; ?>
                        </tbody>
                        <tfoot class="bg-info">
                            <tr>
                                <td colspan="9" class="text-center">Total</td>
                                <td class="text-center"><?= number_format($tbalance, 2); ?> </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
<?php }
} ?>