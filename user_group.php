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

switch($action){
	case 'view':	
	default:
		
		$sql = "select * from ".DB_PREFIX."user_group order by id asc";
		$groupList = $dbObj->selectDataObj($sql);
		$action = 'view';
		break;
		
	case 'update':
	
		if(!empty($_REQUEST['id'])){
			$id = $_REQUEST['id'];
			$sql = "select * from ".DB_PREFIX."user_group WHERE id='".$id."'";	
			$groupList = $dbObj->selectDataObj($sql);
			$group = $groupList[0];
			$name = $group->name;
			$info = $group->info;
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
		
		//Check for not inserting any blank entry
		if($name == ""){
			$msg = PARAM_MISSING;
			if(empty($id)){
				$url = 'user_group.php?action=create&msg='.$msg;
			}else{
				$url = 'user_group.php?action=update&id='.$id.'&msg='.$msg;
			}
			redirect($url);
		}
		
		//check repeation of User Group
		$sql = "select name from ".DB_PREFIX."user_group WHERE id!='".$id."' AND name = '".$name."' limit 1";
		$userGroupList = $dbObj->selectDataObj($sql);		
		
		if(!empty($userGroupList)){
			$msg = $name.ALREADY_EXISTS;
			$url = 'user_group.php?action=update&id='.$id.'&msg='.$msg;
			redirect($url);
		}
		
		$fields = array('name' => $name,
					'info' => $info,
					);
					
		$where = "id = '".$id."'";
		$update_status = $dbObj->updateTableData("user_group", $fields, $where);	
		
		if(!$update_status){
			$msg = $name.COULD_NOT_BE_UPDATED;		
			$action = 'insert';
		}else{
			$msg = $name.HAS_BEEN_UPDATED;
			$url = 'user_group.php?action=view&msg='.$msg;
			redirect($url);
		}
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
				<h1><?php echo USER_GROUP_MANAGEMENT; ?></h1>
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
							<tr class="head">
								<td height="30" width="20%">
									<strong><?php echo GROUP_NAME; ?></strong>
								</td>
								<td height="30" width="50%">
									<strong><?php echo GROUP_INFO; ?></strong>
								</td>
								<td height="30" width="20%">
									<strong><?php echo ACTION; ?></strong>
								</td>
							</tr>
							<?php			
								$rownum = 0;							
								foreach($groupList as $group){		
									if(($rownum%2)==0){//even
										$class = ' class="even"';									
									}else{//odd
										$class = ' class="odd"';									
									}									
							?>
									<tr <?php echo $class; ?>>
										<td width="20%">
											<?php echo $groupList[$rownum]->name; ?> 
										</td>					
										<td width="50%">
											<?php echo $groupList[$rownum]->info; ?> 
										</td>
										<td width="20%">								
											<a class="edit" href="user_group.php?action=update&id=<?php echo $groupList[$rownum]->id; ?>" title="Edit">&nbsp;</a>
										</td>
									</tr>
								<?php 
										$rownum++;
									}//foreach 
								?>	
						</table>
					</td>
				</tr>
			</table>
				
	<?php 
		}elseif($action=="insert"){ 
	?>
				<form action="user_group.php" method="post" name="user_group" id="user_group" onsubmit="return validateUserGroup();">
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
						<tr>
							<td height="30" width="20%">
								<?php echo GROUP_NAME; ?>:
							</td>
							<td width="80%">
								<input name="name" id="name" type="text" class="inputbox" alt="Group Name" size="36" value="<?php echo $name; ?>" />
								<span class="required_field">*</span>
							</td>
						</tr>
						<tr>
							<td height="30" width="20%">
								<?php echo GROUP_INFO; ?>:
							</td>
							<td width="80%">
								<input name="info" id="info" type="text" class="inputbox" alt="Group Info" size="36" value="<?php echo $info; ?>" />
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