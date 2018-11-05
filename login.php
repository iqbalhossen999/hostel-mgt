<?php
require_once("includes/header.php");
//if login values are submitted
if(isset($_POST['login_submitted'])){

	$username = $_POST['username'];
	$password = $_POST['password'];
	$password = md5($password);
	$user = new User();
	
	//Check validity against submitted values
	if($user->validUser($username, $password)){
		redirect("index.php");
		exit;
	}
}else{//If already loggedIn and again try to browse login.php page	
	$usr = $user->getUser();
	if(!empty($usr)){
		redirect("index.php");
		exit;
	}
}//if


//Call templates
require_once("includes/template2.php");
?>
<div id="right_column_extend">
	<form action="login.php" method="post" name="user_login" id="user_login_form" style="margin:0px; padding:0px;">
		<table cellpadding="0" cellspacing="0" border="0" id="login_panel" width="100%">
			<tr>
				<td id="left_panel" width="43%">
					<a href="dashboard.php" id="back_home">&nbsp;</a>
				</td>
				<td width="50%" id="log_panel">
					<table width="100%" cellspacing="0" cellpadding="0" border="0" >
						<tr>
							<td height="70">
								<?php 
								if($_POST['login_submitted']){
									echo '<span id="invalid_login">'.INVALID_USERNAME_PASSWORD.'</span>';
								}
								?>
							</td>			
						</tr>
					</table>
					<table cellpadding="0" cellspacing="0" border="0"  width="100%">
						<tr>
							<td height="25"><?php echo USER_NAME1; ?></td>
						</tr>
						<tr>
							<td class="login_input"> 
								<input type="text" name="username" id="username" value="" />
							</td>
						</tr>
						<tr>
							<td height="25"><?php echo PASSWORD1; ?></td>
						</tr>
						<tr>
							<td class="login_input">
								<input type="password" name="password" id="password" value="" />
							</td>
						</tr>
						<tr>
							<td height="30">
								<!--<input type="checkbox" class="forgot" /> -->
								<div id="log_img"><img src="images/bulet_forgot.gif" border="0" /></div>
								<span class="for_click"><?php echo FORGOT_PASSWORD; ?>?</span>
							</td>
						</tr>
						<tr>
							<td height="25">
								<input type="submit" name="submit" id="submit" class="log_submit" value="<?php echo LOGIN_NOW; ?>" />
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<input type="hidden" name="login_submitted" value="1" />
	</form>
</div>

<?php
require_once("includes/footer.php");
?>
