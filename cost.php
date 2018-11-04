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

//Pagination
$limit = PAGE_LIMIT_DEFAULT;

//Get Page Number 
if(empty($_REQUEST['page'])) {
	$page=1;
}else{
	$page = $_REQUEST['page']; 
}

switch($action){
		
	case 'update':
	default:
	
		$sql = "select * from ".DB_PREFIX."menu";	
		$menuList = $dbObj->selectDataObj($sql);
		$menu = $menuList[0];
		$breakfast = $menu->breakfast;
		$lunch = $menu->lunch;
		$dinner = $menu->dinner;

		$action = 'insert';
		break;
		
	case 'save':	
		$breakfast = $_POST['breakfast'];
		$lunch = $_POST['lunch'];
		$dinner = $_POST['dinner'];
		$ce = current_date_time(); 
		$sql = "UPDATE ".DB_PREFIX."menu SET breakfast = '$breakfast',
				lunch = '$lunch',
				dinner = '$dinner',
				updated_by = '$cur_user_id ',
				updated_datetime ='$ce'" ;
				

		$update_status = mysql_query($sql);
			if(!$update_status){
				$msg = COULD_NOT_BE_UPDATED;		
				$action = 'insert';
			}else{
				$msg = HAS_BEEN_UPDATED;
				$url = 'cost.php?action=view&msg='.$msg;
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
				<h1><?php echo MENU_MANAGEMENT; ?></h1>
			</td>	
			<td class="usr_info">
				<?php echo welcomeMsg($cur_user_id); ?>
			</td>			
		</tr>
	</table>
	<?php
		if($action=="insert"){
	?>
				<form action="cost.php" method="post" name="cost" id="cost" onsubmit="return validateUserGroup();" enctype="multipart/form-data">
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
						<tr>
							<td height="30">
								<?php echo BREAKFAST; ?>:
							</td>
							<td height="30">
								<input name="breakfast" id="breakfast" type="text" class="inputbox" alt="Breakfast" size="36" value="<?php echo $breakfast; ?>" onkeyup="return isNUM('breakfast');" />
							</td>
						</tr>
						<tr>
							<td height="30">
								<?php echo LUNCH; ?>:
							</td>
							<td height="30">
								<input name="lunch" id="lunch" type="text" class="inputbox" alt="Lunch" size="36" value="<?php echo $lunch; ?>" onkeyup="return isNUM('lunch');" />
							</td>
						</tr>
						<tr>
							<td height="30">
								<?php echo DINNER; ?>:
							</td>
							<td height="30">
								<input name="dinner" id="dinner" type="text" class="inputbox" alt="Dinner" size="36" value="<?php echo $dinner; ?>" onkeyup="return isNUM('dinner');" />
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<input type="submit" name="Submit" class="button" value="Save" />
								<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><input type="button" onclick="window.location='<?php echo $_SERVER['HTTP_REFERER']; ?>'"  name="cancel" class="cancel" value="<?php echo CANCEL; ?>" /></a>
							</td>
						</tr>		
					</table>	
					<input type="hidden" name="id" value="<?php echo $id; ?>" />
					<input type="hidden" name="action" value="save" />
				</form>
			
	<?php }?>
</div>
			
<?php
require_once("includes/footer.php");
?>