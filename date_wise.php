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

if($cur_user_group_id != '1'){
	dashboard();
}//if

switch($action){

	case 'view':
		default:
		
			if(isset($_POST['submit'])){
				$posted = $_REQUEST['posted'];
				$days = date_difference($start_date, $end_date);

				$explode = explode('-', $start_date);
				$start_day = $explode[2];
				$start_month = $explode[1];
				$start_year = $explode[0];
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
					<h1><?php echo DATEWISE_STUDENT_REPORTS; ?></h1>
				</td>
				<td class="usr_info">
					<?php echo welcomeMsg($cur_user_id); ?>
				</td>			
			</tr>
		</table>
	<?php if($action=="view"){ ?>
		<form action="date_wise.php" method="post" name="date_wise" id="date_wise" onsubmit="return checkDate();" >
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">	
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
					<td><a href="date_wise_report.php"><img src="images/excel.png" height="24" width="24" alt="save the report" title="save the report" style="padding-bottom:10px;"/></a><br /></td>
				<tr>
				<tr>
					<td colspan="4">
						<table width="100%" cellpadding="0" cellspacing="0" border="0" class="datagrid">			
							<tr class="head">
								<td height="30" width="20%"><strong><?php echo ISSUE_DATE; ?></strong></td>
								<td width="20%" align="center"><strong><?php echo BREAKFAST_COST; ?></strong></td>
								<td width="20%" align="center"><strong><?php echo LUNCH_COST; ?></strong></td>
								<td width="20%" align="center"><strong><?php echo DINNER_COST; ?></strong></td>
								<td width="20%" align="center"><strong><?php echo ONDAY_TOTAL_COST; ?></strong></td>				
							</tr>
							
							<?php
										//For downloading Reports as XLS format
							//if group_id == 1 --->>> Only Super Admin can download the reprot
								if($cur_user_group_id == '1'){
								$downloadTitle[0] = 'Date Wise' ."\n";
								$arr[0]['target_date'] = 'Date';
								$arr[0]['breakfast'] = 'Breakfast';
								$arr[0]['lunch'] = 'Lunch';
								$arr[0]['dinner'] = 'Dinner';
								$arr[0]['ondate_cost'] = 'Ondate Total Cost';
						
							} ?>
							
							<?php
								$total_breakfast = $total_lunch = $total_dinner = $total_cost = 0;
								for($i = 0; $i <= $days; $i++){		
									$class = (($i%2)==0) ? ' class="odd"' : ' class="even"';
									$bf_class = $ln_class = $dn_class = '';
									$breakfast = $lunch = $dinner = $ondate_cost = 0;
									
									//Find out Date
									$ini_day_microtime = mktime(0, 0, 0, $start_month, $start_day, $start_year);
									$day_microtime = $ini_day_microtime + $per_day_sec; //24 Hours, 60 Minutes, 60 Seconds
									$target_date = get_date_from_sec($day_microtime);
									$per_day_sec += (24 * 60 * 60);

									//Find out total order Arr
									$sql = "select SUM(breakfast_cost) as breakfast, SUM(lunch_cost) as lunch, SUM(dinner_cost) as dinner from ".DB_PREFIX."meal where order_date = '".$target_date."'";
									$costArr = $dbObj->selectDataObj($sql);
									
									$breakfast = $costArr[0]->breakfast;
									$lunch = $costArr[0]->lunch;
									$dinner = $costArr[0]->dinner;
									$ondate_cost = $breakfast + $lunch + $dinner;
									
									$total_breakfast += $breakfast;
									$total_lunch += $lunch;
									$total_dinner += $dinner;
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
										echo view_number($breakfast).TK;
										$arr[$i+1]['breakfast'] = view_number($breakfast).TK;	
									?>
								</td>
								<td align="right" style="padding-right:50px;">
									<?php 
										echo view_number($lunch).TK;
										$arr[$i+1]['lunch'] = view_number($lunch).TK; 
									?>
								</td>
								<td align="right" style="padding-right:50px;">
									<?php 
										echo view_number($dinner).TK; 
										$arr[$i+1]['dinner'] = view_number($dinner).TK;
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
								<td height="30"><strong><?php echo TOTAL_COST; ?>:</strong></td>
								<td align="center"><strong><?php echo view_number($total_breakfast).TK; ?></strong></td>
								<td align="center"><strong><?php echo view_number($total_lunch).TK; ?></strong></td>
								<td align="center"><strong><?php echo view_number($total_dinner).TK; ?></strong></td>
								<td align="center"><strong><?php echo view_number($total_cost).TK; ?></strong></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		<input type="hidden" name="start_date" value="<?php echo $start_date;?>" />
		<input type="hidden" name="end_date" value="<?php echo $end_date;?>" />
	<?php 
		}//if post == submitted
		
		$_SESSION['order'] = '';
		$_SESSION['datewise_student_bill'][0] = $downloadTitle; 
		$_SESSION['datewise_student_bill'][1] = $arr;
	}//if $action == view ?>
</div>
			
<?php
require_once("includes/footer.php");
?>