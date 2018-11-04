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
$msg = '';

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
		
		$sql = "select * from ".DB_PREFIX."room_facilities order by name asc";
		$facilitiesList = $dbObj->selectDataObj($sql);
		$action = 'view';
		$path_view = 'attach_file/';
		$msg = $_REQUEST['msg'];
		
		//Pagination 
		if(!empty($facilitiesList)){
			$total_rows = sizeof($facilitiesList);
		}else{
			$total_rows =0;
		}
		//find start
		$s = ($page - 1) * $limit;
		$total_page = $total_rows/$limit;
		
		break;
		
	case 'update':
	case 'create':
	
		$msg = $_REQUEST['msg'];
		if(!empty($_REQUEST['id'])){
			$id = $_REQUEST['id'];
			$sql = "select * from ".DB_PREFIX."room_facilities WHERE id='".$id."'";	
			$facilitiesList = $dbObj->selectDataObj($sql);
			$facilities = $facilitiesList[0];
			$name = $facilities->name;
			$info = $facilities->info;
		}else{
			$id = '';
			$name = '';
			$info = '';
		}
		
		$action = 'insert';
		break;
		
	case 'save':	
		$id = $_POST['id'];
		$name = $_POST['name'];
		$info = $_POST['info'];
		
		//check repeation of Room Facilities
		if(empty($id)){
			$sql = "select name from ".DB_PREFIX."room_facilities WHERE name = '".$name."' limit 1";
			$facilitiesList = $dbObj->selectDataObj($sql);		
			
			if(!empty($facilitiesList)){
				$msg = $name.ALREADY_EXISTS;
				$url = 'room_facilities.php?action=create&msg='.$msg;
				redirect($url);
			}			
		}else if(!empty($id)){
			$sql = "select name from ".DB_PREFIX."room_facilities WHERE id!='".$id."' AND name = '".$name."' limit 1";
			$facilitiesList = $dbObj->selectDataObj($sql);		
			
			if(!empty($facilitiesList)){
				$msg = $name.ALREADY_EXISTS;
				$url = 'room_facilities.php?action=update&id='.$id.'&msg='.$msg;
				redirect($url);
			}
		}
		
		if(!empty($id)){
			$fields = array('name' => $name,
						'info' => $info,
						'updated_by' => $cur_user_id,
						'updated_datetime' => current_date_time()
						);
						
			$where = "id = '".$id."'";
			
			$update_status = $dbObj->updateTableData("room_facilities", $fields, $where);	
			
			if(!$update_status){
				$msg = $name.COULD_NOT_BE_UPDATED;		
				$action = 'insert';
			}else{
				$msg = $name.HAS_BEEN_UPDATED;
				$url = 'room_facilities.php?action=view&msg='.$msg;
				redirect($url);
			}
		}else{
			$fields = array('name' => $name,
						'info' => $info,
						'created_by' => $cur_user_id,
						'created_datetime' => current_date_time(),
						'updated_by' => $cur_user_id,
						'updated_datetime' => current_date_time()
						);
			
			$inserted = $dbObj->insertTableData("room_facilities", $fields);	
			if(!$inserted){
				$msg = $name.COULD_NOT_BE_CREATED;	
				$action = 'insert';
			}else{
				$msg = $name.CREATED_SUCCESSFULLY;
				$url = 'room_facilities.php?action=view&msg='.$msg;
				redirect($url);
			}
		}
		break;

	case 'delete':	
		$id = $_REQUEST['id'];
		$sql = "select * from ".DB_PREFIX."room_facilities WHERE id='".$id."'";	
		$facilitiesList = $dbObj->selectDataObj($sql);
		$facilities = $facilitiesList[0];
		$name = $facilities->name;
			
		$where = "id='".$id."'";	
		
		$success = $dbObj->deleteTableData("room_facilities", $where);	
		
		if(!$success){
			$msg = $name.COULD_NOT_BE_DELETED;
		}else{
			$msg = $name.DELETED_SUCCESSFULLY;
		}
		
		$url = 'room_facilities.php?action=view&msg='.$msg;
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
				<h1><?php echo ROOM_FACILITIES; ?></h1>
			</td>	
			<td class="usr_info">
				<?php echo welcomeMsg($cur_user_id); ?>
			</td>			
		</tr>
	</table>
	<?php
		if($action=="view"){
	?>
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
				<tr>
					<td>
						<table width="100%" cellpadding="0" cellspacing="0" border="0" class="datagrid">
								<tr class="footer">
									<td colspan="3" style=" background:#EEEEEE;">
										<b><a href="room_facilities.php?action=create"><?php echo CREATE; ?></a></b>
									</td>
								</tr>				
							<tr class="head">
								<td height="30" width="33%">
									<strong><?php echo FACILITIES_NAME; ?></strong>
								</td>
								<td height="30" width="33%">
									<strong><?php echo FACILITIES_INFO; ?></strong>
								</td>
								<td height="30" width="34%">
									<strong><?php echo ACTION; ?></strong>
								</td>
							</tr>
							
							
							<?php			
							if(!empty($facilitiesList)){	
								
								if(($s+$limit) > $total_rows){
									$maxPageLimit = $total_rows;
								}else{
									$maxPageLimit = $s+$limit;
								}		
								
								for($rownum = $s; $rownum <$maxPageLimit; $rownum++){		
									if(($rownum%2)==0){//even
										$class = ' class="even"';									
									}else{//odd
										$class = ' class="odd"';									
									}									
							?>
									<tr <?php echo $class; ?>>
										<td width="20%">
											<?php echo $facilitiesList[$rownum]->name; ?> 
										</td>	
										<td width="30%">
											<?php echo $facilitiesList[$rownum]->info; ?> 
										</td>				
										<td width="50%">								
											<a class="edit" href="room_facilities.php?action=update&id=<?php echo $facilitiesList[$rownum]->id; ?>" title="Edit">&nbsp;</a>
											<a class="delete" href="room_facilities.php?action=delete&id=<?php echo $facilitiesList[$rownum]->id; ?>" onclick="return confirm('Are you sure you want to delete?');" title="Delete">&nbsp;</a>
										</td>
									</tr>
								<?php 
									}//for
								}else{ ?>
								<tr height="30">
									<td colspan="3">
										<?php echo EMPTY_DATA; ?>
									</td>
								</tr>
								<?php 
								}
								if($total_page > 1){ ?>
								<tr height="50">
									<td colspan="3">
										<?php 
										echo pagination($total_rows,$limit,$page,''); ?>
									</td>
								</tr>
								<?php } ?>	
								<tr class="footer">
									<td colspan="3">
										<b><a href="room_facilities.php?action=create"><?php echo CREATE; ?></a></b>
									</td>
								</tr>				
						</table>
					</td>
				</tr>
			</table>
				
	<?php 
		}else if($action=="insert"){
	?>
				
				<form action="room_facilities.php" method="post" name="faclilities" id="faclilities" onsubmit="return validatefacilities();" enctype="multipart/form-data">
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
						<tr>
							<td height="30" width="20%">
								<?php echo ROOM_NAME; ?>:
							</td>
							<td width="80%">
								<input name="name" id="name" type="text" class="inputbox" alt="Room Name" size="36" value="<?php echo $name; ?>" />
								<span class="required_field">*</span>
							</td>
						</tr>
						<tr>
							<td height="30" width="20%">
								<?php echo ROOM_INFO; ?>:
							</td>
							<td width="80%">
								<input name="info" id="info" type="text" class="inputbox" alt="Room Info" size="36" value="<?php echo $info; ?>" />
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
				</form>
			
	<?php }?>
</div>
			
<?php
require_once("includes/footer.php");
?>