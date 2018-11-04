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
$cur_user_wing_id = $usr[0]->wing_id;
$action = $_REQUEST['action'];
$msg = '';

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
		
		$sql = "select * from ".DB_PREFIX."meal order by event_date desc";
		$mealList = $dbObj->selectDataObj($sql);
		$action = 'view';
		$msg = $_REQUEST['msg'];
		
		//Pagination 
		if(!empty($mealList)){
			$total_rows = sizeof($mealList);
		}else{
			$total_rows =0;
		}
		//find start
		$s = ($page - 1) * $limit;
		$total_page = $total_rows/$limit;
		
		break;
		
	case 'update':
	
	
	case 'create':
	
	
	//default:
	
		$msg = $_REQUEST['msg'];
		if(!empty($_REQUEST['id'])){
			$id = $_REQUEST['id'];
			$sql = "select * from ".DB_PREFIX."meal WHERE id='".$id."'";	
			$mealList = $dbObj->selectDataObj($sql);
			$mealId = $mealList[0];
			$event_date = $mealId->event_date;
			$breakfast = $mealId->breakfast;
			$lunch = $mealId->lunch;
			$diner = $mealId->diner;		
			
			
			
		}else{
			$id = '';
			$event_date = '';
			$breakfast = '';
			$lunch = '';
			$diner = '';	
		}
		
		$action = 'insert';
		break;
		
	case 'save':	
		$id = $_POST['id'];
		
		$event_date = $_POST['event_date'];
		$breakfast = $_POST['breakfast'];
		$lunch = $_POST['lunch'];
		$diner = $_POST['diner'];
		
			$sql = "select * from ".DB_PREFIX."meal WHERE event_date = '".$event_date."'  limit 1";
		$existing = $dbObj->selectDataObj($sql);
		
		
		if(!empty($existing)){
			$msg = "Cost has been set";
			$url = 'meal.php?action=create&msg='.$msg;
			redirect($url);
		}
		
		//if(empty($id)){
//			$sql = "select hall_block from ".DB_PREFIX."meal WHERE name = '".$name."' AND hall_name = '".$hall_name."' limit 1";
//			$mealList = $dbObj->selectDataObj($sql);		
//			
//			if(!empty($mealList)){
//				$msg = block.' '.$hall_block.ALREADY_EXISTS;
//				$url = 'meal.php?action=create&msg='.$msg;
//				redirect($url);
//			}			
//		}else if(!empty($id)){
//			$sql = "select hall_block from ".DB_PREFIX."meal WHERE id!='".$id."' AND hall_block = '".$hall_block."' AND hall_name = '".$hall_name."' limit 1";
//			$mealList = $dbObj->selectDataObj($sql);		
//			
//			if(!empty($mealList)){
//				$msg = block.' '.$hall_block.ALREADY_EXISTS;
//				$url = 'meal.php?action=update&page='.$page.'&id='.$id.'&msg='.$msg;
//				redirect($url);
//			}
//		}
		
		
	
		
		if(!empty($id)){
			$fields = array('event_date' => $event_date,
						'breakfast' => $breakfast,
						'lunch' => $lunch,
						'diner' => $diner
						);
						
			$where = "id = '".$id."'";
			
			$update_status = $dbObj->updateTableData("meal", $fields, $where);	
			
			if(!$update_status){
				$msg = mealId.' '.$name.COULD_NOT_BE_UPDATED;	
				$action = 'insert';
			}else{
				$msg = mealId.' '.$name.HAS_BEEN_UPDATED;
				$url = 'meal.php?action=view&page='.$page.'&msg='.$msg;
				redirect($url);
			}
		}else{
			$fields = array('event_date' => $event_date,
						'breakfast' => $breakfast,
						'lunch' => $lunch,
						'diner' => $diner
						);
			
			$inserted = $dbObj->insertTableData("meal", $fields);	
			if(!$inserted){
				$msg = mealId.' '.$name.COULD_NOT_BE_CREATED;
				$action = 'insert';
			}else{
				$msg = mealId.' '.$name.CREATED_SUCCESSFULLY;
				$url = 'meal.php?action=view&msg='.$msg;
				redirect($url);
			}
		}
		break;

	case 'delete':	
		$id = $_REQUEST['id'];	
		$sql = "select * from ".DB_PREFIX."meal WHERE id='".$id."'";	
		$mealList = $dbObj->selectDataObj($sql);
		$mealId = $mealList[0];
		$hall_seat = $mealId->hall_seat;
		$where = "id='".$id."'";	
		
		$success = $dbObj->deleteTableData("meal", $where);	
		
		if(!$success){
			$msg = mealId.' '.$name.COULD_NOT_BE_DELETED;
		}else{
			$msg = mealId.' '.$name.HAS_BEEN_DELETED;
		}
		
		$url = 'meal.php?action=view&page='.$page.'&msg='.$msg;
		redirect($url);
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
				<h1><?php echo MEAL_SETTING; ?></h1>
			</td>	
			<td class="usr_info">
				<?php 
				$group = getNameById('user_group', $cur_user_group_id);
				$usrName = $usr[0]->username;
				$grpName = $group->name;
				echo welcomeMsg($usrName, $grpName);
				?>
			</td>			
		</tr>
	</table>
	<?php
		if($action=="view"){
	?>
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
				<tr>
					<td>
						<table width="100%" cellpadding="0" cellspacing="0" border="0" class="datagrid">
								<tr class="footer">
									<td colspan="6" style=" background:#EEEEEE;">
										<b><a href="meal.php?action=create"><?php echo CREATE; ?></a></b>
									</td>
								</tr>				
							<tr class="head">
								<td height="30" width="20%">
									<strong><?php echo DATE; ?></strong>
								</td>
								<td height="30" width="20%">
									<strong><?php echo BREAKFAST; ?></strong>
								</td>
								<td height="30" width="20%">
									<strong><?php echo LUNCH; ?></strong>
								</td>
								<td height="30" width="20%">
									<strong><?php echo DINER; ?></strong>
								</td>
								
								<td height="30" width="20%">
									<strong><?php echo ACTION; ?></strong>
								</td>
								
							</tr>
							<?php			
							if(!empty($mealList)){	
								
								if(($s+$limit) > $total_rows){
									$maxPageLimit = $total_rows;
								}else{
									$maxPageLimit = $s+$limit;
								}		
								
								for($rownum = $s; $rownum <$maxPageLimit; $rownum++){		
									if(($rownum%2)==0){//even
										$class = ' class="even"';									
									}else{//odd
										$class = ' class="odd"';									
									}									
							?>
									<tr <?php echo $class; ?>>
										<td width="20%">
											<?php
											echo $mealList[$rownum]->event_date;
											?> 
										</td>
										<td width="20%">
											<?php
											echo 'Tk. '. $mealList[$rownum]->breakfast.'/=';
											?> 
										</td>
										
										<td width="20%">
											<?php
											echo 'Tk. '. $mealList[$rownum]->lunch. '/=';
											?> 
										</td>
										
										<td width="20%">
											<?php
											echo 'Tk. ' .$mealList[$rownum]->diner. '/=';
											?> 
										</td>						
											<td width="20%">								
											<a href="meal.php?action=update&page=<?php echo $page; ?>&id=<?php echo $mealList[$rownum]->id; ?>"><?php echo UPDATE; ?></a> <br >
											<a href="meal.php?action=delete&page=<?php echo $page; ?>&id=<?php echo $mealList[$rownum]->id; ?>" onclick="return confirm('Are you sure you want to Delete?');"><?php echo DELETE; ?></a>
										</td>
									</tr>
								<?php 
									}//for
								}else{ ?>
								<tr height="30">
									<td colspan="6">
										<?php echo EMPTY_DATA; ?>
									</td>
								</tr>
								<?php 
								}
								if($total_page > 1){ ?>
								<tr height="50">
									<td colspan="5">
										<?php 
										echo pagination($total_rows,$limit,$page,''); ?>
									</td>
								</tr>
								<?php } ?>	
								<tr class="footer">
									<td colspan="6">
										<b><a href="meal.php?action=create"><?php echo CREATE; ?></a></b>
									</td>
								</tr>				
						</table>
					</td>
				</tr>
			</table>
				
	<?php 
		}elseif($action=="insert"){ 
	?>
				<form action="meal.php" method="post" name="block" id="block" onsubmit="return validateblock();">
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
						
						<tr>
							<td height="30" width="20%">
								<?php echo Date; ?>:
							</td>
							<td width="80%">
								<input name="event_date" id="event_date" type="text" class="inputbox" alt="r_date" size="25" value="<?php echo $event_date; ?>" />
								
								<img id="f_rangeStart_trigger" src="date/src/css/img/calendar.gif" title="Pick a Date" />
								<img id="f_clearRangeStart" src="date/src/css/img/no.png" title="Clear Date" onClick="return makeEmpty('event_date')" height="16" width="16"  />
								<script type="text/javascript">
								  RANGE_CAL_1 = new Calendar({
										  inputField: "event_date",
										  dateFormat: "%Y-%m-%d",
										  trigger: "f_rangeStart_trigger",
										  bottomBar: true,
										  onSelect: function() {
												  var date = Calendar.intToDate(this.selection.get());
												  LEFT_CAL.args.min = date;
												  LEFT_CAL.redraw();
												  this.hide();
										  }
								  });
								  function clearRangeStart() {
										  document.getElementById("event_date").value = "";
										  LEFT_CAL.args.min = null;
										  LEFT_CAL.redraw();
								  };
								</script>
							</td>
						</tr>
						
						
						
						<tr>
							<td height="30" width="20%">
								<?php echo Breakfast; ?>:
							</td>
							<td width="80%">
								<input name="breakfast" id="breakfast" type="text" class="inputbox" alt="breakfast" size="10" value="<?php echo $breakfast  ;?>" />
								<span class="required_field">*</span>
								
							</td>
						</tr>
						<tr>
							<td height="30" width="20%">
								<?php echo Lunch; ?>:
							</td>
							<td width="80%">
								<input name="lunch" id="lunch" type="text" class="inputbox" alt="lunch " size="10" value="<?php echo $lunch; ?>" />
								<span class="required_field">*</span>
							</td>
						</tr><tr>
							<td height="30" width="20%">
								<?php echo Diner; ?>:
							</td>
							<td width="80%">
								<input name="diner" id="diner" type="text" class="inputbox" alt="diner" size="10" value="<?php echo $diner; ?>" />
								<span class="required_field">*</span>
							</td>
						</tr>
						
						
						<tr>
							<td colspan="2">
								<input type="submit" name="Submit" class="button" value="save" />
								<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><input type="button" onclick="window.location='<?php echo $_SERVER['HTTP_REFERER']; ?>'"  name="cancel" class="cancel" value="<?php echo CANCEL; ?>" /></a>
							</td>
						</tr>		
					</table>	
					<input type="hidden" name="id" value="<?php echo $id; ?>" />
					<input type="hidden" name="action" value="save" />
					<input type="hidden" name="page" id="page" value="<?php echo $page; ?>" />
				</form>
			
	<?php }?>
</div>
			
<?php
require_once("includes/footer.php");
?>