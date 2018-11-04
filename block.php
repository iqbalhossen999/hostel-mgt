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
	//echo '<pre>';print_r($_REQUEST);exit;
}

//Heading
$hall_id = $_REQUEST['hall_id'];
$sql = "select *FROM ".DB_PREFIX."hall WHERE id ='".$hall_id."' GROUP BY id " ;
$hallList = $dbObj->selectDataObj($sql);

switch($action){
	case 'view':
	default:

		if(!empty($_REQUEST['hall_id'])){
			$hall_id = $_REQUEST['hall_id'];
			
			$sql = "select b.* from ".DB_PREFIX."block as b, ".DB_PREFIX."hall as h WHERE h.id = b.hall_id AND b.hall_id ='".$hall_id."' ORDER BY b.name asc" ;
			$blockList = $dbObj->selectDataObj($sql);
			
			//Pagination 
			if(!empty($blockList)){
				$total_rows = sizeof($blockList);
			}else{
				$total_rows =0;
			}
			//find start
			$s = ($page - 1) * $limit;
			$total_page = $total_rows/$limit;
			}else{
				$id = '';
				$hall_id = '';
				$name = '';
			}
			$action = 'view';
			break;
	
	case 'update':
	case 'create':
	
		if(!empty($_REQUEST['block_id'])){
			$id = $_REQUEST['block_id'];
			$sql = "select * from ".DB_PREFIX."block WHERE id='".$id."'";	
			$blockList = $dbObj->selectDataObj($sql);
			$block = $blockList[0];
			$name = $block->name;
		}
		
		$hall_id = $_REQUEST['hall_id'];
		
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
		$hall_id = $_POST['hall_id'];
		$name = $_POST['name'];
		$info = $_POST['info'];
			
	 	//Check if block already exists in the db in same Block
		if(empty($id)){
			$sql = "select name from ".DB_PREFIX."block WHERE name = '".$name."' AND hall_id = '".$hall_id."' limit 1";
			$blockList = $dbObj->selectDataObj($sql);		
			
			if(!empty($blockList)){
				$msg = $name.ALREADY_EXISTS;
				$url = 'block.php?action=create&msg='.$msg;
				redirect($url);
			}			
		}else if(!empty($id)){
			$sql = "select name from ".DB_PREFIX."block WHERE id!='".$id."' AND name = '".$name."' AND hall_id = '".$hall_id."'  limit 1";
			$blockList = $dbObj->selectDataObj($sql);		
			
			if(!empty($blockList)){
				$msg = $name.ALREADY_EXISTS;
				$msg = $name.ALREADY_EXISTS;
				$url = 'block.php?action=update&page='.$page.'&id='.$id.'&msg='.$msg;
				redirect($url);
			}
		}
		 
		
		if(!empty($id)){
			$fields = array('hall_id' => $hall_id,
						'name' => $name,
						'updated_by' => $cur_user_id,
						'updated_datetime' => current_date_time()
						
						);
						
			$where = "id = '".$id."'";
			
			$update_status = $dbObj->updateTableData("block", $fields, $where);	
			
			if(!$update_status){
				$msg = $name.COULD_NOT_BE_UPDATED;	
				$action = 'insert';
			}else{
				$msg = $name.HAS_BEEN_UPDATED;
				$url = 'block.php?action=view&hall_id='.$hall_id.'&page='.$page.'&msg='.$msg;
				redirect($url);
			}
		}else{
			$fields = array('hall_id' => $hall_id,
						'name' => $name,
						'created_by' => $cur_user_id,
						'created_datetime' => current_date_time(),
						'updated_by' => $cur_user_id,
						'updated_datetime' => current_date_time()
						
						);
			
			$inserted = $dbObj->insertTableData("block", $fields);	
			if(!$inserted){
				$msg = $name.COULD_NOT_BE_CREATED;
				$action = 'insert';
			}else{
				$msg = $name.CREATED_SUCCESSFULLY;
				$url = 'block.php?action=view&hall_id='.$hall_id.'&msg='.$msg;
				redirect($url);
			}
		}
		break;
		
	case 'delete':	
		$id = $_REQUEST['block_id'];	
		$sql = "select * from ".DB_PREFIX."block WHERE id='".$id."'";	
		$blockList = $dbObj->selectDataObj($sql);
		$block = $blockList[0];
		$name = $block->name;
		$where = "id='".$id."'";	
		
		$success = $dbObj->deleteTableData("block", $where);	
		
		if(!$success){
			$msg = $name.COULD_NOT_BE_DELETED;
		}else{
			$msg = $name.HAS_BEEN_DELETED;
		}
		
		$url = 'block.php?action=view&page='.$page.'&hall_id='.$hall_id.'&msg='.$msg;
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
		}//if
	?>
	<table width="100%" cellspacing="0" cellpadding="0" border="0" class="module_header">
		<tr>
			<td>
			<?php foreach($hallList as $item){?>				
				<div id="view_all"><?php echo '<b>'."Hall &raquo; ".'</b>'.'<a href="block.php?action=view&hall_id='.$_REQUEST['hall_id'].'" title ="View Hall Details">'.$item->name.'</a>';?></div>
			<?php }?>
			</td>	
		</tr>
		
		<tr>
			<td>
				<h4><?php echo BLOCK; ?></h4>
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
									<td colspan="5" style=" background:#EEEEEE;">
										
										<b><a href="block.php?action=create&hall_id=<?php echo $_REQUEST['hall_id']; ?>"><?php echo CREATE; ?></a></b>
										
									</td>
								</tr>				
							<tr class="head">
								<td height="30" width="80%">
									<strong><?php echo NAME; ?></strong>
								</td>
								<td width="20%">
									<strong><?php echo ACTION; ?></strong>
								</td>
							</tr>
							
							
							<?php			
							if(!empty($blockList)){	
								
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
											<a href="floor.php?action=view&block_id=<?php echo $blockList[$rownum]->id;?>&hall_id=<?php echo $blockList[$rownum]->hall_id; ?>" title="View Block Details"><?php echo $blockList[$rownum]->name; ?> </a>
										</td>	
										<td>								
											<a class="edit" href="block.php?action=update&block_id=<?php echo $blockList[$rownum]->id; ?>&hall_id=<?php echo $_REQUEST['hall_id']; ?>&page=<?php echo $page; ?>" title="Edit">&nbsp;</a>
											<a class="delete" href="block.php?action=delete&block_id=<?php echo $blockList[$rownum]->id; ?>&hall_id=<?php echo $_REQUEST['hall_id']; ?>" onclick="return confirm('Are you sure you want to delete?');" title="Delete">&nbsp;</a>
										</td>
									</tr>
								<?php 
									}//for
								}else{ ?>
								<tr height="30">
									<td colspan="5">
										<?php echo EMPTY_DATA; ?>
									</td>
								</tr>
								<?php 
								}
								if($total_page > 1){ ?>
								<tr height="50">
									<td colspan="5">
										<?php echo pagination($total_rows,$limit,$page,'&hall_id='.$hall_id); ?>
									</td>
								</tr>
								<?php } ?>	
								<tr class="footer">
									<td colspan="6">
										<b><a href="block.php?action=create&hall_id=<?php echo $_REQUEST['hall_id']; ?>"><?php echo CREATE; ?></a></b>
									</td>
								</tr>				
						</table>
					</td>
				</tr>
			</table>
		
	<?php 
		}elseif($action=="insert"){ 
	?>
				<form action="block.php" method="post" name="block" id="block" onsubmit="return validateblock();">
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
						<tr>
							<td height="30" width="20%">
								<?php echo HALL; ?>:
							</td>
							<td width="80%">
								<?php echo $hallList_opt; ?>
										
								<span class="required_field">*</span>
							</td>
						</tr>
						<tr>
							<td height="30" width="20%">
								<?php echo BLOCK; ?>:
							</td>
							<td width="80%">
								<input name="name" id="name" type="text" class="inputbox" alt="Block Name" size="36" value="<?php echo $name; ?>" />
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
						<!--<input type="hidden" name="hall_id" value="<?php //echo $hall_id; ?>" />-->
					<input type="hidden" name="action" value="save" />
					<input type="hidden" name="page" id="page" value="<?php echo $page; ?>" />
				</form>
	
	<?php }//else if?>
			
</div>
			
<?php
require_once("includes/footer.php");
?>