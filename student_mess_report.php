<?php
require_once("includes/header.php");

//check for loggedin
$usr = $user->getUser();
if(empty($usr)){
	$url = 'index.php';
	redirect($url);
}

$cur_user_id = $usr[0]->id;
$cur_user_group_id = $usr[0]->group_id;
$action = $_REQUEST['action'];
$msg = $_REQUEST['msg'];

if($cur_user_group_id != '1'){
	dashboard();
}//if

//Pagination
$limit = PAGE_LIMIT_DEFAULT;

//Get Page Number 
if(empty($_REQUEST['page'])) {
	$page=1;
}else{
	$page = $_REQUEST['page']; 
}
switch($action){

	case 'view':
		default:
			$posted = $_REQUEST['posted'];
			
			if($posted == "true"){
				$year = $_REQUEST['year'];
				$month = $_REQUEST['month'];
				$hall_id = $_REQUEST['hall_id'];
			//	$month = 
				$sql_year = "SELECT distinct(id) sess_id, session_year FROM ".DB_PREFIX."session where session_year ='".$year."'";
				$sess_yearArr = $dbObj->selectDataObj($sql_year);
				$sess_id = $sess_yearArr[0]->sess_id;
				 
				$sql = "select p.user_id ,p.hall_id, p.session, p.registration_no, p.name, p.course_name, s.estab, s.readm, s.sd, s.messad, s.donation, s.seatrent, s.utencro, s.maint, s.crnpape, s.inter, s.conti from ".DB_PREFIX."prebooking as p, ".DB_PREFIX."seat_charge as s where p.seat_id != 0 AND s.seat_id = p.seat_id AND  p.session = '".$sess_id."' AND p.hall_id = '".$hall_id."'";
				$mess_report = $dbObj->selectDataObj($sql);
				
				//Pagination 
				$total_rows = (!empty($mess_report)) ? sizeof($mess_report) : 0;
				$s = ($page - 1) * $limit;
				$total_page = $total_rows/$limit;
			}
			//Build Hall Array
			$sql = "select id, name from ".DB_PREFIX."hall order by name asc";
			$hallArr = $dbObj->selectDataObj($sql);
			
			$hallId = array();
			$hallId[0] = SELECT_HALL_OPT;
			if(!empty($hallArr)){			
				foreach($hallArr as $item){
					$hallId[$item->id] = $item->name;
				}	
			}			
			$hallList_opt = formSelectElement($hallId, $hall_id, 'hall_id');
			
			$yearArr = array();
			for($i = 2012; $i <= date('Y'); $i++){
				$yearArr[$i] = $i;
			}
			$yearList_opt = formSelectElement($yearArr, $year, 'year');
			
		$action = 'view';
		break;

}//switch

require_once("includes/templates.php");
require_once("templates/top_menu.php");
require_once("templates/left_menu.php");
?>

<div id="right_column">
	<table width="100%" cellspacing="0" cellpadding="0" border="0" class="module_header">
		<tr>
			<td>
				<h1><?php echo STUDENT_MESS_BILL_REPORT; ?></h1>
			</td>
			<td class="usr_info">
				<?php echo welcomeMsg($cur_user_id); ?>
			</td>			
		</tr>
	</table>
	<?php
		if($action=="view"){
	?>
		<form action="student_mess_report.php" method="post" name="student_mess_report" id="student_mess_report" onsubmit="return validateStudent_mess_bill();" >
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">	
				<tr>
					<td height="30" width="20%"><?php echo YEAR; ?>:</td>
					<td width="80%"><?php echo $yearList_opt; ?></td>
				</tr>
				<tr>
					<td height="30"><?php echo MONTH; ?>:</td>
					<td><?php echo monthList($month); ?></td>
				</tr>
				<tr>
					<td height="30"><?php echo HALL_NAME; ?>:</td>
					<td><?php echo $hallList_opt; ?></td>
				</tr>	
				<tr>
					<td colspan="2" height="30">
						<input type="submit" name="submit" class="button" value="View Report"/>
					</td>
				</tr>	
			</table>
			<input type="hidden" name="action" value="view" />
			<input type="hidden" name="posted" value="true" />
		</form>
		
	<?php if($posted == 'true'){ ?>
	
		<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
			<tr>
				<td><a href="student_mess_report_download.php"><img src="images/excel.png" height="24" width="24" alt="save the report" title="save the report" style="padding-bottom:10px;"/></a><br /></td>
			<tr>
			<tr>
				<td colspan="2">
					<h1><?php echo STUDENT_MESS_BILL_REPORT; ?></h1>
				</td>
			</tr>	
			<tr>
				<td colspan="2">
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="datagrid">				
						<tr class="head">
							<td height="30" width="20%">
								<strong><?php echo SL_NO; ?></strong>
							</td>
							<td width="20%">
								<strong><?php echo REGISTRATION_NO; ?></strong>
							</td>
							<td width="20%">
								<strong><?php echo STUDENT_NAME; ?></strong>
							</td>
							<td width="20%">
								<strong><?php echo COURSE_NAME; ?></strong>
							</td>
							<td width="20%" align="right">
								<strong><?php echo MESS_BILL; ?></strong>
							</td>
						</tr>
						
						<?php
							//For downloading Reports as XLS format
							//if group_id == 1 --->>> Only Super Admin can download the reprot
								$downloadTitle[0] = 'Student Mess Bill Report'."\n";
								$arr[0]['sl'] = 'Sl No';
								$arr[0]['registration_no'] = 'Registration No';
								$arr[0]['name'] = 'Student Name';
								$arr[0]['course_name'] = 'Course Name';
								$arr[0]['total'] = 'Mess Bill';

						if(!empty($mess_report)){
							$total_messBill = 0;$rownum = 0;
							$r =1;
							foreach($mess_report as $report){
								$sql = "select sum(breakfast_cost + lunch_cost + dinner_cost) as total from ".DB_PREFIX."meal  where student_id = '".$report->user_id."' AND order_date >= '$year-$month-01' AND order_date <= '$year-$month-31'";
								$messBill= $dbObj->selectDataObj($sql);
								//echo '<pre>';print_r($report->registration_no);
								$arr[$r]['sl'] = $r;
								$arr[$r]['registration_no'] = $report->registration_no;
								$arr[$r]['name'] = $report->name;
								$arr[$r]['course_name'] = $report->course_name;
								$arr[$r]['total'] = view_number($messBill[0]->total);
								$r++;
								$total_messBill +=$messBill[0]->total;
								
							}	
							
							$sl = ($limit*$page)-($limit-1);	
							$maxPageLimit = (($s+$limit) > $total_rows) ? $total_rows : ($s+$limit);
							for($rownum = $s; $rownum <$maxPageLimit; $rownum++){
								//$sl = $rownum+1;		
								$class = (($rownum%2)==0) ? ' class="even"' : ' class="odd"';
							$sql = "select sum(breakfast_cost + lunch_cost + dinner_cost) as total from ".DB_PREFIX."meal  where student_id = '".$mess_report[$rownum]->user_id."' AND order_date >= '$year-$month-01' AND order_date <= '$year-$month-31'";
							$messBill= $dbObj->selectDataObj($sql);
		
						?>
								
						<tr <?php echo $class; ?>>
							<td height="30"><?php echo $sl; ?></td>	
							<td><?php echo $mess_report[$rownum]->registration_no;?></td>				
							<td><?php echo $mess_report[$rownum]->name;?></td>
							<td><?php echo $mess_report[$rownum]->course_name; ?></td>
							<td align="right"><?php echo view_number($messBill[0]->total);?></td>
						</tr>
							
						<?php  
							$sl++;
							$s++;
							}//for
						?>
						<tr height="50">
							<td colspan="5" align="right">
								<strong><?php echo TOTAL_MESS_BILL; ?>:</strong>
								<?php echo view_number($total_messBill); ?>&nbsp;
							</td>
						</tr>
						
					<?php }else{ ?>
							
						<tr height="30">
							<td colspan="5">
								<?php echo EMPTY_DATA; ?>
							</td>
						</tr>
							
						<?php 
							}//else
							if($total_page > 1){ 
						?>
							
						<tr height="50">
							<td colspan="5"><?php echo pagination($total_rows,$limit,$page, '&posted='.$posted.'&year='.$year.'&month='.$month.'&hall_id='.$hall_id); ?></td>
						</tr>
						<?php }//if 
							$arr[$r+1]['sl'] = "\n";
							$arr[$r+1]['sl'] = '';
							$arr[$r+1]['registration_no'] = '';
							$arr[$r+1]['name'] = '';
							$arr[$r+1]['course_name'] = 'Net Total Bill';
							$arr[$r+1]['total'] = view_number($total_messBill);
							//echo '<pre>';print_r($arr);
						?>					
					</table>
				</td>
			</tr>
		</table>
	<?php }//if true 	
		
		$_SESSION['student_mess_report'] = '';
		$_SESSION['student_mess_report'][0] = $downloadTitle; 
		$_SESSION['student_mess_report'][1] = $arr;
		
		}//if view
	?>	
</div>
			
<?php
require_once("includes/footer.php");
?>