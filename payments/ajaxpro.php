<?php


   require('../includes/db.php');
   if(!empty($_POST["from_account"])){ 
        $query = "SELECT * FROM payments INNER JOIN bank_accounts ON bank_accounts.bank_acc_id = payments.to_account WHERE from_account = '".$_POST['from_account']."' GROUP BY bank_acc_id,bank_acc_name"; 
        $result = $conn->query($query); 
        
        if($result->num_rows > 0){ 
            echo '<option value="">Select To Account</option>'; 
            while($row = $result->fetch_assoc()){  
                echo '<option value="'.$row['bank_acc_id'].'">'.$row['bank_acc_name'].'</option>'; 
            } 
        }else{ 
            echo '<option value="">To Account not available</option>'; 
        } 
    }
?>