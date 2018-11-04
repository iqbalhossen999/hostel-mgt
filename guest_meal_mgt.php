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
			
		$sql = "select * from ".DB_PREFIX."guest_meal";
		$guestMeal = $dbObj->selectDataObj($sql);
		
		$action = 'view';
		break;
		
		
	case 'user_block':
		$id = $_REQUEST['id'];
		
		$sql = "select * from ".DB_PREFIX."guest_meal WHERE id='".$id."'";	
		$guestMeal = $dbObj->selectDataObj($sql);
		$status = $guestMeal[0]->status;
		if($status == '1'){
			$fields = array('status' => '0');
		}else if($status == '0'){
			$fields = array('status' => '1');
		}
		
		
		$where = "id='".$id."'";
		$success = $dbObj->updateTableData("guest_meal", $fields, $where);
		
		if(!$success){
			$msg =' Could not be Status Change';
		}else{
			$msg = ' Status has been Change Successfully';
		}
		
		$url = 'guest_meal_mgt.php?action=view&page='.$page.'&limit='.$limit.'&msg='.$msg;
		redirect($url);
	
		$action = 'user_block';
		break;

}//switch

require_once("includes/templates.php");
require_once("templates/top_menu.php");
require_once("templates/left_menu.php");
?>

<div id="right_column">
	<table width="100%" cellspacing="0" cellpadding="0" border="0" class="module_header">
		<tr>
			<td>
				<h1><?php echo GUEST_MEAL_MANAGEMENT; ?></h1>
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
				<td colspan="2">
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="datagrid">				
						<tr class="head">
							
							<td width="10%">
								<strong><?php echo STATUS; ?></strong>
							</td>
							<td width="20%">
								<strong><?php echo ACTION; ?></strong>
							</td>
						</tr>
						<tr>
							<td height="30">
								<?php 
									if($guestMeal[0]->status == '1'){
										echo ACTIVE;
									}else{
										echo INACTIVE;
									}												
								?> 
							</td>
							<td height="30">
								<?php if($guestMeal[0]->status == '1'){?>
										<a class="active" href="guest_meal_mgt.php?action=user_block&page=<?php echo $page; ?>&id=<?php echo $guestMeal[0]->id; ?>&limit=<?php echo $limit; ?>" onclick="return confirm('Are you sure you want to Inactived?');" title="Inactived"></a>
								<?php }else if($guestMeal[0]->status == '0'){?>
										<a class="inctive" href="guest_meal_mgt.php?action=user_block&page=<?php echo $page; ?>&id=<?php echo $guestMeal[0]->id; ?>&limit=<?php echo $limit; ?>" title="Actived" onclick="return confirm('Are you sure you want to Actived?');"></a>
								<?php }?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	
	<?php 
		}//if view
	?>	
</div>
			
<?php
require_once("includes/footer.php");
?>