<?php
include('City.php');
$city = new City();

if(!empty($_POST['action']) && $_POST['action'] == 'listCity') {
	$city->cityList();
}
if(!empty($_POST['action']) && $_POST['action'] == 'addCity') {
	$city->addCity();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getCity') {
	$city->getCity();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateCity') {
	$city->updateCity();
}
if(!empty($_POST['action']) && $_POST['action'] == 'deleteCity') {
	$city->deleteCity();
}

?>