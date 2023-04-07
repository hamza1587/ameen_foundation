<?php
require('../config.php');
class Expense extends Dbconfig {	
    protected $hostName;
    protected $userName;
    protected $password;
	protected $dbName;
	private $expenseTable = 'expenses';
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
	public function expenseList(){		
		$sqlQuery = "SELECT * FROM ".$this->expenseTable." ";
		if(!empty($_POST["search"]["value"])){
			$sqlQuery .= 'where(expense_title LIKE "%'.$_POST["search"]["value"].'%" ) ';			
		}
		if(!empty($_POST["order"])){
			$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		} else {
			$sqlQuery .= 'ORDER BY expense_id DESC ';
		}
		if($_POST["length"] != -1){
			$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}	
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$numRows = mysqli_num_rows($result);
		
		$sqlQueryTotal = "SELECT * FROM ".$this->expenseTable;
		$resultTotal = mysqli_query($this->dbConnect, $sqlQueryTotal);
		$numRowsTotal = mysqli_num_rows($resultTotal);
		
		$expenseData = array();
		$i = 1;	
		while( $expense = mysqli_fetch_assoc($result) ) {		
			$expenseRows = array();			
			$expenseRows[] = $i++;
			$expenseRows[] = ucfirst($expense['expense_title']);
            $expenseRows[] = '<button type="button" name="update" id="' . $expense["expense_id"] . '" class="btn btn-warning btn-sm update"><i class="fa fa-edit"></i></button>
						  <button type="button" name="delete" id="' . $expense["expense_id"] . '" class="btn btn-danger btn-sm delete" ><i class="fa fa-trash"></i></button>';
            $expenseData[] = $expenseRows;
		}
		$output = array(
			"draw"	=>	intval($_POST["draw"]),			
			"iTotalRecords"	=> 	$numRows,
			"iTotalDisplayRecords"	=>  $numRowsTotal,
			"data"	=> 	$expenseData
		);
		echo json_encode($output);
	}
	public function getexpense(){
		if($_POST["editId"]) {
			$sqlQuery = "
				SELECT expense_id,expense_title FROM ".$this->expenseTable." 
				WHERE expense_id = '".$_POST["editId"]."'";
			$result = mysqli_query($this->dbConnect, $sqlQuery);	
			$row = mysqli_fetch_array($result);
			echo json_encode($row);
		}
	}
	public function updateexpense(){
		if($_POST['editId']) {	
			$updateQuery = "UPDATE ".$this->expenseTable." 
			SET expense_title = '".$_POST["expense_title"]."'
			WHERE expense_id ='".$_POST["editId"]."'";
			$isUpdated = mysqli_query($this->dbConnect, $updateQuery);		
		}	
	}
	public function addexpense(){
		$insertQuery = "INSERT INTO ".$this->expenseTable." (expense_title) 
			VALUES ('".$_POST["expense_title"]."')";
		$isUpdated = mysqli_query($this->dbConnect, $insertQuery);		
	}
	public function deleteexpense(){
		if($_POST["editId"]) {
			$sqlDelete = "
				DELETE FROM ".$this->expenseTable."
				WHERE expense_id = '".$_POST["editId"]."'";		
			mysqli_query($this->dbConnect, $sqlDelete);		
		}
	}
}
?>