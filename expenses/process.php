<?php
include('Expense.php');
$expense = new Expense();
if(!empty($_POST['action']) && $_POST['action'] == 'listExpense') {
	$expense->expenseList();
}
if(!empty($_POST['action']) && $_POST['action'] == 'addExpense') {
	$expense->addExpense();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getExpense') {
	$expense->getExpense();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateExpense') {
	$expense->updateExpense();
}
if(!empty($_POST['action']) && $_POST['action'] == 'deleteExpense') {
	$expense->deleteExpense();
}
?>