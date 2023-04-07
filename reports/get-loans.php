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
        $sqlQuery = "SELECT * FROM loans INNER JOIN person_information ON loans.person_id = person_information.person_id INNER JOIN bank_accounts ON bank_accounts.bank_acc_id = loans.bank_acc_id ";
        if (!empty($_POST["from_date"]) && !empty($_POST["to_date"]) && empty($_POST['payment_type']) && empty($_POST['person_id'])) {
            $sqlQuery .= "WHERE date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "' ";
        } else if (!empty($_POST["from_date"]) && !empty($_POST["to_date"]) && !empty($_POST["person_id"]) && empty($_POST['payment_type'])) {
            $sqlQuery .= "WHERE date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "' AND loans.person_id = '" . $_POST['person_id'] . "' ";
        }
        $sqlQuery .= 'ORDER BY date DESC';
        $runQuery = mysqli_query($conn, $sqlQuery);
?>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Loan Report</h3>
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
                                <th>Person Name</th>
                                <th>Bank Name</th>
                                <th>Date</th>
                                <th class="text-center">Credit</th>
                                <th class="text-center">Debit</th>
                                <th class="text-center">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            $tbalance = 0;
                            while ($sqlRows = mysqli_fetch_assoc($runQuery)) :
                                $chkbala = $sqlRows['credit'] - $sqlRows['debit'];
                            ?>
                                <tr>
                                    <td><?= $sqlRows['invoice_no']; ?></td>
                                    <td><?= ucfirst($sqlRows['name']); ?></td>
                                    <td><?= $sqlRows['bank_acc_name']; ?></td>
                                    <td><?= $sqlRows['date']; ?></td>
                                    <td class="text-right"><?= number_format($sqlRows['credit'], 2); ?></td>
                                    <td class="text-center"><?= number_format($sqlRows['debit'], 2); ?></td>
                                    <td class="text-center"><?= number_format($tbalance += $chkbala, 2); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot class="bg-info">
                            <tr>
                                <td colspan="6" class="text-right">Total</td>
                                <td class="text-center"><?= number_format($tbalance, 2); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
<?php }
} ?>