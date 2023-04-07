<?php
    include('../includes/db.php');
    $state_id   = $_POST['state_id'];
    $city_id    = $_POST['city_id'];
    if(!empty($state_id))
    {
        $cityData = "SELECT city_id, city_name from cities WHERE state_id = $state_id";
        $result = mysqli_query($conn, $cityData);
        if(mysqli_num_rows($result) > 0)
        {
            echo "<option value=''>Choose City</option>";
            while($arr=mysqli_fetch_assoc($result))
            {
                ?>
                <option value=<?php echo $arr['city_id']; ?> <?php if($arr['city_id'] == $city_id){ echo "selected"; }?>><?php echo $arr['city_name']; ?></option><br>
                <?php
            }
        }
    }
?>