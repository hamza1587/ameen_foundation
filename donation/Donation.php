<?php
require('../config.php');
require('../API/api.php');
session_start();
class Donation extends Dbconfig {	
    protected $hostName;
    protected $userName;
    protected $password;
	protected $dbName;
	private $donationTable = 'donations';
	private $dbConnect = false;
    public function __construct(){
        if(!$this->dbConnect){ 		
			$database = new dbConfig();            
            $this -> hostName = $database -> serverName;
            $this -> userName = $database -> userName;
            $this -> password = $database ->password;
			$this -> dbName = $database -> dbName;			
            $conn = new mysqli($this->hostName, $this->userName, $this->password, $this->dbName);
            if($conn->connect_error){
                die("Error failed to connect to MySQL: " . $conn->connect_error);
            } else{
                $this->dbConnect = $conn;
            }
        }
    }
	private function getData($sqlQuery) {
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if(!$result){
			die('Error in query: '. mysqli_error());
		}
		$data= array();
		while ($row = mysqli_fetch_array($result)) {
			$data[]=$row;            
		}
		return $data;
	}
	private function getNumRows($sqlQuery) {
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if(!$result){
			die('Error in query: '. mysqli_error());
		}
		$numRows = mysqli_num_rows($result);
		return $numRows;
	}
	public function donationList(){
        $sqlQuery1 = "SELECT isAdmin,role_id FROM users WHERE user_id = '".$_SESSION['user_id']."'";
        $result1= mysqli_query($this->dbConnect, $sqlQuery1);
        $row1 = mysqli_fetch_array($result1);
        if($row1['isAdmin'] != 1 && $row1['role_id'] != 3){
            $sqlQuery = "SELECT * FROM ".$this->donationTable." INNER JOIN bank_accounts ON donations.bank_acc_id = bank_accounts.bank_acc_id INNER JOIN users ON donations.user_id = users.user_id WHERE donations.user_id = '".$_SESSION['user_id']."'";
        }else{
            $sqlQuery = "SELECT * FROM ".$this->donationTable." INNER JOIN bank_accounts ON donations.bank_acc_id = bank_accounts.bank_acc_id INNER JOIN users ON donations.user_id = users.user_id INNER JOIN roles ON users.role_id = roles.role_id ";
        }
		if(!empty($_POST["search"]["value"])){
            if($row1['isAdmin'] != 1){
			    $sqlQuery .= 'and (donator_name LIKE "%'.$_POST["search"]["value"].'%" ';
            }else {
                $sqlQuery .= 'where(donator_name LIKE "%'.$_POST["search"]["value"].'%" ';
                $sqlQuery .= ' OR phone_number LIKE "%' . $_POST["search"]["value"] . '%" ';
                $sqlQuery .= ' OR bank_acc_name LIKE "%' . $_POST["search"]["value"] . '%" ';
                $sqlQuery .= ' OR total LIKE "%' . $_POST["search"]["value"] . '%" ';
                $sqlQuery .= ' OR date LIKE "%' . $_POST["search"]["value"] . '%" ) ';
            }
		}
		if(!empty($_POST["order"])){
			$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		} else {
			$sqlQuery .= 'ORDER BY donation_id DESC ';
		}
		if($_POST["length"] != -1){
			$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}	
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$numRows = mysqli_num_rows($result);
		
		$sqlQueryTotal = "SELECT * FROM ".$this->donationTable;
		$resultTotal = mysqli_query($this->dbConnect, $sqlQueryTotal);
		$numRowsTotal = mysqli_num_rows($resultTotal);

		$donationDate = array();
		$i = 1;	
		while( $donation = mysqli_fetch_assoc($result) ) {		
			$donationRows = array();			
			$donationRows[] = $i++;		
			$donationRows[] = ucfirst($donation['donator_name']);
			$donationRows[] = $donation['phone_number'];
			$donationRows[] = $donation['bank_acc_name'];
			$donationRows[] = number_format($donation['total'], 2);
			$donationRows[] = $donation['date'];
			$donationRows[] = $donation['name'];
			$donationRows[] = $donation['role_name'];
            $donationRows[] = '<a href="donation-invoice/'.urlencode($donation["donation_id"]).'" target="_blank" class="btn btn-primary btn-sm generate"><i class="fa fa-print"></i></a>';
            $donationRows[] = '<button type="button" name="update" id="'.$donation["donation_id"].'" class="btn btn-warning btn-sm update"><i class="fa fa-edit"></i></button>
						  <button type="button" name="delete" id="'.$donation["donation_id"].'" class="btn btn-danger btn-sm delete" ><i class="fa fa-trash"></i></button>';
			$donationDate[] = $donationRows;
		}
		$output = array(
			"draw"	=>	intval($_POST["draw"]),			
			"iTotalRecords"	=> 	$numRows,
			"iTotalDisplayRecords"	=>  $numRowsTotal,
			"data"	=> 	$donationDate
		);
		echo json_encode($output);
	}
	public function getDonation(){
		if($_POST["editId"]) {
			$sqlQuery = "
				SELECT * FROM ".$this->donationTable." 
				WHERE donation_id = '".$_POST["editId"]."'";
			$result = mysqli_query($this->dbConnect, $sqlQuery);	
			$row = mysqli_fetch_array($result);
			echo json_encode($row);
		}
	}
	public function updateDonation(){
		$date = date('Y-m-d H:i:s');
		if($_POST['editId']) {	
			$updateQuery = "UPDATE ".$this->donationTable." 
			SET receipt_no = '".$_POST["receipt_no"]."', donator_name = '".$_POST["donator_name"]."', phone_number = '".$_POST["phone_number"]."', total_amount_num = '".$_POST["total_amount_num"]."', total_amount_words = '".$_POST["total_amount_words"]."', amount_type = '".$_POST["amount_type"]."', bank_acc_id = '".$_POST["bank_acc_id"]."' , income_source_id = '".$_POST["income_source_id"]."', cheque_no = '".$_POST["cheque_no"]."' , total = '".$_POST["total"]."', date = '".$_POST["date"]."', description = '".$_POST["description"]."', address = '".$_POST["address"]."' , updated_at = '".$date."'
			WHERE donation_id ='".$_POST["editId"]."'";
			$isUpdated = mysqli_query($this->dbConnect, $updateQuery);		
		}	
	}
	public function addDonation(){
		$date = date('Y-m-d H:i:s');
		$phone = ltrim(str_replace(' ', '', $_POST['phone_number']), '+0');
		$text = "Thank you for your Donation ".$_POST["total"]." PKR to AMEEN FOUNDATION.Your donation is entrusted to us. You will be highly appreciated and hope you will be donated us again.";
		$insertQuery = "INSERT INTO ".$this->donationTable." (receipt_no, donator_name, phone_number, total_amount_num, total_amount_words, amount_type, bank_acc_id, income_source_id, cheque_no, total, date, description, address, user_id, created_at)
			VALUES ('".$_POST["receipt_no"]."', '".$_POST["donator_name"]."', '".$_POST["phone_number"]."', '".$_POST["total_amount_num"]."', '".$_POST["total_amount_words"]."', '".$_POST["amount_type"]."' , '".$_POST["bank_acc_id"]."', '".$_POST["income_source_id"]."', '".$_POST["cheque_no"]."', '".$_POST["total"]."', '".$_POST["date"]."', '".$_POST["description"]."', '".$_POST["address"]."' , '".$_POST["user_id"]."' , '".$date."')";
		$isUpdated = mysqli_query($this->dbConnect, $insertQuery);
		if($isUpdated == TRUE)
		{
			API($phone, $text);
		}		
	}
	public function deleteDonation(){
		if($_POST["editId"]) {
			$sqlDelete = "
				DELETE FROM ".$this->donationTable."
				WHERE donation_id = '".$_POST["editId"]."'";		
			mysqli_query($this->dbConnect, $sqlDelete);		
		}
	}
}
?>