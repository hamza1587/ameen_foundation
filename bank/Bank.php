<?php
require('../config.php');
class Bank extends Dbconfig {	
    protected $hostName;
    protected $userName;
    protected $password;
	protected $dbName;
	private $bankTable = 'bank_accounts';
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
	public function bankList(){		
		$sqlQuery = "SELECT * FROM ".$this->bankTable." ";
		if(!empty($_POST["search"]["value"])){
			$sqlQuery .= 'where(bank_acc_name LIKE "%'.$_POST["search"]["value"].'%" ) ';			
		}
		if(!empty($_POST["order"])){
			$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		} else {
			$sqlQuery .= 'ORDER BY bank_acc_id DESC ';
		}
		if($_POST["length"] != -1){
			$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}	
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$numRows = mysqli_num_rows($result);
		
		$sqlQueryTotal = "SELECT * FROM ".$this->bankTable;
		$resultTotal = mysqli_query($this->dbConnect, $sqlQueryTotal);
		$numRowsTotal = mysqli_num_rows($resultTotal);
		
		$bankData = array();
		$i = 1;	
		while( $bank = mysqli_fetch_assoc($result) ) {		
			$bankRows = array();			
			$bankRows[] = $i++;
			$bankRows[] = ucfirst($bank['bank_acc_name']);						
			$bankRows[] = '<button type="button" name="update" id="'.$bank["bank_acc_id"].'" class="btn btn-warning btn-sm update"><i class="fa fa-edit"></i></button>
						  <button type="button" name="delete" id="'.$bank["bank_acc_id"].'" class="btn btn-danger btn-sm delete" ><i class="fa fa-trash"></i></button>';
			$bankData[] = $bankRows;
		}
		$output = array(
			"draw"	=>	intval($_POST["draw"]),			
			"iTotalRecords"	=> 	$numRows,
			"iTotalDisplayRecords"	=>  $numRowsTotal,
			"data"	=> 	$bankData
		);
		echo json_encode($output);
	}
	public function getBank(){
		if($_POST["editId"]) {
			$sqlQuery = "
				SELECT bank_acc_id,bank_acc_name FROM ".$this->bankTable." 
				WHERE bank_acc_id = '".$_POST["editId"]."'";
			$result = mysqli_query($this->dbConnect, $sqlQuery);	
			$row = mysqli_fetch_array($result);
			echo json_encode($row);
		}
	}
	public function updateBank(){
		if($_POST['editId']) {	
			$updateQuery = "UPDATE ".$this->bankTable." 
			SET bank_acc_name = '".$_POST["bank_acc_name"]."'
			WHERE bank_acc_id ='".$_POST["editId"]."'";
			$isUpdated = mysqli_query($this->dbConnect, $updateQuery);		
		}	
	}
	public function addBank(){
		$insertQuery = "INSERT INTO ".$this->bankTable." (bank_acc_name) 
			VALUES ('".$_POST["bank_acc_name"]."')";
		$isUpdated = mysqli_query($this->dbConnect, $insertQuery);		
	}
	public function deleteBank(){
		if($_POST["editId"]) {
			$sqlDelete = "
				DELETE FROM ".$this->bankTable."
				WHERE bank_acc_id = '".$_POST["editId"]."'";		
			mysqli_query($this->dbConnect, $sqlDelete);		
		}
	}
}
?>