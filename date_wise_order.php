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
					<h1><?php echo DATEWISE_ORDERS; ?></h1>
				</td>
				<td class="usr_info">
					<?php echo welcomeMsg($cur_user_id); ?>
				</td>			
			</tr>
		</table>
	<?php if($action=="view"){ ?>
		<form action="date_wise_order.php" method="post" name="date_wise" id="date_wise" onsubmit="return checkDate();" >
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
					<td height="30" colspan="4">
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
					<td><a href="date_wise_order_report.php"><img src="images/excel.png" height="24" width="24" alt="save the report" title="save the report" style="padding-bottom:10px;"/></a><br /></td>
				<tr>
				<tr>
					<td colspan="4">
						<table width="100%" cellpadding="0" cellspacing="0" border="0" class="datagrid">			
							<tr class="head">
								<td height="30" width="20%"><strong><?php echo ISSUE_DATE; ?></strong></td>
								<td width="20%" align="center"><strong><?php echo BREAKFAST; ?></strong></td>
								<td width="20%" align="center"><strong><?php echo LUNCH; ?></strong></td>
								<td width="20%" align="center"><strong><?php echo DINNER; ?></strong></td>
								<td width="20%" align="center"><strong><?php echo ONDATE_TOTAL_ORDER; ?></strong></td>				
							</tr>
							<?php
								//For downloading Reports as XLS format
								$downloadTitle[0] = REQUISITION_WISE ."\n";
								$arr[0]['target_date'] = 'Date';
								$arr[0]['breakfast'] = 'Breakfast';
								$arr[0]['lunch'] = 'Lunch';
								$arr[0]['dinner'] = 'Dinner';
								$arr[0]['ondate_order'] = 'Ondate Total Order';

								$total_breakfast = $total_lunch = $total_dinner = $total_order = 0;
								for($i = 0; $i <= $days; $i++){		
									$class = (($i%2)==0) ? ' class="odd"' : ' class="even"';
									$breakfast = $lunch = $dinner = $ondate_order = 0;
									
									//Find out Date
									$ini_day_microtime = mktime(0, 0, 0, $start_month, $start_day, $start_year);
									$day_microtime = $ini_day_microtime + $per_day_sec; //24 Hours, 60 Minutes, 60 Seconds
									$target_date = get_date_from_sec($day_microtime);
									$per_day_sec += (24 * 60 * 60);

									//Find out total order Arr
									$sql = "select * from ".DB_PREFIX."meal where order_date = '".$target_date."'";
									$orderArr = $dbObj->selectDataObj($sql);
									
									foreach($orderArr as $item){
										$p_bf = empty($item->breakfast) ? 0 : $item->breakfast;
										$p_ln = empty($item->lunch) ? 0 : $item->lunch;
										$p_dn = empty($item->dinner) ? 0 : $item->dinner;
										$breakfast += $p_bf;
										$lunch += $p_ln;
										$dinner += $p_dn;
									}//foreach
									
									$ondate_order = $breakfast + $lunch + $dinner;
									
									$total_breakfast += $breakfast;
									$total_lunch += $lunch;
									$total_dinner += $dinner;
									$total_order += $ondate_order;
									
									
									
							?>
							<tr <?php echo $class; ?>>
								<td height="30"><?php echo $target_date;
								$arr[$i+1]['target_date'] = $target_date; ?></td>
								
								
								<td align="right" style="padding-right:50px;"><?php echo $breakfast;
								$arr[$i+1]['breakfast'] = $breakfast;?></td>
								
								
								<td align="right" style="padding-right:50px;"><?php echo $lunch;
								$arr[$i+1]['lunch'] = $lunch;?></td>
								
								
								<td align="right" style="padding-right:50px;"><?php echo $dinner;
								$arr[$i+1]['dinner'] = $dinner;?></td>
								
								
								<td align="right" style="padding-right:50px;"><?php echo $ondate_order;
								$arr[$i+1]['ondate_order'] = $ondate_order;	 ?></td>
								
								
							</tr>
							<?php 
							}//foreach
								$arr[$i+1]['target_date'] = 'Total: ';
								$arr[$i+1]['breakfast'] = $total_breakfast;
								$arr[$i+1]['lunch'] = $total_lunch;
								$arr[$i+1]['dinner'] = $total_dinner;
								$arr[$i+1]['ondate_order'] = $total_order;
							?>
							<tr class="head">
								<td height="30"><strong><?php echo TOTAL_COST; ?>:</strong></td>
								<td align="right" style="padding-right:50px;"><strong><?php echo $total_breakfast; ?></strong></td>
								<td align="right" style="padding-right:50px;"><strong><?php echo $total_lunch; ?></strong></td>
								<td align="right" style="padding-right:50px;"><strong><?php echo $total_dinner; ?></strong></td>
								<td align="right" style="padding-right:50px;"><strong><?php echo $total_order; ?></strong></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		<input type="hidden" name="start_date" value="<?php echo $start_date;?>" />
		<input type="hidden" name="end_date" value="<?php echo $end_date;?>" />
	<?php 
		}//if post == submitted
	//if $action == view ?>
	<?php

	
		$_SESSION['order'] = '';
		$_SESSION['order'][0] = $downloadTitle; 
		$_SESSION['order'][1] = $arr;

	}?>
</div>
			
<?php
require_once("includes/footer.php");
?>