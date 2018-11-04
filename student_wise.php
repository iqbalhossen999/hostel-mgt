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

$start_date = $_REQUEST['start_date'];
$end_date = $_REQUEST['end_date'];

switch($action){
	case 'view':
		default:
		
			if(isset($_POST['submit'])){
				$posted = $_REQUEST['posted'];
				$student_id = $_POST['student_id'];
				
				$sql = "SELECT hall_id from ".DB_PREFIX."prebooking WHERE user_id = '".$student_id."'";
				$hallArr = $dbObj->selectDataObj($sql);
				$hall_id = $hallArr[0]->hall_id;
				
				
				$diff = abs(strtotime($end_date) - strtotime($start_date));
				$years = floor($diff / (365*60*60*24));
				$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
				$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

				$explode = explode('-', $start_date);
				$start_day = $explode[2];
				$start_month = $explode[1];
				$start_year = $explode[0];
				
			}
			
			// Build Student List Arry
			$sql = "select u.id, p.registration_no, u.full_name from ".DB_PREFIX."prebooking as p, ".DB_PREFIX."user as u where u.group_id = '3' and p.user_id = u.id";
			$studentList = $dbObj->selectDataObj($sql);
			$studentId = array();
			$studentId[0] = SELECT_STUDENT_OPT;
			if(!empty($studentList)){			
				foreach($studentList as $item){
					$studentId[$item->id] = $item->registration_no.' &raquo; '.$item->full_name;
				}	
			}			
			$studentList_opt = formSelectElement($studentId, $student_id, 'student_id');
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
				<h1><?php echo STUDENT_WISE_REPORT; ?></h1>
			</td>
			<td class="usr_info">
				<?php echo welcomeMsg($cur_user_id); ?>
			</td>			
		</tr>
	</table>
	<?php if($action=="view"){ ?>
		<form action="student_wise.php" method="post" name="student_wise" id="student_wise" onsubmit="return validateStudentReport();" >
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
				<tr>
					<td height="30" width="15%">
						<?php echo STUDENT_WISE; ?>:
					</td>
					<td height="30" width="80%"  colspan="4">
						<?php echo $studentList_opt; ?>
					</td>
				</tr>	
				<tr>
					<td height="30">
						<?php echo START_DATE; ?>:
					</td>
					<td>
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
					<td height="30">
						<?php echo END_DATE; ?>:
					</td>
					<td>
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
					<td colspan="4">
						<input type="submit" name="submit" class="button" value="View Report"/>
					</td>
				</tr>		
			</table>
			<input type="hidden" name="action" value="view" />
			<input type="hidden" name="posted" value="true" />
		</form>
	<?php 
		if($posted == 'true'){ 
	?>
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
				<tr>
					<td>
						<table width="100%" cellpadding="0" cellspacing="0" border="0" class="datagrid">			
							<tr class="head">
								<td height="30" width="20%"><strong><?php echo DATE; ?></strong></td>
								<td width="20%"align="right" style="padding-right:50px;"><strong><?php echo BREAKFAST; ?></strong></td>
								<td width="20%"align="right" style="padding-right:50px;"><strong><?php echo LUNCH; ?></strong></td>
								<td width="20%"align="right" style="padding-right:50px;"><strong><?php echo DINNER; ?></strong></td>
								<td width="20%" align="right" style="padding-right:50px;"><strong><?php echo TOTAL; ?></strong></td>					
							</tr>
							
							
							<?php
								$total_breakfast = $total_lunch = $total_dinner = 0;
								for($i = 0; $i <= $days; $i++){		
									$class = (($i%2)==0) ? ' class="odd"' : ' class="even"';
									
									//Find out Date
									$ini_day_microtime = mktime(0, 0, 0, $start_month, $start_day, $start_year);
									$day_microtime = $ini_day_microtime + $per_day_sec; //24 Hours, 60 Minutes, 60 Seconds
									$target_date = get_date_from_sec($day_microtime);
									$per_day_sec += (24 * 60 * 60);
									
									//Find out total breakfast cost on that day & total order against this breakfast, then find out the breakfast bill/cost
									$sql = "select id from ".DB_PREFIX."meal where order_date = '".$target_date."' AND hall_id = '".$hall_id."' AND breakfast = '1'";
									$totalbreakfasrArr = $dbObj->selectDataObj($sql);
									$total_breakfast_order = sizeof($totalbreakfasrArr);
									
									$sql = "select SUM(total_price) as total from ".DB_PREFIX."consume where issue_date = '".$target_date."' AND hall_id = '".$hall_id."' AND type_id = '1'";
									$totalbreakfastcostArr = $dbObj->selectDataObj($sql);
									$total_breakfast_cost = $totalbreakfastcostArr[0]->total;

									$per_break_fast_cost = ($total_breakfast_cost/$total_breakfast_order);
									
									//Find out total Lunch cost on that day & total order against this Lunch, then find out the Lunch bill/cost
									$sql = "select id as total from ".DB_PREFIX."meal where order_date = '".$target_date."' AND hall_id = '".$hall_id."' AND lunch = '1'";
									$totalLunchArr = $dbObj->selectDataObj($sql);
									$total_lunch_order = sizeof($totalLunchArr);
									
									$sql = "select SUM(total_price) as total from ".DB_PREFIX."consume where issue_date = '".$target_date."' AND hall_id = '".$hall_id."' AND type_id = '2'";
									$totalLunchCostArr = $dbObj->selectDataObj($sql);
									$total_lunch_cost = $totalLunchCostArr[0]->total;

									$per_lunch_cost = ($total_lunch_cost/$total_lunch_order);
									
									//Find out total Dinner cost on that day & total order against this Dinner, then find out the Dinner bill/cost
									$sql = "select SUM(id) as total from ".DB_PREFIX."meal where order_date = '".$target_date."' AND hall_id = '".$hall_id."' AND dinner = '1'";
									$totalDinnerArr = $dbObj->selectDataObj($sql);
									$total_dinner_order = sizeof($totalDinnerArr);
									
									$sql = "select SUM(total_price) as total from ".DB_PREFIX."consume where issue_date = '".$target_date."' AND hall_id = '".$hall_id."' AND type_id = '3'";
									$totalDinnerCostArr = $dbObj->selectDataObj($sql);
									$total_dinner_cost = $totalDinnerCostArr[0]->total;

									$per_dinner_cost = ($total_dinner_cost/$total_dinner_order);
									
									if(empty($per_break_fast_cost)){
										$per_break_fast_cost = '0';
									}
									
									if(empty($per_lunch_cost)){
										$per_lunch_cost = '0';
									}
									
									if(empty($per_dinner_cost)){
										$per_dinner_cost = '0';
									}
									$student_total_cost = $per_break_fast_cost + $per_lunch_cost + $per_dinner_cost;
									$total_breakfast += $per_break_fast_cost;
									$total_lunch += $per_lunch_cost;
									$total_dinner += $per_dinner_cost;
									
							?>
							<tr <?php echo $class; ?>>
								<td height="30"><?php echo $target_date; ?></td>
								<td align="right" style="padding-right:50px;"><?php echo $per_break_fast_cost.'/-'; ?></td>
								<td align="right" style="padding-right:50px;"><?php echo $per_lunch_cost.'/-'; ?></td>
								<td align="right" style="padding-right:50px;"><?php echo $per_dinner_cost.'/-'; ?></td>
								<td align="right" style="padding-right:50px;"><?php echo $student_total_cost.'/-'; ?></td>	
							</tr>
							<?php 
							}//foreach
							$total_cost = $total_breakfast + $total_lunch + $total_dinner;
							?>
							<tr class="head">
								<td height="30"><strong><?php echo TOTAL; ?>:</strong></td>
								<td align="right" style="padding-right:50px;"><strong><?php echo $total_breakfast.'/-'; ?></strong></td>
								<td align="right" style="padding-right:50px;"><strong><?php echo $total_lunch.'/-'; ?></strong></td>
								<td align="right" style="padding-right:50px;"><strong><?php echo $total_dinner.'/-'; ?></strong></td>
								<td align="right" style="padding-right:50px;"><strong><?php echo $total_cost.'/-'; ?></strong></td>
							</tr>	
						<?php 	}//for ?>				
						</table>
					</td>
				</tr>
			</table>
		<input type="hidden" name="start_date" value="<?php echo $start_date;?>" />
		<input type="hidden" name="end_date" value="<?php echo $end_date;?>" />
	<?php 
		}//if view
	?>	
</div>
			
<?php
require_once("includes/footer.php");
?>