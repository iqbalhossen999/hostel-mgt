<?php
require_once("includes/header.php");

$_SESSION['captcha'] = captcha();

$action = $_REQUEST['action'];
$msg = $_REQUEST['msg'];

switch($action){
		
	case 'create':
	default:
		$name = '';
		$email = '';
		$course_name = '';
		$class_name = '';
		$batch_name = '';
		$registration_no = '';
		$faculty_name = '';
		$address = '';
		$mobile = '';
		$message = '';
		$action = 'insert';
		break;
	
	case 'success':
		
		$action = 'success';
		break;
		
	case 'save':
		
		$name = $_POST['name'];
		$email = $_POST['email'];
		$course_name = $_POST['course_name'];
		$registration_no = $_POST['registration_no'];
		$faculty_name = $_POST['faculty_name'];
		$address = $_POST['address'];
		$mobile = $_POST['mobile'];
		$gender = $_POST['gender'];
		$permanent_address = $_POST['permanent_address'];
		$captcha_real = strtolower($_SESSION['securimage_code_value']['default']);
		$captcha_send = strtolower($_POST['ct_captcha']);
		$password = generateSecKey();
		
		process_si_contact_form(); // Process the form, if it was submitted
		if($captcha_real != $captcha_send){
			$msg = CODE_DOESNOT_MATCH;
			$url = 'prebooking.php?action=create&msg='.$msg;
			redirect($url);
		}
		
		//check repeation of Prebooking
		$sql = "select registration_no from ".DB_PREFIX."prebooking WHERE registration_no = '".$registration_no."' limit 1";
		$prebookingList = $dbObj->selectDataObj($sql);		
		
		if(!empty($prebookingList)){
			$msg = INVALID_REGISTRATION_NO;
			$url = 'prebooking.php?action=create&msg='.$msg;
			redirect($url);
		}
					
	
		
		$fields = array('name' => $name,
					'email' => $email,
					'course_name' => $course_name,
					'registration_no' => $registration_no,
					'faculty_name' => $faculty_name,
					'present_address' => $address,
					'address' => $permanent_address,
					'mobile' => $mobile,
					'password' => $password,
					'gender' => $gender,
					'created_datetime' => current_date_time()
					);
		
		$inserted = $dbObj->insertTableData("prebooking", $fields);	
		if(!$inserted){
			$msg = $name.NOT_SEND_PLEASE_TRY_AGAIN;
			$url = 'prebooking.php?action=success&msg='.$msg;
		}else{
			$msg = 'Your request for Registration No. '.$registration_no.' has been sent successfully';
			$url = 'prebooking.php?action=success&msg='.$msg;
		}
		redirect($url);
		break;

}//switch


require_once("includes/templates.php");
require_once("templates/top_menu.php");
require_once("templates/left_menu.php");
?>

<div id="right_column">
	
	<table width="100%" cellspacing="0" cellpadding="0" border="0" class="module_header">
		<tr>
			<td><h1><?php echo PREBOOKING; ?></h1></td>
		</tr>
	</table>
	<?php if(!empty($msg)){ ?>
		<table id="system_message">
			<tr>
				<td><?php echo $msg; ?></td>
			</tr>
		</table>
	<?php } 
	if($action=="insert"){ ?>

	<form action="prebooking.php" method="post" name="prebooking" id="prebooking" onsubmit="return validateUserdGroup();" enctype="multipart/form-data">
		<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
			<tr>
				<td height="30" width="20%"><?php echo ADMISSION_ROLL_CLASS_ID; ?>:</td>
				<td width="80%">
					<input name="registration_no" id="registration_no" type="text" class="inputbox2" alt="Registration No" size="78" value="<?php echo $registration_no; ?>" />
					<span class="required_field">*</span>
				</td>
			</tr>
			<tr>
				<td height="30"><?php echo NAME; ?>:</td>
				<td>
					<input name="name" id="name" type="text" class="inputbox2" alt="Name" size="78" value="<?php echo $name; ?>" />
					<span class="required_field">*</span>
				</td>
			</tr>
			<tr>
				<td height="30"><?php echo COURSE_NAME; ?>:</td>
				<td >
					<input name="course_name" id="course_name" type="text" class="inputbox2" alt="Course Name" size="78" value="<?php echo $course_name; ?>" />
					<span class="required_field">*</span>
				</td>
			</tr>
			<tr>
				<td height="30"><?php echo FACULTY_NAME; ?>:</td>
				<td>
					<input name="faculty_name" id="faculty_name" type="text" class="inputbox2" alt="Faculty Name" size="78" value="<?php echo $faculty_name; ?>" />
					<span class="required_field">*</span>
				</td>
			</tr>
			<tr>
				<td height="30"><?php echo GENDER; ?>:</td>
				<td>
					<input type="radio" name="gender" id="gender_m" value="1" /> <?php echo MALE; ?> 
					<input type="radio" name="gender" id="gender_f" value="2" /> <?php echo FEMALE; ?>
					<span class="required_field">*</span>
				</td>
			</tr>
			<tr>
				<td height="30"><?php echo EMAIL; ?>:</td>
				<td>
					<input name="email" id="email" type="text" class="inputbox2" alt="Email" size="78" value="<?php echo $email; ?>" />
					<span class="required_field">*</span>
				</td>
			</tr>
				<td height="30"><?php echo MOBILE; ?>:</td>
				<td>
					<input name="mobile" id="mobile" type="text" class="inputbox2" alt="Mobile" size="78" value="<?php echo $mobile; ?>" />
					<span class="required_field">*</span>
				</td>
			</tr>
			<tr>
				<td height="30"><?php echo PRESENT_ADDRESS; ?>:</td>
				<td>
					<input type="text" name="address" id="address" class="inputbox2" alt="address" size="78" value="<?php echo $address; ?>" />
					<span class="required_field">*</span>
				</td>
			</tr>
			<tr>
				<td height="30"><?php echo PERMANENT_ADDRESS; ?>:</td>
				<td>
					<input type="text" name="permanent_address" id="permanent_address" class="inputbox2" alt="Permanent Address" size="78" value="<?php echo $address; ?>" />
					<span class="required_field">*</span>
				</td>
			</tr>
			<tr>
				<td><?php echo ACTIVATION_CODE; ?></td>
				<td>
						<img id="siimage" style="border: 1px solid #000; margin-right: 15px" src="captcha/securimage_show.php?sid=<?php echo md5(uniqid()) ?>" alt="CAPTCHA Image" align="left" />
						<object type="application/x-shockwave-flash" data="captcha/securimage_play.swf?bgcol=#ffffff&amp;icon_file=captcha/images/audio_icon.png&amp;audio_file=captcha/securimage_play.php" height="32" width="32">
						<param name="movie" value="captcha/securimage_play.swf?bgcol=#ffffff&amp;icon_file=captcha/images/audio_icon.png&amp;audio_file=captcha/securimage_play.php" />
						</object>
						&nbsp;
						<a tabindex="-1" style="border-style: none;" href="#" title="Refresh Image" onclick="document.getElementById('siimage').src = 'captcha/securimage_show.php?sid=' + Math.random(); this.blur(); return false"><img src="captcha/images/refresh.png" alt="Reload Image" height="32" width="32" onclick="this.blur()" align="bottom" border="0" /></a><br />
						<strong><?php echo ENTER_ACTIVATION_CODE; ?>:</strong><br />
						 <?php echo @$_SESSION['ctform']['captcha_error'] ?>
						<input type="text" name="ct_captcha" size="18" maxlength="8" />
				</td>
			</tr>
			<?php $_SESSION['ctform']['success'] = false; // clear success value after running ?>
			<tr>
				<td colspan="2" height="50" align="left">
					<input type="submit" name="Submit" class="button" value="Save" />
					<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><input type="button" onclick="window.location='<?php echo $_SERVER['HTTP_REFERER']; ?>'"  name="cancel" class="cancel" value="<?php echo CANCEL; ?>" /></a>
				</td>
			</tr>		
		</table>	
		<input type="hidden" name="action" value="save" />
		<input type="hidden" name="captcha_real" id="captcha_real" value="<?php echo $_SESSION['captcha']['code']; ?>" />
	</form>
	<?php }else if($action == 'success'){?>
	<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
		<tr>
			<td align="center">
			<img src="images/yes.png" width="30%" height="30%" />
			</td>
		</tr>
	</table>
	<?php }//else if ?>
</div>
			
<?php
require_once("includes/footer.php");
?>