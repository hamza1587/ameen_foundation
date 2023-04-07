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
        $sqlQuery = "SELECT * FROM payments ";
                    if(!empty($_POST["from_date"]) && !empty($_POST["to_date"]) && empty($_POST['amount_type']) && empty($_POST['from_account']) && empty($_POST['to_account'])){
                        $sqlQuery .= "WHERE date BETWEEN '".$_POST['from_date']."' AND '".$_POST['to_date']."' ";
                    }
                    else if(!empty($_POST["from_date"]) && !empty($_POST["to_date"]) && empty($_POST["amount_type"]) && !empty($_POST['from_account']) && empty($_POST['to_account'])){
                        $sqlQuery .= "WHERE date BETWEEN '".$_POST['from_date']."' AND '".$_POST['to_date']."' AND from_account = '".$_POST['from_account']."' ";
                    }
                    else if(!empty($_POST["from_date"]) && !empty($_POST["to_date"]) && !empty($_POST["amount_type"]) && !empty($_POST['from_account']) && empty($_POST['to_account'])){
                        $sqlQuery .= "WHERE date BETWEEN '".$_POST['from_date']."' AND '".$_POST['to_date']."' AND to_account = '".$_POST['to_account']."' ";
                    }
                    else if(!empty($_POST["from_date"]) && !empty($_POST["to_date"]) && empty($_POST["amount_type"]) && !empty($_POST['from_account']) && !empty($_POST['to_account'])){
                        $sqlQuery .= "WHERE date BETWEEN '".$_POST['from_date']."' AND '".$_POST['to_date']."' AND from_account = '".$_POST['from_account']."' AND to_account = '".$_POST['to_account']."' ";
                    }
                    else if(!empty($_POST["from_date"]) && !empty($_POST["to_date"]) && !empty($_POST["amount_type"]) && empty($_POST['from_account']) && empty($_POST['to_account'])){
                        $sqlQuery .= "WHERE date BETWEEN '".$_POST['from_date']."' AND '".$_POST['to_date']."' AND amount_type = '".$_POST['amount_type']."' ";
                    }
                    else if(!empty($_POST["from_date"]) && !empty($_POST["to_date"]) && !empty($_POST["amount_type"]) && !empty($_POST['from_account']) && !empty($_POST['to_account'])){
                        $sqlQuery .= "WHERE date BETWEEN '".$_POST['from_date']."' AND '".$_POST['to_date']."' AND amount_type = '".$_POST['amount_type']."' AND from_account = '".$_POST['from_account']."' AND to_account = '".$_POST['to_account']."' ";
                    }
                    $sqlQuery .= 'GROUP BY payment_id ';
                    $runQuery = mysqli_query($conn, $sqlQuery);
                    ?>
                    <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Payment Report</h3>
                                <div class="card-tools">
                                    <button type="button" value="print" onclick="PrintDiv();"><i class="fa fa-print"></i></button>
                                </div>
                            </div>
                            <div class="card-body" id="debit">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4>
                                            <?php if(!empty($_POST['from_date']) && !empty($_POST['to_date'])){ echo "<span style='float: right;'>".$_POST['from_date']." to ". $_POST['to_date'] ."</span>";} ?>
                                        </h4>
                                    </div>
                                </div>
                                <div class="table-responsive" >
                                    <table class="table table-bordered table-striped">
                                        <thead class="bg-info">
                                        <tr>
                                            <th>Sr#</th>
                                            <th>Bank From</th>
                                            <th>Bank To</th>
                                            <th>Amount Type</th>
                                            <th>Credit</th>
                                            <th>Debit</th>
                                            <th>Total Balance</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $i = 1;
                                        $tbalance = 0;
                                        while ($debit = mysqli_fetch_assoc($runQuery)) :
                                            $bank_fromQuery = $conn->query("SELECT bank_acc_name FROM bank_accounts WHERE bank_acc_id = '".$debit['from_account']."'");
                                            $bank_toQuery = $conn->query("SELECT bank_acc_name FROM bank_accounts WHERE bank_acc_id = '".$debit['to_account']."'");
                                            while ($bank_from = mysqli_fetch_assoc($bank_fromQuery)) :
                                                while ($bank_to = mysqli_fetch_assoc($bank_toQuery)) :
                                                    $chkbala = $debit['credit'] - $debit['debit'];
                                                    ?>
                                                    <tr>
                                                        <td><?= $i++;?></td>
                                                        <td><?= $bank_from['bank_acc_name'];?></td>
                                                        <td><?= $bank_to['bank_acc_name'];?></td>
                                                        <td><?= $debit['amount_type'];?></td>
                                                        <td class="text-right"><?= number_format($debit['credit'], 2);?></td>
                                                        <td class="text-right"><?= number_format($debit['debit'], 2);?></td>
                                                        <td class="text-right"><?= number_format($tbalance += $chkbala, 2);?></td>
                                                    </tr>
                                                <?php
                                                endwhile;
                                            endwhile;
                                        endwhile;?>
                                        </tbody>
                                        <tfoot class="bg-info">
                                        <tr>
                                            <td colspan="6" class="text-center">Total</td>
                                            <td><?= number_format($tbalance, 2); ?> </td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php }} ?>