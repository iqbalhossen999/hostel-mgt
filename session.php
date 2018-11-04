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
		
		$sql = "select * from ".DB_PREFIX."session order by name asc";
		$targetList = $dbObj->selectDataObj($sql);
		$action = 'view';
		
		//Pagination 
		if(!empty($targetList)){
			$total_rows = sizeof($targetList);
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
			$sql = "select * from ".DB_PREFIX."session WHERE id='".$id."'";	
			$targetList = $dbObj->selectDataObj($sql);
			
			$target = $targetList[0];
			$name = $target->name;
			$session_year = $target->session_year;
			$status = $target->status;
		}else{
			$name = '';
			$status = '';
			$session_year = '';
		}
		
		
		//Build Status Array
		$statusArr = array(
					'0' => INACTIVE,
					'1' => ACTIVE
					);
		
		$statusId = array();
		if(!empty($statusArr)){			
			foreach($statusArr as $key=>$val){
				$statusId[$key] = $val;
			}	
		}			
		$status_opt = formSelectElement($statusId, $status, 'status');
		
		
		//Build Status Array
		$session_yearArr = array(
					'2011' => '2011',
					'2012' => '2012',
					'2013' => '2013',
					'2014' => '2014',
					'2015' => '2015',
					'2016' => '2016',
					'2017' => '2017',
					'2018' => '2018',
					'2019' => '2019',
					'2020' => '2020'
					);
		
		$session_yearId = array();
		if(!empty($session_yearArr)){			
			foreach($session_yearArr as $key=>$val){
				$session_yearId[$key] = $val;
			}	
		}			
		$session_year_opt = formSelectElement($session_yearId, $session_year, 'session_year');
		
		$action = 'insert';
		break;
		
	case 'save':
	
		$id = $_POST['id'];
		$name = $_POST['name'];
		$status = $_POST['status'];
		$session_year = $_POST['session_year'];
		//check repeation of Session Name
		if(empty($id)){
			$sql = "select name from ".DB_PREFIX."session WHERE name = '".$name."' AND session_year = '".$session_year."' limit 1";
			$sessionList = $dbObj->selectDataObj($sql);		
			
			if(!empty($sessionList)){
				$msg = $name.ALREADY_EXISTS;
				$url = 'session.php?action=create&msg='.$msg;
				redirect($url);
			}			
		}else if(!empty($id)){
			$sql = "select name from ".DB_PREFIX."session WHERE id!='".$id."' AND name = '".$name."'  AND session_year  ='".$session_year."' limit 1";
			$sessionList = $dbObj->selectDataObj($sql);		
			
			if(!empty($sessionList)){
				$msg = $name.ALREADY_EXISTS;
				$url = 'session.php?action=update&id='.$id.'&msg='.$msg;
				redirect($url);
			}
		}
		
		if(!empty($id)){
			$fields = array('name' => $name,
						'status' => $status,
						'session_year' => $session_year,
						'updated_by' => $cur_user_id,
						'updated_datetime' => current_date_time()
						);
						
			$where = "id = '".$id."'";
			$update_status = $dbObj->updateTableData("session", $fields, $where);	
			
			if(!$update_status){
				$msg = $name.COULD_NOT_BE_UPDATED;		
				$action = 'insert';
			}else{
				$msg = $name.HAS_BEEN_UPDATED;
				$url = 'session.php?action=view&page='.$page.'&msg='.$msg;
				redirect($url);
			}
		}else{
			$fields = array('name' => $name,
 						'status' => $status,
						'session_year' => $session_year,
						'created_by' => $cur_user_id,
						'created_datetime' => current_date_time(),
						'updated_by' => $cur_user_id,
						'updated_datetime' => current_date_time()
						);
			
			$inserted = $dbObj->insertTableData("session", $fields);	
			if(!$inserted){
				$msg = $name.COULD_NOT_BE_CREATED;	
				$action = 'insert';
			}else{
				$msg = $name.CREATED_SUCCESSFULLY;
				$url = 'session.php?action=view&msg='.$msg;
				redirect($url);
			}
		}
		break;

	case 'delete':	
		$id = $_REQUEST['id'];
		$sql = "select * from ".DB_PREFIX."session WHERE id='".$id."'";	
		$sessionList = $dbObj->selectDataObj($sql);
		$session = $sessionList[0];
		$name = $session->name;
		$session_year = $session->session_year;
			
		$where = "id='".$id."'";	
		
		$success = $dbObj->deleteTableData("session", $where);	
		
		if(!$success){
			$msg = $name.COULD_NOT_BE_DELETED;
		}else{
			$msg = $name.DELETED_SUCCESSFULLY;
		}
		
		$url = 'session.php?action=view&page='.$page.'&msg='.$msg;
		redirect($url);
		break;

}//switch


require_once("includes/templates.php");
require_once("templates/top_menu.php");
require_once("templates/left_menu.php");
?>

<div id="right_column">
	<?php if(!empty($msg)){ ?>
		<table id="system_message">
			<tr>
				<td><?php echo $msg; ?></td>
			</tr>
		</table>
	<?php }	?>
	<table width="100%" cellspacing="0" cellpadding="0" border="0" class="module_header">
		<tr>
			<td>
				<h1><?php echo SESSION_MANAGEMENT; ?></h1>
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
									<td colspan="4" style=" background:#EEEEEE;">
										<b><a href="session.php?action=create"><?php echo CREATE; ?></a></b>
									</td>
								</tr>				
							<tr class="head">
								<td height="30" width="40%"><strong><?php echo NAME; ?></strong></td>
								<td width="20%"><strong><?php echo SESSION_YEAR; ?></strong></td>
								<td width="20%"><strong><?php echo STATUS; ?></strong></td>
								<td width="20%"><strong><?php echo ACTION; ?></strong></td>
							</tr>
							
							
							<?php			
							if(!empty($targetList)){	
								$maxPageLimit = (($s+$limit) > $total_rows) ? $total_rows : ($s+$limit);
								for($rownum = $s; $rownum <$maxPageLimit; $rownum++){		
									$class = (($rownum%2)==0) ? ' class="even"' : ' class="odd"';
									$targetStatus = ($targetList[$rownum]->status == '1') ? ACTIVE : INACTIVE; 
							?>
									<tr <?php echo $class; ?>>
										<td height="30"><?php echo $targetList[$rownum]->name; ?></td>	
										<td><?php echo $targetList[$rownum]->session_year; ?></td>
										<td><?php echo $targetStatus; ?></td>
										
										<td>								
											<a class="edit" href="session.php?action=update&page=<?php echo $page; ?>&id=<?php echo $targetList[$rownum]->id; ?>" title="Edit">&nbsp;</a>
											<a class="delete" href="session.php?action=delete&id=<?php echo $targetList[$rownum]->id; ?>" onclick="return confirm('Are you sure you want to delete?');" title="Delete">&nbsp;</a>
										</td>
									</tr>
								<?php 
									}//for
								}else{ ?>
								<tr height="30">
									<td colspan="4"><?php echo EMPTY_DATA; ?></td>
								</tr>
								<?php 
								}
								if($total_page > 1){ ?>
								<tr height="50">
									<td colspan="4"><?php echo pagination($total_rows,$limit,$page,''); ?></td>
								</tr>
								<?php } ?>	
								<tr class="footer">
									<td colspan="4"><b><a href="session.php?action=create"><?php echo CREATE; ?></a></b></td>
								</tr>				
						</table>
					</td>
				</tr>
			</table>
				
	<?php }else if($action=="insert"){	?>
				
				
				<form action="session.php" method="post" name="session" id="session" onsubmit="return validatesession();" enctype="multipart/form-data">
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
						<tr>
							<td height="20" width="20%">
								<?php echo NAME; ?>:
							</td>
							<td width="80%">
								<input name="name" id="name" type="text" class="inputbox" alt="name" size="36" value="<?php echo $name; ?>" />
								<span class="required_field">*</span>
							</td>
						</tr>
						<tr>
							<td height="20" width="20%">
								<?php echo SESSION_YEAR; ?>:
							</td>
							<td><?php echo $session_year_opt; ?></td>
						</tr>
						<tr>
							<td height="30"><?php echo STATUS; ?>:</td>
							<td><?php echo $status_opt; ?></td>
						</tr>
						<tr>
							<td colspan="2" height="50">
								<input type="submit" name="Submit" class="button" value="Save" />
								<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><input type="button" onclick="window.location='<?php echo $_SERVER['HTTP_REFERER']; ?>'"  name="cancel" class="cancel" value="<?php echo CANCEL; ?>" /></a>
							</td>
						</tr>		
					</table>	
					<input type="hidden" name="id" value="<?php echo $id; ?>" />
					<input type="hidden" name="page" id="page" value="<?php echo $page; ?>" />
					<input type="hidden" name="action" value="save" />
				</form>
			
	<?php }?>
</div>
			
<?php
require_once("includes/footer.php");
?>