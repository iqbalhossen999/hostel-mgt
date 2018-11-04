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


//Heading
	$block_id = $_REQUEST['block_id'];
	$hall_id = $_REQUEST['hall_id'];
	$floor_id = $_REQUEST['floor_id'];

	$sql = "SELECT
					 b.name as block_name,
					 h.name as hall_name,
					 f.name as floor_name
			 FROM ".DB_PREFIX."block b, 
				  ".DB_PREFIX."hall h,
				  ".DB_PREFIX."floor f  
			 WHERE h.id = f.hall_id
			 AND b.id = f.block_id
			 AND f.id ='".$floor_id."'
			 GROUP BY f.id" ;
	$floorList = $dbObj->selectDataObj($sql);

switch($action){
	case 'view':
	default:
		if(!empty($_REQUEST['floor_id'])){
			$hall_id = $_REQUEST['hall_id'];
			$block_id = $_REQUEST['block_id'];
			$floor_id = $_REQUEST['floor_id'];
			
		    $sql = "select r.* from ".DB_PREFIX."room as r, ".DB_PREFIX."floor as f, ".DB_PREFIX."hall as h, ".DB_PREFIX."block as b WHERE  h.id = r.hall_id AND  b.id =r.block_id AND  f.id =r.floor_id AND  r.floor_id ='".$floor_id."' ORDER BY r.name asc";
			$roomList = $dbObj->selectDataObj($sql);
	
			//Pagination 
			if(!empty($roomList)){
				$total_rows = sizeof($roomList);
			}else{
				$total_rows =0;
			}
			//find start
			$s = ($page - 1) * $limit;
			$total_page = $total_rows/$limit;
			}else{
				$hall_id = '';
				$block_id = '';
				$floor_id = '';
				$name = '';
			}
			$action = 'view';
			break;
		
	case 'update':
	case 'create':
	
		$msg = $_REQUEST['msg'];
		if(!empty($_REQUEST['room_id'])){
			$id = $_REQUEST['room_id'];
			$patern_id = $_REQUEST['patern_id'];
			$floor_id = $_REQUEST['floor_id'];
			$block_id = $_REQUEST['block_id'];
			$hall_id = $_REQUEST['hall_id'];
			$sql = "select * from ".DB_PREFIX."room WHERE id='".$id."'";	
			$roomList = $dbObj->selectDataObj($sql);
			$room = $roomList[0];
			$hall_id = $room->hall_id;
			$block_id = $room->block_id;
			$floor_id = $room->floor_id;
			$patern_id = $room->patern_id;
			$name = $room->name;
			
		}
		$hall_id = $_REQUEST['hall_id'];
		//Build hall Array
		$sql = "select id, name from ".DB_PREFIX."hall order by name asc";
		$hallArr = $dbObj->selectDataObj($sql);
		
		$hallId = array();
		$hallId[0] = SELECT_HALL_OPT;
		if(!empty($hallArr)){			
			foreach($hallArr as $item){
				$hallId[$item->id] = $item->name;
			}	
		}			
		$hallList_opt = formSelectElement($hallId, $hall_id, 'hall_id', 'onchange = processFunction("get_block")');
		
		$block_id = $_REQUEST['block_id'];
		//Build Block Array
		$sql = "select id, name from ".DB_PREFIX."block  WHERE hall_id = '".$hall_id."' order by name asc";
		$blockArr = $dbObj->selectDataObj($sql);
		
		$blockId = array();
		$blockId[0] = SELECT_BLOCK_OPT;
		if(!empty($blockArr)){			
			foreach($blockArr as $item){
				$blockId[$item->id] = $item->name;
			}	
		}			
		
		$blockList_opt = formSelectElement($blockId, $block_id, 'block_id', 'onchange = processFunction("get_floor")');
		
		$floor_id = $_REQUEST['floor_id'];
		//Build Floor Array
		$sql = "select id, name from ".DB_PREFIX."floor WHERE hall_id = '".$hall_id."' AND block_id = '".$block_id."' order by name asc";
		$floorArr = $dbObj->selectDataObj($sql);
		
		$floorId = array();
		$floorId[0] = SELECT_FLOOR_OPT;
		if(!empty($floorArr)){			
			foreach($floorArr as $item){
				$floorId[$item->id] = $item->name;
			}	
		}			
		$floorList_opt = formSelectElement($floorId, $floor_id, 'floor_id');
		
		//Build Patern Array
		$sql = "select id, name from ".DB_PREFIX."patern order by name asc";
		$paternArr = $dbObj->selectDataObj($sql);
		
		$paternId = array();
		$paternId[0] = SELECT_PATERN_OPT;
		if(!empty($paternArr)){			
			foreach($paternArr as $item){
				$paternId[$item->id] = $item->name;
			}	
		}			
		$paternList_opt = formSelectElement($paternId, $patern_id, 'patern_id');
		
		$action = 'insert';
		break;
		
	case 'save':	
		$id = $_POST['id'];
		$hall_id = $_POST['hall_id'];
		$block_id = $_POST['block_id'];
		$floor_id = $_POST['floor_id'];
		$patern_id = $_POST['patern_id'];
		//echo '<pre>';print_r($_REQUEST);exit;
		$name = $_POST['name'];
		
		//Check if floor number already exists in the db in same floor
		if(empty($id)){
			$sql = "select name from ".DB_PREFIX."room WHERE name = '".$name."' AND hall_id = '".$hall_id."' AND block_id ='".$block_id."' AND floor_id ='".$floor_id."' limit 1";
			$roomList = $dbObj->selectDataObj($sql);		
			
			if(!empty($roomList)){
				$msg = $name.ALREADY_EXISTS;
				$url = 'room.php?action=create&msg='.$msg;
				redirect($url);
			}			
		}else if(!empty($id)){
			$sql = "select name from ".DB_PREFIX."room WHERE name = '".$name."' AND id != '".$id."' AND hall_id = '".$hall_id."' AND block_id ='".$block_id."' AND floor_id ='".$floor_id."' limit 1";
			$roomList = $dbObj->selectDataObj($sql);		
			
			if(!empty($roomList)){
				$msg = $name.ALREADY_EXISTS;
				$url = 'room.php?action=update&page='.$page.'&id='.$id.'&msg='.$msg;
				redirect($url);
			}
		}
		if(!empty($id)){
			$fields = array('hall_id' => $hall_id,
						'block_id' => $block_id,
						'floor_id' => $floor_id,
						'patern_id' => $patern_id,
						'name' => $name,
						'updated_by' => $cur_user_id,
						'updated_datetime' => current_date_time()
						);
						
			$where = "id = '".$id."'";
			
			$update_status = $dbObj->updateTableData("room", $fields, $where);	
			
			if(!$update_status){
				$msg = $name.COULD_NOT_BE_UPDATED;	
				$action = 'insert';
			}else{
				$msg = $name.HAS_BEEN_UPDATED;
				$url = 'room.php?action=view&hall_id='.$hall_id.'&block_id='.$block_id.'&floor_id='.$floor_id.'&patern_id='.$patern.'&page='.$page.'&msg='.$msg;
				redirect($url);
			}
		}else{
			$fields = array('hall_id' => $hall_id,
						'block_id' => $block_id,
						'floor_id' => $floor_id,
						'patern_id' => $patern_id,
						'name' => $name,
						'created_by' => $cur_user_id,
						'created_datetime' => current_date_time(),
						'updated_by' => $cur_user_id,
						'updated_datetime' => current_date_time()
						);
			
			
			$inserted = $dbObj->insertTableData("room", $fields);	
			if(!$inserted){
				$msg = $name.COULD_NOT_BE_CREATED;
				$action = 'insert';
			}else{
				$msg = $name.CREATED_SUCCESSFULLY;
				$url = 'room.php?action=view&hall_id='.$hall_id.'&block_id='.$block_id.'&floor_id='.$floor_id.'&patern_id='.$patern.'&msg='.$msg;
				redirect($url);
			}
		}
		break;

	case 'delete':
		$hall_id = $_REQUEST['hall_id'];	
		$block_id = $_REQUEST['block_id'];	
		$floor_id = $_REQUEST['floor_id'];		
		$id = $_REQUEST['room_id'];	
		$sql = "select * from ".DB_PREFIX."room WHERE id='".$id."'";	
		$roomList = $dbObj->selectDataObj($sql);
		$room = $roomList[0];
		$name = $room->name;
		$where = "id='".$id."'";	
		
		$success = $dbObj->deleteTableData("room", $where);	
		
		if(!$success){
			$msg = $name.COULD_NOT_BE_DELETED;
		}else{
			$msg = $name.HAS_BEEN_DELETED;
		}
		
		$url = 'room.php?action=view&hall_id='.$hall_id.'&block_id='.$block_id.'&floor_id='.$floor_id.'&patern_id='.$patern.'&page='.$page.'&msg='.$msg;
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
			
			<?php foreach($floorList as $item){?>
			<div id="view_all"><?php echo '<b>'.'Hall &raquo; '.'</b>'.'<a href="block.php?action=view&hall_id='.$_REQUEST['hall_id'].'" title ="View Hall Details">'.$item->hall_name.'</a>'.'&nbsp'.'<b>'.' Block &raquo; '.'</b>'.'<a href="floor.php?action=view&hall_id='.$_REQUEST['hall_id'].'&block_id='.$_REQUEST['block_id'].'"  title ="View Block Details">'.$item->block_name.'</a>'.'&nbsp'.'<b>'.'  Floor &raquo; '.'</b>'.'<a href="room.php?action=view&hall_id='.$_REQUEST['hall_id'].'&block_id='.$_REQUEST['block_id'].'&floor_id='.$_REQUEST['floor_id'].'" title ="View Floor Details">'.$item->floor_name.'</a>';?></div>
			<?php }?>
			</td>	
		</tr>
	
		<tr>
			<td>
				<h4><?php echo ROOM; ?></h4>
			</td>	
			<td class="usr_info">
				<?php echo welcomeMsg($cur_user_id); ?>
			</td>			
		</tr>
	</table>
	<?php
		if($action=="insert"){ ?>
	
				<form action="room.php" method="post" name="room" id="room" onsubmit="return validateroom();">
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
						<tr>
							<td height="30" width="20%">
								<?php echo HALL_NAME; ?>:
							</td>
							<td width="80%">
								<?php echo $hallList_opt; ?>
								<span class="required_field">*</span>
							</td>
						</tr>
						
						<tr>
							<td height="30">
								<?php echo BLOCK_NAME; ?>:
							</td>
							<td>
							<div id="loaderContainer"></div>
							<div id="block_display">
								<?php echo $blockList_opt; ?>
								<span class="required_field">*</span>
							</div>
							</td>
						</tr>
						<tr>
							<td height="30">
								<?php echo FLOOR_NAME; ?>:
							</td>
							<td>
							<div id="loaderContainer"></div>
							<div id="floor_display">
								<?php echo $floorList_opt; ?>
								<span class="required_field">*</span>
							</div>
							</td>
						</tr>
						<tr>
							<td height="30" width="20%">
								<?php echo PATERN_NAME; ?>:
							</td>
							<td width="80%">
							<div id="patern_display">
								<?php echo $paternList_opt; ?>
								<span class="required_field">*</span>
							</div>
							</td>
						</tr>
						<tr>
							<td height="30" width="20%">
								<?php echo ROOM_NO; ?>:
							</td>
							<td width="80%">
								<input name="name" id="name" type="text" class="inputbox" alt="Room No" size="36" value="<?php echo $name; ?>" />
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
					<!--<input type="hidden" name="hall_id" value="<?php //echo $hall_id; ?>" />
					<input type="hidden" name="block_id" value="<?php //echo $block_id; ?>" />
					<input type="hidden" name="floor_id" value="<?php //echo $floor_id; ?>" />-->
					<input type="hidden" name="action" value="save" />
					<input type="hidden" name="page" id="page" value="<?php echo $page; ?>" />
				</form>
				
				
	<?php }else if($action=="view"){
	?>
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
				<tr>
					<td>
						<table width="100%" cellpadding="0" cellspacing="0" border="0" class="datagrid">
								<tr class="footer">
									<td colspan="5" style=" background:#EEEEEE;">
									<b> <a href="room.php?action=create&hall_id=<?php echo $_REQUEST['hall_id']; ?>&block_id=<?php echo $_REQUEST['block_id']; ?>&floor_id=<?php echo $_REQUEST['floor_id']; ?>"><?php echo CREATE; ?></a><b>
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
							if(!empty($roomList)){	
								
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
											<a href="seat.php?action=view&room_id=<?php echo $roomList[$rownum]->id;?>&hall_id=<?php echo $roomList[$rownum]->hall_id; ?>&block_id=<?php echo $roomList[$rownum]->block_id; ?>&floor_id=<?php echo $roomList[$rownum]->floor_id; ?>" title="View Room Details"><?php echo $roomList[$rownum]->name; ?> </a>
										</td>	
										<td>								
											<a class="edit" href="room.php?action=update&page=<?php echo $page; ?>&room_id=<?php echo $roomList[$rownum]->id;?>&hall_id=<?php echo $roomList[$rownum]->hall_id;?>&block_id=<?php echo $roomList[$rownum]->block_id; ?>&floor_id=<?php echo $roomList[$rownum]->floor_id; ?>&patern_id=<?php echo $roomList[$rownum]->patern_id; ?>" title="Edit">&nbsp;</a>
											<a class="delete" href="room.php?action=delete&page=<?php echo $page; ?>&room_id=<?php echo $roomList[$rownum]->id; ?>&hall_id=<?php echo $roomList[$rownum]->hall_id;?>&block_id=<?php echo $roomList[$rownum]->block_id; ?>&floor_id=<?php echo $roomList[$rownum]->floor_id; ?>&patern_id=<?php echo $roomList[$rownum]->patern_id; ?>" onclick="return confirm('Are you sure you want to delete?');" title="Delete">&nbsp;</a>
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
										<?php echo pagination($total_rows,$limit,$page,'&hall_id='.$hall_id.'&block_id='.$block_id.'&floor_id='.$floor_id); ?>
									</td>
								</tr>
								<?php } ?>	
								<tr class="footer">
									<td colspan="6">
										<b> <a href="room.php?action=create&hall_id=<?php echo $_REQUEST['hall_id']; ?>&block_id=<?php echo $_REQUEST['block_id']; ?>&floor_id=<?php echo $_REQUEST['floor_id']; ?>"><?php echo CREATE; ?></a><b>
									</td>
								</tr>				
						</table>
					</td>
				</tr>
			</table>			
				
	<?php }?>
</div>
			
<?php
require_once("includes/footer.php");
?>