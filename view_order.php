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
$start_date = $_REQUEST['start_date'];
$end_date = $_REQUEST['end_date'];

if(!empty($start_date) && empty($end_date)){
	$end_date = $start_date;
}

if(!empty($end_date) && empty($start_date)){
	$start_date = $end_date;
}

if($cur_user_group_id == '2'){
	dashboard();
}//if

switch($action){

	case 'view':
		default:
			$posted = $_REQUEST['posted'];
			
			if($posted == "true"){
				if($cur_user_group_id == '1'){
					$student_id = $_POST['student_id'];
				}else{
					$student_id = $cur_user_id;
				}
				
				$sql = "SELECT hall_id from ".DB_PREFIX."prebooking WHERE user_id = '".$student_id."'";
				$hallArr = $dbObj->selectDataObj($sql);
				$hall_id = $hallArr[0]->hall_id;
				
				$days = date_difference($start_date, $end_date);
				
				$explode = explode('-', $start_date);
				$start_day = $explode[2];
				$start_month = $explode[1];
				$start_year = $explode[0];
			}
			
			if($cur_user_group_id == '1'){
				//Build Student Array
				$sql = "select u.id, p.registration_no, p.name from ".DB_PREFIX."prebooking as p, ".DB_PREFIX."user as u WHERE u.id = p.user_id order by p.registration_no asc";
				$studentArr = $dbObj->selectDataObj($sql);
				
				$studentId = array();
				$studentId[0] = SELECT_STUDENT_OPT;
				if(!empty($studentArr)){			
					foreach($studentArr as $item){
						$studentId[$item->id] = $item->registration_no.' &raquo; '.$item->name;
					}	
				}			
				$studentList_opt = formSelectElement($studentId, $student_id, 'student_id');
			}

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
				<h1><?php echo INDIVIDUAL_STUDENT_ORDERS; ?></h1>
			</td>
			<td class="usr_info">
				<?php echo welcomeMsg($cur_user_id); ?>
			</td>			
		</tr>
	</table>
	<?php if($action=="view"){ ?>
			
			<form action="view_order.php" method="post" name="view_order" id="view_order" onsubmit="return checkDate();" >
				<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
				<?php if($cur_user_group_id == '1'){ ?>
					<tr>
						<td height="30"><?php echo SELECT_STUDENT_OPT; ?>:</td>
						<td colspan="3"><?php echo $studentList_opt; ?>
					</tr>
				<?php }//if ?>
					<tr>
						<td height="30" width="10%">
							<?php echo START_DATE; ?>:
						</td>
						<td height="30">
							<input name="start_date" id="start_date" type="text" class="inputbox readonly" readonly="readonly" alt="Start Date" size="18" value="<?php echo $start_date; ?>" />
							<img id="f_rangeStart_triggerm_start" src="date/src/css/img/calendar.gif" title="Pick a Date" />
							<img id="f_clearRangeStart" src="date/src/css/img/no.png" title="Clear Date" onClick="return makeEmpty('start_date')" height="16" width="16" />
							<script type="text/javascript">
							RANGE_CAL_1 = new Calendar({
								inputField: "start_date",
								dateFormat: "%Y-%m-%d",
								trigger: "f_rangeStart_triggerm_start",
								bottomBar: true,
								onSelect: function(){
								var date = Calendar.intToDate(this.selection.get());
									this.hide();
								}
							});
							</script>
						</td>
						<td height="30" width="10%">
							<?php echo END_DATE; ?>:
						</td>
						<td height="30">
							<input name="end_date" id="end_date" type="text" class="inputbox readonly" readonly="readonly" alt="End Date" size="18" value="<?php echo $end_date; ?>" />
							<img id="f_rangeStart_triggerm_end" src="date/src/css/img/calendar.gif" title="Pick a Date" />
							<img id="f_clearRangeStart" src="date/src/css/img/no.png" title="Clear Date" onClick="return makeEmpty('end_date')" height="16" width="16" />
							<script type="text/javascript">
							RANGE_CAL_1 = new Calendar({
								inputField: "end_date",
								dateFormat: "%Y-%m-%d",
								trigger: "f_rangeStart_triggerm_end",
								bottomBar: true,
								onSelect: function(){
								var date = Calendar.intToDate(this.selection.get());
									this.hide();
								}
							});
							</script>
						</td>
					</tr>
					<tr>
						<td>
							<input type="submit" name="submit" class="button" value="View Order"/>
						</td>
					</tr>
				</table>
				<input type="hidden" name="action" value="view" />
				<input type="hidden" name="posted" value="true" />
			</form>
			
	<?php if($posted == 'true'){ ?>
	
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
				<tr>
					<td><a href="view_order_report.php"><img src="images/excel.png" height="24" width="24" alt="save the report" title="save the report" style="padding-bottom:10px;"/></a><br /></td>
				<tr>
				<tr>
					<td colspan="8">
						<table width="100%" cellpadding="0" cellspacing="0" border="0" class="datagrid">			
							<tr class="head">
								<td width="10%"><strong><?php echo DATE; ?></strong></td>
								<td width="10%" align="center"><strong><?php echo BREAKFAST; ?></strong></td>
								<td width="10%" align="center"><strong><?php echo LUNCH; ?></strong></td>
								<td width="10%" align="center"><strong><?php echo DINNER; ?></strong></td>		
								<td width="15%" align="center"><strong><?php echo TOTAL_ORDER; ?></strong></td>					
							</tr>
							<?php
								if($cur_user_group_id == '1'){
									//For downloading Reports as XLS format
								$downloadTitle[0] = 'Individual Student Order' ."\n";

								$arr[0]['date'] = 'Date';
								$arr[0]['breakfast'] = 'Breakfast';
								$arr[0]['lunch'] = 'Lunch';
								$arr[0]['dinner'] = 'Dinner';
								$arr[0]['total_order'] = 'Total Order';
								}
							?>
							
							<?php
							if($start_date > $end_date){
								echo '<tr><td height="30" colspan="5">Start Date can not be greater than End Date</td></tr>';
							}else{
								$total_breakfast = $total_lunch = $total_dinner = $ondate_order = 0;
								for($i = 0; $i <= $days; $i++){		
									$class = (($i%2)==0) ? ' class="odd"' : ' class="even"';
									$bf_class = $ln_class = $dn_class = '';
									$ondate_order = 0;
									$sl = $i;
									//Find out Date
									$ini_day_microtime = mktime(0, 0, 0, $start_month, $start_day, $start_year);
									$day_microtime = $ini_day_microtime + $per_day_sec; //24 Hours, 60 Minutes, 60 Seconds
									$target_date = get_date_from_sec($day_microtime);
									$per_day_sec += (24 * 60 * 60);

									//Find out total order Arr
									$sql = "select * from ".DB_PREFIX."meal where order_date = '".$target_date."' AND hall_id = '".$hall_id."' AND student_id = '".$student_id."'";
									$orderArr = $dbObj->selectDataObj($sql);
									
									$breakfast = $orderArr[0]->breakfast;
									$lunch = $orderArr[0]->lunch;
									$dinner = $orderArr[0]->dinner;
									
									if($breakfast >= '1'){
										$bf_class = 'class="brekfast"';
										$total_breakfast += $breakfast;
										$ondate_order += $breakfast;
									}elseif($breakfast == ''){
										$breakfast = '0';
									}
									
									if($lunch >= '1'){
										$ln_class = 'class="lunch"';
										$total_lunch += $lunch;
										$ondate_order += $lunch;
									}elseif($lunch == ''){
										$lunch = '0';
									}
									
									if($dinner >= '1'){
										$dn_class = 'class="dinner"';
										$total_dinner += $dinner;
										$ondate_order += $dinner;
									}elseif($dinner == ''){
										$dinner = '0';
									}
									
									$total_order = $total_breakfast + $total_lunch + $total_dinner;
									
							?>
							<tr <?php echo $class; ?>>
							
								<td align="center" height="30"><?php echo $target_date;
								 $arr[$sl+1]['date'] = $target_date;
								 ?>
								 </td>
								 
								<td align="center" <?php echo $bf_class;?>>
								<?php  $arr[$sl+1]['breakfast'] = $breakfast; echo $breakfast; ?>
								</td>
								
								<td align="center"<?php echo $ln_class;  ?>>
								<?php $arr[$sl+1]['lunch'] = $lunch; echo $lunch; ?>
								 </td>
								 
								<td align="center"<?php echo $dn_class;  ?>>
								<?php  $arr[$sl+1]['dinner'] = $dinner; echo $dinner?>
								 </td>
								 
								<td align="center"><?php echo $ondate_order; 
								$arr[$sl+1]['total_order'] = $ondate_order;?>
								</td>	
								
							</tr>
							<?php 
							}//for
							$total_cost = $total_breakfast + $total_lunch + $total_dinner;
								$arr[$i+1]['date'] = 'Total:';
								$arr[$i+1]['breakfast'] = $total_breakfast;
								$arr[$i+1]['lunch'] = $total_lunch;
								$arr[$i+1]['dinner'] = $total_dinner;
								$arr[$i+1]['total_order'] = $total_cost;
							?>
							<tr class="head">
								<td height="30"><strong><?php echo TOTAL; ?>:</strong></td>
								<td align="center"><strong><?php echo $total_breakfast; ?></strong></td>
								<td align="center"><strong><?php echo $total_lunch; ?></strong></td>
								<td align="center"><strong><?php echo $total_dinner; ?></strong></td>
								<td align="center"><strong><?php echo $total_cost; ?></strong></td>
							</tr>
						<?php }//if ?>
						</table>
					</td>
				</tr>
			</table>
	
	<?php 
		}//if submitted
	?>
	<?php

	
		$_SESSION['requisition'] = '';
		$_SESSION['requisition'][0] = $downloadTitle; 
		$_SESSION['requisition'][1] = $arr;

	}
	?>



</div>
			
<?php
require_once("includes/footer.php");
?>