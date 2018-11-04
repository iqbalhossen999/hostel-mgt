<?php
require_once("includes/header.php");
$action = $_REQUEST['action'];
$msg = $_REQUEST['msg'];
$user_id = $_REQUEST['username'];
$email = $_REQUEST['email'];

switch($action){
	case 'first':	
	default:
		$action = 'first';
	
		break;
		
	case 'first_save':
		
		$token_id = $_POST['token_id'];
		
		if(empty($token_id)){
			$msg = 'Please, Insert a valid Token ID';
			$url = 'resetpass.php?action=first&msg='.$msg;
			redirect($url);
		}
		
		$sql = "select id from ".DB_PREFIX."user WHERE token_id = '".$token_id."' LIMIT 1";
		$userStatus = $dbObj->selectDataObj($sql);
		
		if(empty($userStatus)){
			$msg = 'Your Token ID does not match!';
			$url = 'resetpass.php?action=first&msg='.$msg;
			redirect($url);
		}else{
			$id = base64_encode($userStatus[0]->id);
			$url = 'resetpass.php?action=second&id='.$id;
			redirect($url);	
		}
		
		break;
	
	case 'second':	
		$id = $_REQUEST['id'];
		$action = 'second';
	
		break;
	
	case 'second_save':
	
		$id = base64_decode($_REQUEST['id']);
		$password = $_REQUEST['password'];

		$fields = array('password' => md5($password),
						'token_id' => '');
		$where = "id = '".$id."'";
		$update = $dbObj->updateTableData("user", $fields, $where);
		
		$update_counter = mysql_affected_rows();
		if($update_counter == 0){
			$msg = 'Invalid argument supplied for Password Recovery';
			$url = 'resetpass.php?action=second&msg='.$msg;
		}else{
			$msg = 'Your password has been reset successfully';
			$url = 'resetpass.php?action=third&msg='.$msg;
		}
		redirect($url);	
		break;
		
	case 'third':	
		$action = 'third';
	
		break;
}//switch

require_once("includes/templates.php");
require_once("templates/top_menu.php");
?>
<link href="css/template.css" rel="stylesheet" type="text/css" />


<div id="right_column_extend">
	<?php
		if(!empty($msg)){
	?>
		<table id="system_message_pass_recovery">
			<tr>
				<td>
					<?php echo $msg; ?>
				</td>
			</tr>
		</table>
	<?php
		}
	?>
	
	<?php if($action=="first"){ ?>
				<form action="resetpass.php" method="post" name="resetpass" id="resetpass" onsubmit="return validateInsertToken();">
					<table width="60%" cellpadding="0" cellspacing="0" border="0" class="module_header_extend module_content">
						<tr>
							<td colspan="2"><h1><?php echo PASSWORD_RECOVERY; ?></h1></td>
						</tr>
						<tr>
							<td height="30" width="20%">
								<?php echo TOKEN_ID; ?>:
							</td>
							<td width="80%">
								<input name="token_id" id="token_id" type="text" class="inputbox" alt="Token ID" size="36" value=""/>
								<span class="required_field">*</span>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="50">
								<input type="submit" name="Submit" class="button" value="Submit" />
							</td>
						</tr>		
					</table>	
					<input type="hidden" name="action" value="first_save" />
				</form>
		<?php }else if($action == "second"){ ?>
			<form action="resetpass.php" method="post" name="resetpass" onsubmit="return passwordRecovery();">
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_header_extend module_content">
						<tr>
							<td colspan="2" height="30">
								<?php echo PASSWORD_INSTRUCTION; ?>
							</td>
						</tr>
						<tr>
							<td height="30" width="20%"><?php echo PASSWORDLABEL; ?>:</td>
							<td width="80%">
								<input name="password" id="password" type="password" class="inputbox" alt="Password" size="36" value=""/>
								<span class="required_field">*</span>
							</td>
						</tr>
						<tr>
							<td height="30"><?php echo CONFIRM_PASSWORD; ?>:</td>
							<td>
								<input name="confirm_password" id="confirm_password" type="password" class="inputbox" alt="Confirm Password" size="36" value=""/>
								<span class="required_field">*</span>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="50"><input type="submit" name="Submit" class="button" value="Submit" /></td>
						</tr>		
					</table>	
					<input type="hidden" name="action" value="second_save" />
					<input type="hidden" name="id" value="<?php echo $id; ?>" />
				</form>
		<?php }else if($action == "third"){ ?>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_header_extend">
						<tr>
							<td>
								Your Password Reset Operation has been completed successfully.<br /><br />
								Please, <a href="index.php" style="color:#FF0000;">Click here</a> to log in.
							</td>
						</tr>
					</table>
		<?php }//if ?>
</div>
			
<?php
require_once("includes/footer.php");
?>