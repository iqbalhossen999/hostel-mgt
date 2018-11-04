<?php
require_once("includes/header.php");

$action = $_REQUEST['action'];
$msg = $_REQUEST['msg'];
$username = $_REQUEST['username'];
$email = $_REQUEST['email'];

switch($action){
	case 'view':	
	default:
		$action = 'view';
	
		break;
		
	case 'reset':
		
		 $sql = "select id, username, email, official_name from ".DB_PREFIX."user WHERE username = '".$username."' AND email = '".$email."'";
		 $userStatus = $dbObj->selectDataObj($sql);
		//print_r($_REQUEST);
		if(empty($userStatus)){
			$msg = 'Invalid User Name or Email Address';
			$url = 'passforgot.php?action=view&username='.$username.'&email='.$email.'&msg='.$msg;
			redirect($url);
		}else{
			password_mail($userStatus[0]->id, $userStatus[0]->email, $userStatus[0]->official_name);
			$url = 'passforgot.php?action=success&email='.$email;
			redirect($url);	
		}
		
		break;
	
	case 'success':	
		$action = 'success';
	
		break;
	

	
	case 'invalid':	
		$action = 'invalid';
	
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
	
	<?php if($action=="view"){ ?>
				<form action="passforgot.php" method="post" name="reset_password" id="reset_password" onsubmit="return validateResetPassword();">
					<table width="60%" cellpadding="0" cellspacing="0" border="0" class="module_header_extend module_content">
						<tr>
							<td colspan="2"><h1><?php echo PASSWORD_RECOVERY; ?></h1></td>
						</tr>
						<tr>
							<td height="30" width="20%">
								<?php echo USERNAME; ?>:
							</td>
							<td width="80%">
								<input name="username" id="username" type="text" class="inputbox" alt="User Name" size="36" value="<?php echo $username; ?>"/>
								<span class="required_field">*</span>
							</td>
						</tr>
						<tr>
							<td height="30"><?php echo EMAIL; ?>:</td>
							<td>
								<input name="email" id="email" type="text" class="inputbox" alt="Email" size="36" value="<?php echo $email; ?>"/>
								<span class="required_field">*</span>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="30">
								<input type="submit" name="Submit" class="button" value="Reset" />
								<a href="index.php"><input type="button" onclick="window.location='index.php'"  name="cancel" class="button" value="<?php echo CANCEL; ?>" /></a>
							</td>
						</tr>		
					</table>	
					<input type="hidden" name="action" value="reset" />
				</form>
		<?php }else if($action == "success"){ ?>
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_header_extend module_content">
				<tr>
					<td height="30">
						<?php echo PASSWORD_RECOVERY_MSG1."<strong>$email</strong><br /><br />".PASSWORD_RECOVERY_MSG2; ?>
					</td>
				</tr>
			</table>
		<?php }else if($action == "invalid"){ ?>
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
				<tr>
					<td height="30">
						<?php 
						echo 'Invalid arguments supplied for your password Recovery Operation.<br />
						Please, Click the following link to Reset your password.<br /><br />
						<a href="passforgot.php" title="Click here to Reset your password">Reset Your Password</a>';
						?>
					</td>
				</tr>
			</table>
		
		<?php }//if ?>
</div>
			
<?php
require_once("includes/footer.php");
?>