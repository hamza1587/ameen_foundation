<div class="row">
<?php
$sql = $conn->query('SELECT module_name, module_icon, permissions FROM roles INNER JOIN users ON users.role_id = roles.role_id INNER JOIN permission ON permission.role_id = roles.role_id INNER JOIN modules ON modules.module_id = permission.module_id WHERE users.user_id = ' .$user_id. " AND permissions = '1'");
if (mysqli_num_rows($sql) > 0) {
    while ($row_permissions = $sql->fetch_assoc()) {
        $str = str_replace("_"," ",$row_permissions['module_name']);
        $link = "";
        $total = 0;
        if($row_permissions['module_name'] == "users"){
            $link = "users";
            $active = "donation_type";
        }else if($row_permissions['module_name'] == "roles"){
            $link = "user-roles";
            $active = "roles";
        }else if($row_permissions['module_name'] == "services"){
            $link = "add-services";
            $active = "service";
            $sql_service = $conn->query("SELECT COUNT(service_id)'total' FROM services");
            $row_service = $sql_service->fetch_assoc();
            $total = $row_service['total'];
        }else if($row_permissions['module_name'] == "cash_accounts"){
            $link = "add-accounts";
            $active = "bank_account";
            $sql_bank_accounts = $conn->query("SELECT COUNT(bank_acc_id)'total' FROM bank_accounts");
            $row_bank_accounts = $sql_bank_accounts->fetch_assoc();
            $total = $row_bank_accounts['total'];
        }else if($row_permissions['module_name'] == "donation_type"){
            $link = "add-donation-type";
            $active = "donation_type";
            $sql_income_source = $conn->query("SELECT COUNT(income_source_id)'total' FROM income_source");
            $row_income_source = $sql_income_source->fetch_assoc();
            $total = $row_income_source['total'];
        }else if($row_permissions['module_name'] == "donations"){
            $link = "add-donation";
            $active = "donation";
            $sql_donations = $conn->query("SELECT COUNT(donation_id)'total' FROM donations");
            $row_donations = $sql_donations->fetch_assoc();
            $total = $row_donations['total'];
        }else if($row_permissions['module_name'] == "expense_type"){
            $link = "add-expense-type";
            $active = "expense_type";
            $sql_expense_type = $conn->query("SELECT COUNT(expense_id)'total' FROM expenses");
            $row_expense_type = $sql_expense_type->fetch_assoc();
            $total = $row_expense_type['total'];
        }else if($row_permissions['module_name'] == "expense"){
            $link = "add-expense";
            $active = "expenditure";
            $sql_expense = $conn->query("SELECT COUNT(exp_id)'total' FROM expense");
            $row_expense = $sql_expense->fetch_assoc();
            $total = $row_expense['total'];
        }else if($row_permissions['module_name'] == "project_expense"){
            $link = "add-helping-category";
            $active = "project_expense";
            $sql_project_expense = $conn->query("SELECT COUNT(project_expense_id)'total' FROM project_expense");
            $row_project_expense = $sql_project_expense->fetch_assoc();
            $total = $row_project_expense['total'];
        }else if($row_permissions['module_name'] == "loan_management"){
            $link = "loans";
            $active = "loan_management";
            $sql_loan_management = $conn->query("SELECT COUNT(loan_id)'total' FROM loans");
            $row_loan_management = $sql_loan_management->fetch_assoc();
            $total = $row_loan_management['total'];
        }else if($row_permissions['module_name'] == "opening_balance"){
            $link = "add-balance";
            $active = "opening_balance";
            $sql_opening_balance = $conn->query("SELECT COUNT(ob_id)'total' FROM opening_balance");
            $row_opening_balance = $sql_opening_balance->fetch_assoc();
            $total = $row_opening_balance['total'];
        }else if($row_permissions['module_name'] == "membership_system"){
            $link = "add-membership";
            $active = "membership_system";
            $sql_membership_system = $conn->query("SELECT COUNT(membership_id)'total' FROM membership");
            $row_membership_system = $sql_membership_system->fetch_assoc();
            $total = $row_membership_system['total'];
        }
?>
    <div class="col-lg-3 col-6" style="<?php if ($row_permissions['module_name'] == "reports") { echo "display:none"; }else{ echo "";} ?>">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $total;?></h3>

                <p><?= ucfirst($str); ?></p>
            </div>
            <div class="icon">
                <?= $row_permissions['module_icon']; ?>
            </div>
            <a href="<?= $link;?>" class="small-box-footer"><?= ucfirst($str); ?> <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
<?php }}?>
</div>