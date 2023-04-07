<?php
include('UserRole.php');
$role = new UserRole();

if(!empty($_POST['action']) && $_POST['action'] == 'listRole') {
	$role->roleList();
}
if(!empty($_POST['action']) && $_POST['action'] == 'addRole') {
	$role->addRole();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getRole') {
	$role->getRole();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateRole') {
	$role->updateRole();
}
if(!empty($_POST['action']) && $_POST['action'] == 'deleteRole') {
	$role->deleteRole();
}

?>