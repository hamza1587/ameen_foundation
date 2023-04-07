<?php
require('../config.php');
session_start();
class Membership extends Dbconfig {
    protected $hostName;
    protected $userName;
    protected $password;
    protected $dbName;
    private $membershipTable = 'membership';
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
    public function membershipList(){
        $sqlQuery1 = "SELECT isAdmin FROM users WHERE user_id = '".$_SESSION['user_id']."'";
        $result1= mysqli_query($this->dbConnect, $sqlQuery1);
        $row1 = mysqli_fetch_array($result1);
        if($row1['isAdmin'] != 1){
            $sqlQuery = "SELECT *,membership.name AS person_name FROM ".$this->membershipTable." INNER JOIN bank_accounts ON membership.bank_acc_id = bank_accounts.bank_acc_id INNER JOIN users ON membership.user_id = users.user_id WHERE membership.user_id = '".$_SESSION['user_id']."'";
        }else{
            $sqlQuery = "SELECT *,membership.name AS person_name FROM ".$this->membershipTable." INNER JOIN bank_accounts ON membership.bank_acc_id = bank_accounts.bank_acc_id INNER JOIN users ON membership.user_id = users.user_id INNER JOIN roles ON users.role_id = roles.role_id ";
        }
        if(!empty($_POST["search"]["value"])){
            $sqlQuery .= 'and (person_name LIKE "%'.$_POST["search"]["value"].'%" ';
            $sqlQuery .= ' OR father_name LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR fee_type LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR amount_type LIKE "%' . $_POST["search"]["value"] . '%" ';
            $sqlQuery .= ' OR bank_acc_name LIKE "%' . $_POST["search"]["value"] . '%" ) ';
        }
        if(!empty($_POST["order"])){
            $sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
        } else {
            $sqlQuery .= 'ORDER BY date DESC ';
        }
        if($_POST["length"] != -1){
            $sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        $numRows = mysqli_num_rows($result);

        $sqlQueryTotal = "SELECT * FROM ".$this->membershipTable;
        $resultTotal = mysqli_query($this->dbConnect, $sqlQueryTotal);
        $numRowsTotal = mysqli_num_rows($resultTotal);

        $membershipData = array();
        $i = 1;
        while( $membership = mysqli_fetch_assoc($result) ) {
            $membershipRows = array();
            $membershipRows[] = $i++;
            $membershipRows[] = ucfirst($membership['person_name']);
            $membershipRows[] = ucfirst($membership['father_name']);
            $membershipRows[] = number_format($membership['fee'], 2);
            $membershipRows[] = $membership['fee_type'];
            $membershipRows[] = $membership['amount_type'];
            $membershipRows[] = $membership['bank_acc_name'];
            $membershipRows[] = $membership['fee_month'];
            $membershipRows[] = $membership['name'];
            $membershipRows[] = $membership['role_name'];
            $membershipRows[] = '<a href="membership-invoice/'.urlencode($membership["membership_id"]).'" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>';
            $membershipRows[] = '<button type="button" name="update" id="'.$membership["membership_id"].'" class="btn btn-warning btn-sm update"><i class="fa fa-edit"></i></button>
						        <button type="button" name="delete" id="'.$membership["membership_id"].'" class="btn btn-danger btn-sm delete" ><i class="fa fa-trash"></i></button>';
            $membershipData[] = $membershipRows;
        }
        $output = array(
            "draw"	=>	intval($_POST["draw"]),
            "iTotalRecords"	=> 	$numRows,
            "iTotalDisplayRecords"	=>  $numRowsTotal,
            "data"	=> 	$membershipData
        );
        echo json_encode($output);
    }
    public function getMembership(){
        if($_POST["editId"]) {
            $sqlQuery = "
				SELECT * FROM ".$this->membershipTable." 
				WHERE membership_id = '".$_POST["editId"]."'";
            $result = mysqli_query($this->dbConnect, $sqlQuery);
            $row = mysqli_fetch_array($result);
            echo json_encode($row);
        }
    }
    public function updateMembership(){
        $date = date('Y-m-d H:i:s');
        if($_POST['editId']) {
            $updateQuery = "UPDATE ".$this->membershipTable." 
			SET invoice_no = '".$_POST["invoice_no"]."', date = '".$_POST["date"]."', name = '".$_POST["name"]."', father_name = '".$_POST["father_name"]."', fee = '".$_POST["fee"]."', fee_type = '".$_POST["fee_type"]."', amount_type = '".$_POST["amount_type"]."' , bank_acc_id = '".$_POST["bank_acc_id"]."', fee_month = '".$_POST["fee_month"]."' , cheque_no = '".$_POST["cheque_no"]."', designation = '".$_POST["designation"]."', city = '".$_POST["city"]."', level = '".$_POST["level"]."' , updated_at = '".$date."', phone_no = '".$_POST['phone_no']."'
			WHERE membership_id ='".$_POST["editId"]."'";
            $isUpdated = mysqli_query($this->dbConnect, $updateQuery);
        }
    }
    public function addMembership(){
        $date = date('Y-m-d H:i:s');
        $phone = $_POST['phone_no'];
        $text = "";
        $insertQuery = "INSERT INTO ".$this->membershipTable." (invoice_no, date, name, father_name, fee, fee_type, amount_type, bank_acc_id, fee_month, cheque_no, designation, city, level, user_id, created_at, phone_no)
			VALUES ('".$_POST["invoice_no"]."', '".$_POST["date"]."', '".$_POST["name"]."', '".$_POST["father_name"]."', '".$_POST["fee"]."', '".$_POST["fee_type"]."' , '".$_POST["amount_type"]."', '".$_POST["bank_acc_id"]."', '".$_POST["fee_month"]."', '".$_POST["cheque_no"]."', '".$_POST["designation"]."', '".$_POST["city"]."', '".$_POST["level"]."' , '".$_POST["user_id"]."' , '".$date."', '".$_POST['phone_no']."')";
        $isUpdated = mysqli_query($this->dbConnect, $insertQuery);
        if($isUpdated == TRUE){
            API($phone, $text);
        }
    }
    public function deleteMembership(){
        if($_POST["editId"]) {
            $sqlDelete = "
				DELETE FROM ".$this->membershipTable."
				WHERE membership_id  = '".$_POST["editId"]."'";
            mysqli_query($this->dbConnect, $sqlDelete);
        }
    }
}
?>