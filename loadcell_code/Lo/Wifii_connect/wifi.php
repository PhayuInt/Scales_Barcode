<?php

function fetureExtraction($acc_data,$gyro_data,$time2,$name)
{
	$output = [$acc_data,$gyro_data,$time2,$name];
	$output=shell_exec("python3 EBM_Predict.py " . escapeshellarg(json_encode($output)). ' 2>&1');
	return $output;
}

function getCurrentActivity(): array{

	$select_currentActivity = "SELECT `ac_id`,`n_id` FROM `activity` WHERE ac_id = (SELECT max(ac_id) FROM `activity` WHERE `e_id` = '".$GLOBALS['e_id']."')";
	$n_id = 0 ;
	$ac_id = 0 ;
	$result1 = mysqli_query($GLOBALS['con'], $select_currentActivity);

	if (mysqli_num_rows($result1) > 0) {
		$row = mysqli_fetch_assoc($result1);
		$n_id = $row["n_id"];
		$ac_id = $row["ac_id"];
	}
	else{
		echo "No......\n";
	}
	return [$n_id,$ac_id];
}

require '../connection.php'; #connectionDatabase

$data = json_decode(file_get_contents('php://input'),true);#getdatafromjson
$w_id = $data["w_id"];
$acc_data = explode ("S", $data["acc_data"]);  #spiltdata
$gyro_data = explode ("S", $data["gyro_data"]);
$ltime = (count($acc_data)-1)/5;
$time = date("H:i:s ", time() - $ltime);


$numtime = count($acc_data)-1;
$Ctime = strtotime($time);
$time2 = array();
$i = 1;
for ($x = 0; $x < $numtime; $x++) {

	if($x % 5 == 0){
		$time2[] = date("H:i:s ", $Ctime + $i);
		$i++;
	}

}


$sql = "SELECT wearable_holder.e_id,elderly.e_nickname FROM wearable_holder INNER JOIN elderly ON wearable_holder.e_id = elderly.e_id WHERE wearable_holder.w_id = '".$w_id."'";
$result = mysqli_query($con, $sql);
$e_id = 0;
$name = "";
if (mysqli_num_rows($result) > 0) {
	$row = mysqli_fetch_assoc($result);
	$e_id = $row["e_id"];
	$name = $row["e_nickname"];
}
$predict = fetureExtraction($acc_data,$gyro_data,$time,$name);
$predict = substr($predict, 0,-1);
//$predict = substr($predict, 1,-2);
//$predict = explode (",", $predict);


//for ($x = 0; $x < count($predict); $x++) {
	  [$n_id,$ac_id] = getCurrentActivity();
		$date1 = date("Y/m/d");
    $predict = trim($predict);
		var_dump ($predict);
		var_dump ($n_id);

    if($predict == $n_id){
			$sql_update = "UPDATE `activity` SET `timeend` = '".end($time2)."' WHERE ac_id = '".$ac_id."'";

			//$sql_update = "UPDATE `activity` SET `timeend` = '".$time2[$x*5]."' WHERE ac_id = '".$ac_id."'";
	    $result3 = mysqli_query($con, $sql_update);
			if ($result3) {

				echo "update\n";
				echo $sql_update;
			}else {
				echo "No update";
			}
		}
		else{

			$sql_insert = "INSERT INTO `activity`(`e_id`, `w_id`, `n_id`, `date`, `timestart`, `timeend`) VALUES('".$e_id."','".$w_id."','".$predict."','".$date1."','".$time2[0]."','".end($time2)."')";
			//$sql_insert = "INSERT INTO `activity`(`e_id`, `w_id`, `n_id`, `date`, `timestart`, `timeend`) VALUES('".$e_id."','".$w_id."','".$predict[$x]."','".$date1."','".$time2[$x*5]."','".$time2[$x*5+4]."')";
			$result3 = mysqli_query($con, $sql_insert);
			if ($result3) {
				echo "insert\n";
				echo $sql_insert;
			}
			else {
				echo "No insert";
			}

		}
//}
mysqli_close($con);

?>
