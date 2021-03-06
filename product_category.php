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
		
		$sql = "select * from ".DB_PREFIX."product_category order by name asc";
		$product_categoryList = $dbObj->selectDataObj($sql);
		$action = 'view';
		$msg = $_REQUEST['msg'];
		
		//Pagination 
		if(!empty($product_categoryList)){
			$total_rows = sizeof($product_categoryList);
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
			$sql = "select * from ".DB_PREFIX."product_category WHERE id='".$id."'";	
			$product_categoryList = $dbObj->selectDataObj($sql);
			$product_category = $product_categoryList[0];
			$unit_id = $product_category->unit_id;
			$name = $product_category->name;
		}else{
			$id = '';
			$unit_id = '';
			$name = '';
		}//else
		
		//Build Unit Array
		$sql = "select id, name from ".DB_PREFIX."unit order by name asc";
		$unitArr = $dbObj->selectDataObj($sql);
		
		$unitId = array();
		$unitId[0] = SELECT_UNIT_OPT;
		if(!empty($unitArr)){			
			foreach($unitArr as $item){
				$unitId[$item->id] = $item->name;
			}//foreach
		}//if
		$unitList_opt = formSelectElement($unitId, $unit_id, 'unit_id');
		
		$action = 'insert';
		break;
		
	case 'save':	
		$id = $_POST['id'];
		$unit_id = $_POST['unit_id'];
		$name = $_POST['name'];
		
	 	//Check if product_category already exists in the db in same product_category
		if(empty($id)){
			$sql = "select name from ".DB_PREFIX."product_category WHERE name = '".$name."' AND unit_id = '".$unit_id."' limit 1";
			$product_categoryList = $dbObj->selectDataObj($sql);		
			
			if(!empty($product_categoryList)){
				$msg = $name.ALREADY_EXISTS;
				$url = 'product_category.php?action=create&msg='.$msg;
				redirect($url);
			}			
		}else if(!empty($id)){
			$sql = "select name from ".DB_PREFIX."product_category WHERE id!='".$id."' AND name = '".$name."' AND unit_id = '".$unit_id."'  limit 1";
			$product_categoryList = $dbObj->selectDataObj($sql);		
			
			if(!empty($product_categoryList)){
				$msg = $name.ALREADY_EXISTS;
				$msg = $name.ALREADY_EXISTS;
				$url = 'product_category.php?action=update&page='.$page.'&id='.$id.'&msg='.$msg;
				redirect($url);
			}//if
		}//else if
		 
		
		if(!empty($id)){
			$fields = array('unit_id' => $unit_id,
						'name' => $name,
						'updated_by' => $cur_user_id,
						'updated_datetime' => current_date_time()
						);
						
			$where = "id = '".$id."'";
			$update_status = $dbObj->updateTableData("product_category", $fields, $where);	
			
			if(!$update_status){
				$msg = $name.COULD_NOT_BE_UPDATED;	
				$action = 'insert';
			}else{
				$msg = $name.HAS_BEEN_UPDATED;
				$url = 'product_category.php?action=view&page='.$page.'&unit_id='.$unit_id.'&msg='.$msg;
				redirect($url);
			}
		}else{
			$fields = array('unit_id' => $unit_id,
						'name' => $name,
						'created_by' => $cur_user_id,
						'created_datetime' => current_date_time(),
						'updated_by' => $cur_user_id,
						'updated_datetime' => current_date_time()
						
						);
			
			$inserted = $dbObj->insertTableData("product_category", $fields);	
			if(!$inserted){
				$msg = $name.COULD_NOT_BE_CREATED;
				$action = 'insert';
			}else{
				$msg = $name.CREATED_SUCCESSFULLY;
				$url = 'product_category.php?action=view&unit_id='.$unit_id.'&msg='.$msg;
				redirect($url);
			}
		}
		break;

	case 'delete':	
		$id = $_REQUEST['id'];	
		$sql = "select * from ".DB_PREFIX."product_category WHERE id='".$id."'";	
		$product_categoryList = $dbObj->selectDataObj($sql);
		$product_category = $product_categoryList[0];
		$name = $product_category->name;
		$where = "id='".$id."'";	
		
		$success = $dbObj->deleteTableData("product_category", $where);	
		
		if(!$success){
			$msg = $name.COULD_NOT_BE_DELETED;
		}else{
			$msg = $name.HAS_BEEN_DELETED;
		}
		
		$url = 'product_category.php?action=view&page='.$page.'&unit_id='.$unit_id.'&msg='.$msg;
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
				<h1><?php echo PRODUCT_CATEGORY; ?></h1>
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
										<b><a href="product_category.php?action=create"><?php echo CREATE; ?></a></b>
									</td>
								</tr>				
							<tr class="head">
								<td height="30" width="35%"><strong><?php echo CATEGORY; ?></strong></td>
								<td width="35%"><strong><?php echo UNIT; ?></strong></td>
								<td width="30%"><strong><?php echo ACTION; ?></strong></td>
							</tr>
							<?php			
							if(!empty($product_categoryList)){	
								$maxPageLimit = (($s+$limit) > $total_rows) ? $total_rows : ($s+$limit);
								
								for($rownum = $s; $rownum <$maxPageLimit; $rownum++){		
									$class = (($rownum%2)==0) ? ' class="even"' : ' class="odd"';
									$product_category = getNameById('unit', $product_categoryList[$rownum]->unit_id);
							?>
									<tr <?php echo $class; ?>>
										<td height="30"><?php echo $product_categoryList[$rownum]->name; ?></td>
										<td><?php echo $product_category->name; ?></td>
										<td>								
											<a class="edit" href="product_category.php?action=update&page=<?php echo $page; ?>&id=<?php echo $product_categoryList[$rownum]->id; ?>" title="Edit">&nbsp;</a>
											<a class="delete" href="product_category.php?action=delete&id=<?php echo $product_categoryList[$rownum]->id; ?>" onclick="return confirm('Are you sure you want to delete?');" title="Delete">&nbsp;</a>
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
									<td colspan="4">
										<?php 
										echo pagination($total_rows,$limit,$page,''); ?>
									</td>
								</tr>
								<?php } ?>	
								<tr class="footer">
									<td colspan="4"><b><a href="product_category.php?action=create"><?php echo CREATE; ?></a></b></td>
								</tr>				
						</table>
					</td>
				</tr>
			</table>
				
	<?php 
		}elseif($action=="insert"){ 
	?>
				<form action="product_category.php" method="post" name="category" id="category" onsubmit="return validateproduct_category();">
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
						<tr>
							<td height="30" width="20%">
								<?php echo CATEGORY_NAME; ?>:
							</td>
							<td width="80%">
								<input name="name" id="name" type="text" class="inputbox" alt="product_category Name" size="36" value="<?php echo $name; ?>" />
								<span class="required_field">*</span>
							</td>
						</tr>
						<tr>
							<td height="30">
								<?php echo UNIT_NAME; ?>:
							</td>
							<td>
								<?php echo $unitList_opt; ?>
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