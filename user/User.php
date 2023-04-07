<?php
require('../config.php');
class User extends Dbconfig {	
    protected $hostName;
    protected $userName;
    protected $password;
	protected $dbName;
	private $userTable = 'users';
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
	public function userList(){		
		$sqlQuery = "SELECT * FROM ".$this->userTable." INNER JOIN roles ON users.role_id = roles.role_id WHERE isAdmin = 0 ";
		if(!empty($_POST["search"]["value"])){
			$sqlQuery .= 'and(name LIKE "%'.$_POST["search"]["value"].'%" ';
			$sqlQuery .= ' OR user_name LIKE "%'.$_POST["search"]["value"].'%" ';	
			$sqlQuery .= ' OR email LIKE "%'.$_POST["search"]["value"].'%" ) ';			
		}
		if(!empty($_POST["order"])){
			$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		} else {
			$sqlQuery .= 'ORDER BY user_id DESC ';
		}
		if($_POST["length"] != -1){
			$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}	
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$numRows = mysqli_num_rows($result);
		
		$sqlQueryTotal = "SELECT * FROM ".$this->userTable;
		$resultTotal = mysqli_query($this->dbConnect, $sqlQueryTotal);
		$numRowsTotal = mysqli_num_rows($resultTotal);
		
		$userData = array();
		$i = 1;	
		while( $user = mysqli_fetch_assoc($result) ) {		
			$userRows = array();			
			$userRows[] = $i++;
			$userRows[] = ucfirst($user['name']);
			$userRows[] = $user['user_name'];		
			$userRows[] = $user['email'];
			$userRows[] = $user['role_name'];
            $userRows[] = '<button type="button" name="update" id="' . $user["user_id"] . '" class="btn btn-warning btn-sm update"><i class="fa fa-edit"></i></button>
						  <button type="button" name="delete" id="' . $user["user_id"] . '" class="btn btn-danger btn-sm delete" ><i class="fa fa-trash"></i></button>';
            $userData[] = $userRows;
		}
		$output = array(
			"draw"	=>	intval($_POST["draw"]),			
			"iTotalRecords"	=> 	$numRows,
			"iTotalDisplayRecords"	=>  $numRowsTotal,
			"data"	=> 	$userData
		);
		echo json_encode($output);
	}
	public function getUser(){
		if($_POST["editId"]) {
			$sqlQuery = "
				SELECT user_id,name,user_name,email,role_id FROM " .$this->userTable." 
				WHERE user_id = '".$_POST["editId"]."'";
			$result = mysqli_query($this->dbConnect, $sqlQuery);	
			$row = mysqli_fetch_array($result);
			echo json_encode($row);
		}
	}
	public function updateUser(){
		if($_POST['editId']) {	
			$updateQuery = "UPDATE ".$this->userTable." 
			SET name = '".$_POST["name"]."', user_name = '".$_POST["user_name"]."', email = '".$_POST["email"]."', role_id = '".$_POST["role_id"]."'
			WHERE user_id ='".$_POST["editId"]."'";
			$isUpdated = mysqli_query($this->dbConnect, $updateQuery);		
		}	
	}
	public function addUser(){
		$enctype_password = password_hash($_POST["user_password"], PASSWORD_DEFAULT);
		$insertQuery = "INSERT INTO ".$this->userTable." (name, user_name, email, user_password, isAdmin, role_id) 
			VALUES ('".$_POST["name"]."', '".$_POST["user_name"]."', '".$_POST["email"]."', '".$enctype_password."' , '0', '".$_POST["role_id"]."')";
		$isUpdated = mysqli_query($this->dbConnect, $insertQuery);		
	}
	public function deleteUser(){
		if($_POST["editId"]) {
			$sqlDelete = "
				DELETE FROM ".$this->userTable."
				WHERE user_id = '".$_POST["editId"]."'";		
			mysqli_query($this->dbConnect, $sqlDelete);		
		}
	}
}
?>