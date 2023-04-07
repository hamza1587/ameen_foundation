<?php
include('OB.php');
$ob = new OB();
if(!empty($_POST['action']) && $_POST['action'] == 'listOB') {
	$ob->OBList();
}
if(!empty($_POST['action']) && $_POST['action'] == 'addOB') {
	$ob->addOB();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getOB') {
	$ob->getOB();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateOB') {
	$ob->updateOB();
}
if(!empty($_POST['action']) && $_POST['action'] == 'deleteOB') {
	$ob->deleteOB();
}
?>