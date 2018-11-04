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

if(empty($start_date) && !empty($end_date)){
	$start_date = $end_date;
}

if(empty($end_date) && !empty($start_date)){
	$end_date = $start_date;
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
				<h1><?php echo INDIVIDUAL_STUDENT_REPORTS; ?></h1>
			</td>
			<td class="usr_info">
				<?php echo welcomeMsg($cur_user_id); ?>
			</td>			
		</tr>
	</table>
	<?php if($action=="view"){ ?>
			
			<form action="individual_date_wise.php" method="post" name="individual_date_wise" id="individual_date_wise" onsubmit="return checkDate();" >
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
	<?php 
	if($posted == 'true'){ ?>
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
				<tr>
					<td><a href="individual_date_wise_report.php"><img src="images/excel.png" height="24" width="24" alt="save the report" title="save the report" style="padding-bottom:10px;"/></a><br /></td>
				<tr>
				<tr>
					<td colspan="8">
						<table width="100%" cellpadding="0" cellspacing="0" border="0" class="datagrid">			
							<tr class="head">
								<td width="10%"><strong><?php echo DATE; ?></strong></td>
								<td width="10%" align="center"><strong><?php echo BREAKFAST; ?></strong></td>
								<td width="10%" align="center"><strong><?php echo LUNCH; ?></strong></td>
								<td width="10%" align="center"><strong><?php echo DINNER; ?></strong></td>		
								<td width="15%" align="center"><strong><?php echo TOTAL_COST; ?></strong></td>					
							</tr>
							
							<?php
							//For downloading Reports as XLS format
							//if group_id == 1 --->>> Only Super Admin can download the reprot
								$downloadTitle[0] = 'Individual Student Bill Report'."\n";
								$arr[0]['target_date'] = 'Date';
								$arr[0]['breakfast'] = 'Breakfast';
								$arr[0]['lunch'] = 'Lunch';
								$arr[0]['dinner'] = 'Dinner';
								$arr[0]['ondate_cost'] = 'Total Cost';
						
							 ?>
							
							<?php
								$total_breakfast = $total_lunch = $total_dinner = $total_cost = $ondate_cost = 0;
								for($i = 0; $i <= $days; $i++){		
									$class = (($i%2)==0) ? ' class="odd"' : ' class="even"';
									
									//Find out Date
									$ini_day_microtime = mktime(0, 0, 0, $start_month, $start_day, $start_year);
									$day_microtime = $ini_day_microtime + $per_day_sec; //24 Hours, 60 Minutes, 60 Seconds
									$target_date = get_date_from_sec($day_microtime);
									$per_day_sec += (24 * 60 * 60);

									//Find out total order Arr
									$sql = "select * from ".DB_PREFIX."meal where order_date = '".$target_date."' AND hall_id = '".$hall_id."' AND student_id = '".$student_id."'";
									$orderArr = $dbObj->selectDataObj($sql);
									$breakfast_cost = empty($orderArr[0]->breakfast_cost) ? '0.00' : $orderArr[0]->breakfast_cost;
									$lunch_cost = empty($orderArr[0]->lunch_cost) ? '0.00' : $orderArr[0]->lunch_cost;
									$dinner_cost = empty($orderArr[0]->dinner_cost) ? '0.00' : $orderArr[0]->dinner_cost;
									$ondate_cost = $breakfast_cost + $lunch_cost + $dinner_cost;
									
									$total_breakfast += $breakfast_cost;
									$total_lunch += $lunch_cost;
									$total_dinner += $dinner_cost;
									$total_cost += $ondate_cost;
									
							?>
							<tr <?php echo $class; ?>>
								<td height="30">
									<?php 
										echo $target_date; 
										$arr[$i+1]['target_date'] = $target_date;
									?>
								</td>
								<td align="right" style="padding-right:50px;">
									<?php 
										echo view_number($breakfast_cost).TK;
										$arr[$i+1]['breakfast_cost'] = $breakfast_cost.TK; 
									?>
								</td>
								<td align="right" style="padding-right:50px;">
									<?php 
										echo view_number($lunch_cost).TK;
										$arr[$i+1]['lunch_cost'] = $lunch_cost.TK; 
									?>
								</td>
								<td align="right" style="padding-right:50px;">
									<?php 
										echo view_number($dinner_cost).TK;
										$arr[$i+1]['dinner_cost'] = $dinner_cost.TK; 
									?>
								</td>
								<td align="right" style="padding-right:50px;">
									<?php 
										echo view_number($ondate_cost).TK;
										$arr[$i+1]['ondate_cost'] = view_number($ondate_cost).TK;
									?>
								</td>	
							</tr>
							<?php 
							}//foreach
							
								$arr[$i+1]['target_date'] = 'Total Bill:';
								$arr[$i+1]['breakfast'] = view_number($total_breakfast).TK; 
								$arr[$i+1]['lunch'] = view_number($total_lunch).TK; 
								$arr[$i+1]['dinner'] = view_number($total_dinner).TK; 
								$arr[$i+1]['ondate_cost'] = view_number($total_cost).TK;
							?>
							<tr class="head">
								<td height="30"><strong><?php echo TOTAL; ?>:</strong></td>
								<td align="right" style="padding-right:50px;"><strong><?php echo view_number($total_breakfast).TK; ?></strong></td>
								<td align="right" style="padding-right:50px;"><strong><?php echo view_number($total_lunch).TK; ?></strong></td>
								<td align="right" style="padding-right:50px;"><strong><?php echo view_number($total_dinner).TK; ?></strong></td>
								<td align="right" style="padding-right:50px;"><strong><?php echo view_number($total_cost).TK; ?></strong></td>
							</tr>	
						</table>
					</td>
				</tr>
			</table>
	
	<?php 
		}//if submitted
	?>

	<?php 
	
		$_SESSION['order'] = '';
		$_SESSION['student_bill'][0] = $downloadTitle; 
		$_SESSION['student_bill'][1] = $arr;
	}?>

</div>
			
<?php
require_once("includes/footer.php");
?>