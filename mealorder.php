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

if($cur_user_group_id != '3'){
	dashboard();
}//if

$sql = "SELECT hall_id, seat_id from ".DB_PREFIX."prebooking WHERE user_id = '".$cur_user_id."'";
$seatdb = $dbObj->selectDataObj($sql);
$seat = $seatdb[0]->seat_id;
$hall_id = $seatdb[0]->hall_id;


//Pagination
$limit = PAGE_LIMIT_DEFAULT;

//Get Page Number 
if(empty($_REQUEST['page'])) {
	$page=1;
}else{
	$page = $_REQUEST['page']; 
}

switch($action){
	case 'create':
	default:
		$action = 'insert';
		break;
		
	case 'save':
	
		$meals = $_POST['meal'];
		$break  = $dinner = $lunch = $count = 0;
		$g_status = $_POST['g_status'];
		
		$meal_date = $_POST['single_date'];
		
		if(!empty($meal_date)){
			foreach($meal_date as $key=>$val){
				$sql = "SELECT breakfast, lunch, dinner from ".DB_PREFIX."meal WHERE student_id = '".$cur_user_id."' AND order_date = '".$val."'";
				$prevOrder = $dbObj->selectDataObj($sql);
				
				//First delete existing entry from meal table
				$where = " order_date = '".$val."' AND student_id = '".$cur_user_id."'";
				$delete = $dbObj->deleteTableData("meal", $where);
				
				if($g_status == '1'){
					$break = empty($_POST['multi_break_'.$val]) ? '0' : $_POST['multi_break_'.$val];
					$lunch = empty($_POST['multi_lunch_'.$val]) ? '0' : $_POST['multi_lunch_'.$val];
					$dinner = empty($_POST['multi_dinner_'.$val]) ? '0' : $_POST['multi_dinner_'.$val];
				}else{
					if(isset($_POST['dis_bf_'.$val])){
						$break = $prevOrder[0]->breakfast;
					}else{
						$break = (isset($_POST['break_'.$val])) ? '1' : '0';
					}
					
					if(isset($_POST['dis_ln_'.$val])){
						$lunch = $prevOrder[0]->lunch;
					}else{
						$lunch = (isset($_POST['lunch_'.$val])) ? '1' : '0';
					}
					
					if(isset($_POST['dis_dn_'.$val])){
						$dinner = $prevOrder[0]->dinner;
					}else{
						$dinner = (isset($_POST['dinner_'.$val])) ? '1' : '0';
					}//else
				}//else
				
				$fields = array(
								'hall_id' => $hall_id,
								'student_id' => $cur_user_id,
								'breakfast' => $break,
								'lunch' => $lunch,
								'dinner' => $dinner,					
								'created_datetime' => current_date_time(),
								'order_date' => $val				
						);
				$inserted = $dbObj->insertTableData("meal", $fields);
			}//foreach
		}//if
			
		if(!$inserted){
			$msg = 'Order could not be completed';	
			$action = 'insert';
		}else{
			$msg = 'Order has been given successfully';
			$url = 'mealorder.php?action=view&msg='.$msg;
			redirect($url);
		}
		
		break;
}//switch

require_once("includes/templates.php");
require_once("templates/top_menu.php");
require_once("templates/left_menu.php");
?>

<div id="right_column">
	<?php
		if(!empty($msg)){
	?>
		<table id="system_message">
			<tr>
				<td>
					<?php echo $msg; ?>
				</td>
			</tr>
		</table>
	<?php
		}
	?>
	<table width="100%" cellspacing="0" cellpadding="0" border="0" class="module_header">
		<tr>
			<td>
				<h1><?php echo MEAL_ORDER; ?></h1>
			</td>	
			<td class="usr_info">
				<?php echo welcomeMsg($cur_user_id); ?>
			</td>			
		</tr>
	</table>
	<?php if($action=="insert"){ ?>
				
			<?php if($seat == '0'){ ?>
				<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
						<tr>
							<td height="30">
							<?php echo NOT_ASSIGNED_MESSAGE; ?>
							</td>
						</tr>
				</table>
			<?php }else{ ?>	
				<form action="mealorder.php" method="post" name="mealorder" id="mealorder" onsubmit="return validateMealOrder();" >
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
						<tr>
							<td height="30" width="10%"><?php echo START_DATE; ?>:</td>
							<td height="30">
								<input name="issue_date" id="issue_date" type="text" class="inputbox readonly" readonly="readonly" alt="Issue Date" size="18" value="<?php echo $issue_date; ?>" />
								<img id="f_rangeStart_triggerm_start" src="date/src/css/img/calendar.gif" title="Pick a Date" />
								<img id="f_clearRangeStart" src="date/src/css/img/no.png" title="Clear Date" onClick="return makeEmpty('start_date')" height="16" width="16" />
								<img id="proceed" src="images/proceed.png" title="Proceed"  onClick="check_student_order();" />
								<script type="text/javascript">
								RANGE_CAL_1 = new Calendar({
									inputField: "issue_date",
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
							<td height="30" width="10%"><?php echo END_DATE; ?>:</td>
							<td height="30">
								<input name="end_date" id="end_date" type="text" class="inputbox readonly" readonly="readonly" alt="End Date" size="18" value="<?php echo $end_date; ?>" />
								<img id="f_rangeStart_triggerm_end" src="date/src/css/img/calendar.gif" title="Pick a Date" />
								<img id="f_clearRangeStart" src="date/src/css/img/no.png" title="Clear Date" onClick="return makeEmpty('end_date')" height="16" width="16" />
								<img id="proceed" src="images/proceed.png" title="Proceed"  onClick="check_student_order();" />
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
								<div id="loaderContainer"></div>
								<div id="order_display"></div>
							</td>
						</tr>		
					</table>	
					<input type="hidden" name="action"  value="save" />
				</form>
			<?php }//if ?>
	<?php }?>
</div>
			
<?php
require_once("includes/footer.php");
?>
