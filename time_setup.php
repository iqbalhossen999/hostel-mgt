<?php
require_once("includes/header.php");

//check for loggedin
$usr = $user->getUser();
if(empty($usr)){
	header("Location:login.php");
	exit;
}

$cur_user_id = $usr[0]->id;
$cur_user_group_id = $usr[0]->group_id;
$action = $_REQUEST['action'];
$msg = $_REQUEST['msg'];

if($cur_user_group_id != '1'){
	dashboard();
}//if

switch($action){
	case 'view':	
	default:
		
		$sql = "select * from ".DB_PREFIX."time";
		$timeList = $dbObj->selectDataObj($sql);
		$breakfast= $timeList[0]->breakfast;
		$explode = explode(':', $breakfast);
		$hour_breakfast = $explode[0];
		if($hour_breakfast > '12'){
			$time_zone_breakfast = '1';
			$hour_breakfast = $hour_breakfast-12;
		}
		if(strlen($hour_breakfast) == '1'){
			$hour_breakfast = '0'.$hour_breakfast;
		}
		$minutes_breakfast = $explode[1];
		$seconds_breakfast = $explode[2];
		
		$lunch= $timeList[0]->lunch;
		$explode = explode(':', $lunch);
		$hour_lunch = $explode[0];
		if($hour_lunch > '12'){
			$time_zone_lunch = '1';
			$hour_lunch = $lunch-12;
		}
		if(strlen($hour_lunch) == '1'){
			$hour_lunch = '0'.$hour_lunch;
		}
		$minutes_lunch = $explode[1];
		$seconds_lunch = $explode[2];
		
		$dinner= $timeList[0]->dinner;
		$explode = explode(':', $dinner);
		$hour_dinner = $explode[0];
		if($hour_dinner > '12'){
			$time_zone_dinner = '1';
			$hour_dinner = $hour_dinner-12;
		}
		if(strlen($hour_dinner) == '1'){
			$hour_dinner = '0'.$hour_dinner;
		}
		$minutes_dinner = $explode[1];
		$seconds_dinner = $explode[2];
		
		$bf_hour = $timeList[0]->bf_hour;
		$ln_hour = $timeList[0]->ln_hour;
		$dn_hour = $timeList[0]->dn_hour;
		
	
		$action = 'view';
		break;
		
	case 'save':
		/*echo '<pre>';	
		print_r($_POST);exit;*/
		$hour_break = $_POST['hour_break'];
		$minutes_break = $_POST['minutes_break'];
		$seconds_break = $_POST['seconds_break'];
		$breakfast_zones = $_POST['breakfast_zones'];
		if($breakfast_zones == '1'){
			$hour_break = $hour_break+12;
		}
		$breakfast = $hour_break.':'.$minutes_break.':'.$seconds_break;
		$hour_lunch = $_POST['hour_lunch'];
		$minutes_lunch = $_POST['minutes_lunch'];
		$seconds_lunch = $_POST['seconds_lunch'];
		$lunch_zones = $_POST['lunch_zones'];
		if($lunch_zones == '1'){
			$hour_lunch = $hour_lunch+12;
		}
		$lunch = $hour_lunch.':'.$minutes_lunch.':'.$seconds_lunch;
		$hour_dinner = $_POST['hour_dinner'];
		$minutes_dinner = $_POST['minutes_dinner'];
		$seconds_dinner = $_POST['seconds_dinner'];
		$dinner_zones = $_POST['dinner_zones'];
		if($dinner_zones == '1'){
			$hour_dinner = $hour_dinner+12;
		}
		$dinner = $hour_dinner.':'.$minutes_dinner.':'.$seconds_dinner;
		//echo $dinner.'<br/>';
		$bf_hour = $_POST['bf_hour'];
		$ln_hour = $_POST['ln_hour'];
		$dn_hour = $_POST['dn_hour'];
		
		$sql = "update ".DB_PREFIX."time set breakfast='".$breakfast."',lunch='".$lunch."',dinner='".$dinner."',bf_hour='".$bf_hour."',ln_hour='".$ln_hour."',dn_hour='".$dn_hour."'";
		$update_status = $dbObj->executeData($sql);	
		
		if(!$update_status){
			$msg = $name.COULD_NOT_BE_UPDATED;		
			$action = 'insert';
		}else{
			$msg = 'Meal Booking Time'.HAS_BEEN_UPDATED;
			$url = 'time_setup.php?action=view&msg='.$msg;
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
				<h1><?php echo TIME_SETUP; ?></h1>
			</td>			
		</tr>
	</table>
	<?php
		if($action=="view"){
	?>		
		<form action="time_setup.php" method="post" name="time_setup" id="time_setup" onsubmit="return dfdfdfdfdf();">
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
				<tr>
					<td>
						<table width="100%" cellpadding="0" cellspacing="0" border="0" class="datagrid">
							<tr class="head">
								<td height="30" width="20%">
									<strong><?php echo MEAL_NAME; ?></strong>
								</td>
								<td width="50%" align="center">
									<strong><?php echo MEAL_TIME; ?></strong>
								</td>
								<td width="30%">
									<strong><?php echo REMAINING_ORDER_TIME; ?></strong>
								</td>
							</tr>
							<tr class="even">
								<td height="30"><?php echo BREAKFAST; ?></td>
								<td>
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td align="center">
												H</br><input type="text" name="hour_break" id="hour_break" class="inputtext" size="2" maxlength="2" value="<?php echo $hour_breakfast; ?>" onkeyup="return breakfast('hour_break');"/> :											</td>
											<td align="center">
												M</br><input type="text" name="minutes_break" id="minutes_break" class="inputtext" size="2" maxlength="2" value="<?php echo $minutes_breakfast; ?>" onkeyup="return minutesbreakfast('minutes_break');"/> :
											</td>
											<td align="center">
												S</br><input type="text" name="seconds_break" id="seconds_break" class="inputtext" size="2" maxlength="2" value="<?php echo $seconds_breakfast; ?>" onkeyup="return brakfastseconds('seconds_break')";/>
											</td>
											<td align="center" style="padding-top:17px;">
												<?php echo timeZones($time_zone, $time_zone_breakfast, 'breakfast_zones');?>
											</td>
										</tr>
									</table>
								</td>
								<td style="padding-top:17px;"><input type="text" name="bf_hour" id="bf_hour" class="inputtext"  size="2" maxlength="2" value="<?php echo $bf_hour; ?>" onkeyup="return brakfasthour('bf_hour');"/> Hour</td>
							</tr>
							<tr class="odd">
								<td height="30"><?php echo LUNCH; ?></td>
								<td>
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td align="center">
												<input type="text" name="hour_lunch" id="hour_lunch" class="inputtext" size="2" maxlength="2" value="<?php echo $hour_lunch; ?>" onkeyup="return hourLunch('hour_lunch');"/> :
											</td>
											<td align="center">
												<input type="text" name="minutes_lunch" id="minutes_lunch" class="inputtext" size="2" maxlength="2" value="<?php echo $minutes_lunch; ?>" onkeyup="return minutesLunch('minutes_lunch');"/> :
											</td>
											<td align="center">
												<input type="text" name="seconds_lunch" id="seconds_lunch" class="inputtext" size="2" maxlength="2" value="<?php echo $seconds_lunch; ?>" onkeyup="return lunchseconds('seconds_lunch')";/>
											</td>
											<td align="center">
												<?php echo timeZones($time_zone, $time_zone_lunch, 'lunch_zones');?>
											</td>
										</tr>
									</table>
								</td>
								<td><input type="text" name="ln_hour" id="ln_hour" class="inputtext"  size="2" maxlength="2" value="<?php echo $ln_hour; ?>" onkeyup="return lunchhour('ln_hour');" /> Hour</td>
							</tr>
							<tr class="even">
								<td height="30"><?php echo DINNER; ?></td>
								<td>
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td align="center">
												<input type="text" name="hour_dinner" id="hour_dinner" size="2" class="inputtext" maxlength="2" value="<?php echo $hour_dinner; ?>"  onkeyup="return dinner('hour_dinner');"/> :
											</td>
											<td align="center">
												<input type="text" name="minutes_dinner" id="minutes_dinner" class="inputtext" size="2" maxlength="2" value="<?php echo $minutes_dinner; ?>" onkeyup="return minutesdinner('minutes_dinner');"/> :
											</td>
											<td align="center">
												<input type="text" name="seconds_dinner" id="seconds_dinner" size="2" class="inputtext" maxlength="2" value="<?php echo $seconds_dinner; ?>" onkeyup="return dinnerseconds('seconds_dinner')";/>
											</td>
											<td align="center">
												<?php echo timeZones($time_zone, $time_zone_dinner, 'dinner_zones');?>
											</td>
										</tr>
									</table>
								</td>
								<td><input type="text" name="dn_hour" id="dn_hour" class="inputtext"  size="2" maxlength="2" value="<?php echo $dn_hour; ?>" onkeyup="return dinnerhour('dn_hour');" /> Hour</td>
							</tr>
							<tr class="odd">
								<td  height="50" colspan="3">
									<input type="submit" name="Submit" class="button" value="Save" />
									<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><input type="button" onclick="window.location='<?php echo $_SERVER['HTTP_REFERER']; ?>'"  name="cancel" class="cancel" value="<?php echo CANCEL; ?>" /></a>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<input type="hidden" name="action" value="save" />
		</form>
	<?php }//if view ?>
</div>
			
<?php
require_once("includes/footer.php");
?>