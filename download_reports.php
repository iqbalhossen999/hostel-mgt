<?php
//include required files
require_once("includes/session.php");
require_once("includes/db_config.php");
require_once("includes/db_connect.php");
require_once("libraries/user.class.php");
require_once("includes/header.php");

//check for loggedin
$usr = $user->getUser();
if(empty($usr)){
	$url = 'index.php';
	redirect($url);
}

global $dbObj;
$cur_user_group_id = $usr[0]->group_id;
$catType = $_SESSION['report_type'];

//Associatiive Array with the query
$sql = $_SESSION['report_query'];
echo $sql;
exit;
$correspondencelist = $dbObj->selectDataObjAssoc($sql);
$array_size = count($correspondencelist);

if($catType == 'exercise'){
	for($i = 0; $i<$array_size; $i++){
		if($cur_user_group_id == '5'){
			$grade = findGrade($correspondencelist[$i]['ds_marking']);
		}else if($cur_user_group_id == '4'){
			$grade = findGrade($correspondencelist[$i]['total_mark']);
		}
		$correspondencelist[$i]['Grade'] = $grade;
	}
}


//Call Function 
showExport($correspondencelist,'DSCSC', 'csv', $catType, $cur_user_group_id);


function showExport($rows, $title, $option, $catType, $cur_user_group_id){
	$data = "" ;
    $sep = "\t"; //tabbed character
	  
    if(count($rows)>0){
          
		//Find out total fields dynamically
		$fields = (array_keys($rows[0]));
		$columns = count($fields);
		// Put the name of all fields to $out.
		/*if($cur_user_group_id == '5'){
			for ($i = 0; $i < $columns; $i++) {
				if($i == 0){	
					$data .= 'Ser No'.$sep;		
					$data .= 'Course No'.$sep;
				}else if($i == 1){
					$data .= 'Rank'.$sep;
				}else if($i == 2){
					$data .= 'Official Name'.$sep;
				}else if($i == 3){
					$data .= '%'.$sep;
				}else if($i == 4){
					$data .= 'Marks'.$sep;
				}else if($i == 5){
					$data .= 'Weightage'.$sep;
				}else{
					$data .= $fields[$i].$sep;
				}//if
			}//for
		}else if($cur_user_group_id == '4'){*/
			for ($i = 0; $i < $columns; $i++) {
				if($i == 0){	
					$data .= 'Ser No'.$sep;		
					$data .= 'Course No'.$sep;
				}else if($i == 1){
					$data .= 'Rank'.$sep;
				}else if($i == 2){
					$data .= 'Official Name'.$sep;
				}else if($i == 3){
					$data .= 'Syndicate'.$sep;
				}else if($i == 4){
					$data .= '%'.$sep;
				}else if($i == 5){
					$data .= 'Marks'.$sep;
				}else if($i == 6){
					$data .= 'Weightage'.$sep;
				}else{
					$data .= $fields[$i].$sep;
				}//if
			}//for
		//}
		$data .= "\n";
		

		for($k=0; $k < count( $rows ); $k++) {
			$row = $rows[$k];
			$line = '';
			
			$f_counter = 0;
			foreach ($row as $value) {
				
				//For Name instead of ID/Number
				if($f_counter == 1){
					$rank = getNameById("rank", $value);
					$value = $rank->name;
				}
				
				if($cur_user_group_id == '4' && $f_counter == '3'){
					$syndicate = getNameById("syndicate", $value);
					$value = $syndicate->name;
				}
				
				if($f_counter == 0){
					$sl = (int)$k+1;
					$sl = str_replace('"', '""', $sl);
					$line .= '"' . $sl . '"' . "\t";
					$line .= '"' . $value . '"' . "\t";
				}else{
					$value = str_replace('"', '""', $value);
					$line .= '"' . $value . '"' . "\t";
				}
			  	$f_counter++;
			}
			$data .= trim($line)."\n";
		}
		
		$data = str_replace("\r","",$data);
		
		if (count( $rows ) == 0) {
		  $data .= "\n(0) Records Found!\n";
		}
		
	}else{
		$data = "\n(0) Records Found!\n";
	}
 
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".$title."_report.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	header("Lacation: excel.htm?id=yes");
	print $data ;
	die();
}
?>	  
