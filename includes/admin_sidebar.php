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
    include "includes/db.php";
    // If the user is not logged in redirect to the login page...
    if (!isset($_SESSION['loggedin'])) {
        @header('Location: login.php');
        exit;
    } else {
?>
        <li class="nav-item has-treeview">
            <a href="<?= $redirect; ?>users" class="nav-link <?php if ($page == "users") {
                                                        echo "active";
                                                    } ?>">
                <i class="nav-icon fa fa-users"></i>
                <p>
                    Users
                </p>
            </a>
        </li>
        <li class="nav-item has-treeview">
            <a href="<?= $redirect; ?>user-roles" class="nav-link <?php if ($page == "roles") {
                                                                echo "active";
                                                            } ?>">
                <i class="nav-icon fa fa-key"></i>
                <p>
                    User Role
                </p>
            </a>
        </li>
        <li class="nav-item has-treeview">
            <a href="<?= $redirect; ?>add-state" class="nav-link <?php if ($page == "states") {
                                                                echo "active";
                                                            } ?>">
                <i class="nav-icon fa fa-home"></i>
                <p>
                    States
                </p>
            </a>
        </li>
        <li class="nav-item has-treeview">
            <a href="<?= $redirect; ?>add-city" class="nav-link <?php if ($page == "cities") {
                                                                echo "active";
                                                            } ?>">
                <i class="nav-icon fa fa-building"></i>
                <p>
                    Cities
                </p>
            </a>
        </li>
        <li class="nav-item has-treeview">
            <a href="<?= $redirect; ?>add-balance" class="nav-link <?php if ($page == "opening_balance") {
                                                                echo "active";
                                                            } ?>">
                <i class="nav-icon fa fa-balance-scale"></i>
                <p>
                    Opening Balance
                </p>
            </a>
        </li>
        <li class="nav-item has-treeview">
            <a href="<?= $redirect; ?>loans" class="nav-link <?php if ($page == "loan_management") {
                echo "active";
            } ?>">
                <i class="nav-icon fa fa-calculator"></i>
                <p>
                    Loan Management
                </p>
            </a>
        </li>
        <li class="nav-item has-treeview">
            <a href="<?= $redirect; ?>add-membership" class="nav-link <?php if ($page == "membership") {
                echo "active";
            } ?>">
                <i class="nav-icon fa fa-users"></i>
                <p>
                    Membership System
                </p>
            </a>
        </li>
        <li class="nav-item has-treeview">
            <a href="<?= $redirect; ?>add-payments" class="nav-link <?php if ($page == "payments") {
                                                                echo "active";
                                                            } ?>">
                <i class="nav-icon fa fa-money"></i>
                <p>
                    Payments
                </p>
            </a>
        </li>
        <li class="nav-item has-treeview">
            <a href="<?= $redirect; ?>add-services" class="nav-link <?php if ($page == "service") {
                                                                echo "active";
                                                            } ?>">
                <i class="nav-icon fa fa-podcast"></i>
                <p>
                    Our Services
                </p>
            </a>
        </li>
        <li class="nav-item has-treeview">
            <a href="<?= $redirect; ?>add-accounts" class="nav-link <?php if ($page == "bank_account") {
                                                                    echo "active";
                                                                } ?>">
                <i class="nav-icon fa fa-money"></i>
                <p>
                    Cash Accounts
                </p>
            </a>
        </li>
        <li class="nav-item has-treeview">
            <a href="<?= $redirect; ?>add-donation-type" class="nav-link <?php if ($page == "donation_type") {
                                                                echo "active";
                                                            } ?>">
                <i class="nav-icon fa fa-wheelchair"></i>
                <p>
                    Donation Types
                </p>
            </a>
        </li>
        <li class="nav-item has-treeview">
            <a href="<?= $redirect; ?>add-donation" class="nav-link <?php if ($page == "donation") {
                                                            echo "active";
                                                        } ?>">
                <i class="nav-icon fa fa-wheelchair-alt"></i>
                <p>
                    Donations
                </p>
            </a>
        </li>
        <li class="nav-item has-treeview">
            <a href="<?= $redirect; ?>add-helping-category" class="nav-link <?php if ($page == "project_expense") {
                                                                    echo "active";
                                                                } ?>">
                <i class="nav-icon fa fa-sitemap"></i>
                <p>
                    Helping Categories
                </p>
            </a>
        </li>
        <li class="nav-item has-treeview">
            <a href="<?= $redirect; ?>add-expense-type" class="nav-link <?php if ($page == "expense_type") {
                                                            echo "active";
                                                        } ?>">
                <i class="nav-icon fa fa-rocket"></i>
                <p>
                    Expense Type
                </p>
            </a>
        </li>
        <li class="nav-item has-treeview">
            <a href="<?= $redirect; ?>add-expense" class="nav-link <?php if ($page == "expenditure") {
                                                                echo "active";
                                                            } ?>">
                <i class="nav-icon fa fa-line-chart"></i>
                <p>
                    Expenses
                </p>
            </a>
        </li>
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link <?php if ($page == "report") {
                                            echo "active";
                                        } ?>">
                <i class="nav-icon fa fa-print"></i>
                <p>
                    Reports
                    <i class="fa fa-angle-left right"></i>
                    <span class="badge badge-info right"></span>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="<?= $redirect; ?>loan-report" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p> Loan Report </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= $redirect; ?>donation-report" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p> Donation Report </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= $redirect; ?>expense-report" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p> Expense Report </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= $redirect; ?>helping-category-report" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p> Helping Categories Report </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= $redirect; ?>payment-report" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p> Payment Report </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= $redirect; ?>member-report" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p> Membership Report </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= $redirect; ?>account-report" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p> Cash Account Report </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= $redirect; ?>balance-report" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p> Balance Sheet Report </p>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item has-treeview">
            <a href="<?= $redirect; ?>system-backup" class="nav-link <?php if ($page == "backup") {
                                            echo "active";
                                        } ?>">
                <i class="nav-icon fa fa-database"></i>
                <p>
                    Backup
                </p>
            </a>
        </li>
         <li class="nav-item has-treeview">
            <a href="#" class="nav-link <?php if ($page == "settings") {
                                            echo "active";
                                        } ?>">
                <i class="nav-icon fa fa-cogs"></i>
                <p>
                    Settings
                    <i class="fa fa-angle-left right"></i>
                    <span class="badge badge-info right"></span>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="<?= $redirect; ?>profile" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p> Profile Settings </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= $redirect; ?>system-settings" class="nav-link">
                        <i class="fa fa-circle-o nav-icon"></i>
                        <p> System Settings </p>
                    </a>
                </li>
            </ul>
        </li>
<?php }
} ?>