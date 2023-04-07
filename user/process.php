<?php
include('User.php');
$user = new User();
if(!empty($_POST['action']) && $_POST['action'] == 'listUser') {
	$user->userList();
}
if(!empty($_POST['action']) && $_POST['action'] == 'addUser') {
	$user->addUser();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getUser') {
	$user->getUser();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateUser') {
	$user->updateUser();
}
if(!empty($_POST['action']) && $_POST['action'] == 'deleteUser') {
	$user->deleteUser();
}

?>