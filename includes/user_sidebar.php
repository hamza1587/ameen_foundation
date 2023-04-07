<?php

    @session_start();
    include "includes/db.php";
    // If the user is not logged in redirect to the login page...
    if (!isset($_SESSION['loggedin'])) {
        @header('Location: login.php');
        exit;
    } else {
        $sql = $conn->query('SELECT modules.module_id,module_name, module_icon, permissions FROM roles INNER JOIN users ON users.role_id = roles.role_id INNER JOIN permission ON permission.role_id = roles.role_id INNER JOIN modules ON modules.module_id = permission.module_id WHERE users.user_id = ' . $user_id . " AND permissions = '1' ORDER BY modules.module_id ASC");
        if (mysqli_num_rows($sql) > 0) {
            while ($row_permissions = $sql->fetch_assoc()) {
                $str = str_replace("_", " ", $row_permissions['module_name']);
                $link = "";
                $active = "";
                if ($row_permissions['module_name'] == "users") {
                    $link = "users";
                    $active = "users";
                    $sql_users = $conn->query("SELECT module_id FROM modules WHERE module_name = 'users'");
                    $row_users = $sql_users->fetch_assoc();
                    $sql_users1 = $conn->query("SELECT permissions FROM permission WHERE module_id = ".$row_users['module_id']);
                    $row_users1 = $sql_users1->fetch_assoc();
                    if($row_users1['permissions']){
                        $users_access = TRUE;
                    }else{
                        $users_access = FALSE;
                    }
                }
                if ($row_permissions['module_name'] == "roles") {
                    $link = "user-roles";
                    $active = "roles";
                    $sql_roles = $conn->query("SELECT module_id FROM modules WHERE module_name = 'roles'");
                    $row_roles = $sql_roles->fetch_assoc();
                    $sql_roles1 = $conn->query("SELECT permissions FROM permission WHERE module_id = ".$row_roles['module_id']);
                    $row_roles1 = $sql_roles1->fetch_assoc();
                    if($row_roles1['permissions']){
                        $roles_access = TRUE;
                    }else{
                        $roles_access = FALSE;
                    }
                }
                if ($row_permissions['module_name'] == "services") {
                    $link = "add-services";
                    $active = "service";
                    $sql_services = $conn->query("SELECT module_id FROM modules WHERE module_name = 'services'");
                    $row_services = $sql_services->fetch_assoc();
                    $sql_services1 = $conn->query("SELECT permissions FROM permission WHERE module_id = ".$row_services['module_id']);
                    $row_services1 = $sql_services1->fetch_assoc();
                    if($row_services1['permissions']){
                        $services_access = TRUE;
                    }else{
                        $services_access = FALSE;
                    }
                }
                if ($row_permissions['module_name'] == "cash_accounts") {
                    $link = "add-accounts";
                    $active = "bank_account";
                    $sql_cash_accounts = $conn->query("SELECT module_id FROM modules WHERE module_name = 'cash_accounts'");
                    $row_cash_accounts = $sql_cash_accounts->fetch_assoc();
                    $sql_cash_accounts1 = $conn->query("SELECT permissions FROM permission WHERE module_id = ".$row_cash_accounts['module_id']);
                    $row_cash_accounts1 = $sql_cash_accounts1->fetch_assoc();
                    if($row_cash_accounts1['permissions']){
                        $cash_accounts_access = TRUE;
                    }else{
                        $cash_accounts_access = FALSE;
                    }
                }
                if ($row_permissions['module_name'] == "donation_type") {
                    $link = "add-donation-type";
                    $active = "donation_type";
                    $sql_donation_type = $conn->query("SELECT module_id FROM modules WHERE module_name = 'donation_type'");
                    $row_donation_type = $sql_donation_type->fetch_assoc();
                    $sql_donation_type1 = $conn->query("SELECT permissions FROM permission WHERE module_id = ".$row_donation_type['module_id']);
                    $row_donation_type1 = $sql_donation_type1->fetch_assoc();
                    if($row_donation_type1['permissions']){
                        $donation_type_access = TRUE;
                    }else{
                        $donation_type_access = FALSE;
                    }
                }
                if ($row_permissions['module_name'] == "donations") {
                    $link = "add-donation";
                    $active = "donation";
                    $sql_donations = $conn->query("SELECT module_id FROM modules WHERE module_name = 'donations'");
                    $row_donations = $sql_donations->fetch_assoc();
                    $sql_donations1 = $conn->query("SELECT permissions FROM permission WHERE module_id = ".$row_donations['module_id']);
                    $row_donations1 = $sql_donations1->fetch_assoc();
                    if($row_donations1['permissions']){
                        $donation_access = TRUE;
                    }else{
                        $donation_access = FALSE;
                    }
                }
                if ($row_permissions['module_name'] == "expense_type") {
                    $link = "add-expense-type";
                    $active = "expense_type";
                    $sql_expense_type = $conn->query("SELECT module_id FROM modules WHERE module_name = 'expense_type'");
                    $row_expense_type = $sql_expense_type->fetch_assoc();
                    $sql_expense_type1 = $conn->query("SELECT permissions FROM permission WHERE module_id = ".$row_expense_type['module_id']);
                    $row_expense_type1 = $sql_expense_type1->fetch_assoc();
                    if($row_expense_type1['permissions']){
                        $expense_type_access = TRUE;
                    }else{
                        $expense_type_access = FALSE;
                    }
                }
                if ($row_permissions['module_name'] == "expense") {
                    $link = "add-expense";
                    $active = "expenditure";
                    $sql_expense = $conn->query("SELECT module_id FROM modules WHERE module_name = 'expense'");
                    $row_expense = $sql_expense->fetch_assoc();
                    $sql_expense1 = $conn->query("SELECT permissions FROM permission WHERE module_id = ".$row_expense['module_id']);
                    $row_expense1 = $sql_expense1->fetch_assoc();
                    if($row_expense1['permissions']){
                        $expense_access = TRUE;
                    }else{
                        $expense_access = FALSE;
                    }
                }
                if ($row_permissions['module_name'] == "project_expense") {
                    $link = "add-helping-category";
                    $active = "project_expense";
                    $sql_project_expense = $conn->query("SELECT module_id FROM modules WHERE module_name = 'project_expense'");
                    $row_project_expense = $sql_project_expense->fetch_assoc();
                    $sql_project_expense1 = $conn->query("SELECT permissions FROM permission WHERE module_id = ".$row_project_expense['module_id']);
                    $row_project_expense1 = $sql_project_expense1->fetch_assoc();
                    if($row_project_expense1['permissions']){
                        $project_expense_access = TRUE;
                    }else{
                        $project_expense_access = FALSE;
                    }
                }
                if ($row_permissions['module_name'] == "reports") {
                    $link = "#";
                    $active = "report";
                    $sql_reports = $conn->query("SELECT module_id FROM modules WHERE module_name = 'reports'");
                    $row_reports = $sql_reports->fetch_assoc();
                    $sql_reports1 = $conn->query("SELECT permissions FROM permission WHERE module_id = ".$row_reports['module_id']);
                    $row_reports1 = $sql_reports1->fetch_assoc();
                    if($row_reports1['permissions']){
                        $reports_access = TRUE;
                    }else{
                        $reports_access = FALSE;
                    }
                }
                if ($row_permissions['module_name'] == "settings") {
                    $link = "profile";
                    $active = "settings";
                    $sql_settings = $conn->query("SELECT module_id FROM modules WHERE module_name = 'settings'");
                    $row_settings = $sql_settings->fetch_assoc();
                    $sql_settings1 = $conn->query("SELECT permissions FROM permission WHERE module_id = ".$row_settings['module_id']);
                    $row_settings1 = $sql_settings1->fetch_assoc();
                    if($row_settings1['permissions']){
                        $settings_access = TRUE;
                    }else{
                        $settings_access = FALSE;
                    }
                }
                if ($row_permissions['module_name'] == "loan_management") {
                    $link = "loans";
                    $active = "loan_management";
                    $sql_loan_management = $conn->query("SELECT module_id FROM modules WHERE module_name = 'loan_management'");
                    $row_loan_management = $sql_loan_management->fetch_assoc();
                    $sql_loan_management1 = $conn->query("SELECT permissions FROM permission WHERE module_id = ".$row_loan_management['module_id']);
                    $row_loan_management1 = $sql_loan_management1->fetch_assoc();
                    if($row_loan_management1['permissions']){
                        $loan_management_access = TRUE;
                    }else{
                        $loan_management_access = FALSE;
                    }
                }

                if ($row_permissions['module_name'] == "opening_balance") {
                    $link = "add-balance";
                    $active = "opening_balance";
                    $sql_opening_balance = $conn->query("SELECT module_id FROM modules WHERE module_name = 'opening_balance'");
                    $row_opening_balance = $sql_opening_balance->fetch_assoc();
                    $sql_opening_balance1 = $conn->query("SELECT permissions FROM permission WHERE module_id = ".$row_opening_balance['module_id']);
                    $row_opening_balance1 = $sql_opening_balance1->fetch_assoc();
                    if($row_opening_balance1['permissions']){
                        $opening_balance_access = TRUE;
                    }else{
                        $opening_balance_access = FALSE;
                    }
                }

                if ($row_permissions['module_name'] == "membership_system") {
                    $link = "add-membership";
                    $active = "membership";
                    $sql_membership_system = $conn->query("SELECT module_id FROM modules WHERE module_name = 'membership_system'");
                    $row_membership_system = $sql_membership_system->fetch_assoc();
                    $sql_membership_system1 = $conn->query("SELECT permissions FROM permission WHERE module_id = ".$row_membership_system['module_id']);
                    $row_membership_system1 = $sql_membership_system1->fetch_assoc();
                    if($row_membership_system1['permissions']){
                        $membership_system_access = TRUE;
                    }else{
                        $membership_system_access = FALSE;
                    }
                }

                if ($row_permissions['module_name'] == "payments") {
                    $link = "add-payments";
                    $active = "payments";
                    $sql_payments = $conn->query("SELECT module_id FROM modules WHERE module_name = 'payments'");
                    $row_payments = $sql_payments->fetch_assoc();
                    $sql_payments1 = $conn->query("SELECT permissions FROM permission WHERE module_id = ".$row_payments['module_id']);
                    $row_payments1 = $sql_payments1->fetch_assoc();
                    if($row_payments1['permissions']){
                        $payments_access = TRUE;
                    }else{
                        $payments_access = FALSE;
                    }
                }


                ?>
                <li class="nav-item has-treeview">
                    <a href="<?= $link; ?>" class="nav-link <?php if ($page == $active) {
                                                                echo "active";
                                                            } ?>" style="<?php if ($row_permissions['module_name'] == "reports" || $row_permissions['module_name'] == "loan_management") { echo "display:none"; } ?>">
                        <?= $row_permissions['module_icon']; ?>
                        <p>
                            <?= ucfirst($str); ?>
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="loan_management.php" class="nav-link <?php if ($page == $active) {
                        echo "active";
                    } ?>" style="<?php if ($row_permissions['module_name'] == "loan_management") { echo ""; }else{ echo "display:none";} ?>">
                        <?= $row_permissions['module_icon']; ?>
                        <p>
                            <?= ucfirst($str); ?>
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link <?php if ($page == $active) {
                        echo "active";
                    } ?>" style="<?php if ($row_permissions['module_name'] == "reports") { echo ""; }else{ echo "display:none";} ?>">
                        <?= $row_permissions['module_icon']; ?>
                        <p>
                            <?= ucfirst($str); ?>
                            <i class="fa fa-angle-left right"></i>
                            <span class="badge badge-info right"></span>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="loan-report" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p> Loan Report </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="donation-report" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p> Donation Report </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="expense-report" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p> Expense Report </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="helping-category-report" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p> Helping Category Report </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="payment-report" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p> Payment Report </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="member-report" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p> Membership Report </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="account-report" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p> Cash Account Report </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="balance-report" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p> Balance Sheet Report </p>
                            </a>
                        </li>
                    </ul>
                </li>
<?php }?>
<?php } }  ?>