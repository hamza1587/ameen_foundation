<?php
include('Payments.php');
$payments = new Payments();
if(!empty($_POST['action']) && $_POST['action'] == 'listPayment') {
	$payments->paymentsList();
}
if(!empty($_POST['action']) && $_POST['action'] == 'addPayment') {
	$payments->addPayment();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getPayment') {
	$payments->getPayment();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updatePayment') {
	$payments->updatePayment();
}
if(!empty($_POST['action']) && $_POST['action'] == 'deletePayment') {
	$payments->deletePayment();
}
?>