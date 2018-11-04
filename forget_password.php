<?php
require_once("includes/header.php");

$action=$_REQUEST['action'];
$msg = $_REQUEST['msg'];

switch($action){
	case 'view':	
	default:
	
		$msg = $_REQUEST['msg'];
		$action = 'view';
		
	break;
		
	case 'save':	
		$username = $_REQUEST['username'];
		$email = $_REQUEST['email'];
		
		
			///Retrive the data row from user table with the given username
			
			$sql = "select id,full_name,email,username from ".DB_PREFIX."user WHERE username='".$username."'";
			$userArr = $dbObj->selectDataObj($sql);
			$userArr=$userArr[0];
			
			if(!empty($userArr->username)){
			
				if($userArr->email == $email){
					$activation_code = random_number(20);
					$fields = array('activition_code' => $activation_code);
					$where = "id = '".$userArr->id."'";
					
					$update_status = $dbObj->updateTableData("user", $fields, $where);
					
					if(!$update_status){
						redirect("forget_password.php?action=view&msg=Execution error.");
					}
			
					$send_link='<a href="'.LIVE_URL."/reset_password.php?action=view&activation_code=".$activation_code.'" >'.LIVE_URL."/reset_password.php?action=view&activation_code=".$activation_code ."</a>";
					
				
					$message="Dear ".$userArr->full_name.", 
					Your password reset request has been submitted successfully.
					Plese click on the following link and reset your password.
					".$send_link." 
					Thanks.";
					
					sendMail($userArr->email,"info@localhost.com","Password Reset Request",$message);
				
					redirect("forget_password.php?action=view&msg=Please check your email and click on reset link.");
				
				}else{
					redirect("forget_password.php?action=view&msg=Your given username exist but you have no email address in user information.");
				}
		
			}else{
				redirect("forget_password.php?action=view&msg=Your given username not exist.");
			}
		
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
	<table width="100%" cellspacing="0" cellpadding="0" border="0" class="module_header">
		<tr>
			<td>
				<h1><?php echo FORGET_PASSWORD; ?></h1>
			</td>	
		</tr>
	</table>
	<?php if($action=="view"){ ?>
				<form action="forget_password.php" method="post" name="forget_password" id="forget_password" onsubmit="return validateforget_password();" >
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
						<tr>
							<td height="30" width="30%">
								<?php echo ENTER_YOUR_USERNAME;?>
							</td>
							<td width="70%">
								<input type="text" name="username" id="username" alt="username" class="inputbox" size="50" />
							</td>
						</tr>
						<tr>
							<td height="30" width="30%">
								<?php echo ENTER_YOUR_EMAIL;?>
							</td>
							<td width="70%">
								<input type="text" name="email" id="email" alt="email" class="inputbox" size="50" />
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
				</form>
			
<?php	}?>
</div>
			
<?php
require_once("includes/footer.php");
?>