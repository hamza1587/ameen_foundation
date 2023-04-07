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
        $bank_account_id = $_POST['bank_acc_id'];
        $sql1 = $conn->query("SELECT bank_acc_name FROM bank_accounts WHERE bank_acc_id = '$bank_account_id'");
        //get donations
        $sqlQuery = "";
        if (empty($_POST["bank_acc_id"]) && !empty($_POST["select_by"])) {
            if ($_POST["select_by"] == "DAY") {
                $sqlQuery = "Select expenses.expense_title, expense.entry_type, expense.expense_date, expense.amount_type, expense.expense_amount - expense.expense_amount AS credit, expense.expense_amount
                    From expense inner join expenses on expenses.expense_id = expense.expense_id
                    where expense.expense_for is not null AND expense.amount_type is not null AND expense.expense_amount is not null AND expense.expense_amount is not null AND expense.expense_date is not null AND expense_date > DATE_SUB(NOW(), INTERVAL 1 DAY)

                    UNION ALL

                    select donations.donator_name, donations.entry_type, donations.date, donations.amount_type, donations.total, donations.total - donations.total As debit
                    from donations
                    where donations.donator_name is not null AND donations.amount_type is not null AND donations.total is not null AND donations.date is not null AND date > DATE_SUB(NOW(), INTERVAL 1 DAY)
                    
                    UNION ALL

                    select person_information.name, loans.entry_type, loans.date, loans.payment_type, loans.credit, loans.debit
                    from loans inner join person_information on person_information.person_id = loans.person_id
                    where person_information.name is not null AND loans.payment_type is not null AND loans.credit is not null AND loans.debit is not null AND loans.date is not null AND date > DATE_SUB(NOW(), INTERVAL 1 DAY)
                    UNION ALL

                    select membership.name, membership.entry_type, membership.date, membership.amount_type, membership.fee, membership.fee - membership.fee AS debit
                    from membership
                    where membership.name is not null AND membership.amount_type is not null AND membership.fee is not null AND membership.date is not null AND date > DATE_SUB(NOW(), INTERVAL 1 DAY)

                    UNION ALL

                    select opening_balance.ob_details, opening_balance.entry_type, opening_balance.ob_date, opening_balance.amount_type, opening_balance.ob_amount, opening_balance.ob_amount - opening_balance.ob_amount as debit
                    from opening_balance
                    where opening_balance.ob_details is not null AND opening_balance.amount_type is not null AND opening_balance.ob_amount is not null AND opening_balance.ob_date is not null AND ob_date > DATE_SUB(NOW(), INTERVAL 1 DAY)

                    UNION ALL

                    select payments.details, payments.entry_type, payments.date, payments.amount_type, payments.credit ,payments.debit
                    from payments
                    where payments.details is not null AND payments.amount_type is not null AND payments.credit is not null AND payments.date is not null AND date > DATE_SUB(NOW(), INTERVAL 1 DAY) 

                    UNION ALL

                    select project_expense.person_name, project_expense.entry_type, project_expense.date, project_expense.amount_type, project_expense.donate_amount - project_expense.donate_amount as amount, project_expense.donate_amount
                    from project_expense
                    where project_expense.person_name is not null AND project_expense.amount_type is not null AND project_expense.donate_amount is not null AND project_expense.date is not null AND date > DATE_SUB(NOW(), INTERVAL 1 DAY) 
                    order by expense_date";
            } else if ($_POST["select_by"] == "WEEK") {
                $sqlQuery = "Select expenses.expense_title, expense.entry_type, expense.expense_date, expense.amount_type, expense.expense_amount - expense.expense_amount AS credit, expense.expense_amount
                    From expense inner join expenses on expenses.expense_id = expense.expense_id
                    where expense.expense_for is not null AND expense.amount_type is not null AND expense.expense_amount is not null AND expense.expense_amount is not null AND expense.expense_date is not null AND expense_date > DATE_SUB(NOW(), INTERVAL 1 WEEK)

                    UNION ALL

                    select donations.donator_name, donations.entry_type, donations.date, donations.amount_type, donations.total, donations.total - donations.total As debit
                    from donations
                    where donations.donator_name is not null AND donations.amount_type is not null AND donations.total is not null AND donations.date is not null AND date > DATE_SUB(NOW(), INTERVAL 1 WEEK)
                    
                    UNION ALL

                    select person_information.name, loans.entry_type, loans.date, loans.payment_type, loans.credit, loans.debit
                    from loans inner join person_information on person_information.person_id = loans.person_id
                    where person_information.name is not null AND loans.payment_type is not null AND loans.credit is not null AND loans.debit is not null AND loans.date is not null AND date > DATE_SUB(NOW(), INTERVAL 1 WEEK)
                    UNION ALL

                    select membership.name, membership.entry_type, membership.date, membership.amount_type, membership.fee, membership.fee - membership.fee AS debit
                    from membership
                    where membership.name is not null AND membership.amount_type is not null AND membership.fee is not null AND membership.date is not null AND date > DATE_SUB(NOW(), INTERVAL 1 WEEK)

                    UNION ALL

                    select opening_balance.ob_details, opening_balance.entry_type, opening_balance.ob_date, opening_balance.amount_type, opening_balance.ob_amount, opening_balance.ob_amount - opening_balance.ob_amount as debit
                    from opening_balance
                    where opening_balance.ob_details is not null AND opening_balance.amount_type is not null AND opening_balance.ob_amount is not null AND opening_balance.ob_date is not null AND ob_date > DATE_SUB(NOW(), INTERVAL 1 WEEK)

                    UNION ALL

                    select payments.details, payments.entry_type, payments.date, payments.amount_type, payments.credit ,payments.debit
                    from payments
                    where payments.details is not null AND payments.amount_type is not null AND payments.credit is not null AND payments.date is not null AND date > DATE_SUB(NOW(), INTERVAL 1 WEEK) 

                    UNION ALL

                    select project_expense.person_name, project_expense.entry_type, project_expense.date, project_expense.amount_type, project_expense.donate_amount - project_expense.donate_amount as amount, project_expense.donate_amount
                    from project_expense
                    where project_expense.person_name is not null AND project_expense.amount_type is not null AND project_expense.donate_amount is not null AND project_expense.date is not null AND date > DATE_SUB(NOW(), INTERVAL 1 WEEK) 
                    order by expense_date";
            } else if ($_POST["select_by"] == "MONTH") {
                $sqlQuery = "Select expenses.expense_title, expense.entry_type, expense.expense_date, expense.amount_type, expense.expense_amount - expense.expense_amount AS credit, expense.expense_amount
                    From expense inner join expenses on expenses.expense_id = expense.expense_id
                    where expense.expense_for is not null AND expense.amount_type is not null AND expense.expense_amount is not null AND expense.expense_amount is not null AND expense.expense_date is not null AND expense_date > DATE_SUB(NOW(), INTERVAL 1 MONTH)

                    UNION ALL

                    select donations.donator_name, donations.entry_type, donations.date, donations.amount_type, donations.total, donations.total - donations.total As debit
                    from donations
                    where donations.donator_name is not null AND donations.amount_type is not null AND donations.total is not null AND donations.date is not null AND date > DATE_SUB(NOW(), INTERVAL 1 MONTH)
                    
                    UNION ALL

                    select person_information.name, loans.entry_type, loans.date, loans.payment_type, loans.credit, loans.debit
                    from loans inner join person_information on person_information.person_id = loans.person_id
                    where person_information.name is not null AND loans.payment_type is not null AND loans.credit is not null AND loans.debit is not null AND loans.date is not null AND date > DATE_SUB(NOW(), INTERVAL 1 MONTH)
                    UNION ALL

                    select membership.name, membership.entry_type, membership.date, membership.amount_type, membership.fee, membership.fee - membership.fee AS debit
                    from membership
                    where membership.name is not null AND membership.amount_type is not null AND membership.fee is not null AND membership.date is not null AND date > DATE_SUB(NOW(), INTERVAL 1 MONTH)

                    UNION ALL

                    select opening_balance.ob_details, opening_balance.entry_type, opening_balance.ob_date, opening_balance.amount_type, opening_balance.ob_amount, opening_balance.ob_amount - opening_balance.ob_amount as debit
                    from opening_balance
                    where opening_balance.ob_details is not null AND opening_balance.amount_type is not null AND opening_balance.ob_amount is not null AND opening_balance.ob_date is not null AND ob_date > DATE_SUB(NOW(), INTERVAL 1 MONTH)

                    UNION ALL

                    select payments.details, payments.entry_type, payments.date, payments.amount_type, payments.credit ,payments.debit
                    from payments
                    where payments.details is not null AND payments.amount_type is not null AND payments.credit is not null AND payments.date is not null AND date > DATE_SUB(NOW(), INTERVAL 1 MONTH) 

                    UNION ALL

                    select project_expense.person_name, project_expense.entry_type, project_expense.date, project_expense.amount_type, project_expense.donate_amount - project_expense.donate_amount as amount, project_expense.donate_amount
                    from project_expense
                    where project_expense.person_name is not null AND project_expense.amount_type is not null AND project_expense.donate_amount is not null AND project_expense.date is not null AND date > DATE_SUB(NOW(), INTERVAL 1 MONTH) 
                    order by expense_date";
            } else if ($_POST['select_by'] == "CUSTOM") {
                if (!empty($_POST["from_date"]) && !empty($_POST["to_date"]) && empty($_POST["bank_acc_id"])) {
                    $sqlQuery = "Select expenses.expense_title, expense.entry_type, expense.expense_date, expense.amount_type, expense.expense_amount - expense.expense_amount AS credit, expense.expense_amount
                    From expense inner join expenses on expenses.expense_id = expense.expense_id
                    where expense.expense_for is not null AND expense.amount_type is not null AND expense.expense_amount is not null AND expense.expense_date is not null AND expense_date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "' 

                    UNION ALL

                    select donations.donator_name, donations.entry_type, donations.date, donations.amount_type, donations.total, donations.total - donations.total As debit
                    from donations
                    where donations.donator_name is not null AND donations.amount_type is not null AND donations.total is not null AND donations.date is not null AND date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "' 

                    UNION ALL

                    select person_information.name, loans.entry_type, loans.date, loans.payment_type, loans.credit, loans.debit
                    from loans inner join person_information on person_information.person_id = loans.person_id
                    where person_information.name is not null AND loans.payment_type is not null AND loans.credit is not null AND loans.debit is not null AND loans.date is not null AND date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "'

                    UNION ALL

                    select membership.name, membership.entry_type, membership.date, membership.amount_type, membership.fee, membership.fee - membership.fee AS debit
                    from membership
                    where membership.name is not null AND membership.amount_type is not null AND membership.fee is not null AND membership.date is not null AND date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "'

                    UNION ALL

                    select opening_balance.ob_details, opening_balance.entry_type, opening_balance.ob_date, opening_balance.amount_type, opening_balance.ob_amount, opening_balance.ob_amount - opening_balance.ob_amount as debit
                    from opening_balance
                    where opening_balance.ob_details is not null AND opening_balance.amount_type is not null AND opening_balance.ob_amount is not null AND opening_balance.ob_date is not null AND ob_date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "'

                    UNION ALL

                    select payments.details, payments.entry_type, payments.date, payments.amount_type, payments.credit ,payments.debit
                    from payments
                    where payments.details is not null AND payments.amount_type is not null AND payments.credit is not null AND payments.debit is not null AND payments.date is not null AND date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "'

                    UNION ALL

                    select project_expense.person_name, project_expense.entry_type, project_expense.date, project_expense.amount_type, project_expense.donate_amount - project_expense.donate_amount as amount, project_expense.donate_amount
                    from project_expense
                    where project_expense.person_name is not null AND project_expense.amount_type is not null AND project_expense.donate_amount is not null AND project_expense.date is not null AND date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "'
                    order by expense_date";
                } else if (!empty($_POST["from_date"]) && !empty($_POST["to_date"]) && !empty($_POST["bank_acc_id"])) {
                    $sqlQuery = "Select expenses.expense_title, expense.entry_type, expense.expense_date, expense.amount_type, expense.expense_amount - expense.expense_amount AS credit, expense.expense_amount
                    From expense inner join expenses on expenses.expense_id = expense.expense_id
                    where expense.expense_for is not null AND expense.amount_type is not null AND expense.expense_amount is not null AND expense.expense_amount is not null AND expense.expense_date is not null AND expense_date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "' AND expense.bank_acc_id = '$bank_account_id'

                    UNION ALL

                    select donations.donator_name, donations.entry_type, donations.date, donations.amount_type, donations.total, donations.total - donations.total As debit
                    from donations
                    where donations.donator_name is not null AND donations.amount_type is not null AND donations.total is not null AND donations.date is not null AND date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "' AND donations.bank_acc_id = '$bank_account_id'
                    
                    UNION ALL

                    select person_information.name, loans.entry_type, loans.date, loans.payment_type, loans.credit, loans.debit
                    from loans inner join person_information on person_information.person_id = loans.person_id
                    where person_information.name is not null AND loans.payment_type is not null AND loans.credit is not null AND loans.debit is not null AND loans.date is not null AND date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "' AND loans.bank_acc_id = '$bank_account_id'
                    UNION ALL

                    select membership.name, membership.entry_type, membership.date, membership.amount_type, membership.fee, membership.fee - membership.fee AS debit
                    from membership
                    where membership.name is not null AND membership.amount_type is not null AND membership.fee is not null AND membership.date is not null AND date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "' AND membership.bank_acc_id = '$bank_account_id'

                    UNION ALL

                    select opening_balance.ob_details, opening_balance.entry_type, opening_balance.ob_date, opening_balance.amount_type, opening_balance.ob_amount, opening_balance.ob_amount - opening_balance.ob_amount as debit
                    from opening_balance
                    where opening_balance.ob_details is not null AND opening_balance.amount_type is not null AND opening_balance.ob_amount is not null AND opening_balance.ob_date is not null AND ob_date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "' AND opening_balance.bank_acc_id = '$bank_account_id'

                    UNION ALL

                    select payments.details, payments.entry_type, payments.date, payments.amount_type, payments.credit ,payments.debit
                    from payments
                    where payments.details is not null AND payments.amount_type is not null AND payments.credit is not null AND payments.date is not null AND date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "' AND payments.from_account = '$bank_account_id'

                    UNION ALL

                    select project_expense.person_name, project_expense.entry_type, project_expense.date, project_expense.amount_type, project_expense.donate_amount - project_expense.donate_amount as amount, project_expense.donate_amount
                    from project_expense
                    where project_expense.person_name is not null AND project_expense.amount_type is not null AND project_expense.donate_amount is not null AND project_expense.date is not null AND date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "' AND project_expense.bank_acc_id = '$bank_account_id' 
                    order by expense_date";
                }
            }
        } else if (!empty($_POST['bank_acc_id']) && !empty($_POST['select_by'])) {
            if ($_POST["select_by"] == "DAY") {
                $sqlQuery = "Select expenses.expense_title, expense.entry_type, expense.expense_date, expense.amount_type, expense.expense_amount - expense.expense_amount AS credit, expense.expense_amount
                    From expense inner join expenses on expenses.expense_id = expense.expense_id
                    where expense.expense_for is not null AND expense.amount_type is not null AND expense.expense_amount is not null AND expense.expense_amount is not null AND expense.expense_date is not null AND expense_date > DATE_SUB(NOW(), INTERVAL 1 DAY) AND expense.bank_acc_id = '$bank_account_id'

                    UNION ALL

                    select donations.donator_name, donations.entry_type, donations.date, donations.amount_type, donations.total, donations.total - donations.total As debit
                    from donations
                    where donations.donator_name is not null AND donations.amount_type is not null AND donations.total is not null AND donations.date is not null AND date > DATE_SUB(NOW(), INTERVAL 1 DAY) AND donations.bank_acc_id = '$bank_account_id'
                    
                    UNION ALL

                    select person_information.name, loans.entry_type, loans.date, loans.payment_type, loans.credit, loans.debit
                    from loans inner join person_information on person_information.person_id = loans.person_id
                    where person_information.name is not null AND loans.payment_type is not null AND loans.credit is not null AND loans.debit is not null AND loans.date is not null AND date > DATE_SUB(NOW(), INTERVAL 1 DAY) AND loans.bank_acc_id = '$bank_account_id'
                    UNION ALL

                    select membership.name, membership.entry_type, membership.date, membership.amount_type, membership.fee, membership.fee - membership.fee AS debit
                    from membership
                    where membership.name is not null AND membership.amount_type is not null AND membership.fee is not null AND membership.date is not null AND date > DATE_SUB(NOW(), INTERVAL 1 DAY) AND membership.bank_acc_id = '$bank_account_id'

                    UNION ALL

                    select opening_balance.ob_details, opening_balance.entry_type, opening_balance.ob_date, opening_balance.amount_type, opening_balance.ob_amount, opening_balance.ob_amount - opening_balance.ob_amount as debit
                    from opening_balance
                    where opening_balance.ob_details is not null AND opening_balance.amount_type is not null AND opening_balance.ob_amount is not null AND opening_balance.ob_date is not null AND ob_date > DATE_SUB(NOW(), INTERVAL 1 DAY) AND opening_balance.bank_acc_id = '$bank_account_id'

                    UNION ALL

                    select payments.details, payments.entry_type, payments.date, payments.amount_type, payments.credit ,payments.debit
                    from payments
                    where payments.details is not null AND payments.amount_type is not null AND payments.credit is not null AND payments.date is not null AND date > DATE_SUB(NOW(), INTERVAL 1 DAY) AND payments.from_account = '$bank_account_id'

                    UNION ALL

                    select project_expense.person_name, project_expense.entry_type, project_expense.date, project_expense.amount_type, project_expense.donate_amount - project_expense.donate_amount as amount, project_expense.donate_amount
                    from project_expense
                    where project_expense.person_name is not null AND project_expense.amount_type is not null AND project_expense.donate_amount is not null AND project_expense.date is not null AND date > DATE_SUB(NOW(), INTERVAL 1 DAY) AND project_expense.bank_acc_id = '$bank_account_id'
                    order by expense_date";
            } else if ($_POST["select_by"] == "WEEK") {
                $sqlQuery = "Select expenses.expense_title, expense.entry_type, expense.expense_date, expense.amount_type, expense.expense_amount - expense.expense_amount AS credit, expense.expense_amount
                    From expense inner join expenses on expenses.expense_id = expense.expense_id
                    where expense.expense_for is not null AND expense.amount_type is not null AND expense.expense_amount is not null AND expense.expense_amount is not null AND expense.expense_date is not null AND expense_date > DATE_SUB(NOW(), INTERVAL 1 WEEK) AND expense.bank_acc_id = '$bank_account_id'

                    UNION ALL

                    select donations.donator_name, donations.entry_type, donations.date, donations.amount_type, donations.total, donations.total - donations.total As debit
                    from donations
                    where donations.donator_name is not null AND donations.amount_type is not null AND donations.total is not null AND donations.date is not null AND date > DATE_SUB(NOW(), INTERVAL 1 WEEK) AND donations.bank_acc_id = '$bank_account_id'
                    
                    UNION ALL

                    select person_information.name, loans.entry_type, loans.date, loans.payment_type, loans.credit, loans.debit
                    from loans inner join person_information on person_information.person_id = loans.person_id
                    where person_information.name is not null AND loans.payment_type is not null AND loans.credit is not null AND loans.debit is not null AND loans.date is not null AND date > DATE_SUB(NOW(), INTERVAL 1 WEEK) AND loans.bank_acc_id = '$bank_account_id'
                    UNION ALL

                    select membership.name, membership.entry_type, membership.date, membership.amount_type, membership.fee, membership.fee - membership.fee AS debit
                    from membership
                    where membership.name is not null AND membership.amount_type is not null AND membership.fee is not null AND membership.date is not null AND date > DATE_SUB(NOW(), INTERVAL 1 WEEK) AND membership.bank_acc_id = '$bank_account_id'

                    UNION ALL

                    select opening_balance.ob_details, opening_balance.entry_type, opening_balance.ob_date, opening_balance.amount_type, opening_balance.ob_amount, opening_balance.ob_amount - opening_balance.ob_amount as debit
                    from opening_balance
                    where opening_balance.ob_details is not null AND opening_balance.amount_type is not null AND opening_balance.ob_amount is not null AND opening_balance.ob_date is not null AND ob_date > DATE_SUB(NOW(), INTERVAL 1 WEEK) AND opening_balance.bank_acc_id = '$bank_account_id'

                    UNION ALL

                    select payments.details, payments.entry_type, payments.date, payments.amount_type, payments.credit ,payments.debit
                    from payments
                    where payments.details is not null AND payments.amount_type is not null AND payments.credit is not null AND payments.date is not null AND date > DATE_SUB(NOW(), INTERVAL 1 WEEK) AND payments.from_account = '$bank_account_id'

                    UNION ALL

                    select project_expense.person_name, project_expense.entry_type, project_expense.date, project_expense.amount_type, project_expense.donate_amount - project_expense.donate_amount as amount, project_expense.donate_amount
                    from project_expense
                    where project_expense.person_name is not null AND project_expense.amount_type is not null AND project_expense.donate_amount is not null AND project_expense.date is not null AND date > DATE_SUB(NOW(), INTERVAL 1 WEEK) AND project_expense.bank_acc_id = '$bank_account_id'
                    order by expense_date";
            } else if ($_POST["select_by"] == "MONTH") {
                $sqlQuery = "Select expenses.expense_title, expense.entry_type, expense.expense_date, expense.amount_type, expense.expense_amount - expense.expense_amount AS credit, expense.expense_amount
                    From expense inner join expenses on expenses.expense_id = expense.expense_id
                    where expense.expense_for is not null AND expense.amount_type is not null AND expense.expense_amount is not null AND expense.expense_amount is not null AND expense.expense_date is not null AND expense_date > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND expense.bank_acc_id = '$bank_account_id'

                    UNION ALL

                    select donations.donator_name, donations.entry_type, donations.date, donations.amount_type, donations.total, donations.total - donations.total As debit
                    from donations
                    where donations.donator_name is not null AND donations.amount_type is not null AND donations.total is not null AND donations.date is not null AND date > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND donations.bank_acc_id = '$bank_account_id'
                    
                    UNION ALL

                    select person_information.name, loans.entry_type, loans.date, loans.payment_type, loans.credit, loans.debit
                    from loans inner join person_information on person_information.person_id = loans.person_id
                    where person_information.name is not null AND loans.payment_type is not null AND loans.credit is not null AND loans.debit is not null AND loans.date is not null AND date > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND loans.bank_acc_id = '$bank_account_id'
                    UNION ALL

                    select membership.name, membership.entry_type, membership.date, membership.amount_type, membership.fee, membership.fee - membership.fee AS debit
                    from membership
                    where membership.name is not null AND membership.amount_type is not null AND membership.fee is not null AND membership.date is not null AND date > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND membership.bank_acc_id = '$bank_account_id'

                    UNION ALL

                    select opening_balance.ob_details, opening_balance.entry_type, opening_balance.ob_date, opening_balance.amount_type, opening_balance.ob_amount, opening_balance.ob_amount - opening_balance.ob_amount as debit
                    from opening_balance
                    where opening_balance.ob_details is not null AND opening_balance.amount_type is not null AND opening_balance.ob_amount is not null AND opening_balance.ob_date is not null AND ob_date > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND opening_balance.bank_acc_id = '$bank_account_id'

                    UNION ALL

                    select payments.details, payments.entry_type, payments.date, payments.amount_type, payments.credit ,payments.debit
                    from payments
                    where payments.details is not null AND payments.amount_type is not null AND payments.credit is not null AND payments.date is not null AND date > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND payments.from_account = '$bank_account_id'

                    UNION ALL

                    select project_expense.person_name, project_expense.entry_type, project_expense.date, project_expense.amount_type, project_expense.donate_amount - project_expense.donate_amount as amount, project_expense.donate_amount
                    from project_expense
                    where project_expense.person_name is not null AND project_expense.amount_type is not null AND project_expense.donate_amount is not null AND project_expense.date is not null AND date > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND project_expense.bank_acc_id = '$bank_account_id'
                    order by expense_date";
            } else if ($_POST['select_by'] == "CUSTOM") {
                if (!empty($_POST["from_date"]) && !empty($_POST["to_date"]) && !empty($_POST["bank_acc_id"])) {
                    $sqlQuery = "Select expenses.expense_title, expense.entry_type, expense.expense_date, expense.amount_type, expense.expense_amount - expense.expense_amount AS credit, expense.expense_amount
                        From expense inner join expenses on expenses.expense_id = expense.expense_id
                        where expense.expense_for is not null AND expense.amount_type is not null AND expense.expense_amount is not null AND expense.expense_amount is not null AND expense.expense_date is not null AND expense_date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "' AND bank_acc_id = '" . $_POST['bank_acc_id'] . "'

                        UNION ALL

                        select donations.donator_name, donations.entry_type, donations.date, donations.amount_type, donations.total, donations.total - donations.total As debit
                        from donations
                        where donations.donator_name is not null AND donations.amount_type is not null AND donations.total is not null AND donations.date is not null AND date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "' AND bank_acc_id = '" . $_POST['bank_acc_id'] . "'
                        
                        UNION ALL

                        select person_information.name, loans.entry_type, loans.date, loans.payment_type, loans.credit, loans.debit
                        from loans inner join person_information on person_information.person_id = loans.person_id
                        where person_information.name is not null AND loans.payment_type is not null AND loans.credit is not null AND loans.debit is not null AND loans.date is not null AND date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "' AND bank_acc_id = '" . $_POST['bank_acc_id'] . "'
                        UNION ALL

                        select membership.name, membership.entry_type, membership.date, membership.amount_type, membership.fee, membership.fee - membership.fee AS debit
                        from membership
                        where membership.name is not null AND membership.amount_type is not null AND membership.fee is not null AND membership.date is not null AND date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "' AND bank_acc_id = '" . $_POST['bank_acc_id'] . "'

                        UNION ALL

                        select opening_balance.ob_details, opening_balance.entry_type, opening_balance.ob_date, opening_balance.amount_type, opening_balance.ob_amount, opening_balance.ob_amount - opening_balance.ob_amount as debit
                        from opening_balance
                        where opening_balance.ob_details is not null AND opening_balance.amount_type is not null AND opening_balance.ob_amount is not null AND opening_balance.ob_date is not null AND ob_date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "' AND bank_acc_id = '" . $_POST['bank_acc_id'] . "'

                        UNION ALL

                        select payments.details, payments.entry_type, payments.date, payments.amount_type, payments.credit ,payments.debit
                        from payments
                        where payments.details is not null AND payments.amount_type is not null AND payments.credit is not null AND payments.debit is not null AND payments.date is not null AND payments.date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "' AND from_account = '" . $_POST['bank_acc_id'] . "' AND account_type = 'Debit'

                        UNION ALL

                        select payments.details, payments.entry_type, payments.date, payments.amount_type, payments.credit ,payments.debit
                        from payments
                        where payments.details is not null AND payments.amount_type is not null AND payments.credit is not null AND payments.debit is not null AND payments.date is not null AND payments.date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "' AND to_account = '" . $_POST['bank_acc_id'] . "' AND account_type = 'Credit'

                        UNION ALL

                        select project_expense.person_name, project_expense.entry_type, project_expense.date, project_expense.amount_type, project_expense.donate_amount - project_expense.donate_amount as amount, project_expense.donate_amount
                        from project_expense
                        where project_expense.person_name is not null AND project_expense.amount_type is not null AND project_expense.donate_amount is not null AND project_expense.date is not null AND date BETWEEN '" . $_POST['from_date'] . "' AND '" . $_POST['to_date'] . "' AND bank_acc_id = '" . $_POST['bank_acc_id'] . "' 
                        order by expense_date";
                }
            }
        } else {
            $sqlQuery = "Select expenses.expense_title, expense.entry_type, expense.expense_date, expense.amount_type, expense.expense_amount - expense.expense_amount AS credit, expense.expense_amount
                    From expense inner join expenses on expenses.expense_id = expense.expense_id
                    where expense.expense_for is not null AND expense.amount_type is not null AND expense.expense_amount is not null AND expense.expense_amount is not null AND expense.expense_date is not null

                    UNION ALL

                    select donations.donator_name, donations.entry_type, donations.date, donations.amount_type, donations.total, donations.total - donations.total As debit
                    from donations
                    where donations.donator_name is not null AND donations.amount_type is not null AND donations.total is not null AND donations.date is not null
                    
                    UNION ALL

                    select person_information.name, loans.entry_type, loans.date, loans.payment_type, loans.credit, loans.debit
                    from loans inner join person_information on person_information.person_id = loans.person_id
                    where person_information.name is not null AND loans.payment_type is not null AND loans.credit is not null AND loans.debit is not null AND loans.date is not null
                    UNION ALL

                    select membership.name, membership.entry_type, membership.date, membership.amount_type, membership.fee, membership.fee - membership.fee AS debit
                    from membership
                    where membership.name is not null AND membership.amount_type is not null AND membership.fee is not null AND membership.date is not null

                    UNION ALL

                    select opening_balance.ob_details, opening_balance.entry_type, opening_balance.ob_date, opening_balance.amount_type, opening_balance.ob_amount, opening_balance.ob_amount - opening_balance.ob_amount as debit
                    from opening_balance
                    where opening_balance.ob_details is not null AND opening_balance.amount_type is not null AND opening_balance.ob_amount is not null AND opening_balance.ob_date is not null

                    UNION ALL

                    select payments.details, payments.entry_type, payments.date, payments.amount_type, payments.credit ,payments.debit
                    from payments
                    where payments.details is not null AND payments.amount_type is not null AND payments.credit is not null AND payments.debit is not null AND payments.date is not null

                    UNION ALL

                    select project_expense.person_name, project_expense.entry_type, project_expense.date, project_expense.amount_type, project_expense.donate_amount - project_expense.donate_amount as amount, project_expense.donate_amount
                    from project_expense
                    where project_expense.person_name is not null AND project_expense.amount_type is not null AND project_expense.donate_amount is not null AND project_expense.date is not null
                    order by expense_date";
        }
        $sql = mysqli_query($conn, $sqlQuery);
        $sql1 = mysqli_query($conn, $sqlQuery);
?>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Cash Account Report</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-info btn-sm" value="print" onclick="PrintDiv();"><i class="fa fa-print"></i></button>
                </div>
            </div>
            <?php
            $total = 0;
            $credit = 0;
            $debit = 0;
            while ($row = mysqli_fetch_array($sql1)) {
                $total += $row['credit'] - $row['expense_amount'];
                $credit += $row['credit'];
                $debit += $row['expense_amount'];
            }
            ?>
            <div class="card-body" id="debit">
                <div class="form-group">
                    <div class="col-lg-12 bg-info">
                        <div class="row d-flex justify-content-between px-4">
                            <p class="mb-1 text-left">Total Credit:</p>
                            <h6 class="mb-1 text-right"><?= "+ " . number_format($credit, 2); ?></h6>
                        </div>
                        <div class="row d-flex justify-content-between px-4">
                            <p class="mb-1 text-left">Total Debit:</p>
                            <h6 class="mb-1 text-right"><?= "- " . number_format($debit, 2); ?></h6>
                        </div>
                        <div class="row d-flex justify-content-between px-4">
                            <p class="mb-1 text-left">Ending Balance:</p>
                            <h6 class="mb-1 text-right"><?= number_format($total, 2); ?></h6>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6">
                        <h3 class="card-title">
                            <?php
                            if (!empty($_POST['select_by'])) {
                                if (mysqli_num_rows($sql1) > 0) {
                                    $row1 = mysqli_fetch_assoc($sql1);
                                    echo 'Account Type: <span style="font-weight:bold">' . $row1['bank_acc_name'] . '</span>';
                                } else {
                                    echo 'Account Type: <span style="font-weight:bold">All</span>';
                                }
                            } else {
                                echo 'Account Type: <span style="font-weight:bold">All</span>';
                            }
                            ?>
                        </h3>
                    </div>
                    <div class="col-md-6 text-right">
                        <div class="card-tools" style="font-weight:bold">
                            <?php if (!empty($_POST['from_date']) && !empty($_POST['to_date'])) {
                                echo $_POST['from_date'] . " to " . $_POST['to_date'];
                            } else {
                                if ($_POST['select_by'] == "DAY") {
                                    echo "Today";
                                } else if ($_POST['select_by'] == "WEEK") {
                                    echo "Weekly";
                                } else if ($_POST['select_by'] == "MONTH") {
                                    echo "Monthly";
                                }
                            } ?>
                        </div>
                    </div>
                </div>
                <div class="form-group row ">
                    <div class="table-responsive">
                        <table id="donations" class="table table-bordered table-striped">
                            <thead class="bg-info">
                                <tr>
                                    <th>Details</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Amount Type</th>
                                    <th>Credit</th>
                                    <th>Debit</th>
                                    <th>Total Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                while ($rows = mysqli_fetch_array($sql)) {
                                    $total_balance = 0;
                                    $tbal = 0;
                                    $bal = 0;
                                    $cr = $rows['credit'];
                                    $dit= $rows['expense_amount'];
                                    $tbal.$i  = ($i+$cr -$dit); 
                                    //$total_balance = $rows['credit'] - $rows['expense_amount'];
                                    $entry_type = str_replace("_", " ", $rows['entry_type']);
                                    if($rows['entry_type'] == "project_expense"){
                                        $type = "Debit";
                                    } else if($rows['entry_type'] == "expense"){
                                        $type = "Debit";
                                    } else if($rows['entry_type'] == "membership"){
                                        $type = "Credit";
                                    } else if($rows['entry_type'] == "opening_balance"){
                                        $type = "Credit";
                                    } else if($rows['entry_type'] == "donation"){
                                        $type = "Credit";
                                    } else if($rows['entry_type'] == "payment"){
                                        $type = "Debit";
                                    } else{
                                        $type = $rows['amount_type'];
                                    }
                                ?>
                                    <tr>
                                        <?php
                                        $title = "";
                                        if ($rows['expense_title'] == "Monthly" || $rows['expense_title'] == "Registration") {
                                            $title = "Fee Type";
                                        } else {
                                            $title = $rows['expense_title'];
                                        }
                                        ?>
                                        <td><?= ucwords($rows['expense_title']); ?></td>
                                        <td><?= ucwords($entry_type); ?></td>
                                        <td><?= $rows['expense_date']; ?></td>
                                        <td><?= $type; ?></td>
                                        <td><?= number_format($rows['credit'], 2); ?></td>
                                        <td><?= number_format($rows['expense_amount'], 2); ?></td>
                                        <td><?= number_format(substr($tbal.$i, 1), 2); ?></td>
                                    </tr>
                                <?php 
                                }
                                ?>
                            </tbody>
                            <tfoot class="bg-info">
                                <tr>
                                    <th>Details</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Amount Type</th>
                                    <th>Credit</th>
                                    <th>Debit</th>
                                    <th>Total Balance</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
<?php }
} ?>