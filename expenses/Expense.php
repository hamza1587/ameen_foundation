<?php
require('../config.php');
session_start();
class Expense extends Dbconfig {
    protected $hostName;
    protected $userName;
    protected $password;
    protected $dbName;
    private $expenseTable = 'expense';
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
            $sqlQuery = "SELECT * FROM ".$this->expenseTable." INNER JOIN expenses ON expense.expense_id = expenses.expense_id INNER JOIN users ON expense.user_id = users.user_id WHERE expense.user_id = '".$_SESSION['user_id']."'";
        }else{
            $sqlQuery = "SELECT * FROM ".$this->expenseTable." INNER JOIN expenses ON expense.expense_id = expenses.expense_id INNER JOIN users ON expense.user_id = users.user_id INNER JOIN roles ON users.role_id = roles.role_id ";
        }
        if(!empty($_POST["search"]["value"])){
            if($row1['isAdmin'] != 1){
                $sqlQuery .= 'and (expense_date LIKE "%'.$_POST["search"]["value"].'%" ';
            }else{
                $sqlQuery .= 'where(expense_date LIKE "%'.$_POST["search"]["value"].'%" ';
            }
            $sqlQuery .= ' OR refrence_no LIKE "%'.$_POST["search"]["value"].'%" ';
            $sqlQuery .= ' OR expense_title LIKE "%'.$_POST["search"]["value"].'%" ';
            $sqlQuery .= ' OR expense_for LIKE "%'.$_POST["search"]["value"].'%" ';
            $sqlQuery .= ' OR expense_amount LIKE "%'.$_POST["search"]["value"].'%" ) ';
        }
        if(!empty($_POST["order"])){
            $sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
        } else {
            $sqlQuery .= 'ORDER BY exp_id DESC ';
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
            $expenseRows[] = $expense['refrence_no'];
            $expenseRows[] = ucfirst($expense['expense_title']);
            $expenseRows[] = $expense['expense_for'];
            $expenseRows[] = number_format($expense['expense_amount'], 2);
            $expenseRows[] = $expense['expense_date'];
            $expenseRows[] = $expense['name'];
            $expenseRows[] = $expense['role_name'];
            $expenseRows[] = '<a href="expense-invoice/'.urlencode($expense["exp_id"]).'" target="_blank" class="btn btn-primary btn-sm generate"><i class="fa fa-print"></i></a>';
            $expenseRows[] = '<button type="button" name="update" id="' . $expense["exp_id"] . '" class="btn btn-warning btn-sm update"><i class="fa fa-edit"></i></button>
						  <button type="button" name="delete" id="' . $expense["exp_id"] . '" class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i></button>';
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
				WHERE exp_id = '".$_POST["editId"]."'";
            $result = mysqli_query($this->dbConnect, $sqlQuery);
            $row = mysqli_fetch_array($result);
            echo json_encode($row);
        }
    }
    public function updateExpense(){
        $date = date('Y-m-d H:i:s');
        if($_POST['editId']) {
            $updateQuery = "UPDATE ".$this->expenseTable." 
			SET expense_date = '".$_POST["expense_date"]."', refrence_no = '".$_POST["refrence_no"]."', expense_id = '".$_POST["expense_id"]."', amount_type = '".$_POST["amount_type"]."' ,bank_acc_id = '".$_POST["bank_acc_id"]."', expense_for = '".$_POST["expense_for"]."', expense_amount = '".$_POST["expense_amount"]."', updated_at = '".$date."', cheque_no = '".$_POST["cheque_no"]."'
			WHERE exp_id ='".$_POST["editId"]."'";
            $isUpdated = mysqli_query($this->dbConnect, $updateQuery);
        }
    }
    public function addExpense(){
        $date = date('Y-m-d H:i:s');
        $insertQuery = "INSERT INTO ".$this->expenseTable." (expense_date,refrence_no,expense_id,amount_type,bank_acc_id,expense_for,expense_amount,created_at,cheque_no, user_id) 
			VALUES ('".$_POST["expense_date"]."', '".$_POST["refrence_no"]."', '".$_POST["expense_id"]."', '".$_POST["amount_type"]."', '".$_POST["bank_acc_id"]."', '".$_POST["expense_for"]."', '".$_POST["expense_amount"]."', '".$date."', '".$_POST["cheque_no"]."', '".$_POST["user_id"]."')";
        $isUpdated = mysqli_query($this->dbConnect, $insertQuery);
    }
    public function deleteExpense(){
        if($_POST["editId"]) {
            $sqlDelete = "
				DELETE FROM ".$this->expenseTable."
				WHERE exp_id = '".$_POST["editId"]."'";
            mysqli_query($this->dbConnect, $sqlDelete);
        }
    }
    public function getReceipt(){
        $sqlQuery = "
				SELECT refrence_no FROM ".$this->expenseTable." 
				WHERE exp_id = (SELECT MAX(exp_id) FROM ".$this->expenseTable.")";
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_array($result);
            $code = $row['refrence_no'] + 1;
        }
        else{
            $code = "1";
        }
        echo json_encode($code);
    }
}
?>