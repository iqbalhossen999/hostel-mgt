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

if($cur_user_group_id == '2'){
	dashboard();
}//if

switch($action){
	case 'view':	
	default:
		$action = 'view';
		$msg = $_REQUEST['msg'];

		break;
		
		
	case 'save':	
		$cur_password = $_POST['cur_password'];
		$cur_password = md5($cur_password);
		$new_password = $_POST['new_password'];
		$retype_password = $_POST['retype_password'];
		
		//Check for not inserting any blank entry
		if($cur_password == "" || $new_password == "" || $retype_password == ""){
			$msg = PARAM_MISSING;
			$url = 'change_pass.php?action=view&msg='.$msg;
			redirect($url);
		}
		
		//For matching between two passwords
		if($cur_password != $usr[0]->password){
			$msg = YOUR_OLD_PASSWORD_INCORRECT;
			$url = 'change_pass.php?action=view&msg='.$msg;
			redirect($url);
		}
		
		//For matching between two new passwords
		if($new_password != $retype_password){
			$msg = PASSWORD_CONFIRMATION_DOESNT_MATCH;
			$url = 'change_pass.php?action=view&msg='.$msg;
			redirect($url);
		}
		
		//Save new password
		$fields = array(
					'password' => md5($new_password),
					);
		
		$where = "id = '".$cur_user_id."'";
		
		$update_status = $dbObj->updateTableData("user", $fields, $where);	
		
		if(!$update_status){
			$msg = UPDATE_FAILED;		
			$action = 'view';
		}else{
			$msg = UPDATE_CONFIRMED;
			$url = 'change_pass.php?action=view&msg='.$msg;
			redirect($url);
		}
		
		$action = 'insert';
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
				<h1><?php echo CHANGE_PASS; ?></h1>
			</td>	
			<td class="usr_info">
				<?php echo welcomeMsg($cur_user_id); ?>
			</td>			
		</tr>
	</table>
	
	<?php if($action=="view"){ ?>
	
			<form action="change_pass.php" method="post" name="change_pass" id="change_pass" onsubmit="return validateChangePass();">
				<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
					<tr>
						<td height="30" width="20%">
							<strong><?php echo FULL_NAME ;?>:</strong>
						</td>
						<td width="80%">
							<strong><?php echo $usr[0]->full_name;?></strong>
						</td>
					</tr>
					<tr>
						<td height="30" width="20%">
							<?php echo CURRENT_PASSWORD; ?>:
						</td>
						<td width="80%">
							<input name="cur_password" id="cur_password" type="password" class="inputbox" alt="Current Password" size="25" autocomplete="off" />
							<span class="required_field">*</span>
						</td>
					</tr>
					<tr>
						<td height="30" width="20%">
							<?php echo NEW_PASSWORD; ?>:
						</td>
						<td width="80%">
							<input name="new_password" id="new_password" type="password" class="inputbox" alt="New Password" size="25" />
							<span class="required_field">*</span>
						</td>
					</tr>
					<tr>
						<td height="30" width="20%">
							<?php echo RETYPE_PASSWORD; ?>:
						</td>
						<td width="80%">
							<input name="retype_password" id="retype_password" type="password" class="inputbox" alt="Retype New Password" size="25" />
							<span class="required_field">*</span>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="submit" name="Submit" class="button" value="Save" />
							<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><input type="button" onclick="window.location='<?php echo $_SERVER['HTTP_REFERER']; ?>'"  name="cancel" class="cancel" value="<?php echo CANCEL; ?>" /></a>							</td>
					</tr>		
				</table>	
				<input type="hidden" name="id" value="<?php echo $id; ?>" />
				<input type="hidden" name="action" value="save" />
			</form>
				
	<?php 
		}
	?>
				
</div>
			
<?php
require_once("includes/footer.php");
?>