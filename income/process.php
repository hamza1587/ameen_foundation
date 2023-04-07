<?php
include('Income.php');
$income = new Income();
if(!empty($_POST['action']) && $_POST['action'] == 'listIncome') {
	$income->incomeList();
}
if(!empty($_POST['action']) && $_POST['action'] == 'addIncome') {
	$income->addIncome();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getIncome') {
	$income->getIncome();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateIncome') {
	$income->updateIncome();
}
if(!empty($_POST['action']) && $_POST['action'] == 'deleteIncome') {
	$income->deleteIncome();
}
?>