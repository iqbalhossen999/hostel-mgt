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
$path_view = 'attach_file/';
//Get Page Number 
if(empty($_REQUEST['page'])) {
	$page=1;
}else{
	$page = $_REQUEST['page']; 
}
		
switch($action){
	case 'view':	
	default:
		
		$sql = "select * from ".DB_PREFIX."user where group_id != '3' order by group_id, username asc";
		$userList = $dbObj->selectDataObj($sql);
		$action = 'view';
		$path_view = 'attach_file/';
		$msg = $_REQUEST['msg'];
		
		//Pagination 
		if(!empty($userList)){
			$total_rows = sizeof($userList);
		}else{
			$total_rows =0;
		}
		//find start
		$s = ($page - 1) * $limit;
		$total_page = $total_rows/$limit;
		
		break;
		
	case 'update':
	case 'create':
	
		if(!empty($_REQUEST['id'])){
			$id = $_REQUEST['id'];
			$sql = "select * from ".DB_PREFIX."user WHERE id='".$id."'";	
			$userList = $dbObj->selectDataObj($sql);
			$uuser = $userList[0];
			$group_id = $uuser->group_id;
			$hall_id = $uuser->hall_id;
			$full_name = $uuser->full_name;
			$official_name = $uuser->official_name;
			$username = $uuser->username;
			$email = $uuser->email;
			$photo = $uuser->photo;
			
		}else{
			$id = '';
			$group_id = '';
			$hall_id = '';
			$full_name = '';
			$official_name = '';
			$username = '';
			$password = '';
			$email = '';
			$photo = '';
		}
		
		//Build Hall Array
		$sql = "select id, name from ".DB_PREFIX."hall order by name asc";
		$hallArr = $dbObj->selectDataObj($sql);
		
		$hallId = array();
		$hallId[0] = SELECT_HALL_OPT;
		if(!empty($hallArr)){			
			foreach($hallArr as $item){
				$hallId[$item->id] = $item->name;
			}	
		}			
		$hallList_opt = formSelectElement($hallId, $hall_id, 'hall_id');
		
		$action = 'insert';
		break;
		
	case 'save':	
		$id = $_POST['id'];
		$group_id = $_POST['group_id'];
		$hall_id = $_POST['hall_id'];
		$full_name = $_POST['official_name'];
		$official_name = $_POST['official_name'];
		$username = $_POST['username'];
		$password = $_POST['password'];
		$email = $_POST['email'];
		
		//check repeation of Username
		if(empty($id)){
			$sql = "select username from ".DB_PREFIX."user WHERE username = '".$username."' limit 1";
			$userList = $dbObj->selectDataObj($sql);		
			
			if(!empty($userList)){
				$msg = $username.ALREADY_EXISTS;
				$url = 'user.php?action=create&msg='.$msg;
				redirect($url);
			}			
		}else if(!empty($id)){
			$sql = "select username from ".DB_PREFIX."user WHERE id!='".$id."' AND username = '".$username."' limit 1";
			$userList = $dbObj->selectDataObj($sql);		
			
			if(!empty($userList)){
				$msg = $username.ALREADY_EXISTS;
				$url = 'user.php?action=update&page='.$page.'&id='.$id.'&msg='.$msg;
				redirect($url);
			}
		}
		
		//Upload file
		$path = 'attach_file/';
		$uploaded = upload_file($_FILES['photo'], $path, $username, 'jpg,jpeg,png,gif');
		
		//Check for not inserting any unsupported format files
		if($uploaded['error_counter'] != '0'){
			$msg = implode("<br />",$uploaded['error']);
			$url = empty($id) ? 'user.php?action=create&msg='.$msg : 'user.php?action=update&id='.$id.'&msg='.$msg;
			redirect($url);
		}
		
		if(!empty($id)){
			$fields = array('group_id' => $group_id,
						'hall_id' => $hall_id,
						'full_name' => $full_name,
						'official_name' => $official_name,
						'email' => $email,
						'username' => $username,
						'updated_by' => $cur_user_id,
						'updated_datetime' => current_date_time()
						);
				
			if(!empty($password)){
				$fields['password'] = md5($password);
			}
			
			if($uploaded['file'][0]['upfile'] != ''){
				$fields['photo'] = $uploaded['file'][0]['upfile'];
			}
						
			$where = "id = '".$id."'";
			$update_status = $dbObj->updateTableData("user", $fields, $where);	
			
			if(!$update_status){
				$msg = $username.COULD_NOT_BE_UPDATED;		
				$action = 'insert';
			}else{
				$msg = $username.HAS_BEEN_UPDATED;
				$url = 'user.php?action=view&page='.$page.'&msg='.$msg;
				redirect($url);
			}
		}else{
			$fields = array('group_id' => $group_id,
						'hall_id' => $hall_id,
						'full_name' => $full_name,
						'official_name' => $official_name,
						'username' => $username,
						'password' => md5($password),
						'email' => $email,
						'photo' => $photo,
						'created_by' => $cur_user_id,
						'created_datetime' => current_date_time(),
						'updated_by' => $cur_user_id,
						'updated_datetime' => current_date_time()
						);
			
			if($uploaded['file'][0]['upfile'] != ''){
				$fields['photo'] = $uploaded['file'][0]['upfile'];
			}
		
			$inserted = $dbObj->insertTableData("user", $fields);	
			if(!$inserted){
				$msg = $username.COULD_NOT_BE_CREATED;	
				$action = 'insert';
			}else{
				$msg = $username.CREATED_SUCCESSFULLY;
				$url = 'user.php?action=view&msg='.$msg;
				redirect($url);
			}
		}
		break;
		
	case 'delete':	
		$id = $_REQUEST['id'];	
		$sql = "select * from ".DB_PREFIX."user WHERE id='".$id."'";	
		$userList = $dbObj->selectDataObj($sql);
		$user = $userList[0];
		$username = $user->username;
		$where = "id='".$id."'";	
		$success = $dbObj->deleteTableData("user", $where);	
		if(!$success){
			$msg = $username.COULD_NOT_BE_DELETED;
		}else{
			$msg = $username.HAS_BEEN_DELETED;
		}
		$url = 'user.php?action=view&page='.$page.'&msg='.$msg;
		redirect($url);
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
				<h1><?php echo USER_MANAGEMENT; ?></h1>
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
								<tr class="footer">
									<td colspan="7" style="background:#EEEEEE;">
										<b><a href="user.php?action=create"><?php echo CREATE; ?></a></b>
									</td>
								</tr>				
							<tr class="head">
								<td height="30" width="15%">
									<strong><?php echo GROUP; ?></strong>
								</td>
								<td width="15%">
									<strong><?php echo HALL; ?></strong>
								</td>
								<td width="15%">
									<strong><?php echo OFFICIAL_NAME; ?></strong>
								</td>
								<td width="10%">
									<strong><?php echo USERNAME; ?></strong>
								</td>
								<td width="15%">
									<strong><?php echo EMAIL; ?></strong>
								</td>
								<td width="15%">
									<strong><?php echo PHOTO; ?></strong>
								</td>
								<td width="15%">
									<strong><?php echo ACTION; ?></strong>
								</td>
							</tr>
							<?php
							if(!empty($userList)){	
								//echo '<pre>';print_r($userList);exit;
								if(($s+$limit) > $total_rows){
									$maxPageLimit = $total_rows;
								}else{
									$maxPageLimit = $s+$limit;
								}		
								
								$sl = ($limit*$page)-($limit-1);
								for($rownum = $s; $rownum <$maxPageLimit; $rownum++){					
										if(($rownum%2)==0){//even
											$class = ' class="even"';									
										}else{//odd
											$class = ' class="odd"';									
										}	 								
							?>
							<tr <?php echo $class; ?>>
								<td>
									<?php
									$group = getNameById("user_group", $userList[$rownum]->group_id);
									echo $group->name;
									?>
								</td>
								<td>
									<?php
									$hall = getNameById("hall", $userList[$rownum]->hall_id);
									if($hall->name == ""){
										echo NOT_SELECTED;
									}else{
										echo $hall->name;	
									}
									?>
								</td>
								<td>
									<?php echo $userList[$rownum]->official_name; ?> 
								</td>
								<td>
									<?php echo $userList[$rownum]->username; ?> 
								</td>
								<td>
									<?php echo $userList[$rownum]->email; ?> 
								</td>
								<td>
									<?php if($userList[$rownum]->photo == ''){?>
											<img height="50" width="60" src="<?php echo $path_view.$userList[$rownum]->photo; ?>" title="<?php echo $userList[$rownum]->official_name;?>" />
									<?php }else {?> 
											<a id="example4" href="<?php echo $path_view.$userList[$rownum]->photo ;?>" ><img height="50" width="60" src="<?php echo $path_view.$userList[$rownum]->photo ;?>" title="<?php echo $userList[$rownum]->official_name;?>" /></a>
									<?php } ?>
								</td>
								<td>
									<a class="edit" href="user.php?action=update&page=<?php echo $page; ?>&id=<?php echo $userList[$rownum]->id; ?>" title="Edit">&nbsp;</a>
									<?php if($userList[$rownum]->group_id != '1'){?>	
									<a class="delete" href="user.php?action=delete&id=<?php echo $userList[$rownum]->id; ?>" onclick="return confirm('Are you sure you want to delete?');" title="Delete">&nbsp;</a>
									<?php } ?>
								</td>
							</tr>
							<?php 
								}//for
							}else{ ?>
							<tr height="30">
								<td colspan="7">
									<?php echo EMPTY_DATA; ?>
								</td>
							</tr>
							<?php 
							}
							if($total_page > 1){ ?>
							<tr height="50">
								<td colspan="7">
									<?php 
									echo pagination($total_rows,$limit,$page,''); ?>
								</td>
							</tr>
							<?php } ?>
							<tr class="footer">
								<td colspan="7">
									<b><a href="user.php?action=create"><?php echo CREATE; ?></a></b>
								</td>
							</tr>				
						</table>
					</td>
				</tr>
			</table>
				
	<?php }elseif($action=="insert"){ 
		if(empty($id)){
			$token = 0;//token has been set for password repeatation in case of update
		}else{
			$token = 1;
		}
	?>
	
		<form action="user.php" method="post" name="user" id="user" onsubmit="return validateUserCreate(<?php echo $token; ?>);" enctype="multipart/form-data">
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
				<tr>
					<td height="30" width="20%">
						<?php echo SELECT_GROUP_OPT; ?>:
					</td>
					<td width="80%">
						<?php if($group_id == '1'){?>
									<strong><?php echo 'Administrator';?></strong>
									<input type="hidden" name="group_id" value="1" />
					  	<?php }else{ ?>
								<strong><?php echo 'Others';?></strong>
									<input type="hidden" name="group_id" value="2" />
						<?php }//else?>
					</td>
				</tr>
			
				<tr>
					<td colspan="2">
					<div id="loaderContainer"></div>
					<div id="hall_display">
						<?php if(empty($id) || ($group_id == 2)){?>
							<table width="100%" cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td height="30" width="20%">
										<?php echo SELECT_HALL_OPT; ?>:
									</td>
									<td width="80%">
										<?php echo $hallList_opt; ?>
										<span class="required_field">*</span>
									</td>
								</tr>
							</table>
						<?php }//if ?>
					</div>
					</td>
				</tr>
				<tr>
					<td height="30" width="20%">
						<?php echo OFFICIAL_NAME; ?>:
					</td>
					<td width="80%">
						<input name="official_name" id="official_name" type="text" class="inputbox" alt="Official Name" size="36" value="<?php echo $official_name; ?>" autocomplete="off" />
						<span class="required_field">*</span>
					</td>
				</tr>
				<tr>
					<td height="30" width="20%">
						<?php echo USERNAME; ?>:
					</td>
					<td width="80%">
						<input name="username" id="username" type="text" class="inputbox" alt="User Name" size="36" value="<?php echo $username; ?>" autocomplete="off" />
						<span class="required_field">*</span>
						<input type="button" name="check_user" id="check_user" value="Check" onclick="checkUsername();" />
					</td>
				</tr>
				<tr>
					<td style="padding-left:140px;" colspan="2">
						<div id="loaderContainer"></div>
						<div id="username_display"></div>
					</td>
				</tr>
				<tr>
					<td height="30" width="20%">
						<?php echo PASSWORD1; ?>:
					</td>
					<td width="80%">
						<input name="password" id="password" type="password" class="inputbox" alt="Password" size="36" value="<?php echo $password; ?>" autocomplete="off"  />
						<span class="required_field">*</span>
					</td>
				</tr>
				<tr>
					<td height="30" width="20%">
						<?php echo CONFIRM_PASSWORD; ?>:
					</td>
					<td width="80%">
						<input name="retype_password" id="retype_password" type="password" class="inputbox" alt="Retype Password" size="36" value="<?php echo $retype_password; ?>" autocomplete="off" />
						<span class="required_field">*</span>
					</td>
				</tr>
				<tr>
					<td height="30" width="20%">
						<?php echo EMAIL; ?>:
					</td>
					<td width="80%">
						<input name="email" id="email" type="text" class="inputbox" alt="email" size="36" value="<?php echo $email; ?>" />
						<span class="required_field">*</span>
					</td>
				</tr>
				<tr>
					<td height="30" width="20%">
						<?php echo PHOTO; ?>:
					</td>
					<td width="80%">
						<input name="photo" id="photo" type="file" class="inputbox" alt="Photo" size="23" value="<?php echo $photo; ?>" />
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
			<input type="hidden" name="page" id="page" value="<?php echo $page; ?>" />
		</form>
			
		<?php }//else if	?>
			
</div>

<?php
require_once("includes/footer.php");
?>