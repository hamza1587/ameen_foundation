<?php
require('../config.php');
class Payments extends Dbconfig {	
    protected $hostName;
    protected $userName;
    protected $password;
	protected $dbName;
	private $paymentTable = 'payments';
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
			die('Error in query: '. mysqli_error($this->dbConnect));
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
			die('Error in query: '. mysqli_error($this->dbConfig()));
		}
		$numRows = mysqli_num_rows($result);
		return $numRows;
	}   	
	public function paymentsList(){		
		$sqlQuery = "SELECT * FROM ".$this->paymentTable." ";
		if(!empty($_POST["search"]["value"])){
			$sqlQuery .= 'where(receipt_no LIKE "%'.$_POST["search"]["value"].'%" ';
			$sqlQuery .= ' OR date LIKE "%'.$_POST["search"]["value"].'%" ';
			$sqlQuery .= ' OR amount LIKE "%'.$_POST["search"]["value"].'%" ';
			$sqlQuery .= ' OR amount_type LIKE "%'.$_POST["search"]["value"].'%" ';			
			$sqlQuery .= ' OR account_type LIKE "%'.$_POST["search"]["value"].'%" ) ';					
		}
		if(!empty($_POST["order"])){
			$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		} else {
			$sqlQuery .= 'GROUP BY receipt_no ORDER BY receipt_no ASC ';
		}
		if($_POST["length"] != -1){
			$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}	
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$numRows = mysqli_num_rows($result);
		
		$sqlQueryTotal = "SELECT * FROM ".$this->paymentTable;
		$resultTotal = mysqli_query($this->dbConnect, $sqlQueryTotal);
		$numRowsTotal = mysqli_num_rows($resultTotal);

		$paymentDate = array();
		$i = 1;	
		$tbalance = 0;
		while( $payment = mysqli_fetch_assoc($result) ) {
		    $from = "SELECT * FROM bank_accounts WHERE bank_acc_id = '".$payment['from_account']."'";
			$from_accountSQL = mysqli_query($this->dbConnect, $from);
            $from_accountROWS = mysqli_fetch_array($from_accountSQL);
            $to = "SELECT * FROM bank_accounts WHERE bank_acc_id = '".$payment['to_account']."'";
			$to_accountSQL = mysqli_query($this->dbConnect, $to);
            $to_accountROWS = mysqli_fetch_array($to_accountSQL);
			$paymentRows = array();			
			$paymentRows[] = $i++;		
			$paymentRows[] = $payment['receipt_no'];
			$paymentRows[] = $payment['date'];
			$paymentRows[] = $from_accountROWS['bank_acc_name'];
			$paymentRows[] = $to_accountROWS['bank_acc_name'];
			$paymentRows[] = $payment['amount_type'];
			$paymentRows[] = '<a href="payment_invoice.php?invoice='.urlencode($payment["receipt_no"]).'" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>';
			$paymentRows[] = '<button type="button" name="delete" id="'.$payment["receipt_no"].'" class="btn btn-danger btn-sm delete" ><i class="fa fa-trash"></i></button>';
			$paymentDate[] = $paymentRows;
		}
		$output = array(
			"draw"	=>	intval($_POST["draw"]),			
			"iTotalRecords"	=> 	$numRows,
			"iTotalDisplayRecords"	=>  $numRowsTotal,
			"data"	=> 	$paymentDate
		);
		echo json_encode($output);
	}
	public function getPayment(){
		if($_POST["editId"]) {
			$sqlQuery = "
				SELECT * FROM ".$this->paymentTable." 
				WHERE payment_id = '".$_POST["editId"]."'";
			$result = mysqli_query($this->dbConnect, $sqlQuery);	
			$row = mysqli_fetch_array($result);
			echo json_encode($row);
		}
	}
	public function updatePayment(){
		if($_POST['editId']) {
            $date = date('Y-m-d H:i:s');
            if($_POST['account_type'] == "Credit"){
                $updateQuery = "UPDATE ".$this->paymentTable." 
                SET receipt_no = '".$_POST["receipt_no"]."', date = '".$_POST["date"]."', credit = '".$_POST["amount"]."', debit = '0', from_account = '".$_POST["from_account"]."', to_account = '".$_POST["to_account"]."', amount_type = '".$_POST["amount_type"]."', cheque_no = '".$_POST["cheque_no"]."', details = '".$_POST["details"]."', account_type = '".$_POST["account_type"]."', updated_at = '".$date."'
                WHERE payment_id ='".$_POST["editId"]."'";
            }else{
                $updateQuery = "UPDATE ".$this->paymentTable." 
                SET receipt_no = '".$_POST["receipt_no"]."', date = '".$_POST["date"]."', credit = '0', debit = '".$_POST["amount"]."', from_account = '".$_POST["from_account"]."', to_account = '".$_POST["to_account"]."', amount_type = '".$_POST["amount_type"]."', cheque_no = '".$_POST["cheque_no"]."', details = '".$_POST["details"]."', account_type = '".$_POST["account_type"]."', updated_at = '".$date."'
                WHERE payment_id ='".$_POST["editId"]."'";
            }
			$isUpdated = mysqli_query($this->dbConnect, $updateQuery);		
		}	
	}
	public function addPayment(){
		$date = date('Y-m-d H:i:s');
		$insertQuery = "";
		$insertQuery = "INSERT INTO ".$this->paymentTable." (receipt_no, date, credit, from_account, to_account, amount_type, cheque_no, details, account_type, created_at)
			VALUES ('".$_POST["receipt_no"]."', '".$_POST["date"]."', '".$_POST["amount"]."', '".$_POST["from_account"]."', '".$_POST["to_account"]."', '".$_POST["amount_type"]."', '".$_POST["cheque_no"]."', '".$_POST["details"]."', 'Credit', '".$date."')";
		
		if($_POST['from_account'] != $_POST['to_account']){
			$account_type = "Debit";
			$query = "INSERT INTO ".$this->paymentTable." (receipt_no, date, debit, from_account, to_account, amount_type, cheque_no, details, account_type, created_at)
				VALUES ('".$_POST["receipt_no"]."', '".$_POST["date"]."', '".$_POST["amount"]."', '".$_POST["from_account"]."', '".$_POST["to_account"]."', '".$_POST["amount_type"]."', '".$_POST["cheque_no"]."', '".$_POST["details"]."', '".$account_type."', '".$date."')";
		}
		mysqli_query($this->dbConnect, $query);
		$isUpdated = mysqli_query($this->dbConnect, $insertQuery);		
	}
	public function deletePayment(){
		if($_POST["editId"]) {
			$sqlDelete = "
				DELETE FROM ".$this->paymentTable."
				WHERE receipt_no = '".$_POST["editId"]."'";		
			mysqli_query($this->dbConnect, $sqlDelete);		
		}
	}
}
?>