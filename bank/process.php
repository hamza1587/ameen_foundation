<?php
include('Bank.php');
$bank = new Bank();
if(!empty($_POST['action']) && $_POST['action'] == 'listBank') {
	$bank->BankList();
}
if(!empty($_POST['action']) && $_POST['action'] == 'addBank') {
	$bank->addBank();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getBank') {
	$bank->getBank();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateBank') {
	$bank->updateBank();
}
if(!empty($_POST['action']) && $_POST['action'] == 'deleteBank') {
	$bank->deleteBank();
}
?>