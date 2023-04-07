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
        $sqlQuery = "SELECT person_name, state_name, city_name, date, service_name, amount_type, bank_acc_name, donate_amount, date FROM `project_expense` INNER JOIN cities ON cities.city_id = project_expense.city_id INNER JOIN states ON states.state_id = project_expense.state_id INNER JOIN services ON services.service_id = project_expense.service_id INNER JOIN bank_accounts ON bank_accounts.bank_acc_id = project_expense.bank_acc_id ";
        if (!empty($_POST["from_date"]) && !empty($_POST["to_date"]) && empty($_POST['amount_type'])) {
            $sqlQuery .= "WHERE date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "' ";
        } else if (!empty($_POST["from_date"]) && !empty($_POST["to_date"]) && !empty($_POST["amount_type"])) {
            $sqlQuery .= "WHERE date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "' AND amount_type = '" . $_POST['amount_type'] . "' ";
        }
        $sqlQuery .= 'ORDER BY date DESC ';
        $runQuery = mysqli_query($conn, $sqlQuery);
?>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Project Expense Report</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-info btn-sm" value="print" onclick="PrintDiv();"><i class="fa fa-print"></i></button>
                </div>
            </div>
            <div class="card-body" id="debit">
                <div id="buttons" class="text-center m-0"></div>
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
                    <table id="helping_categories" class="table table-bordered table-striped">
                        <thead class="bg-info">
                            <tr>
                                <th>Sr#</th>
                                <th>Person Name</th>
                                <th>State</th>
                                <th>Service</th>
                                <th>Amount Type</th>
                                <th>Bank Name</th>
                                <th>Cheque No</th>
                                <th>Date</th>
                                <th>Total Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            $total_amount = 0;
                            while ($debit = mysqli_fetch_assoc($runQuery)) :
                                $total_amount += $debit['donate_amount'];
                            ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= ucfirst($debit['person_name']); ?></td>
                                    <td><?= $debit['state_name']; ?></td>
                                    <td><?= $debit['city_name']; ?></td>
                                    <td><?= $debit['service_name']; ?></td>
                                    <td><?= $debit['amount_type']; ?></td>
                                    <td><?= $debit['bank_acc_name']; ?></td>
                                    <td><?= $debit['date']; ?></td>
                                    <td class="text-right"><?= number_format($debit['donate_amount'], 2); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot class="text-right bg-info">
                            <tr>
                                <td colspan="8" class="text-center">Total</td>
                                <td><?php echo number_format($total_amount, 2); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
<?php }
} ?>