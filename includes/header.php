<?php
error_reporting(0);
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
    include "includes/db.php";
    // If the user is not logged in redirect to the login page...
    if (!isset($_SESSION['loggedin'])) {
        @header('Location: login.php');
        exit;
    } else {
        $stmt = $conn->prepare('SELECT user_id, name, email, isAdmin, role_id FROM users WHERE user_id = ?');
        $stmt->bind_param('s', $_SESSION['user_id']);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $name, $email, $isAdmin, $role_id);
            $stmt->fetch();
        }
        $stmt->close();
    }

    $alertSql = $conn->query(@"
    select expense.expense_amount - expense.expense_amount AS credit, expense.expense_amount
    From expense 
    where expense.expense_amount is not null AND expense_date > DATE_SUB(NOW(), INTERVAL 1 MONTH

    UNION ALL

    select donations.total, donations.total - donations.total As debit
    from donations
    where donations.total is not null AND date > DATE_SUB(NOW(), INTERVAL 1 MONTH

    UNION ALL

    select loans.credit, loans.debit
    from loans
    where loans.credit is not null AND loans.debit is not null AND date > DATE_SUB(NOW(), INTERVAL 1 MONTH

    UNION ALL

    select membership.fee, membership.fee - membership.fee AS debit
    from membership
    where membership.fee is not null AND date > DATE_SUB(NOW(), INTERVAL 1 MONTH

    UNION ALL

    select opening_balance.ob_amount, opening_balance.ob_amount - opening_balance.ob_amount as debit
    from opening_balance
    where opening_balance.ob_amount is not null AND ob_date > DATE_SUB(NOW(), INTERVAL 1 MONTH

    UNION ALL

    select payments.credit ,payments.debit
    from payments
    where payments.credit is not null AND payments.debit is not null AND date > DATE_SUB(NOW(), INTERVAL 1 MONTH

    UNION ALL

    select project_expense.donate_amount - project_expense.donate_amount as amount, project_expense.donate_amount
    from project_expense
    where project_expense.donate_amount is not null AND date > DATE_SUB(NOW(), INTERVAL 1 MONTH");
    $alertTotal = 0;
    while ($alertRows = mysqli_fetch_array($alertSql)) {
        $alertTotal += $alertRows['credit'] - $alertRows['expense_amount'];
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $title; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= $redirect; ?>plugins/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= $redirect; ?>dist/css/adminlte.css">
    <!-- iCheck -->
    <!-- Select2 -->
    <link rel="stylesheet" href="<?= $redirect; ?>plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?= $redirect; ?>plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="<?= $redirect; ?>plugins/iCheck/flat/blue.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="<?= $redirect; ?>plugins/morris/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="<?= $redirect; ?>plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="<?= $redirect; ?>plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="<?= $redirect; ?>plugins/daterangepicker/daterangepicker-bs3.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="<?= $redirect; ?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <!-- DropZone -->
    <link rel="stylesheet" href="<?= $redirect; ?>plugins/dropzone/dropzone.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" href="<?= $redirect; ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= $redirect; ?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= $redirect; ?>plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= $redirect; ?>sweetalert.min.css" />
    <link rel="stylesheet" href="<?= $redirect; ?>build/css/intlTelInput.css">
    <script src="<?= $redirect; ?>plugins/jquery/jquery.min.js"></script>
    <script src="<?= $redirect; ?>sweetalert.min.js"></script>

    <?php
    if ($alertTotal <= 0) {
        $getAlert = $conn->query("SELECT status FROM balance_alert WHERE user_id = " . $_SESSION['user_id']);
        $rowAlert = mysqli_fetch_assoc($getAlert);
        if ($rowAlert['status'] != 1) {
            include 'alert.php';
            if (isset($_POST['saveAlert'])) {
                $user_id = $_SESSION['user_id'];
                $status = 1;
                $balanceSql = $conn->query("INSERT INTO balance_alert(`user_id`, `status`) VALUES('$user_id', '$status')");
                if ($balanceSql) {
                    echo "<script>window.open('dashboard', '_self')</script>";
                } else {
                    echo "<script>window.open('logout.php', '_self')</script>";
                }
            }
            if (isset($_POST['cancelAlert'])) {
                echo "<script>window.open('logout.php', '_self')</script>";
            }
            exit();
        }
    } ?>
    <style>
        .table td,
        .table th {
            padding: 0.4rem !important;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fa fa-bars"></i></a>
                </li>
            </ul>

            <!-- SEARCH FORM -->
            <form class="form-inline ml-3">

            </form>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Messages Dropdown Menu -->
                <li class="nav-item">
                    <a href="<?= $redirect; ?>logout.php" class="nav-link">
                        <i class="nav-icon fa fa-sign-out"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->