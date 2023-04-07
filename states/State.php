<?php
require('../config.php');
class State extends Dbconfig {	
    protected $hostName;
    protected $stateName;
    protected $password;
	protected $dbName;
	private $stateTable = 'states';
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
	public function stateList(){		
		$sqlQuery = "SELECT * FROM ".$this->stateTable." ";
		if(!empty($_POST["search"]["value"])){
			$sqlQuery .= 'where(state_name LIKE "%'.$_POST["search"]["value"].'%" ) ';			
		}
		if(!empty($_POST["order"])){
			$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		} else {
			$sqlQuery .= 'ORDER BY state_id ASC ';
		}
		if($_POST["length"] != -1){
			$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}	
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$numRows = mysqli_num_rows($result);
		
		$sqlQueryTotal = "SELECT * FROM ".$this->stateTable;
		$resultTotal = mysqli_query($this->dbConnect, $sqlQueryTotal);
		$numRowsTotal = mysqli_num_rows($resultTotal);
		
		$stateData = array();
		$i = 1;	
		while( $state = mysqli_fetch_assoc($result) ) {		
			$stateRow = array();			
			$stateRow[] = $i++;
			$stateRow[] = ucfirst($state['state_name']);	
			$stateRow[] = '<button type="button" name="update" id="'.$state["state_id"].'" class="btn btn-warning btn-sm update"><i class="fa fa-edit"></i></button>
						<button type="button" name="delete" id="'.$state["state_id"].'" class="btn btn-danger btn-sm delete" ><i class="fa fa-trash"></i></button>';
			$stateData[] = $stateRow;
		}
		$output = array(
			"draw"	=>	intval($_POST["draw"]),			
			"iTotalRecords"	=> 	$numRows,
			"iTotalDisplayRecords"	=>  $numRowsTotal,
			"data"	=> 	$stateData
		);
		echo json_encode($output);
	}
	public function getState(){
		if($_POST["editId"]) {
			$sqlQuery = "
				SELECT * FROM ".$this->stateTable." 
				WHERE state_id = '".$_POST["editId"]."'";
			$result = mysqli_query($this->dbConnect, $sqlQuery);	
			$row = mysqli_fetch_array($result);
			echo json_encode($row);
		}
	}
	public function updateState(){
		if($_POST['editId']) {	
			$updateQuery = "UPDATE ".$this->stateTable." 
			SET state_name = '".$_POST["state_name"]."'
			WHERE state_id ='".$_POST["editId"]."'";
			$isUpdated = mysqli_query($this->dbConnect, $updateQuery);		
		}	
	}
	public function addState(){
		$insertQuery = "INSERT INTO ".$this->stateTable." (state_name) 
			VALUES ('".$_POST["state_name"]."')";
		$isUpdated = mysqli_query($this->dbConnect, $insertQuery);		
	}
	public function deleteState(){
		if($_POST["editId"]) {
			$sqlDelete = "
				DELETE FROM ".$this->stateTable."
				WHERE state_id = '".$_POST["editId"]."'";		
			mysqli_query($this->dbConnect, $sqlDelete);		
		}
	}
}
?>