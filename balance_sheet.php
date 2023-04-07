<?php ob_start(); ?>
<?php $page = 'report'; ?>
<?php $title = "Balance Sheet"; ?>
<?php include "includes/main_sidebar.php"; ?>
<?php
if($isAdmin != 1){
    header('Location: index.php');
}
?>
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                    <div class="row">
                    <div class="col-md-12">
                        <div class="callout callout-info">
                            <h5><i class="fa fa-info"></i> Balance Sheet</h5>
                        </div>
                    </div>
                    </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="callout callout-info">
                            <h5><i class="fa fa-info"></i> Total Donations:</h5><hr>
                            <?php
                                $donations_in_cash = $conn->query("SELECT SUM(total_amount_num)'Cash' FROM donations WHERE amount_type='Cash'");
                                $donations_in_cheque = $conn->query("SELECT SUM(total_amount_num)'Cheque' FROM donations WHERE amount_type='Cheque'");
                                $donations_in_total = $conn->query("SELECT SUM(total_amount_num)'Total' FROM donations");
                                $donations_in_cash_rows = mysqli_fetch_assoc($donations_in_cash);
                                $donations_in_cheque_rows = mysqli_fetch_assoc($donations_in_cheque);
                                $donations_in_total_rows = mysqli_fetch_assoc($donations_in_total);
                            ?>
                            <p><b>Cash: </b><span class="pull-right"><?php if($donations_in_cash_rows['Cash'] != "") { echo $donations_in_cash_rows['Cash'];}else{ echo "0";}?> Rs.</span></p>
                            <p><b>Cheque: </b><span class="pull-right"><?php if($donations_in_cheque_rows['Cheque'] != "") { echo $donations_in_cheque_rows['Cheque'];}else{ echo "0";}?> Rs.</span></p>
                            <p><b>Total: </b><span class="pull-right"><?php if($donations_in_total_rows['Total'] != "") { echo $donations_in_total_rows['Total'];}else{ echo "0";}?> Rs.</span></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="callout callout-info">
                            <h5><i class="fa fa-info"></i> Total Expenses:</h5><hr>
                            <?php
                                $expense_in_cash = $conn->query("SELECT SUM(expense_amount)'Cash' FROM expense WHERE amount_type='Cash'");
                                $expense_in_cheque = $conn->query("SELECT SUM(expense_amount)'Cheque' FROM expense WHERE amount_type='Cheque'");
                                $expense_in_total = $conn->query("SELECT SUM(expense_amount)'Total' FROM expense");
                                $expense_in_cash_rows = mysqli_fetch_assoc($expense_in_cash);
                                $expense_in_cheque_rows = mysqli_fetch_assoc($expense_in_cheque);
                                $expense_in_total_rows = mysqli_fetch_assoc($expense_in_total);
                            ?>
                            <p><b>Cash: </b><span class="pull-right"><?php if($expense_in_cash_rows['Cash'] != "") { echo $expense_in_cash_rows['Cash'];}else{ echo "0";}?> Rs.</span></p>
                            <p><b>Cheque: </b><span class="pull-right"><?php if($expense_in_cheque_rows['Cheque'] != "") { echo $expense_in_cheque_rows['Cheque'];}else{ echo "0";}?> Rs.</span></p>
                            <p><b>Total: </b><span class="pull-right"><?php if($expense_in_total_rows['Total'] != "") { echo $expense_in_total_rows['Total'];}else{ echo "0";}?> Rs.</span></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="callout callout-info">
                            <h5><i class="fa fa-info"></i> Total Loans:</h5><hr>
                            <?php
                                $loans = $conn->query("SELECT credit, debit, payment_type FROM loans");
                                $loans_rows = mysqli_fetch_assoc($loans);
                                $loan_credit = 0;
                                if($loans_rows['credit'] != "") 
                                { 
                                    $loan_credit += $loans_rows['credit'];
                                }else
                                { 
                                    $loan_credit = "0";
                                }
                                $loan_debit = 0;
                                if($loans_rows['debit'] != "") 
                                { 
                                    $loan_debit += $loans_rows['debit'];
                                }else
                                { 
                                    $loan_debit = "0";
                                }
                                $total_loan = 0;
                                if($loans_rows['credit'] != "" && $loans_rows['debit'] != "") 
                                { 
                                    $total_loan += $loans_rows['credit'] + $loans_rows['debit'];
                                }else
                                { 
                                    $total_loan = "0";
                                }
                            ?>
                            <p><b>Credit: </b><span class="pull-right"><?= $loan_credit; ?> Rs.</span></p>
                            <p><b>Debit: </b><span class="pull-right"><?= $loan_debit; ?> Rs.</span></p>
                            <p><b>Total: </b><span class="pull-right"><?= $total_loan; ?> Rs.</span></p>
                        </div>
                    </div>
                </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="callout callout-info">
                                <h5><i class="fa fa-info"></i> Total Expenses:</h5><hr>
                                <?php
                                    $expense_in_cash = $conn->query("SELECT SUM(fee)'Cash' FROM membership WHERE amount_type='Cash'");
                                    $expense_in_cheque = $conn->query("SELECT SUM(fee)'Cheque' FROM membership WHERE amount_type='Cheque'");
                                    $expense_in_total = $conn->query("SELECT SUM(fee)'Total' FROM membership");
                                    $expense_in_cash_rows = mysqli_fetch_assoc($expense_in_cash);
                                    $expense_in_cheque_rows = mysqli_fetch_assoc($expense_in_cheque);
                                    $expense_in_total_rows = mysqli_fetch_assoc($expense_in_total);
                                ?>
                                <p><b>Cash: </b><span class="pull-right"><?php if($expense_in_cash_rows['Cash'] != "") { echo $expense_in_cash_rows['Cash'];}else{ echo "0";}?> Rs.</span></p>
                                <p><b>Cheque: </b><span class="pull-right"><?php if($expense_in_cheque_rows['Cheque'] != "") { echo $expense_in_cheque_rows['Cheque'];}else{ echo "0";}?> Rs.</span></p>
                                <p><b>Total: </b><span class="pull-right"><?php if($expense_in_total_rows['Total'] != "") { echo $expense_in_total_rows['Total'];}else{ echo "0";}?> Rs.</span></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="callout callout-info">
                                <h5><i class="fa fa-info"></i> Total Helping Categories:</h5><hr>
                                <?php
                                    $help_in_cash = $conn->query("SELECT SUM(donate_amount)'Cash' FROM project_expense WHERE amount_type='Cash'");
                                    $help_in_cheque = $conn->query("SELECT SUM(donate_amount)'Cheque' FROM project_expense WHERE amount_type='Cheque'");
                                    $help_in_total = $conn->query("SELECT SUM(donate_amount)'Total' FROM project_expense");
                                    $help_in_cash_rows = mysqli_fetch_assoc($help_in_cash);
                                    $help_in_cheque_rows = mysqli_fetch_assoc($help_in_cheque);
                                    $help_in_total_rows = mysqli_fetch_assoc($help_in_total);
                                ?>
                                <p><b>Cash: </b><span class="pull-right"><?php if($help_in_cash_rows['Cash'] != "") { echo $help_in_cash_rows['Cash'];}else{ echo "0";}?> Rs.</span></p>
                                <p><b>Cheque: </b><span class="pull-right"><?php if($help_in_cheque_rows['Cheque'] != "") { echo $help_in_cheque_rows['Cheque'];}else{ echo "0";}?> Rs.</span></p>
                                <p><b>Total: </b><span class="pull-right"><?php if($help_in_total_rows['Total'] != "") { echo $help_in_total_rows['Total'];}else{ echo "0";}?> Rs.</span></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="callout callout-info">
                                <h5><i class="fa fa-info"></i> Total Payments:</h5><hr>
                                <?php
                                    $payments = $conn->query("SELECT credit, debit FROM payments");
                                    $payments_rows = mysqli_fetch_assoc($payments);
                                    $payment_credit = 0;
                                    if($payments_rows['credit'] != "") 
                                    { 
                                        $payment_credit += $payments_rows['credit'];
                                    }else
                                    { 
                                        $payment_credit = "0";
                                    }
                                    $payment_debit = 0;
                                    if($payments_rows['debit'] != "") 
                                    { 
                                        $payment_debit += $payments_rows['debit'];
                                    }else
                                    { 
                                        $payment_debit = "0";
                                    }
                                    $total_payment = 0;
                                    if($payments_rows['credit'] != "" && $payments_rows['debit'] != "") 
                                    { 
                                        $total_payment += $payments_rows['credit'] + $payments_rows['debit'];
                                    }else
                                    { 
                                        $total_payment = "0";
                                    }
                                ?>
                                <p><b>Credit: </b><span class="pull-right"><?= $payment_credit; ?> Rs.</span></p>
                                <p><b>Debit: </b><span class="pull-right"><?= $payment_debit; ?> Rs.</span></p>
                                <p><b>Total: </b><span class="pull-right"><?= $total_payment; ?> Rs.</span></p>
                            </div>
                        </div>
                    </div>
            </div>
        </section>
    </div>
<?php include "includes/footer.php"; ?>
<?php ob_end_flush();?>