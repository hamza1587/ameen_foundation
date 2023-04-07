<?php
require('../config.php');
session_start();
class Expense extends Dbconfig {	
    protected $hostName;
    protected $userName;
    protected $password;
	protected $dbName;
	private $expenseTable = 'project_expense';
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
        $sqlQuery1 = "SELECT isAdmin,role_id FROM users WHERE user_id = '".$_SESSION['user_id']."'";
        $result1= mysqli_query($this->dbConnect, $sqlQuery1);
        $row1 = mysqli_fetch_array($result1);
        if($row1['isAdmin'] != 1 && $row1['role_id'] != 3){
            $sqlQuery = "SELECT * FROM ".$this->expenseTable." INNER JOIN bank_accounts ON project_expense.bank_acc_id = bank_accounts.bank_acc_id INNER JOIN users ON project_expense.user_id = users.user_id WHERE project_expense.user_id = '".$_SESSION['user_id']."'";
        }else{
            $sqlQuery = "SELECT * FROM ".$this->expenseTable." INNER JOIN bank_accounts ON project_expense.bank_acc_id = bank_accounts.bank_acc_id INNER JOIN users ON project_expense.user_id = users.user_id INNER JOIN roles ON users.role_id = roles.role_id ";
        }
		if(!empty($_POST["search"]["value"])){
            if($row1['isAdmin'] != 1){
                $sqlQuery .= 'and (expense_date LIKE "%'.$_POST["search"]["value"].'%" ';
            }else{
                $sqlQuery .= 'where(expense_date LIKE "%'.$_POST["search"]["value"].'%" ';
            }
			$sqlQuery .= ' OR person_name LIKE "%'.$_POST["search"]["value"].'%" ';			
			$sqlQuery .= ' OR person_cnic LIKE "%'.$_POST["search"]["value"].'%" ';				
			$sqlQuery .= ' OR bank_acc_name LIKE "%'.$_POST["search"]["value"].'%" ';	
			$sqlQuery .= ' OR donate_amount LIKE "%'.$_POST["search"]["value"].'%" ';	
			$sqlQuery .= ' OR person_city LIKE "%'.$_POST["search"]["value"].'%" ) ';	
		}
		if(!empty($_POST["order"])){
			$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		} else {
			$sqlQuery .= 'ORDER BY project_expense_id DESC ';
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
			$expenseRows[] = ucfirst($expense['person_name']);
			$expenseRows[] = $expense['person_cnic'];
			$expenseRows[] = $expense['bank_acc_name'];
            $expenseRows[] = $expense['amount_type'];
			$expenseRows[] = number_format($expense['donate_amount'], 2);
			$expenseRows[] = $expense['name'];
			$expenseRows[] = $expense['role_name'];
			$expenseRows[] = '<a href="p-expense-invoice/'.urlencode($expense["project_expense_id"]).'" target="_blank" class="btn btn-primary btn-sm generate"><i class="fa fa-print"></i></a>';										
			$expenseRows[] = '<button type="button" name="update" id="'.$expense["project_expense_id"].'" class="btn btn-warning btn-sm update"><i class="fa fa-edit"></i></button>
						  <button type="button" name="delete" id="'.$expense["project_expense_id"].'" class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i></button>';
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
	public function getExpense(){
		if($_POST["editId"]) {
			$sqlQuery = "
				SELECT * FROM ".$this->expenseTable." 
				WHERE project_expense_id = '".$_POST["editId"]."'";
			$result = mysqli_query($this->dbConnect, $sqlQuery);	
			$row = mysqli_fetch_array($result);
			echo json_encode($row);
		}
	}
	public function updateExpense(){
		if($_POST['editId']) {
            $date = date('Y-m-d H:i:s');
			$updateQuery = "UPDATE ".$this->expenseTable." 
			SET receipt_no = '".$_POST["receipt_no"]."', person_name = '".$_POST["person_name"]."', person_cnic = '".$_POST["person_cnic"]."', person_phone_no = '".$_POST["person_phone_no"]."', state_id = '".$_POST["state_id"]."', city_id = '".$_POST["city_id"]."', person_address = '".$_POST["person_address"]."', service_id = '".$_POST["service_id"]."', income_source_id = '".$_POST["income_source_id"]."', amount_type = '".$_POST["amount_type"]."', bank_acc_id = '".$_POST["bank_acc_id"]."', cheque_no = '".$_POST["cheque_no"]."', date = '".$_POST["date"]."', donate_amount = '".$_POST["donate_amount"]."', amount_in_words = '".$_POST["amount_in_words"]."', updated_at = '".$date."'
			WHERE project_expense_id ='".$_POST["editId"]."'";
			$isUpdated = mysqli_query($this->dbConnect, $updateQuery);		
		}	
	}
	public function addExpense(){
        $date = date('Y-m-d H:i:s');
		$insertQuery = "INSERT INTO ".$this->expenseTable." (receipt_no,person_name,person_cnic,person_phone_no,state_id,city_id,person_address,service_id,income_source_id,amount_type,bank_acc_id,cheque_no,date,donate_amount, amount_in_words, user_id, created_at) 
			VALUES ('".$_POST["receipt_no"]."', '".$_POST["person_name"]."', '".$_POST["person_cnic"]."', '".$_POST["person_phone_no"]."', '".$_POST["state_id"]."', '".$_POST["city_id"]."', '".$_POST["person_address"]."', '".$_POST["service_id"]."', '".$_POST["income_source_id"]."', '".$_POST["amount_type"]."', '".$_POST["bank_acc_id"]."', '".$_POST["cheque_no"]."', '".$_POST["date"]."', '".$_POST["donate_amount"]."', '".$_POST["amount_in_words"]."', '".$_POST["user_id"]."', '".$date."')";
		$isUpdated = mysqli_query($this->dbConnect, $insertQuery);		
	}
	public function deleteExpense(){
		if($_POST["editId"]) {
			$sqlDelete = "
				DELETE FROM ".$this->expenseTable."
				WHERE project_expense_id = '".$_POST["editId"]."'";		
			mysqli_query($this->dbConnect, $sqlDelete);		
		}
	}
}
?>