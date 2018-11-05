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

if($cur_user_group_id != '1'){
	dashboard();
}//if

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

	$sql = "select * from ".DB_PREFIX."prebooking where accept = '0' order by id asc";
	$prebookingList = $dbObj->selectDataObj($sql);
	$action = 'view';
	$path_view = 'attach_file/';

		//Pagination 
	if(!empty($prebookingList)){
		$total_rows = sizeof($prebookingList);
	}else{
		$total_rows =0;
	}
		//find start
	$s = ($page - 1) * $limit;
	$total_page = $total_rows/$limit;

	break;

	case 'accept':	
	$id = $_REQUEST['id'];
	$sql = "select * from ".DB_PREFIX."prebooking WHERE id='".$id."'";	
	$prebookingList = $dbObj->selectDataObj($sql);
	$prebooking = $prebookingList[0];
	$registration_no = $prebooking->registration_no;
	$name = $prebooking->name;
	$password = md5($prebooking->password);
	$email = $prebooking->email;
	$fields = array('accept' => '1');
	$where = "id='".$id."'";	
	$accept = $dbObj->updateTableData("prebooking", $fields, $where);	

	if(!$accept){
		$msg = $registration_no.COULD_NOT_BE_ACCEPTED;
	}else{
		$fields = array('username' => $registration_no,
			'group_id' => '3',
			'full_name' => $name,
			'password' => $password,
			'email' => $email,
			'created_by' => $cur_user_id,
			'created_datetime' => current_date_time(),
			'updated_by' => $cur_user_id,
			'updated_datetime' => current_date_time()
		);

		$inserted = $dbObj->insertTableData("user", $fields);
		
		if($inserted){
			$insert_id = $dbObj->dbconnectid->insert_id;
			// $insert_id = $dbObj->Insert_ID();
			$fields2 = array('user_id' => $insert_id);
			$where = "id = '".$id."'";
			$update = $dbObj->updateTableData("prebooking", $fields2, $where);
		}

	}

	$url = 'pre_request.php?action=view&page='.$page.'&id='.$id.'&msg='.$msg;
	redirect($url);
	break;	
	
	case 'detail':	
	$id = $_REQUEST['id'];

	$sql = "select * from ".DB_PREFIX."prebooking WHERE id='".$id."'";	
	$targetArr = $dbObj->selectDataObj($sql);
	$target = $targetArr[0];

	if($target->gender == '1'){
		$gender = MALE;
	}else if($target->gender == '2'){
		$gender = FEMALE;
	}

	$action = 'detail';
	break;

	case 'delete':	
	$id = $_REQUEST['id'];
	$sql = "select * from ".DB_PREFIX."prebooking WHERE id='".$id."'";	
	$prebookingList = $dbObj->selectDataObj($sql);
	$prebooking = $prebookingList[0];
	$registration_no = $prebooking->registration_no;

	$where = "id='".$id."'";	

	$success = $dbObj->deleteTableData("prebooking", $where);	

	if(!$success){
		$msg = $registration_no.COULD_NOT_BE_DELETED;
	}else{
		$msg = $registration_no.HAS_BEEN_DELETED;
	}

	$url = 'pre_request.php?action=view&page='.$page.'&msg='.$msg;
	redirect($url);
	break;

}//switch


require_once("includes/templates.php");
require_once("templates/top_menu.php");
require_once("templates/left_menu.php");
?>

<div id="right_column">
	<?php if(!empty($msg)){	?>
		<table id="system_message">
			<tr>
				<td><?php echo $msg; ?></td>
			</tr>
		</table>
	<?php } ?>
	<table width="100%" cellspacing="0" cellpadding="0" border="0" class="module_header">
		<tr>
			<td>
				<h1><?php echo PENDING_REQUEST; ?></h1>
			</td>	
			<td class="usr_info">
				<?php echo welcomeMsg($cur_user_id); ?>
			</td>			
		</tr>
	</table>
	
	<?php if($action=="view"){ ?>

		<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
			<tr>
				<td>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="datagrid">			
						<tr class="head">
							<td height="30" width="10%"><strong><?php echo REGISTRATION_NO; ?></strong></td>
							<td width="20%"><strong><?php echo NAME; ?></strong></td>
							<td width="10%"><strong><?php echo FACULTY_NAME; ?></strong></td>
							<td width="10%"><strong><?php echo ADDRESS; ?></strong></td>
							<td width="5%"><strong><?php echo MOBILE; ?></strong></td>
							<td width="10%"><strong><?php echo GENDER; ?></strong></td>
							<td width="10%"><strong><?php echo DATE_TIME; ?></strong></td>
							<td width="5%"><strong><?php echo PASSWORD1; ?></strong></td>
							<td width="20%"><strong><?php echo ACTION; ?></strong></td>
						</tr>


						<?php			
						if(!empty($prebookingList)){	
							$maxPageLimit = (($s+$limit) > $total_rows) ? $total_rows : ($s+$limit);
							for($rownum = $s; $rownum <$maxPageLimit; $rownum++){		
								$class = (($rownum%2)==0) ? ' class="even"' : ' class="odd"';
								if($prebookingList[$rownum]->gender == '1'){
									$gender = MALE;
								}else if($prebookingList[$rownum]->gender == '2'){
									$gender = FEMALE;
								}
								?>
								<tr <?php echo $class; ?>>
									<td height="30"><?php echo $prebookingList[$rownum]->registration_no; ?></td>	
									<td><?php echo $prebookingList[$rownum]->name; ?></td>				
									<td><?php echo $prebookingList[$rownum]->faculty_name; ?></td>
									<td><?php echo $prebookingList[$rownum]->address; ?></td>
									<td><?php echo $prebookingList[$rownum]->mobile; ?></td>
									<td><?php echo $gender; ?></td>
									<td><?php echo dateTimeConvertion($prebookingList[$rownum]->created_datetime); ?></td>
									<td><?php echo $prebookingList[$rownum]->password; ?></td>
									<td>								
										<a class="details" href="pre_request.php?action=detail&id=<?php echo $prebookingList[$rownum]->id; ?>" title="Details">&nbsp;</a>
										<a class="accept" href="pre_request.php?action=accept&id=<?php echo $prebookingList[$rownum]->id; ?>" onclick="return confirm('Are you sure you want to Accept?');" title="Accept">&nbsp;</a>
										<a class="delete" href="pre_request.php?action=delete&id=<?php echo $prebookingList[$rownum]->id; ?>" onclick="return confirm('Are you sure you want to delete?');" title="Delete">&nbsp;</a>
									</td>
								</tr>
								<?php 
									}//for
								}else{ ?>
									<tr height="30">
										<td colspan="7">
											<?php echo NO_REQUEST_FOUND; ?>
										</td>
									</tr>
									<?php 
								}
								if($total_page > 1){ ?>
									<tr height="50">
										<td colspan="7"><?php  echo pagination($total_rows,$limit,$page,''); ?></td>
									</tr>
								<?php } ?>					
							</table>
						</td>
					</tr>
				</table>

			<?php }else if($action == 'detail'){ ?>

				<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_details">
					<tr>
						<td height="50" colspan="2">
							<a class="gobackup" href="<?php echo $_SERVER['HTTP_REFERER']; ?>" title="Go Back">&nbsp;</a>
						</td>
					</tr>
					<tr class="holder topholder">
						<td width="30%" height="30"><?php echo REGISTRATION_NO; ?>:</td>
						<td width="70%"><strong><?php echo $target->registration_no; ?></strong></td>
					</tr>
					<tr class="holder">
						<td height="30"><?php echo STUDENT_NAME; ?>:</td>
						<td><strong><?php echo strtoupper($target->name); ?></strong></td>
					</tr>
					<tr class="holder">
						<td height="30"><?php echo COURSE_NAME; ?>:</td>
						<td><?php echo $target->course_name; ?></td>
					</tr>
					<tr class="holder">
						<td height="30"><?php echo FACULTY_NAME; ?>:</td>
						<td><?php echo $target->faculty_name; ?></td>
					</tr>
					<tr class="holder">
						<td height="30"><?php echo GENDER; ?>:</td>
						<td><?php echo $gender; ?></td>
					</tr>
					<tr class="holder">
						<td height="30"><?php echo EMAIL; ?>:</td>
						<td><?php echo $target->email; ?></td>
					</tr>
					<tr class="holder">
						<td height="30"><?php echo MOBILE; ?>:</td>
						<td><?php echo $target->mobile; ?></td>
					</tr>
					<tr class="holder">
						<td height="30"><?php echo PRESENT_ADDRESS; ?>:</td>
						<td><?php echo $target->present_address; ?></td>
					</tr>
					<tr class="holder">
						<td height="30"><?php echo PERMANENT_ADDRESS; ?>:</td>
						<td><?php echo $target->address; ?></td>
					</tr>
					<tr class="holder">
						<td height="30"><?php echo DATE_TIME; ?>:</td>
						<td><?php echo dateTimeConvertion($target->created_datetime); ?></td>
					</tr>
					<tr>
						<td height="50" colspan="2">
							<a class="gobackup" href="<?php echo $_SERVER['HTTP_REFERER']; ?>" title="Go Back">&nbsp;</a>
						</td>
					</tr>
				</table>

			<?php }//else if ?>

		</div>

		<?php
		require_once("includes/footer.php");
		?>