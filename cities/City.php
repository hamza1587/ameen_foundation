<?php
require('../config.php');
class City extends Dbconfig {	
    protected $hostName;
    protected $cityName;
    protected $password;
	protected $dbName;
	private $cityTable = 'cities';
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
	public function cityList(){		
		$sqlQuery = "SELECT * FROM ".$this->cityTable." INNER JOIN states ON cities.state_id = states.state_id ";
		if(!empty($_POST["search"]["value"])){
			$sqlQuery .= 'where(city_name LIKE "%'.$_POST["search"]["value"].'%" ) ';			
		}
		if(!empty($_POST["order"])){
			$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		} else {
			$sqlQuery .= 'ORDER BY city_id ASC ';
		}
		if($_POST["length"] != -1){
			$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}	
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$numRows = mysqli_num_rows($result);
		
		$sqlQueryTotal = "SELECT * FROM ".$this->cityTable;
		$resultTotal = mysqli_query($this->dbConnect, $sqlQueryTotal);
		$numRowsTotal = mysqli_num_rows($resultTotal);
		
		$cityData = array();
		$i = 1;	
		while( $city = mysqli_fetch_assoc($result) ) {		
			$cityRow = array();			
			$cityRow[] = $i++;
			$cityRow[] = ucfirst($city['city_name']);	
			$cityRow[] = ucfirst($city['state_name']);	
			$cityRow[] = '<button type="button" name="update" id="'.$city["city_id"].'" class="btn btn-warning btn-sm update"><i class="fa fa-edit"></i></button>
						<button type="button" name="delete" id="'.$city["city_id"].'" class="btn btn-danger btn-sm delete" ><i class="fa fa-trash"></i></button>';
			$cityData[] = $cityRow;
		}
		$output = array(
			"draw"	=>	intval($_POST["draw"]),			
			"iTotalRecords"	=> 	$numRows,
			"iTotalDisplayRecords"	=>  $numRowsTotal,
			"data"	=> 	$cityData
		);
		echo json_encode($output);
	}
	public function getCity(){
		if($_POST["editId"]) {
			$sqlQuery = "
				SELECT * FROM ".$this->cityTable." 
				WHERE city_id = '".$_POST["editId"]."'";
			$result = mysqli_query($this->dbConnect, $sqlQuery);	
			$row = mysqli_fetch_array($result);
			echo json_encode($row);
		}
	}
	public function updateCity(){
		if($_POST['editId']) {	
			$updateQuery = "UPDATE ".$this->cityTable." 
			SET city_name = '".$_POST["city_name"]."', state_id = '".$_POST["state_id"]."'
			WHERE city_id ='".$_POST["editId"]."'";
			$isUpdated = mysqli_query($this->dbConnect, $updateQuery);		
		}	
	}
	public function addCity(){
		$insertQuery = "INSERT INTO ".$this->cityTable." (city_name,state_id) 
			VALUES ('".$_POST["city_name"]."', '".$_POST['state_id']."')";
		$isUpdated = mysqli_query($this->dbConnect, $insertQuery);		
	}
	public function deleteCity(){
		if($_POST["editId"]) {
			$sqlDelete = "
				DELETE FROM ".$this->cityTable."
				WHERE city_id = '".$_POST["editId"]."'";		
			mysqli_query($this->dbConnect, $sqlDelete);		
		}
	}
}
?>