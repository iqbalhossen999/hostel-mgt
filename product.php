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

if($cur_user_group_id != '1'){
	dashboard();
}//if

//Pagination
$limit = PAGE_LIMIT_DEFAULT;
$page = (empty($_REQUEST['page'])) ? 1 : $_REQUEST['page'];
$msg = $_REQUEST['msg'];

switch($action){
	case 'view':	
	default:
		
		$sql = "select * from ".DB_PREFIX."product order by name asc";
		$productList = $dbObj->selectDataObj($sql);
		$action = 'view';
		
		//Pagination 
		$total_rows = (!empty($productList)) ? sizeof($productList) : 0;
		$s = ($page - 1) * $limit;
		$total_page = $total_rows/$limit;
		
		break;
		
	case 'update':
	case 'create':
	
		if(!empty($_REQUEST['id'])){
			$id = $_REQUEST['id'];
			$sql = "select * from ".DB_PREFIX."product WHERE id='".$id."'";	
			$productList = $dbObj->selectDataObj($sql);
			$product = $productList[0];
			$category_id = $product->category_id;
			$name = $product->name;
		}else{
			$id = '';
			$category_id = '';
			$name = '';
		}//else
		
		//Build Category Array
		$sql = "select id, name from ".DB_PREFIX."product_category order by name asc";
		$categoryArr = $dbObj->selectDataObj($sql);
		$categoryId = array();
		$categoryId[0] = SELECT_CATEGORY_OPT;
		if(!empty($categoryArr)){			
			foreach($categoryArr as $item){
				$categoryId[$item->id] = $item->name;
			}//foreach
		}//if
		$categoryList_opt = formSelectElement($categoryId, $category_id, 'category_id');
		
		$action = 'insert';
		break;
		
	case 'save':	
		$id = $_POST['id'];
		$category_id = $_POST['category_id'];
		$name = $_POST['name'];
	
	 	//Check if product already exists in the db in same product
		if(empty($id)){
			$sql = "select name from ".DB_PREFIX."product WHERE name = '".$name."' AND category_id = '".$category_id."' limit 1";
			$productList = $dbObj->selectDataObj($sql);		
			
			if(!empty($productList)){
				$msg = $name.ALREADY_EXISTS;
				$url = 'product.php?action=create&msg='.$msg;
				redirect($url);
			}			
		}else if(!empty($id)){
			$sql = "select name from ".DB_PREFIX."product WHERE id!='".$id."' AND name = '".$name."' AND category_id = '".$category_id."'  limit 1";
			$productList = $dbObj->selectDataObj($sql);		
			
			if(!empty($productList)){
				$msg = $name.ALREADY_EXISTS;
				$url = 'product.php?action=update&page='.$page.'&id='.$id.'&msg='.$msg;
				redirect($url);
			}
		}
		 
		
		if(!empty($id)){
			$fields = array('category_id' => $category_id,
						'name' => $name,
						'updated_by' => $cur_user_id,
						'updated_datetime' => current_date_time()
						);
						
			$where = "id = '".$id."'";
			$update_status = $dbObj->updateTableData("product", $fields, $where);	
			
			if(!$update_status){
				$msg = $name.COULD_NOT_BE_UPDATED;	
				$action = 'insert';
			}else{
				$msg = $name.HAS_BEEN_UPDATED;
				$url = 'product.php?action=view&page='.$page.'&category_id='.$category_id.'&msg='.$msg;
				redirect($url);
			}
		}else{
			$fields = array('category_id' => $category_id,
						'name' => $name,
						'created_by' => $cur_user_id,
						'created_datetime' => current_date_time(),
						'updated_by' => $cur_user_id,
						'updated_datetime' => current_date_time()
						
						);
			
			$inserted = $dbObj->insertTableData("product", $fields);	
			if(!$inserted){
				$msg = $name.COULD_NOT_BE_CREATED;
				$action = 'insert';
			}else{
				$msg = $name.CREATED_SUCCESSFULLY;
				$url = 'product.php?action=view&category_id='.$category_id.'&msg='.$msg;
				redirect($url);
			}
		}
		break;

	case 'delete':	
		$id = $_REQUEST['id'];	
		$sql = "select * from ".DB_PREFIX."product WHERE id='".$id."'";	
		$productList = $dbObj->selectDataObj($sql);
		$product = $productList[0];
		$name = $product->name;
		$where = "id='".$id."'";	
		
		$success = $dbObj->deleteTableData("product", $where);	
		
		$msg = (!$success) ? $name.COULD_NOT_BE_DELETED : $name.HAS_BEEN_DELETED;
		$url = 'product.php?action=view&category_id='.$category_id.'&page='.$page.'&msg='.$msg;
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
				<h1><?php echo PRODUCT; ?></h1>
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
								<td colspan="4" style=" background:#EEEEEE;">
									<b><a href="product.php?action=create"><?php echo CREATE; ?></a></b>
								</td>
							</tr>				
							<tr class="head">
								<td height="30" width="35%"><strong><?php echo CATEGORY; ?></strong></td>
								<td width="35%"><strong><?php echo PRODUCT; ?></strong></td>
								<td width="30%"><strong><?php echo ACTION; ?></strong></td>
							</tr>
							<?php			
							if(!empty($productList)){	
								$maxPageLimit = (($s+$limit) > $total_rows) ? $total_rows : ($s+$limit);
								for($rownum = $s; $rownum <$maxPageLimit; $rownum++){		
									$class = (($rownum%2)==0) ? ' class="even"' : ' class="odd"';
									$product = getNameById('product_category', $productList[$rownum]->category_id);
							?>
									<tr <?php echo $class; ?>>
										<td height="30"><?php echo $product->name; ?></td>
										<td><?php echo $productList[$rownum]->name; ?></td>
										<td>								
											<a class="edit" href="product.php?action=update&page=<?php echo $page; ?>&id=<?php echo $productList[$rownum]->id; ?>" title="Edit">&nbsp;</a>
											<a class="delete" href="product.php?action=delete&id=<?php echo $productList[$rownum]->id; ?>" onclick="return confirm('Are you sure you want to delete?');" title="Delete">&nbsp;</a>
										</td>
									</tr>
								<?php 
									}//for
								}else{ ?>
								<tr height="30">
									<td colspan="4">
										<?php echo EMPTY_DATA; ?>
									</td>
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
									<td colspan="4">
										<b><a href="product.php?action=create"><?php echo CREATE; ?></a></b>
									</td>
								</tr>				
						</table>
					</td>
				</tr>
			</table>
				
	<?php }elseif($action=="insert"){ ?>
	
				<form action="product.php" method="post" name="product" id="product" onsubmit="return validateproduct();">
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
						<tr>
							<td height="30" width="20%">
								<?php echo CATEGORY_NAME; ?>:
							</td>
							<td width="80%">
								<?php echo $categoryList_opt; ?>
							</td>
						</tr>
						<tr>
							<td height="30">
								<?php echo PRODUCT_NAME; ?>:
							</td>
							<td>
								<input name="name" id="name" type="text" class="inputbox" alt="Product Name" size="36" value="<?php echo $name; ?>" />
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