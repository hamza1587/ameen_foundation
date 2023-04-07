<?php
require('../config.php');
class OB extends Dbconfig {	
    protected $hostName;
    protected $userName;
    protected $password;
	protected $dbName;
	private $obTable = 'opening_balance';
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
	public function obList(){		
		$sqlQuery = "SELECT * FROM ".$this->obTable." INNER JOIN bank_accounts ON bank_accounts.bank_acc_id = opening_balance.bank_acc_id ";
		if(!empty($_POST["search"]["value"])){
			$sqlQuery .= 'where(ob_date LIKE "%'.$_POST["search"]["value"].'%" ';
			$sqlQuery .= ' OR ob_amount LIKE "%'.$_POST["search"]["value"].'%" ';			
			$sqlQuery .= ' OR bank_acc_name LIKE "%'.$_POST["search"]["value"].'%" ) ';	
		}
		if(!empty($_POST["order"])){
			$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		} else {
			$sqlQuery .= 'ORDER BY ob_id DESC ';
		}
		if($_POST["length"] != -1){
			$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}	
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$numRows = mysqli_num_rows($result);
		
		$sqlQueryTotal = "SELECT * FROM ".$this->obTable;
		$resultTotal = mysqli_query($this->dbConnect, $sqlQueryTotal);
		$numRowsTotal = mysqli_num_rows($resultTotal);
		
		$obData = array();
		$i = 1;	
		while( $ob = mysqli_fetch_assoc($result) ) {		
			$obRows = array();			
			$obRows[] = $i++;
			$obRows[] = $ob['ob_date'];
			$obRows[] = ucfirst($ob['bank_acc_name']);
			$obRows[] = $ob['ob_amount'];	
			$obRows[] = $ob['ob_details'];			
			$obRows[] = '<button type="button" name="update" id="'.$ob["ob_id"].'" class="btn btn-warning btn-sm update"><i class="fa fa-edit"></i></button>
						  <button type="button" name="delete" id="'.$ob["ob_id"].'" class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i></button>';
			$obData[] = $obRows;
		}
		$output = array(
			"draw"	=>	intval($_POST["draw"]),			
			"iTotalRecords"	=> 	$numRows,
			"iTotalDisplayRecords"	=>  $numRowsTotal,
			"data"	=> 	$obData
		);
		echo json_encode($output);
	}
	public function getOB(){
		if($_POST["editId"]) {
			$sqlQuery = "
				SELECT * FROM ".$this->obTable." 
				WHERE ob_id = '".$_POST["editId"]."'";
			$result = mysqli_query($this->dbConnect, $sqlQuery);	
			$row = mysqli_fetch_array($result);
			echo json_encode($row);
		}
	}
	public function updateOB(){
		if($_POST['editId']) {
            $date = date('Y-m-d H:i:s');
			$updateQuery = "UPDATE ".$this->obTable." 
			SET amount_type = '".$_POST["amount_type"]."', bank_acc_id = '".$_POST["bank_acc_id"]."', ob_amount = '".$_POST["ob_amount"]."', ob_details = '".$_POST["ob_details"]."', ob_date = '".$_POST["ob_date"]."', cheque_no = '".$_POST["cheque_no"]."', updated_at = '".$date."'
			WHERE ob_id ='".$_POST["editId"]."'";
			$isUpdated = mysqli_query($this->dbConnect, $updateQuery);		
		}	
	}
	public function addOB(){
        $date = date('Y-m-d H:i:s');
		$insertQuery = "INSERT INTO ".$this->obTable." (amount_type,bank_acc_id,ob_amount,ob_details,ob_date,cheque_no, user_id, created_at) 
			VALUES ('".$_POST["amount_type"]."', '".$_POST["bank_acc_id"]."', '".$_POST["ob_amount"]."', '".$_POST["ob_details"]."', '".$_POST["ob_date"]."', '".$_POST["cheque_no"]."', '".$_POST["user_id"]."', '".$date."')";
		$isUpdated = mysqli_query($this->dbConnect, $insertQuery);		
	}
	public function deleteOB(){
		if($_POST["editId"]) {
			$sqlDelete = "
				DELETE FROM ".$this->obTable."
				WHERE ob_id = '".$_POST["editId"]."'";		
			mysqli_query($this->dbConnect, $sqlDelete);		
		}
	}
}
?>