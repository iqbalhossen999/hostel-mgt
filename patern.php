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
		
		$sql = "select * from ".DB_PREFIX."patern order by name asc";
		$paternList = $dbObj->selectDataObj($sql);
		$action = 'view';
		$path_view = 'attach_file/';
		$msg = $_REQUEST['msg'];
		
		//Pagination 
		if(!empty($paternList)){
			$total_rows = sizeof($paternList);
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
			$sql = "select * from ".DB_PREFIX."patern WHERE id='".$id."'";	
			$paternList = $dbObj->selectDataObj($sql);
			$patern = $paternList[0];
			$name = $patern->name;
			$number_seat = $patern->number_seat;
		}else{
			$id = '';
			$name = '';
			$number_seat = '';
		}
		
		$action = 'insert';
		break;
		
	case 'save':	
		$id = $_POST['id'];
		$name = $_POST['name'];
		$number_seat = $_POST['number_seat'];
		
		//check repeation of Room Facilities
		if(empty($id)){
			$sql = "select name from ".DB_PREFIX."patern WHERE name = '".$name."' limit 1";
			$paternList = $dbObj->selectDataObj($sql);		
			
			if(!empty($paternList)){
				$msg = $name.ALREADY_EXISTS;
				$url = 'patern.php?action=create&msg='.$msg;
				redirect($url);
			}			
		}else if(!empty($id)){
			$sql = "select name from ".DB_PREFIX."patern WHERE id!='".$id."' AND name = '".$name."' limit 1";
			$paternList = $dbObj->selectDataObj($sql);		
			
			if(!empty($paternList)){
				$msg = $name.ALREADY_EXISTS;
				$url = 'patern.php?action=update&id='.$id.'&msg='.$msg;
				redirect($url);
			}
		}
		
		if(!empty($id)){
			$fields = array('name' => $name,
						'number_seat' => $number_seat,
						'updated_by' => $cur_user_id,
						'updated_datetime' => current_date_time()
						);
						
			$where = "id = '".$id."'";
			
			$update_status = $dbObj->updateTableData("patern", $fields, $where);	
			
			if(!$update_status){
				$msg = $name.COULD_NOT_BE_UPDATED;		
				$action = 'insert';
			}else{
				$msg = $name.HAS_BEEN_UPDATED;
				$url = 'patern.php?action=view&page='.$page.'&msg='.$msg;
				redirect($url);
			}
		}else{
			$fields = array('name' => $name,
						'number_seat' => $number_seat,
						'created_by' => $cur_user_id,
						'created_datetime' => current_date_time(),
						'updated_by' => $cur_user_id,
						'updated_datetime' => current_date_time()
						);
			
			$inserted = $dbObj->insertTableData("patern", $fields);	
			if(!$inserted){
				$msg = $name.COULD_NOT_BE_CREATED;	
				$action = 'insert';
			}else{
				$msg = $name.CREATED_SUCCESSFULLY;
				$url = 'patern.php?action=view&msg='.$msg;
				redirect($url);
			}
		}
		break;

	case 'delete':
	
		$id = $_REQUEST['id'];
		$sql = "select * from ".DB_PREFIX."patern WHERE id='".$id."'";	
		$paternList = $dbObj->selectDataObj($sql);
		$patern = $paternList[0];
		$name = $patern->name;
			
		$where = "id='".$id."'";	
		
		$success = $dbObj->deleteTableData("patern", $where);	
		
		if(!$success){
			$msg = $name.COULD_NOT_BE_DELETED;
		}else{
			$msg = $name.DELETED_SUCCESSFULLY;
		}
		
		$url = 'patern.php?action=view&page='.$page.'&msg='.$msg;
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
				<h1><?php echo ROOM_CATEGORY; ?></h1>
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
										<b><a href="patern.php?action=create"><?php echo CREATE; ?></a></b>
									</td>
								</tr>				
							<tr class="head">
								<td height="30" width="20%">
									<strong><?php echo CATEGORY; ?></strong>
								</td>
								<td height="30" width="50%">
									<strong><?php echo NUMBER_SEAT; ?></strong>
								</td>
								<td height="30" width="50%">
									<strong><?php echo ACTION; ?></strong>
								</td>
							</tr>
							
							
							<?php			
							if(!empty($paternList)){	
								
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
										<td height="30">
											<?php echo $paternList[$rownum]->name; ?> 
										</td>	
										<td>
											<?php echo $paternList[$rownum]->number_seat; ?> 
										</td>				
										<td>								
											<a class="edit" href="patern.php?action=update&id=<?php echo $paternList[$rownum]->id; ?>&page=<?php echo $page;?>" title="Edit">&nbsp;</a>
											<a class="delete" href="patern.php?action=delete&id=<?php echo $paternList[$rownum]->id; ?>" onclick="return confirm('Are you sure you want to delete?');" title="Delete">&nbsp;</a>
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
										<b><a href="patern.php?action=create"><?php echo CREATE; ?></a></b>
									</td>
								</tr>				
						</table>
					</td>
				</tr>
			</table>
				
	<?php 
		}else if($action=="insert"){
	?>
				
				<form action="patern.php" method="post" name="patern" id="patern" onsubmit="return validatepatern();" enctype="multipart/form-data">
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
						<tr>
							<td height="30" width="20%">
								<?php echo PATERN_NAME; ?>:
							</td>
							<td width="80%">
								<input name="name" id="name" type="text" class="inputbox" alt="Patern Name" size="36" value="<?php echo $name; ?>" />
								<span class="required_field">*</span>
							</td>
						</tr>
						<tr>
							<td height="30" width="20%">
								<?php echo NUMBER_SEAT; ?>:
							</td>
							<td width="80%">
								<input name="number_seat" id="number_seat" type="text" class="inputbox" alt="Seat Number" size="36" value="<?php echo $number_seat; ?>" onkeyup="isNUM('number_seat')" />
								<span class="required_field">*</span>
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
			
	<?php }?>
</div>
			
<?php
require_once("includes/footer.php");
?>