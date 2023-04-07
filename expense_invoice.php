<?php ob_start(); ?>
<?php
@session_start();
require_once 'includes/db.php';
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    @header('Location: ../login.php');
    exit;
} else {
    $stmt = $conn->prepare('SELECT user_id, name, email, isAdmin FROM users WHERE user_id = ?');
    $stmt->bind_param('s', $_SESSION['user_id']);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $name, $email, $isAdmin);
        $stmt->fetch();
    }
    $stmt->close();

    $stmt = $conn->prepare('SELECT `exp_id`, `expense_date`, `refrence_no`, `expense_title`, `amount_type`, `bank_acc_name`, `expense_for`, `expense_amount`, `cheque_no` FROM expense INNER JOIN expenses ON expense.expense_id = expenses.expense_id INNER JOIN bank_accounts ON expense.bank_acc_id = bank_accounts.bank_acc_id WHERE expense.exp_id = ?');
    $stmt->bind_param('s', $_GET['invoice']);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($exp_id, $expense_date, $refrence_no, $expense_title, $amount_type ,$bank_acc_name, $expense_for, $expense_amount, $cheque_no);
        $stmt->fetch();
    }
    $stmt->close();
    
    $get_settings = $conn->query('SELECT `registration_no` FROM system_settings LIMIT 1');
    $registration_no = '';
    if(mysqli_num_rows($get_settings) > 0){
        $fetch_settings = mysqli_fetch_assoc($get_settings);
        $registration_no = $fetch_settings['registration_no'];
    }
}
?>
<?php $page = 'expense'; ?>
<?php $title = "Print Invoice"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900'
        type='text/css'>
    <link rel="stylesheet" type="text/css"
        href="https://demo.harnishdesign.net/html/koice/vendor/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="https://demo.harnishdesign.net/html/koice/css/stylesheet.css" />
</head>

<body>
    <!-- Container -->
    <div class="container-fluid invoice-container">
        <!-- Header -->
        <header>
            <div class="row align-items-center">
                <div class="col-sm-7 text-center text-sm-left mb-3 mb-sm-0"> <img id="logo" src="../images/logo.jpeg"
                        style="height:100px" title="Ameen Foundation" alt="Ameen Foundation" /> </div>
                <div class="col-sm-5 text-center text-sm-right">
                    <h4 class="mb-0">Invoice</h4>
                    <p class="mb-0">Invoice Number - <?= $refrence_no; ?></p>
                    <p class="mb-0">NTN # - 7997489-8</p>
                    <p class="mb-0">Registration No - <?php echo $registration_no;?></p>
                </div>
            </div>
            <hr>
        </header>
        <!-- Main Content -->
        <main>
            <div class="row">
                <div class="col-sm-8 text-sm-left order-sm-0"> <strong>Invoiced To:</strong>
                    <address>
                        <p>Name: <strong>Ameen Foundation</strong></p>
                        <p>Phone: <strong>(041) 436-2655</strong></p>
                        <p>Email: <strong>contact@ameenfoundation.org.pk</strong></p>
                        <p>Whatsapp No: <strong>0343-2772665</strong></p>
                    </address>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6"> <strong>Payment Method:</strong><br>
                    <?php if($amount_type != ""){?>
                    <span><?php echo $amount_type; ?></span> <br />
                    <?php }?>
                    <br />
                </div>
                <div class="col-sm-6 text-sm-right"> <strong>Invoice Date:</strong><br>
                    <span> <?= date('Y-m-d'); ?><br>
                        <br>
                    </span>
                </div>
            </div>
            <div class="card">
                <div class="card-header"> <span class="font-weight-600 text-4">Summary</span> </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <td class="col-4 border-top-0"><strong>Expense Type</strong></td>
                                    <td class="col-4 border-top-0"></td>
                                    <td class="col-4 text-right border-top-0"><strong>Amount</strong></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="text-3"><?= $expense_title; ?></span></td>
                                    <td></td>
                                    <td class="text-right"><?php echo number_format($expense_amount, 2); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3">Expense For: <br>
                                        <small><?= $expense_for; ?></small>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">Cheque #: <br>
                                        <?php if($amount_type == "Cash"){ ?>
                                        <small><?= $cheque_no; ?>-----</small>
                                        <?php }else{ ?>
                                        <small><?php echo $cheque_no ?></small>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="bg-light-2 text-right"><strong>Total</strong></td>
                                    <td class="bg-light-2 text-right"><?php echo number_format($expense_amount, 2); ?>
                                        Rs</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <br>
            <div class="table-responsive d-print-none">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td class="text-center"><strong>Transaction Date</strong></td>
                            <td class="text-center"><strong>Cheque #</strong></td>
                            <td class="text-center"><strong>Amount</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><?= $expense_date ?></td>
                            <?php if($amount_type == "Cash"){ ?>
                            <td class="text-center">-----</td>
                            <?php }else{ ?>
                            <td class="text-center"><?php echo $cheque_no ?></td>
                            <?php } ?>
                            <td class="text-center"><?php echo number_format($expense_amount, 2); ?> Rs</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
        <!-- Footer -->
        <footer class="text-center">
            <p class="text-right text-1"><strong>SIGNATURE :</strong> __________________</p>
            <div class="btn-group btn-group-sm d-print-none"> <a href="javascript:window.print()"
                    class="btn btn-dark border text-white-50 shadow-none"> Print</a> </div>
        </footer>
    </div>
    <!-- Back to My Account Link -->
    <p class="text-center d-print-none"><a href="../add-expense">&laquo; Go Back</a></p>
</body>

</html>
<?php ob_end_flush();?>