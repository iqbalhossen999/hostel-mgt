<?php

//Find Name by ID
function getNameById($table, $id){
	global  $dbObj;
	$sql = "select * from ".DB_PREFIX.$table." where id='".$id."'";	
	$result = $dbObj->selectDataObj($sql);
	return $result[0];
}

//Welcome Message
function welcomeMsg($id){
	global  $dbObj;
	$sql = "select full_name from ".DB_PREFIX."user where id='".$id."'";	
	$result = $dbObj->selectDataObj($sql);
	
	$message = WELCOME.$result[0]->full_name;
	return $message;
}

//Redirect to a URL
function redirect($url = ''){
	echo '<script type="text/javascript">
	window.location = "'.$url.'"
	</script>';
	exit;
}

function pagination($total_rows, $per_page ,$page = 1, $param='', $url = '?'){       
		$total = $total_rows;
        $adjacents = "2"; 

    	$page = ($page == 0 ? 1 : $page);  
    	$start = ($page - 1) * $per_page;								
		
    	$prev = $page - 1;							
    	$next = $page + 1;
        $lastpage = ceil($total/$per_page);
    	$lpm1 = $lastpage - 1;
    	
    	$pagination = "";
    	if($lastpage > 1)
    	{	
    		$pagination .= "<ul class='pagination'>";
                    $pagination .= "<li class='details'>Page $page of $lastpage</li>";
    		if ($lastpage < 7 + ($adjacents * 2))
    		{	
    			for ($counter = 1; $counter <= $lastpage; $counter++)
    			{
    				if ($counter == $page)
    					$pagination.= "<li><a class='current'>$counter</a></li>";
    				else
    					$pagination.= "<li><a href='{$url}page=".$counter.$param."'>".$counter."</a></li>";					
    			}
    		}
    		elseif($lastpage > 5 + ($adjacents * 2))
    		{
    			if($page < 1 + ($adjacents * 2))		
    			{
    				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><a class='current'>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='{$url}page=".$counter.$param."'>".$counter."</a></li>";					
    				}
    				$pagination.= "<li class='dot'>...</li>";
    				$pagination.= "<li><a href='{$url}page=$lpm1".$param."'>$lpm1</a></li>";
    				$pagination.= "<li><a href='{$url}page=$lastpage".$param."'>$lastpage</a></li>";		
    			}
    			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
    			{
    				$pagination.= "<li><a href='{$url}page=1".$param."'>1</a></li>";
    				$pagination.= "<li><a href='{$url}page=2".$param."'>2</a></li>";
    				$pagination.= "<li class='dot'>...</li>";
    				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><a class='current'>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='{$url}page=".$counter.$param."'>".$counter."</a></li>";					
    				}
    				$pagination.= "<li class='dot'>..</li>";
    				$pagination.= "<li><a href='{$url}page=$lpm1".$param."'>$lpm1</a></li>";
    				$pagination.= "<li><a href='{$url}page=$lastpage".$param."'>$lastpage</a></li>";		
    			}
    			else
    			{
    				$pagination.= "<li><a href='{$url}page=1".$param."'>1</a></li>";
    				$pagination.= "<li><a href='{$url}page=2".$param."'>2</a></li>";
    				$pagination.= "<li class='dot'>..</li>";
    				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><a class='current'>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='{$url}page=".$counter.$param."'>".$counter."</a></li>";					
    				}
    			}
    		}
    		
    		if ($page < $counter - 1){ 
    			$pagination.= "<li><a href='{$url}page=$next".$param."'>Next</a></li>";
                $pagination.= "<li><a href='{$url}page=$lastpage".$param."'>Last</a></li>";
    		}else{
    			$pagination.= "<li><a class='current'>Next</a></li>";
                $pagination.= "<li><a class='current'>Last</a></li>";
            }
    		$pagination.= "</ul>\n";		
    	}
    
        return $pagination;
}

//This funcction uploads multiple files
//Input: array of uploadable files
//Output: array of file names 
function upload_file($files, $path, $user_id, $file_ext){
	$uploaded = array();
	$counter = 0;
	$err_counter = 0;
	if(!empty($files)){
			if(!empty($files['name'])){
				$temp_name = $files['name'];
				$temp_arr = explode(".", $temp_name);
				$name = $temp_arr[0];
				$ext = strtolower($temp_arr[sizeof($temp_arr)-1]);
				$size = $item['size'];
				
				$file = $name.'_'.$user_id.'_'.date('YmdHis').'.'.$ext;
				//form exact path with name
				$upload_file = $path.$file;
				
				$uploaded['file'][$counter]['file_name'] = $file;
				$uploaded['file'][$counter]['tmp_name'] = $files['tmp_name'];
				$uploaded['file'][$counter]['uploadable'] = $upload_file;
				$uploaded['file'][$counter]['upfile'] = $file;
				
				if(empty($file_ext)){
					$ext_arr = array(
							'0' => 'pdf',
							'1' => 'doc',
							'2' => 'docx',
							'3' => 'xls',
							'4' => 'jpg',
							'5' => 'jpeg',
							'6' => 'png',
							'7' => 'gif'
							);
				}else{
					$ext_arr = explode(',', $file_ext);
				}
				
			//Check for accepted extentions and size allowed: 256KB
			if((in_array($ext, $ext_arr)) && ($size < 262145)){
			}else{
				if((!in_array($ext, $ext_arr)) && ($size > 262145)){
					$msg = "Attached file format is not supported and File size is bigger than 256 KB";
				}else if((!in_array($ext, $ext_arr)) && ($size < 262145)){
					$msg = "Attached file format is not supported";
				}else{
					$msg = "File size is bigger than 256 KB";
				}
				
				//Track Error
				$uploaded['error'][$counter] = $msg;
				$err_counter++;
			}
				$counter++;
		}//if - name is not empty
		
		//Now Upload the Files if no error found
		$up_counter = 0;
		
		if($err_counter == 0 && !empty($uploaded['file'])){
			foreach($uploaded['file'] as $ufile){
				if(move_uploaded_file($ufile['tmp_name'],$ufile['uploadable'])){
					$uploaded['uploaded'][$up_counter] = $ufile['upfile'];					
				}
				$up_counter++;
			}
		}
		$uploaded['error_counter'] = $err_counter;
	}//if	
	
	return $uploaded;
}//function


//Receive Date of format: 2011-06-28
//Return Date as of format: 28 June, 2011
function dateConvertion($date='0000-00-00'){
	
	$datePortion = explode("-", $date);	
								
	$timeStamp = mktime(0,0,0, $datePortion[1] , $datePortion[2], $datePortion[0]);
	$newDate = date("d F, Y", $timeStamp);
	
	return $newDate;
}

//Receive Date of format: 2011-06-28 03:05:15
//Return Date as of format: 28 June, 2011
function dateTimeConvertion($dateString='0000-00-00 00:00:00'){
	$dateStr = explode(" ", $dateString);
	$date = $dateStr[0];
	$time = $dateStr[1];
	
	$datePortion = explode("-", $date);
	$timePortion = explode(":", $time);
	
	$timeStamp = mktime($timePortion[0], $timePortion[1], $timePortion[2], $datePortion[1] , $datePortion[2], $datePortion[0]);
	$newDate = date("d F, Y h:i:s A", $timeStamp);
	
	return $newDate;
}


//This function creates a select box with supplied info
function formSelectElement($options, $selected = '', $name = 'select_element', $param = ''){
	$str = '';
	$elem = '<select name="'.$name.'" id="'.$name.'" '.$param.'>';
	//$elem .= 	'<option  value="">'.$first_opt.'</option>';
		
	if(!empty($options)){
		if(is_array($selected)){			
			foreach($options as $key => $val){
				if(in_array($key, $selected)){
					$str = 'selected="Selected"';
				}else{
					$str = '';
				}
				$elem1 = '<option '.$str.' value="'.$key.'">'.$val.'</option>';	
				$elem .= $elem1;		
			}//foreach	
		}else{		
			foreach($options as $key => $val){
				if($key == $selected){
					$str = 'selected="Selected"';
				}else{
					$str = '';
				}
				
				$elem1 = '<option '.$str.' value="'.$key.'">'.$val.'</option>';	
				$elem .= $elem1;		
			}//foreach	
		}//else
	}//if
	$elem .= '</select>';
		
	return $elem;
}


function view_number($number){
	$num = $number;
	$num = number_format($num, 2, '.', '');
	return $num;
}

//This function draw a line graph
function draw_line($arr,$title){

	include "libchart/classes/libchart.php";

	$chart = new LineChart();

	$dataSet = new XYDataSet();
	
	if(!empty($arr)){
		foreach($arr as $key=>$val){
			$dataSet->addPoint(new Point($key, $val));
		}//foreach
		$chart->setDataSet($dataSet);
		$chart->setTitle($title);
		$chart->render("graph/demo5.png");
	}
}


//Get Current time :: This function outputs the current time time in GMT +06.00 format
function current_date_time(){
	$target = date('Y-m-d H:i:s', mktime(date('H')+6, date('i'), date('s'), date('m'), date('d'), date('Y')));
	return $target;
}//function

//Get Current Date :: This function outputs the current Date in GMT +06.00 format
function current_date(){
$target = date('Y-m-d', mktime(date('H')+6, date('i'), date('s'), date('m'), date('d'), date('Y')));
return $target;
}//function

//Find HTTP Reffer 
function http_reffer(){
	$reffer_page = $_SERVER['HTTP_REFERER'];
	$explode = explode("/", $reffer_page);
	$leng = sizeof($explode);
	$reffer_page = $explode[($leng-1)];
	$explode = explode("?", $reffer_page);
	$target = $explode[0];

	return $target;
}//function

//This function generate Security Key while creating user
function generateSecKey ($length = 8){

   // start with a blank password
   $password = "";
   $possible = "123467890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNPQRTVWXYZ!@#$%^&*";

   // we refer to the length of $possible a few times, so let's grab it now
   $maxlength = strlen($possible);
 
   // check for length overflow and truncate if necessary
   if ($length > $maxlength) {
     $length = $maxlength;
   }
       
   // set up a counter for how many characters are in the password so far
   $i = 0;
   
   // add random characters to $password until $length is reached
   while ($i < $length) {

     // pick a random character from the possible ones
     $char = substr($possible, mt_rand(0, $maxlength-1), 1);
       
     // have we already used this character in $password?
     if (!strstr($password, $char)) {
       // no, so it's OK to add it onto the end of whatever we've already got...
       $password .= $char;
       // ... and increase the counter by one
       $i++;
     }

   }

   // done!
   return $password;
}//function 

function process_si_contact_form(){
  $_SESSION['ctform'] = array(); // re-initialize the form session data

  if ($_SERVER['REQUEST_METHOD'] == 'POST' && @$_POST['do'] == 'contact') {
  	// if the form has been submitted

    foreach($_POST as $key => $value) {
      if (!is_array($key)) {
      	// sanitize the input data
        if ($key != 'ct_message') $value = strip_tags($value);
        $_POST[$key] = htmlspecialchars(stripslashes(trim($value)));
      }
    }

    $captcha = @$_POST['ct_captcha']; // the user's entry for the captcha code

    $errors = array();  // initialize empty error array

    if (isset($GLOBALS['DEBUG_MODE']) && $GLOBALS['DEBUG_MODE'] == false) {
      // only check for errors if the form is not in debug mode
    }

    // Only try to validate the captcha if the form has no errors
    // This is especially important for ajax calls
    if (sizeof($errors) == 0) {
      require_once dirname(__FILE__) . '/securimage.php';
      $securimage = new Securimage();

      if ($securimage->check($captcha) == false) {
        $errors['captcha_error'] = 'Incorrect security code entered<br />';
      }
    }

    if (sizeof($errors) == 0) {
      $_SESSION['ctform']['error'] = false;  // no error with form
      $_SESSION['ctform']['success'] = true; // message sent
    } else {
      foreach($errors as $key => $error) {
        $_SESSION['ctform'][$key] = "<span style=\"font-weight: bold; color: #f00\">$error</span>";
      }

      $_SESSION['ctform']['error'] = true; // set error floag
    }
  } // POST
}


//edit by sahadat 02-09-2012
function random_number($lenth=8) {
	$salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$len = strlen($salt);
	$makepass="";
	mt_srand(10000000*(double)microtime());
	for ($i = 0; $i < $lenth; $i++)
	$makestr .= $salt[mt_rand(0,$len - 1)];
	return $makestr;
}

function sendMail($to, $from, $subject, $message){
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$headers .= "From: $from\r\n";

	// now lets send the email.
	if(mail($to, $subject, $message, $headers)){
		return true;
	}else{
		return false;
	}
}
function get_date_from_sec($sec){
	$conv_sec = getdate($sec);
	$target_year = $conv_sec['year'];
	
	$target_month = $conv_sec['mon'];
	if(strlen($target_month) == 1){
		$target_month = '0'.$target_month;
	}
	$target_mday = $conv_sec['mday'];
	
	if(strlen($target_mday) == 1){
		$target_mday = '0'.$target_mday;
	}
	
	$target = $target_year.'-'.$target_month.'-'.$target_mday;
	return $target;
}

//This function gives the date difference (How many days)
//between two given dates ($startdate & $enddate)
function date_difference($start_date, $end_date){
	$diff = abs(strtotime($end_date) - strtotime($start_date));
	$years = floor($diff / (365*60*60*24));
	$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
	$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
	$target = $days + ($months * 30) + ($years * 365);
	
	return $target;
}

//Month List Arry Function
function monthList($month){
	$monthArray = array(
		'00' => '--Select Month--',
		'01'=>'January',
		'02'=>'February',
		'03'=>'March',
		'04'=>'April',
		'05'=>'May',
		'06'=>'June',
		'07'=>'July',
		'08'=>'August',
		'09'=>'Septermber',
		'10'=>'October',
		'11'=>'November',
		'12'=>'December'		
	);
	$monthId = array();
	if(!empty($monthArray)){			
		foreach($monthArray as $key=>$val){
			$monthId[$key] = $val;
		}//foreach
	}//if
	$monthList_opt = formSelectElement($monthId, $month, 'month');

	return $monthList_opt;
}//function

//directory search error function
function genterate_page_title(){
	$file = $_SERVER["SCRIPT_NAME"];
	$break = explode('/', $file);
	$uri = $break[count($break) - 1];
	
		if($uri == 'dashboard.php'){
		$target = 'Dashboard';
		}else if($uri == 'login.php'){
		$target = 'Login';
		}else if($uri == 'male_hall.php'){
		$target = 'Male Hall';
		}else if($uri == 'female_hall.php'){
		$target = 'Female Hall';
		}else if($uri == 'prebooking.php'){
		$target = 'Prebooking';
		}else if($uri == 'about_us.php'){
		$target = 'About Us';
		}else if($uri == 'contact.php'){
		$target = 'Contact Us';
		}else if($uri == 'hall.php'){
		$target = 'Hall';
		}else if($uri == 'block.php'){
		$target = 'Block';
		}else if($uri == 'floor.php'){
		$target = 'Floor';
		}else if($uri == 'room.php'){
		$target = 'Room';
		}else if($uri == 'seat.php'){
		$target = 'Seat';
		}else if($uri == 'session.php'){
		$target = 'Session';
		}else if($uri == 'user.php'){
		$target = 'User';
		}else if($uri == 'pre_request.php'){
		$target = 'Pre_Request';
		}else if($uri == 'studentlist.php'){
		$target = 'StudentList';
		}else if($uri == 'form.php'){
		$target = 'Admission Form';
		}else if($uri == 'hallcharge.php'){
		$target = 'Hall Charge';
		}else if($uri == 'room_facilities.php'){
		$target = 'Room Facilities';
		}else if($uri == 'time_setup.php'){
		$target = 'Time Setup';
		}else if($uri == 'patern.php'){
		$target = 'Patern';
		}else if($uri == 'change_pass.php'){
		$target = 'Change Password';
		}else if($uri == 'guest_meal_mgt.php'){
		$target = 'Guest Meal Management';
		}else if($uri == 'unit.php'){
		$target = 'Unit';
		}else if($uri == 'product.php'){
		$target = 'Product';
		}else if($uri == 'product_category.php'){
		$target = 'Product Category';
		}else if($uri == 'stock.php'){
		$target = 'Stock';
		}else if($uri == 'view_stock.php'){
		$target = 'View Stock';
		}else if($uri == 'consume.php'){
		$target = 'Consume';
		}else if($uri == 'view_consume.php'){
		$target = 'View Consume';
		}else if($uri == 'available_stock.php'){
		$target = 'Available Stock';
		}else if($uri == 'meal_order_view.php'){
		$target = 'Meal Order View';
		}else if($uri == 'view_order.php'){
		$target = 'View Order';
		}else if($uri == 'individual_date_wise.php'){
		$target = 'Individual Date Wise';
		}else if($uri == 'date_wise_order.php'){
		$target = 'Date Wise Order';
		}else if($uri == 'date_wise.php'){
		$target = 'Date Wise';
		}else if($uri == 'stock_rep.php'){
		$target = 'Stock Report';
		}else if($uri == 'consump_rep.php'){
		$target = 'Consume Report';
		}else if($uri == 'aval_rep.php'){
		$target = 'Available Stock Report';
		}else if($uri == 'student_mess_report.php'){
		$target = 'Student Mess Report';
		}else if($uri == 'report.php'){
		$target = 'Student Monthly Bill Report';
		}else if($uri == 'item_stock_rep.php'){
		$target = 'Itemwise Stock Report';
		}else if($uri == 'item_consump_rep.php'){
		$target = 'Itemwise Consumption Report';
		}else if($uri == 'search_result.php'){
		$target = 'Search';
		}else if($uri == 'passforgot.php'){
		$target = 'Forgot Password';
		}else if($uri == 'resetpass.php'){
		$target = 'Password Reset';
		}
	
	$target = 'IIUC Hall &raquo; '.$target;
	return $target;
}//function

//redirect from dashboard
function dashboard(){
	$url = 'dashboard.php';
	redirect($url);
}//function

//Forget Password Function
function password_mail($id, $to, $official_name){
	global  $dbObj;
	//Update the recovery field in the secondary user table
	$token_id = md5(uniqid($_SERVER['REMOTE_ADDR']));
	
	$fields = array('token_id' => $token_id);
	$where = "id = '".$id."'";
	$update = $dbObj->updateTableData("user", $fields, $where);	
	
	if(!$update){
		$msg = 'Invalid arguments supplied for updating Token ID';
		$url = 'passforgot.php?action=view&msg='.$msg;
		redirect($url);
	}else{
		$from = NOTIFICATION_EMAIL;
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		$headers .= "From: $from\r\n";
		
		$subject = "BUP Hall : Password Reset Operation";
		$content = 	
		"Dear $official_name,<br /><br />
		Your Password Reset Opertion has been approved.<br />
		Your Token ID is <strong>$token_id</strong><br />
		Please, Copy the Token ID and <a href=\"http://".BUP_HALL_ADDRESS."/resetpass.php?action=first\">click here</a> to Reset your password.<br />
		Thank you for using BUP Hall Management Software<br /><br />
		";
		
		// now send the email.
		if(mail($to, $subject, $content, $headers)){
			return true;
		}else{
			return false;
		}//else
	}//else
}//function

//Status Function
function timeZones($time_zone, $time_zone_name, $time_zone_val){
	$timeZonesArray = array(
		'0' => 'AM',
		'1' => 'PM'		
	);
	
	$timeZonesId = array();
	if(!empty($timeZonesArray)){			
		foreach($timeZonesArray as $key=>$val){
			$timeZonesId[$key] = $val;
		}//foreach
	}//if
	$time_zoneList_opt = formSelectElement($timeZonesId, $time_zone_name, $time_zone_val);

	return $time_zoneList_opt;
}//function



?>