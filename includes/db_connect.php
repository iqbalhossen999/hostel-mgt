<?php
class dbConnect{
	var $dbconnectid;
	var $dataset = array();
	
	//connect Database
	function dbConnect(){
		$this->dbconnectid = mysqli_connect(HOST_NAME, USER_NAME, PASSWORD, DB_NAME);
		
		if (!$this->dbconnectid) {
			die('Could not connect: ' . mysqli_error());
		}else{
			// make foo the current db
			$db_selected = mysqli_select_db($this->dbconnectid,DB_NAME);

			if (!$db_selected) {
				die ('Can\'t use hms: ' . mysqli_error());
			}//if
		}//else				
	}//function - constructor		
	
	//Get data from database as object 
	function selectDataObj($query = ''){
		// Perform Query
		$result = array();
		$data = array();
		$this->dataset = array();
		
		$result = mysqli_query($this->dbconnectid,$query);
		
		//print_r($query);exit;
		$incr = 0;
		while($data = mysqli_fetch_object($result)){
			$this->dataset[$incr] = $data;
			$incr++;
		}//while
		return  $this->dataset;
	}
	
	//Get data from database as object 
	function selectDataObjAssoc($query = ''){
		$this->dataset = '';		
		
		// Perform Query
		$result = mysqli_query($this->dbconnectid, $query);
		$incr = 0;
		while($data = mysqli_fetch_assoc($this->dbconnectid, $result)){
			$this->dataset[$incr] = $data;
			$incr++;
		}//while
		return  $this->dataset;
	}
		
	//Get data from database as object 
	function executeData($query = ''){		
		if(mysqli_query($this->dbconnectid, $query)){
			return true;
		}else{
			return false;
		}
	}//function	
	
	//Insert Data
	function insertTableData($table, $fields)
	{
	   
		//Prepend prefix to the table
		$table = DB_PREFIX.$table;
		if (sizeof($fields) < 1) {
			return false;
		}
		isset($fields['id']) ? $customId = $fields['id'] : $customId = 0;
		//$fields = addSlashes($fields);
		array_walk($fields, create_function('&$v, $k', 'if (is_string($v)) $v = "\'".$v."\'"; else if (is_null($v)) $v = "null"; else if ($v === false) $v = 0; else if ($v === true) $v = 1;'));
		$sql = "insert into $table (".implode(",", array_keys($fields)).") values (".implode(",", ($fields)).")";
		//print_r($sql);exit;
				
		$result = $this -> executeData($sql);
		
		if ($result) {
			if (!$customId) {
			 $id = $this->Insert_ID();
			} else {
			 $id = $customId;
			}
			if ($id == 0) {
				return true;
			} else {
				return $id;
			}
		} else {
			return false;
		}			
			
	}
		
	//Update
	function updateTableData($table, $fields, $where)
	{
		//Prepend prefix to the table
		$table = DB_PREFIX.$table;
		if (sizeof($fields) < 1) {
			return false;
		}
		//$fields = addSlashes($fields);
		array_walk($fields, create_function('&$v, $k', 'if (is_string($v)) $v = "\'".$v."\'"; else if (is_null($v)) $v = "null"; else if ($v === false) $v = 0; else if ($v === true) $v = 1; $v=$k."=".$v;'));
		$sql = "update $table set ".implode(",", $fields)." where ".$where;
		//print_r($sql);exit;
		$result = $this->executeData($sql);
		return $result;
	}
	
	//Delete
	function deleteTableData($table, $where="")
	{
		//Prepend prefix to the table
		$table = DB_PREFIX.$table;
		$sql = "DELETE FROM ".$table;
		if ($where != "") {
			$sql .= " WHERE ".$where;
		}
		//print_r($sql);//exit;
		$result = $this->executeData($sql);
		return $result;
	}
	
	function Insert_ID(){
		return mysqli_insert_id();
	}

	
}//Class

//CReate object of the class
global  $dbObj;
$dbObj = new dbConnect();
?>