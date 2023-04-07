<?php
include('Membership.php');
$membership = new Membership();
if(!empty($_POST['action']) && $_POST['action'] == 'listMembership') {
    $membership->membershipList();
}
if(!empty($_POST['action']) && $_POST['action'] == 'addMembership') {
    $membership->addMembership();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getMembership') {
    $membership->getMembership();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateMembership') {
    $membership->updateMembership();
}
if(!empty($_POST['action']) && $_POST['action'] == 'deleteMembership') {
    $membership->deleteMembership();
}
?>