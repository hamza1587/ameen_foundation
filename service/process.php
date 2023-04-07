<?php
include('Service.php');
$service = new Service();
if(!empty($_POST['action']) && $_POST['action'] == 'listService') {
	$service->ServiceList();
}
if(!empty($_POST['action']) && $_POST['action'] == 'addService') {
	$service->addService();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getService') {
	$service->getService();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateService') {
	$service->updateService();
}
if(!empty($_POST['action']) && $_POST['action'] == 'deleteService') {
	$service->deleteService();
}
?>