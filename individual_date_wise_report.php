<?php
//include required files
require_once("includes/header.php");

//check for loggedin
$usr = $user->getUser();
if(empty($usr)){
	header("Location:login.php");
	exit;
}


global $dbObj;
$cur_user_group_id = $usr[0]->group_id;

//Chek if this user is valid for this file

	$rows_head = $_SESSION['student_bill'][0];
	$rows = $_SESSION['student_bill'][1];
	
	$data = "" ;
	$sep = "\t"; //tabbed character
	
	//for head
	$total_rows = count($rows_head);
	if($total_rows > 0){
		for($i = 0; $i <= $total_rows; $i++){
			$data .= trim($rows_head[$i])."\n";
		}
	}//if
	
	if(count($rows)>0){
		//Find out total fields dynamically
		$columns = count($fields);
	
		for($k=0; $k < count( $rows ); $k++) {
			$row = $rows[$k];
			$line = '';
			
			$f_counter = 0;
			foreach ($row as $value){
				$value = str_replace('"', '""', $value);
				$line .= '"' . $value . '"' . "\t";
				$f_counter++;
			}//if
			$data .= trim($line)."\n";
		}//if
		
		$data = str_replace("\r","",$data);
		
		if (count( $rows ) == 0) {
		  $data .= "\n(0) Records Found!\n";
		}//if
	}//if
	
	//for foot
	
	
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=individual_date_wise_report.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	header("Lacation: excel.htm?id=yes");
	print $data ;
	die();
	
?>	  
