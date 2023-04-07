<?php
include('Donation.php');
$donation = new Donation();
if(!empty($_POST['action']) && $_POST['action'] == 'listDonation') {
	$donation->donationList();
}
if(!empty($_POST['action']) && $_POST['action'] == 'addDonation') {
	$donation->addDonation();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getDonation') {
	$donation->getDonation();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateDonation') {
	$donation->updateDonation();
}
if(!empty($_POST['action']) && $_POST['action'] == 'deleteDonation') {
	$donation->deleteDonation();
}
?>