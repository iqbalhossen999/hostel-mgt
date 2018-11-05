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

$path = 'attach_file/';

switch($action){

	case 'update':
	default:

	$id = $_REQUEST['id'];
	if($cur_user_group_id == '1'){
		$sql = "select * from ".DB_PREFIX."prebooking WHERE id='".$id."'";	
	}else{
		$sql = "select * from ".DB_PREFIX."prebooking WHERE user_id='".$cur_user_id."'";	
	}
	$prebookingList = $dbObj->selectDataObj($sql);


	$prebooking = $prebookingList[0];
	$registration_no = $prebooking->registration_no;
	$password = $prebooking->password;
	$name = $prebooking->name;
	$department = $prebooking->department;
	$roll_no = $prebooking->roll_no;
	$session = $prebooking->session;
	$gender = $prebooking->gender;
	$email = $prebooking->email;
	$mobile = $prebooking->mobile;
	$father_name = $prebooking->father_name;
	$f_office_address = $prebooking->f_office_address;
	$f_office_phone = $prebooking->f_office_phone;
	$father_mobile = $prebooking->father_mobile;
	$mother_name = $prebooking->mother_name;
	$m_office_address = $prebooking->m_office_address;
	$m_office_phone = $prebooking->m_office_phone;
	$mother_mobile = $prebooking->mother_mobile;
	$present_address = $prebooking->present_address;
	$address = $prebooking->address;
	$s_photo = $prebooking->s_photo;
	$g_name = $prebooking->g_name;
	$g_signature = $prebooking->g_signature;
	$g_office_address = $prebooking->g_office_address;
	$g_office_phone = $prebooking->g_office_phone;
	$g_mobile = $prebooking->g_mobile;
	$student_signature = $prebooking->student_signature;
	$g_photo = $prebooking->g_photo;
	$password_cond = '0';

		//Build Session Array
	$sql = "select id, name from ".DB_PREFIX."session order by name asc";
	$sessionArr = $dbObj->selectDataObj($sql);

	$sessionId = array();
	$sessionId[0] = SELECT_SESSION_OPT;
	if(!empty($sessionArr)){			
		foreach($sessionArr as $item){
			$sessionId[$item->id] = $item->name;
		}	
	}			
	$sessionList_opt = formSelectElement($sessionId, $session, 'session');

		//Build Gender Array
	$genderArr = array(
		'0' => SELECT_GENDER_OPT,
		'1' => MALE,
		'2' => FEMALE
	);
	foreach($genderArr as $key=>$val){
		$genderId[$key] = $val;
	}	

	$genderList_opt = formSelectElement($genderId, $gender, 'gender');

	$action = 'insert';
	break;

	case 'readonly':
	$sql = "select * from ".DB_PREFIX."prebooking WHERE user_id='".$cur_user_id."'";	
	$studentArr = $dbObj->selectDataObj($sql);
	$student = $studentArr[0];
	$action = 'readonly';
	break;

	case 'success':
	$action = 'success';
	break;

	case 'save':

	if($cur_user_group_id == '3'){
		$id = $cur_user_id;
	}else{
		$id = $_POST['id'];
	}

	$name = $_POST['name'];
	$registration_no = $_POST['registration_no'];
	$password = md5($_POST['password']);
	$department = $_POST['department'];
	$roll_no = $_POST['roll_no'];
	$session = $_POST['session'];
	$gender = $_POST['gender'];
	$email = $_POST['email'];
	$mobile = $_POST['mobile'];
	$father_name = $_POST['father_name'];
	$f_office_address = $_POST['f_office_address'];
	$f_office_phone = $_POST['f_office_phone'];
	$father_mobile = $_POST['father_mobile'];
	$mother_name = $_POST['mother_name'];

	$m_office_address = $_POST['m_office_address'];
	$m_office_phone = $_POST['m_office_phone'];
	$mother_mobile = $_POST['mother_mobile'];
	$present_address = $_POST['present_address'];
	$address = $_POST['address'];
	$g_name = $_POST['g_name'];
	$g_office_address = $_POST['g_office_address'];
	$g_office_phone = $_POST['g_office_phone'];
	$g_mobile = $_POST['g_mobile'];

	
		//Check not for entering any blank entry
	if($cur_user_group_id == '1'){
		if((empty($registration_no)) || (empty($password) && empty($id))){
			$msg = PARAM_MISSING;
			if(empty($id)){
				$url = 'form.php?action=create&msg='.$msg;
			}else{
				$url = 'form.php?action=update&id='.$id.'&page='.$page.'&msg='.$msg;
			}
			redirect($url);
		}
	}


		//check repeation of Prebooking
	$sql = "select registration_no from ".DB_PREFIX."prebooking WHERE registration_no = '".$registration_no."' AND id != '".$id."' limit 1";
	$prebookingList = $dbObj->selectDataObj($sql);		

	if(!empty($prebookingList)){
		$msg = $registration_no.ALREADY_EXISTS;
		$url = 'form.php?action=create&msg='.$msg;
		redirect($url);
	}

		//Now Enter data into database
	if($cur_user_group_id == '3'){
		$fields = array('name' => $name,
			'department' => $department,
			'roll_no' => $roll_no,
			'session' =>$session,
			'gender' => $gender,
			'email' => $email,
			'mobile' => $mobile,
			'father_name' => $father_name,
			'f_office_address' => $f_office_address,
			'f_office_phone' => $f_office_phone,
			'father_mobile' => $father_mobile,
			'mother_name' => $mother_name,
			'm_office_address' => $m_office_address,
			'm_office_phone' => $m_office_phone,
			'mother_mobile' => $mother_mobile,
			'present_address' => $present_address,
			'address' => $address,
			'g_name' => $g_name,
			'g_office_address' => $g_office_address,
			'g_office_phone' => $g_office_phone,
			'g_mobile' => $g_mobile,

						//upload file
			's_photo' => null,
			'student_signature' => null,
			'g_photo' => null,
			'g_signature' => null,

			'updated_by' => $cur_user_id,
			'updated_datetime' => current_date_time(),
			'status' => 1
		);
	}else if($cur_user_group_id == '1'){
		$fields['registration_no'] = $registration_no;
		$fields['mobile'] = $mobile;
		$fields['present_address'] = $present_address;
		if(!empty($fields['password'])){
			$fields['password'] = $password;
				}//if
		}//if

		if($cur_user_group_id == '3'){
			$where = "user_id = '".$cur_user_id."'";
		}else{
			$where = "id = '".$id."'";
		}

			//Save into database
		if(!empty($id)){
			$update_status = $dbObj->updateTableData("prebooking", $fields, $where);

			$sql = "select user_id from ".DB_PREFIX."prebooking WHERE id = '".$id."'";
			$prebookingList = $dbObj->selectDataObj($sql);
			$user_id = $prebookingList[0]->user_id;

			if($cur_user_group_id == '3'){
				$fields1 = array(
					'full_name' => $name,
					'official_name' => $name,
					'photo' => null,
					'email' => $email
				);
				$where2 = "id = '".$cur_user_id."'";
			}else{
				$fields1 = array('username' => $registration_no);

				if(!empty($password)){
					$fields1['password'] = $password;
				}
				$where2 = "id = '".$user_id."'";
			}

			$update_status1 = $dbObj->updateTableData("user", $fields1, $where2);
			if(!$update_status){
				$msg = $name.COULD_NOT_BE_UPDATED;		
				$action = 'insert';
			}else{
				$msg = $name.HAS_BEEN_UPDATED;
				if($cur_user_group_id == '3'){
					$url = 'form.php?action=success&msg='.$msg;
				}else{
					$url = 'studentlist.php?action=view&page='.$page.'&msg='.$msg;
				}
				redirect($url);
			}
		}
		break;

}//switch


require_once("includes/templates.php");
require_once("templates/top_menu.php");
require_once("templates/left_menu.php");

if($cur_user_group_id == '3' && $status == '1'){
	dashboard();
}
?>

<div id="right_column">
	<table width="100%" cellspacing="0" cellpadding="0" border="0" class="module_header">
		<tr>
			<td align="center">
				<h1><?php echo IIUC; ?></h1>
			</td>
		</tr>
		<tr>
			<td align="center">
				<h2><?php echo IIUC_student_hall; ?></h2>
			</td>
		</tr>
		<tr>
			<td align="center">
				<h4><?php echo REGISTRATION_FORM; ?></h4>
			</td>
		</tr>
		<tr>	
			<td class="usr_info">
				<?php echo welcomeMsg($cur_user_id); ?>
			</td>			
		</tr>
		<tr>
			<td>
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
			</td>
		</tr>
	</table>
	
	<?php if($action=="insert"){ ?>

		<form action="form.php" method="post" name="form" id="form" onsubmit="return validateForm(<?php echo $password_cond; ?>);" enctype="multipart/form-data">
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
				<?php if ($usr[0]->group_id == '1'){?>
					<tr>
						<td height="30" colspan="2">
							<?php echo REGISTRATION_NO; ?>:
							<input name="registration_no" id="registration_no" type="text" class="inputbox2" alt="Registration No" size="16" autocomplete="off" value="<?php echo $registration_no; ?>" />
							<?php echo PASSWORD1; ?>:
							<input name="password" id="password" type="password" class="inputbox2" alt="Password" size="10" autocomplete = "off" value="" />
							<?php echo CONFIRM_PASSWORD; ?>:
							<input name="confirm_password" id="confirm_password" type="password" class="inputbox2" alt="Confirm Passwrd" size="10" value="" />
							<input type="hidden" id="name" name="name" value="<?php echo $name; ?>" />
						</td>
					</tr>
				<?php }//if?>
				<?php if($usr[0]->group_id != '1'){ ?>
					<tr>
						<td height="30" colspan="2">
							<?php echo STUDENT_NAME; ?>:
							<input name="name" id="name" type="text" class="inputbox2" alt="name" size="90" value="<?php echo $name; ?>" />

						</td>
					</tr>
					<tr>
						<td height="30">
							<?php echo DEPARTMENT; ?>:
							<input name="department" id="department" type="text" class="inputbox2" alt="Department" size="38" value="<?php echo $department; ?>" />
						</td>
						<td height="30">
							<?php echo ROLL_NO; ?>:
							<input name="roll_no" id="roll_no" type="text" class="inputbox2" alt="Roll No" size="38" value="<?php echo $roll_no; ?>" />
						</td>
					</tr>
					<tr>
						<td height="30">
							<?php echo SESSION; ?>:
							<?php echo $sessionList_opt; ?>
						</td>
						<td height="30">
							<?php echo GENDER; ?>:
							<?php echo $genderList_opt; ?>
						</td>
					</tr>
					<tr>
						<td height="30">
							<?php echo EMAIL; ?>:
							<input name="email" id="email" type="text" class="inputbox2" alt="email" size="44" value="<?php echo $email; ?>" />
							</td> <?php }//if ?>
							<td height="30">
								<?php echo MOBILE; ?>:
								<input name="mobile" id="mobile" type="text" class="inputbox2" alt="mobile" size="39" value="<?php echo $mobile; ?>" />
							</td>
							<?php if($usr[0]->group_id != '1'){ ?>
							</tr>
							<tr>
								<td height="30" colspan="2">
									<?php echo FATHER_NAME; ?>:
									<input name="father_name" id="father_name" type="text" class="inputbox2" alt="Father Name" size="92" value="<?php echo $father_name; ?>" />
								</td>
							</tr>
							<tr>
								<td height="30" colspan="2">
									<?php echo DESIGNATION_OFFICE_ADDRESS; ?>:
									<input name="f_office_address" id="f_office_address" type="text" class="inputbox2" alt="Designation & Office Address" size="76" value="<?php echo $f_office_address; ?>" />
								</td>
							</tr>
							<tr>
								<td height="30">
									<?php echo PHONE_OFFICE; ?>:
									<input name="f_office_phone" id="f_office_phone" type="text" class="inputbox2" alt="Phone Office" size="38" value="<?php echo $f_office_phone; ?>" />
								</td>
								<td height="30">
									<?php echo MOBILE; ?>:
									<input name="father_mobile" id="father_mobile" type="text" class="inputbox2" alt="Mobile" size="39" value="<?php echo $father_mobile; ?>" />
								</td>
							</tr>	
							<tr>
								<td height="30" colspan="2">
									<?php echo MOTHER_NAME; ?>:
									<input name="mother_name" id="mother_name" type="text" class="inputbox2" alt="Mother Name" size="91" value="<?php echo $mother_name; ?>" />
								</td>
							</tr>
							<tr>
								<td height="30" colspan="2">
									<?php echo DESIGNATION_OFFICE_ADDRESS; ?>:
									<input name="m_office_address" id="m_office_address" type="text" class="inputbox2" alt="Designation & Office Address" size="76" value="<?php echo $m_office_address; ?>" />
								</td>
							</tr>
							<tr>
								<td height="30">
									<?php echo PHONE_OFFICE; ?>:
									<input name="m_office_phone" id="m_office_phone" type="text" class="inputbox2" alt="Phone Office" size="38" value="<?php echo $m_office_phone; ?>" />
								</td>
								<td height="30">
									<?php echo MOBILE; ?>:
									<input name="mother_mobile" id="mother_mobile" type="text" class="inputbox2" alt="Mobile" size="39" value="<?php echo $mother_mobile; ?>" />
								</td>
							</tr>
						<?php }//if ?>
						<tr>

							<td height="30" colspan="2" >
								<?php echo PRESENT_ADDRESS; ?>:
								<input type="text" class="inputbox2" name="present_address" id="present_address" size="89" value="<?php echo $present_address;?>" />
							</td>
						</tr>
						<?php if($usr[0]->group_id != '1'){ ?>
							<tr>
								<td height="30" colspan="2">
									<?php echo PERMANENT_ADDRESS; ?>:
									<input type="text" class="inputbox2" name="address" id="address" size="86" value="<?php echo $address; ?>" />
								</td>
							</tr>
							
							<tr>
								<td colspan="2" align="center">
									<h1><?php echo LOCAL_GUARDIAN; ?></h1>
								</td>
							</tr>
							<tr>
								<td height="30" colspan="2">
									<?php echo NAME; ?>:
									<input name="g_name" id="g_name" type="text" class="inputbox2" alt="Name" size="99" value="<?php echo $g_name; ?>" />

								</td>
							</tr>
							<tr>
								<td height="30" colspan="2">
									<?php echo DESIGNATION_OFFICE_ADDRESS; ?>:
									<input name="g_office_address" id="g_office_address" type="text" class="inputbox2" alt="Designation & Office Address" size="77" value="<?php echo $g_office_address; ?>" />
								</td>
							</tr>
							<tr>
								<td height="30">
									<?php echo PHONE_OFFICE; ?>:
									<input name="g_office_phone" id="g_office_phone" type="text" class="inputbox2" alt="Phone Office" size="38" value="<?php echo $g_office_phone; ?>" />
								</td>
								<td height="30">
									<?php echo MOBILE; ?>:
									<input name="g_mobile" id="g_mobile" type="text" class="inputbox2" alt="Mobile" size="39" value="<?php echo $g_mobile; ?>" />
								</td>
							</tr>	


						<?php }//if ?>
						<tr>
							<td colspan="2" height="50">
								<input type="submit" name="Submit" class="button" value="Save" />
								<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><input type="button" onclick="window.location='<?php echo $_SERVER['HTTP_REFERER']; ?>'"  name="cancel" class="cancel" value="<?php echo CANCEL; ?>" /></a>
							</td>
						</tr>
					</table>
					<input type="hidden" name="id" value="<?php echo $id; ?>" />
					<input type="hidden" name="action" value="save" />
					<input type="hidden" name="page" id="page" value="<?php echo $page; ?>" />
				</form>	

			<?php }else if($action=="readonly"){ ?> 

				<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
					<?php if ($usr[0]->group_id == '3'){?>

						<tr>
							<td height="30" colspan="2">
								<?php echo STUDENT_NAME; ?>:
								<?php echo $student->name; ?>
							</td>
						</tr>
						<tr>
							<td height="30">
								<?php echo DEPARTMENT; ?>:
								<?php echo $student->department; ?>
							</td>
							<td height="30">
								<?php echo ROLL_NO; ?>:
								<?php echo $student->roll_no; ?>
							</td>
						</tr>
						<tr>
							<td height="30">
								<?php echo SESSION; ?>:
								<?php echo $student->session; ?>
							</td>
							<td height="30">
								<?php echo ROOM_NO; ?>:
								<?php echo $student->room_no; ?>
							</td>
						</tr>
						<tr>
							<td height="30" colspan="2">
								<?php echo FATHER_NAME; ?>:
								<?php echo $student->father_name; ?>
							</td>
						</tr>
						<tr>
							<td height="30" colspan="2">
								<?php echo DESIGNATION_OFFICE_ADDRESS; ?>:
								<?php echo $student->f_office_address; ?>
							</td>
						</tr>
						<tr>
							<td height="30">
								<?php echo PHONE_OFFICE; ?>:
								<?php echo $student->f_office_phone; ?>
							</td>
							<td height="30">
								<?php echo MOBILE; ?>:
								<?php echo $student->father_mobile; ?>
							</td>
						</tr>	
						<tr>
							<td height="30" colspan="2">
								<?php echo MOTHER_NAME; ?>:
								<?php echo $student->mother_name; ?>
							</td>
						</tr>
						<tr>
							<td height="30" colspan="2">
								<?php echo DESIGNATION_OFFICE_ADDRESS; ?>:
								<?php echo $student->m_office_address; ?>
							</td>
						</tr>
						<tr>
							<td height="30">
								<?php echo PHONE_OFFICE; ?>:
								<?php echo $student->m_office_phone; ?>
							</td>
							<td height="30">
								<?php echo MOBILE; ?>:
								<?php echo $student->mother_mobile; ?>
							</td>
						</tr>
						<tr>
							<td height="30" colspan="2" >
								<?php echo PRESENT_ADDRESS; ?>:
								<?php echo $student->present_address; ?>
							</td>
						</tr>
						<tr>
							<td height="30" colspan="2">
								<?php echo PERMANENT_ADDRESS; ?>:
								<?php echo $student->address; ?>
							</td>
						</tr>
						<tr>
							<td height="30" colspan="2">
								<?php echo PHOTO; ?>:
								<?php echo $student->s_photo; ?>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<table width="100%" cellspacing="0" cellpadding="0" border="0" class="module_header">
									<tr>
										<td align="center">
											<h1><?php echo LOCAL_GUARDIAN; ?></h1>
										</td>				
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td height="30" colspan="2">
								<?php echo NAME; ?>:
								<?php echo $student->g_name; ?>
							</td>
						</tr>
						<tr>
							<td height="30" colspan="2">
								<?php echo SIGNATURE; ?>:
								<?php echo $student->g_signature; ?>
							</td>
						</tr>
						<tr>
							<td height="30" colspan="2">
								<?php echo DESIGNATION_OFFICE_ADDRESS; ?>:
								<?php echo $student->g_office_address; ?>
							</td>
						</tr>
						<tr>
							<td height="30">
								<?php echo PHONE_OFFICE; ?>:
								<?php echo $student->g_office_phone; ?>
							</td>
							<td height="30">
								<?php echo MOBILE; ?>:
								<?php echo $student->g_mobile; ?>
							</td>
						</tr>	
						<tr>
							<td height="30">
								<?php echo STUDENT_SIGNATURE; ?>:
								<?php echo $student->student_signature; ?>
							</td>
						</tr>
						<tr>
							<td height="30" colspan="2">
								<?php echo PHOTO; ?>:
								<?php echo $student->g_photo; ?>
							</td>
						</tr>
					</table>
				<?php }//if ?>	
			<?php }//else if ?>
		</div>

		<?php
		require_once("includes/footer.php");
		?>