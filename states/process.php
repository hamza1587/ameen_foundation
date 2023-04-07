<?php
include('State.php');
$state = new State();

if(!empty($_POST['action']) && $_POST['action'] == 'listState') {
	$state->stateList();
}
if(!empty($_POST['action']) && $_POST['action'] == 'addState') {
	$state->addState();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getState') {
	$state->getState();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateState') {
	$state->updateState();
}
if(!empty($_POST['action']) && $_POST['action'] == 'deleteState') {
	$state->deleteState();
}

?>