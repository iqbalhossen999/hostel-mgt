<?php
//include required files
require_once("includes/header.php");

//check for loggedin
$usr = $user->getUser();
if(empty($usr)){
	header("Location:login.php");
	exit;
}
	$rows_head = $_SESSION['order'][0];
	$rows = $_SESSION['order'][1];
	
	$data = "" ;
	$sep = "\t"; //tabbed character
	if(count($rows)>0){
		//Find out total fields dynamically
		// echo $columns = count($fields);
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
	}//if
	
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=date_wise_order_report.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	header("Lacation: excel.htm?id=yes");
	print $data ;
	die();
	
?>	  
