<?php
require('../config.php');
class UserRole extends Dbconfig {	
    protected $hostName;
    protected $roleName;
    protected $password;
	protected $dbName;
	private $roleTable = 'roles';
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
	public function roleList(){		
		$sqlQuery = "SELECT * FROM ".$this->roleTable." ";
		if(!empty($_POST["search"]["value"])){
			$sqlQuery .= 'where(role_name LIKE "%'.$_POST["search"]["value"].'%" ) ';			
		}
		if(!empty($_POST["order"])){
			$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		} else {
			$sqlQuery .= 'ORDER BY role_id ASC ';
		}
		if($_POST["length"] != -1){
			$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}	
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$numRows = mysqli_num_rows($result);
		
		$sqlQueryTotal = "SELECT * FROM ".$this->roleTable;
		$resultTotal = mysqli_query($this->dbConnect, $sqlQueryTotal);
		$numRowsTotal = mysqli_num_rows($resultTotal);
		
		$roleData = array();
		$i = 1;	
		while( $role = mysqli_fetch_assoc($result) ) {		
			$roleRow = array();			
			$roleRow[] = $i++;
			$roleRow[] = ucfirst($role['role_name']);	
			if($role['status'] == "1"){
				$roleRow[] = '<span class="btn btn-success btn-sm" style="cursor:default !important;">Active</span>';
			}else if($role['status'] == "2"){
				$roleRow[] = '<span class="btn btn-warning btn-sm" style="cursor:default !important;">Restricted</span>';
			}else{
				$roleRow[] = '<span class="btn btn-danger btn-sm" style="cursor:default !important;">Inactive</span>';
			}	
			if($role['status'] != "2"){		
				$roleRow[] = '<a href="permissions/'.urlencode($role["role_id"]).'" class="btn btn-info btn-sm"><i class="fa fa-sliders"></i></a>';
			}else{
				$roleRow[] = "--";
			}	
			if($role['status'] != "2"){		
				$roleRow[] = '<button type="button" name="update" id="'.$role["role_id"].'" class="btn btn-warning btn-sm update"><i class="fa fa-edit"></i></button>
					<button type="button" name="delete" id="'.$role["role_id"].'" class="btn btn-danger btn-sm delete" ><i class="fa fa-trash"></i></button>';
			}else{
				$roleRow[] = "--";
			}
			$roleData[] = $roleRow;
		}
		$output = array(
			"draw"	=>	intval($_POST["draw"]),			
			"iTotalRecords"	=> 	$numRows,
			"iTotalDisplayRecords"	=>  $numRowsTotal,
			"data"	=> 	$roleData
		);
		echo json_encode($output);
	}
	public function getRole(){
		if($_POST["editId"]) {
			$sqlQuery = "
				SELECT * FROM ".$this->roleTable." 
				WHERE role_id = '".$_POST["editId"]."'";
			$result = mysqli_query($this->dbConnect, $sqlQuery);	
			$row = mysqli_fetch_array($result);
			echo json_encode($row);
		}
	}
	public function updateRole(){
		if($_POST['editId']) {	
			$updateQuery = "UPDATE ".$this->roleTable." 
			SET role_name = '".$_POST["role_name"]."', status = '".$_POST["status"]."'
			WHERE role_id ='".$_POST["editId"]."'";
			$isUpdated = mysqli_query($this->dbConnect, $updateQuery);		
		}	
	}
	public function addRole(){
		$insertQuery = "INSERT INTO ".$this->roleTable." (role_name) 
			VALUES ('".$_POST["role_name"]."')";
		$isUpdated = mysqli_query($this->dbConnect, $insertQuery);		
	}
	public function deleteRole(){
		if($_POST["editId"]) {
			$sqlDelete = "
				DELETE FROM ".$this->roleTable."
				WHERE role_id = '".$_POST["editId"]."'";		
			mysqli_query($this->dbConnect, $sqlDelete);		
		}
	}

	public function updateStatus(){
		if($_POST['id']) {	
			$updateQuery = "UPDATE ".$this->roleTable." 
			SET status = '".$_POST["status"]."'
			WHERE role_id ='".$_POST["id"]."'";
			$isUpdated = mysqli_query($this->dbConnect, $updateQuery);
			echo json_encode($isUpdated);		
		}
	}
}
?>