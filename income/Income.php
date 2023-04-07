<?php
require('../config.php');
class Income extends Dbconfig {	
    protected $hostName;
    protected $userName;
    protected $password;
	protected $dbName;
	private $incomeTable = 'income_source';
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
	public function incomeList(){		
		$sqlQuery = "SELECT * FROM ".$this->incomeTable." ";
		if(!empty($_POST["search"]["value"])){
			$sqlQuery .= 'where(income_source_title LIKE "%'.$_POST["search"]["value"].'%" ) ';			
		}
		if(!empty($_POST["order"])){
			$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		} else {
			$sqlQuery .= 'ORDER BY income_source_id DESC ';
		}
		if($_POST["length"] != -1){
			$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}	
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$numRows = mysqli_num_rows($result);
		
		$sqlQueryTotal = "SELECT * FROM ".$this->incomeTable;
		$resultTotal = mysqli_query($this->dbConnect, $sqlQueryTotal);
		$numRowsTotal = mysqli_num_rows($resultTotal);
		
		$incomeData = array();
		$i = 1;	
		while( $income = mysqli_fetch_assoc($result) ) {		
			$incomeRows = array();			
			$incomeRows[] = $i++;
			$incomeRows[] = ucfirst($income['income_source_title']);						
			$incomeRows[] = '<button type="button" name="update" id="'.$income["income_source_id"].'" class="btn btn-warning btn-sm update"><i class="fa fa-edit"></i></button>
						  <button type="button" name="delete" id="'.$income["income_source_id"].'" class="btn btn-danger btn-sm delete" ><i class="fa fa-trash"></i></button>';
			$incomeData[] = $incomeRows;
		}
		$output = array(
			"draw"	=>	intval($_POST["draw"]),			
			"iTotalRecords"	=> 	$numRows,
			"iTotalDisplayRecords"	=>  $numRowsTotal,
			"data"	=> 	$incomeData
		);
		echo json_encode($output);
	}
	public function getIncome(){
		if($_POST["editId"]) {
			$sqlQuery = "
				SELECT income_source_id,income_source_title FROM ".$this->incomeTable." 
				WHERE income_source_id = '".$_POST["editId"]."'";
			$result = mysqli_query($this->dbConnect, $sqlQuery);	
			$row = mysqli_fetch_array($result);
			echo json_encode($row);
		}
	}
	public function updateIncome(){
		if($_POST['editId']) {	
			$updateQuery = "UPDATE ".$this->incomeTable." 
			SET income_source_title = '".$_POST["income_source_title"]."'
			WHERE income_source_id ='".$_POST["editId"]."'";
			$isUpdated = mysqli_query($this->dbConnect, $updateQuery);		
		}	
	}
	public function addIncome(){
		$insertQuery = "INSERT INTO ".$this->incomeTable." (income_source_title) 
			VALUES ('".$_POST["income_source_title"]."')";
		$isUpdated = mysqli_query($this->dbConnect, $insertQuery);		
	}
	public function deleteIncome(){
		if($_POST["editId"]) {
			$sqlDelete = "
				DELETE FROM ".$this->incomeTable."
				WHERE income_source_id = '".$_POST["editId"]."'";		
			mysqli_query($this->dbConnect, $sqlDelete);		
		}
	}
}
?>