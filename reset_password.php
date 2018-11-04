<?php
require_once("includes/header.php");

$action=$_REQUEST['action'];
$msg = $_REQUEST['msg'];
$activation_code=$_REQUEST['activation_code'];

$error=0;

switch($action){
	case 'view':
	
	if(trim($activation_code)){
		
		$sql = "select id  from ".DB_PREFIX."user WHERE activition_code='".$activation_code."'";
		$userArr = $dbObj->selectDataObj($sql);
		$userArr=$userArr[0];
		
		if(count($userArr)==0){
		    redirect("reset_password.php?action=view&msg=Activation code is not valid.");	
		}//if
	
	}//if
	
	default:
	
		$msg = $_REQUEST['msg'];

		
		$action = 'view';
	break;
		
	case 'save':	
		$new_password = $_REQUEST['new_password'];
		$retype_password = $_REQUEST['retype_password'];
		$reset_usrid=$_REQUEST['reset_usrid'];
		$query="UPDATE ".DB_PREFIX."user SET password='".md5($new_password)."', activition_code='' WHERE id='".$reset_usrid."'";
		if(!$dbObj->executeData($query)){
			redirect("reset_password.php?action=view&msg=Execution error.");
		}
		
		redirect("login.php?msg=Your Password Reset Successfully.");
	break;
	
}//switch


require_once("includes/templates.php");
require_once("templates/top_menu.php");

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
	<?php if($action=="view" AND count($userArr)){ ?>
				<form action="reset_password.php" method="post" name="reset_password" id="reset_password" onsubmit="return validatereset_password();" >
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content" style="padding-left:200px;">
						<tr>
							<td height="30">
								<?php echo NEW_PASSWORD;?>
							</td>
							<td height="30">
								<input type="password" id="new_password" name="new_password" value="" />
							</td>
						</tr>
						<tr>
							<td height="30">
								<?php echo RETYPE_PASSWORD;?>
							</td>
							<td height="30">
								<input type="password" id="retype_password" name="retype_password" value="" />
							</td>
						</tr>
						<tr>
							<td class="forget_password_submit">
								<input type="submit" name="Submit" class="button" value="save" />
								<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><input type="button" onclick="window.location='<?php echo $_SERVER['HTTP_REFERER']; ?>'"  name="cancel" class="cancel" value="<?php echo CANCEL; ?>" /></a>
							</td>
						</tr>
					</table>
					<input type="hidden" name="action" value="save" />
					<input type="hidden" name="reset_usrid" value="<?php echo $userArr->id;?>" />
				</form>
			
<?php	}?>
</div>
			
<?php
require_once("includes/footer.php");
?>