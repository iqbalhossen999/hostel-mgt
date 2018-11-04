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
	$room_id = $_REQUEST['room_id'];

	$sql = "SELECT
					 b.name as block_name,
					 h.name as hall_name,
					 f.name as floor_name,
					 r.name as room_name
			 FROM ".DB_PREFIX."block b, 
				  ".DB_PREFIX."hall h,
				  ".DB_PREFIX."floor f,
				  ".DB_PREFIX."room r   
			 WHERE h.id = r.hall_id
			 AND b.id = r.block_id
			 AND f.id = r.floor_id
			 AND r.id ='".$room_id."'
			 GROUP BY r.id" ;
	$roomList = $dbObj->selectDataObj($sql);
	
switch($action){
	case 'view':	
	default:
		
	
		if(!empty($_REQUEST['room_id'])){
			$hall_id = $_REQUEST['hall_id'];
			$block_id = $_REQUEST['block_id'];
			$floor_id = $_REQUEST['floor_id'];
			$room_id = $_REQUEST['room_id'];
		
			$sql = "select s.* from ".DB_PREFIX."seat as s, ".DB_PREFIX."floor as f, ".DB_PREFIX."hall as h, ".DB_PREFIX."block as b, ".DB_PREFIX."room as r WHERE  h.id = s.hall_id AND  b.id =s.block_id AND  f.id =s.floor_id   AND  r.id =s.room_id AND  s.room_id ='".$room_id."' ORDER BY s.name asc";
			$seatList = $dbObj->selectDataObj($sql);
	
			//Pagination 
			if(!empty($seatList)){
			
				 $total_rows = sizeof($seatList);
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
				$room_id = '';
				$name = '';
			}
			$action = 'view';
			break;	
		
	case 'release':	
		$id = $_REQUEST['id'];
		$fields = array('seat_id' => '0',
						'updated_by' => $cur_user_id,
						'updated_datetime' => current_date_time()
						);
								
		$where = "seat_id = '".$id."'";
		$update_status = $dbObj->updateTableData("prebooking", $fields, $where);	
		
		if(!$update_status){
			$msg = 'Cannot Update Prebooking List';	
			$action = 'insert';
		}else{
			$fields2 = array('book' => '0',
						'updated_by' => $cur_user_id,
						'updated_datetime' => current_date_time()
						);
						
		$where2 = "id = '".$id."'";
		$update_status2 = $dbObj->updateTableData("seat", $fields2, $where2);	
			if(!$update_status){
				$msg = 'Cannot Update Seat No';	
				$action = 'insert';
			}else{
				$msg = 'Seat has been Successfully Release to Student';
				$url = 'seat.php?action=view&page='.$page.'&msg='.$msg;
				redirect($url);
			}
		}	
	
		break;
		
	case 'update':
	case 'create':
		
		$id = $_REQUEST['seat_id'];
		if(!empty($id)){
			
			$room_id = $_REQUEST['room_id'];
			$floor_id = $_REQUEST['floor_id'];
			$block_id = $_REQUEST['block_id'];
			$hall_id = $_REQUEST['hall_id'];
			$sql = "select * from ".DB_PREFIX."seat WHERE id='".$id."'";	
			$seatList = $dbObj->selectDataObj($sql);
			$seat= $seatList[0];
			$hall_id = $seat->hall_id;
			$block_id = $seat->block_id;
			$floor_id = $seat->floor_id;
			$room_id = $seat->room_id;
			$name = $seat->name;
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
		$floorList_opt = formSelectElement($floorId, $floor_id, 'floor_id', 'onchange = processFunction("get_room")');
		$room_id = $_REQUEST['room_id'];
		
		//Build room name Array
		$sql = "select id, name from ".DB_PREFIX."room  WHERE hall_id = '".$hall_id."' AND block_id = '".$block_id."' AND floor_id = '".$floor_id."'order by name asc";
		$roomArr = $dbObj->selectDataObj($sql);
		$roomId = array();
		$roomId[0] = SELECT_ROOM_OPT;
		if(!empty($roomArr)){			
			foreach($roomArr as $item){
				$roomId[$item->id] = $item->name;
			}	
		}			
		$roomList_opt = formSelectElement($roomId, $room_id, 'room_id');
		
		$action = 'insert';
		break;
	
	case 'setting':
		$id = $_REQUEST['id'];
		
		$sql = "select name, hall_id from ".DB_PREFIX."seat WHERE id = '".$id."'";
		$hallInf = $dbObj->selectDataObj($sql);
		$hall_id = $hallInf[0]->hall_id;
		
		$sql = "SELECT YEAR(NOW()) as year";
		$curYear = $dbObj->selectDataObj($sql);
		$year = $curYear[0]->year;
		
		//Build Year Array
		$yearArr = array();
		$yearArr[0] = SELECT_YEAR_OPT;
		for($i = 2012; $i <= date('Y'); $i++){
			$yearArr[$i] = $i;
		}
		$yearList_opt = formSelectElement($yearArr, $year, 'year', 'onchange = processFunction("get_seat_setting")');
		
		$sql = "select * from ".DB_PREFIX."seat_charge WHERE hall_id='".$hall_id."' AND year = '".$year."' AND seat_id = '".$id."'";
		$settingList = $dbObj->selectDataObj($sql);
		$setting= $settingList[0];
		$estab = $setting->estab;
		$readm = $setting->readm;
		$sd = $setting->sd;
		$messad = $setting->messad;
		$donation = $setting->donation;
		$seatrent = $setting->seatrent;
		$utencro = $setting->utencro;
		$maint = $setting->maint;
		$crnpape = $setting->crnpape;
		$inter = $setting->inter;
		$conti = $setting->conti;
		
		$action = 'setting';
		break;
	
	case 'setting_save':
		$id = $_POST['id'];
		$hall_id = $_POST['hall_id'];
		$estab = $_POST['estab'];
		$readm = $_POST['readm'];
		$sd = $_POST['sd'];
		$messad = $_POST['messad'];
		$donation = $_POST['donation'];
		$seatrent = $_POST['seatrent'];
		$utencro = $_POST['utencro'];
		$maint = $_POST['maint'];
		$crnpape = $_POST['crnpape'];
		$inter = $_POST['inter'];
		$conti = $_POST['conti'];
		$year = $_POST['year'];
		
		//Delete Existing Data of this seat of same year from Seat Charge Table
		$where = "year = '".$year."' AND seat_id = '".$id."' AND hall_id = '".$hall_id."'";	
		$delete = $dbObj->deleteTableData("seat_charge", $where);
		
		$fields = array(
						'hall_id' => $hall_id,
						'seat_id' => $id,
						'`year`' => $year,
						'estab' => $estab,
						'readm' => $readm,
						'sd' => $sd,
						'messad' => $messad,
						'donation' => $donation,
						'seatrent' => $seatrent,
						'utencro' => $utencro,
						'maint' => $maint,
						'crnpape' => $crnpape,
						'inter' => $inter,
						'conti' => $conti,
						'created_by' => $cur_user_id,
						'created_datetime' => current_date_time()
						);
		$setting = $dbObj->insertTableData("seat_charge", $fields);	
		
		if(!$setting){
			$msg = 'Could not update setting';	
			$action = 'setting';
		}else{
			$msg = 'Setting has been changed successfully';
			$url = 'seat.php?action=view&page='.$page.'&msg='.$msg;
			redirect($url);
		}
		break;
		
	case 'save':	
		$id = $_POST['id'];
		$hall_id = $_POST['hall_id'];
		$block_id = $_POST['block_id'];
		$floor_id = $_POST['floor_id'];
		$room_id = $_POST['room_id'];
		$name = $_POST['name'];
		
		//Find highest number of seat in a room
		$sql = "select p.name, p.number_seat from ".DB_PREFIX."room as r, ".DB_PREFIX."patern as p WHERE r.id ='".$room_id."' AND p.id = r.patern_id";
		$highestArr = $dbObj->selectDataObj($sql);		
		$highest = $highestArr[0]->number_seat;
		$patern_name = $highestArr[0]->name;
		
		//Find total number of seat created in this room
		$sql = "select count(id) as total from ".DB_PREFIX."seat WHERE room_id ='".$room_id."'";
		$totalArr = $dbObj->selectDataObj($sql);		
		$total = $totalArr[0]->total;
		
		//Confirm that the seat number doesn't exceed the pattern crossing
		if($total >= $highest){
			$msg = 'Seat could not be created!<br />N.B: '.$patern_name.' paterned room can contain total '.$highest.' seats';
			if(empty($id)){
				$url = 'seat.php?action=create&msg='.$msg;
			}else{
				$url = 'seat.php?action=update&page='.$page.'&id='.$id.'&msg='.$msg;
			}
			redirect($url);
		}
		
		//Check if floor number already exists in the db in same floor
		if(empty($id)){
			$sql = "select name from ".DB_PREFIX."seat WHERE name = '".$name."' AND hall_id = '".$hall_id."' AND block_id ='".$block_id."' AND floor_id ='".$floor_id."' AND room_id ='".$room_id."' limit 1";
			$seatList = $dbObj->selectDataObj($sql);		
			
			if(!empty($seatList)){
				$msg = $name.ALREADY_EXISTS;
				$url = 'seat.php?action=create&msg='.$msg;
				redirect($url);
			}			
		}else if(!empty($id)){
			$sql = "select name from ".DB_PREFIX."seat WHERE id!='".$id."' AND name = '".$name."' AND hall_id = '".$hall_id."' AND block_id ='".$block_id."' AND floor_id ='".$floor_id."' AND room_id ='".$room_id."' limit 1";
			$seatList = $dbObj->selectDataObj($sql);		
			
			if(!empty($seatList)){
				$msg = $name.ALREADY_EXISTS;
				$url = 'seat.php?action=update&page='.$page.'&id='.$id.'&msg='.$msg;
				redirect($url);
			}
		}
		
		
		if(!empty($id)){
			$fields = array('hall_id' => $hall_id,
						'block_id' => $block_id,
						'floor_id' => $floor_id,
						'room_id' => $room_id,
						'name' => $name,
						'updated_by' => $cur_user_id,
						'updated_datetime' => current_date_time()
						);
						
			$where = "id = '".$id."'";
			
			$update_status = $dbObj->updateTableData("seat", $fields, $where);	
			
			if(!$update_status){
				$msg = $name.COULD_NOT_BE_UPDATED;	
				$action = 'insert';
			}else{
				$msg = $name.HAS_BEEN_UPDATED;
				$url = 'seat.php?action=view&hall_id='.$hall_id.'&block_id='.$block_id.'&floor_id='.$floor_id.'&room_id='.$room_id.'&page='.$page.'&msg='.$msg;
				redirect($url);
			}
		}else{
			$fields = array('hall_id' => $hall_id,
						'block_id' => $block_id,
						'floor_id' => $floor_id,
						'room_id' => $room_id,
						'name' => $name,
						'created_by' => $cur_user_id,
						'created_datetime' => current_date_time(),
						'updated_by' => $cur_user_id,
						'updated_datetime' => current_date_time()
						);
			
			$inserted = $dbObj->insertTableData("seat", $fields);	
			if(!$inserted){
				$msg = $name.COULD_NOT_BE_CREATED;
				$action = 'insert';
			}else{
				$msg = $name.CREATED_SUCCESSFULLY;
				$url = 'seat.php?action=view&hall_id='.$hall_id.'&block_id='.$block_id.'&floor_id='.$floor_id.'&room_id='.$room_id.'&msg='.$msg;
				redirect($url);
			}
		}
		
		break;

	case 'delete':	
		$id = $_REQUEST['seat_id'];
		$hall_id = $_REQUEST['hall_id'];	
		$block_id = $_REQUEST['block_id'];	
		$floor_id = $_REQUEST['floor_id'];	
		$room_id = $_REQUEST['room_id'];			
		$sql = "select * from ".DB_PREFIX."seat WHERE id='".$id."'";	
		$seatList = $dbObj->selectDataObj($sql);
		$seat = $seatList[0];
		$name = $seat->name;
		$where = "id='".$id."'";	
		
		$success = $dbObj->deleteTableData("seat", $where);	
		
		$msg = (!$success) ? $name.COULD_NOT_BE_DELETED : $name.HAS_BEEN_DELETED;
		$url = 'seat.php?action=view&hall_id='.$hall_id.'&block_id='.$block_id.'&floor_id='.$floor_id.'&room_id='.$room_id.'&page='.$page.'&msg='.$msg;
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
	<?php } ?>
	<table width="100%" cellspacing="0" cellpadding="0" border="0" class="module_header">
		<tr>
			<td>
			
			<?php foreach($roomList as $item){?>
			
			<div id="view_all"><?php echo '<b>'.'Hall &raquo; '.'</b>'.'<a href="block.php?action=view&hall_id='.$_REQUEST['hall_id'].'">'.$item->hall_name.'</a>'.'&nbsp'.'<b>'.' Block &raquo; '.'</b>'.'<a href="floor.php?action=view&hall_id='.$_REQUEST['hall_id'].'&block_id='.$_REQUEST['block_id'].'">'.$item->block_name.'</a>'.'&nbsp'.'<b>'.'  Floor &raquo; '.'</b>'.'<a href="room.php?action=view&hall_id='.$_REQUEST['hall_id'].'&block_id='.$_REQUEST['block_id'].'&floor_id='.$_REQUEST['floor_id'].'">'.$item->floor_name.'</a>'.'&nbsp'.'<b>'.'  Room &raquo; '.'</b>'.'<a href="seat.php?action=view&hall_id='.$_REQUEST['hall_id'].'&block_id='.$_REQUEST['block_id'].'&floor_id='.$_REQUEST['floor_id'].'&room_id='.$_REQUEST['room_id'].'">'.$item->room_name.'</a>';?></div>
			<?php }?>
			</td>	
		</tr>
		<tr>
			<td>
				<h4><?php echo SEAT; ?></h4>
			</td>	
			<td class="usr_info">
				<?php echo welcomeMsg($cur_user_id); ?>
			</td>			
		</tr>
	</table>
	<?php
		if($action=="insert"){ ?>
	
				<form action="seat.php" method="post" name="seat" id="seat" onsubmit="return validateseat();">
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
						<tr>
							<td height="30">
								<?php echo HALL_NAME; ?>:
							</td>
							<td height="30">
								<?php echo $hallList_opt; ?>
								<span class="required_field">*</span>
							</td>
						</tr>
						
						<tr>
							<td height="30">
								<?php echo BLOCK_NAME; ?>:
							</td>
							<td height="30">
								<div id="loaderContainer"></div>
								<div id="block_display">
									<?php echo $blockList_opt; ?>
									<span class="required_field">*</span>
								</div>
							</td>
						</tr>
						<tr>
							<td height="30"><?php echo FLOOR_NAME; ?>:</td>
							<td>
								<div id="loaderContainer"></div>
								<div id="floor_display"><?php echo $floorList_opt; ?><span class="required_field">*</span>
								</div>
							</td>
						</tr>
						<tr>
							<td height="30"><?php echo ROOM_NO; ?>:</td>
							<td>
								<div id="loaderContainer"></div>
								<div id="room_display">
									<?php echo $roomList_opt; ?>
									<span class="required_field">*</span>
								</div>
							</td>
						</tr>
						<tr>
							<td height="30"><?php echo SEAT_NO; ?>:</td>
							<td>
								<input type="text" name="name" id="name"  class="inputbox" alt="Seat No" size="36" value="<?php echo $name; ?>" />
								<span class="required_field">*</span>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="50">
								<input type="submit" name="Submit" class="button" value="Save" />
								<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><input type="button" onclick="window.location='<?php echo $_SERVER['HTTP_REFERER']; ?>'"  name="cancel" class="cancel" value="<?php echo CANCEL; ?>" /></a>
							</td>
						</tr>		
					</table>	
					<input type="hidden" name="id" value="<?php echo $id; ?>" />
					<input type="hidden" name="action" value="save" />
					<input type="hidden" name="page" id="page" value="<?php echo $page; ?>" />
				</form>
			
	<?php }else if($action=="setting"){?>
	
				<form action="seat.php" method="post" name="seatsetting" id="seatsetting" onsubmit="return validateseat();">
								<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_details">
									<tr class="holder topholder">
										<td height="30" width="20%"><?php echo SEAT_NO; ?></td>
										<td width="80%"><strong><?php echo $hallInf[0]->name; ?></strong></td>
									</tr>
									<tr class="holder">
										<td height="30"><?php echo YEAR; ?>:</td>
										<td><?php echo $yearList_opt; ?></td>
									</tr>
									<tr>
										<td colspan="2">
											<div id="loaderContainer"></div>
											<div id="setting_display">
												<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_details no_padding">
													<tr class="holder">
														<td height="30" width="20%"><?php echo ESTAB; ?>:</td>
														<td width="80%">
															<input type="text" name="estab" id="estab"  class="inputbox5" alt="estab" size="8" value="<?php echo $estab; ?>" onkeyup="isNUM('estab')" />
															<?php echo TK;?>
														</td>
													</tr>
													<tr class="holder">
														<td height="30"><?php echo RE_ADM; ?>:</td>
														<td>
															<input name="readm" id="readm" type="text" class="inputbox5" alt="Re-Adm" size="8" value="<?php echo $readm; ?>" onkeyup="isNUM('readm')" />
															<?php echo TK;?>
														</td>
													</tr>
													<tr class="holder">
														<td height="30"><?php echo SD; ?>:</td>
														<td>
															<input name="sd" id="sd" type="text" class="inputbox5" alt="SD" size="8" value="<?php echo $sd; ?>"  onkeyup="isNUM('sd')" />
															<?php echo TK;?>
														</td>
													</tr>
													<tr class="holder">
														<td height="30"><?php echo MESS_AD; ?>:</td>
														<td>
															<input name="messad" id="messad" type="text" class="inputbox5" alt="Mess Ad" size="8" value="<?php echo $messad; ?>"  onkeyup="isNUM('messad')" />
															<?php echo TK;?>
														</td>
													</tr>
													<tr class="holder">
														<td height="30"><?php echo DONATION; ?>:</td>
														<td>
															<input name="donation" id="donation" type="text" class="inputbox5" alt="Donation" size="8" value="<?php echo $donation; ?>"  onkeyup="isNUM('donation')" />
															<?php echo TK;?>
														</td>
													</tr>
													<tr class="holder">
														<td height="30"><?php echo SEAT_RENT; ?>:</td>
														<td>
															<input name="seatrent" id="seatrent" type="text" class="inputbox5" alt="Seat Rent" size="8" value="<?php echo $seatrent; ?>"  onkeyup="isNUM('seatrent')" />
															<?php echo TK;?>
														</td>
													</tr>
													<tr class="holder">
														<td height="30"><?php echo UTEN_CRO; ?>:</td>
														<td>
															<input name="utencro" id="utencro" type="text" class="inputbox5" alt="Uten.&Cro" size="8" value="<?php echo $utencro; ?>"  onkeyup="isNUM('utencro')" />
															<?php echo TK;?>
														</td>
													</tr>
													<tr class="holder">
														<td height="30"><?php echo MAINT; ?>:</td>
														<td>
															<input name="maint" id="maint" type="text" class="inputbox5" alt="Maint" size="8" value="<?php echo $maint; ?>"  onkeyup="isNUM('maint')" />
															<?php echo TK;?>
														</td>
													</tr>
													<tr class="holder">
														<td height="30"><?php echo CRNPAPE; ?>:</td>
														<td>
															<input name="crnpape" id="crnpape" type="text" class="inputbox5" alt="C.R/N.Pape" size="8" value="<?php echo $crnpape; ?>"  onkeyup="isNUM('crnpape')" />
															<?php echo TK;?>
														</td>
													</tr>
													<tr class="holder">
														<td height="30"><?php echo INTER; ?>:</td>
														<td>
															<input name="inter" id="inter" type="text" class="inputbox5" alt="Inter" size="8" value="<?php echo $inter; ?>"  onkeyup="isNUM('inter')" />
															<?php echo TK;?>
														</td>
													</tr>
													<tr class="holder">
														<td height="30"><?php echo CONTI; ?>:</td>
														<td>
															<input name="conti" id="conti" type="text" class="inputbox5" alt="Conti" size="8" value="<?php echo $conti; ?>" onkeyup="isNUM('conti')"  />
															<?php echo TK;?>
														</td>
													</tr>
													<tr>
														<td colspan="2" height="50">
															<input type="submit" name="Submit" class="button" value="Save" />
															<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><input type="button" onclick="window.location='<?php echo $_SERVER['HTTP_REFERER']; ?>'"  name="cancel" class="cancel" value="<?php echo CANCEL; ?>" /></a>
														</td>
													</tr>
												</table>
											</div>
										</td>
									</tr>
								</table>
					<!--<input type="hidden" name="hall_id" id="hall_id" value="<?php //echo $hall_id; ?>" />-->
					<input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
					<input type="hidden" name="action" value="setting_save" />
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
										<b>  <a href="seat.php?action=create&hall_id=<?php echo $_REQUEST['hall_id']; ?>&block_id=<?php echo $_REQUEST['block_id']; ?>&floor_id=<?php echo $_REQUEST['floor_id']; ?>&room_id=<?php echo $_REQUEST['room_id']; ?>&seat_id=<?php echo $_REQUEST['seat_id']; ?>"><?php echo CREATE; ?></a> <b> 
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
							if(!empty($seatList)){	
								
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
										<?php echo $seatList[$rownum]->name; ?>
										<td>								
											<a class="edit" href="seat.php?action=update&page=<?php echo $page;?>&seat_id=<?php echo $seatList[$rownum]->id;?>&hall_id=<?php echo $seatList[$rownum]->hall_id;?>&block_id=<?php echo $seatList[$rownum]->block_id; ?>&floor_id=<?php echo $seatList[$rownum]->floor_id; ?>&room_id=<?php echo $seatList[$rownum]->room_id;?>" title="Edit">&nbsp;</a>
											<a class="delete" href="seat.php?action=delete&seat_id=<?php echo $seatList[$rownum]->id;?>&hall_id=<?php echo $seatList[$rownum]->hall_id;?>&block_id=<?php echo $seatList[$rownum]->block_id; ?>&floor_id=<?php echo $seatList[$rownum]->floor_id; ?>&room_id=<?php echo $seatList[$rownum]->room_id;?>" onclick="return confirm('Are you sure you want to delete?');" title="Delete">&nbsp;</a>
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
										<?php echo pagination($total_rows,$limit,$page,'&hall_id='.$hall_id.'&block_id='.$block_id.'&floor_id='.$floor_id.'&room_id='.$room_id); ?>
									</td>
								</tr>
								<?php } ?>	
								<tr class="footer">
									<td colspan="6">
											<b> <a href="seat.php?action=create&hall_id=<?php echo $_REQUEST['hall_id']; ?>&block_id=<?php echo $_REQUEST['block_id']; ?>&floor_id=<?php echo $_REQUEST['floor_id']; ?>&room_id=<?php echo $_REQUEST['room_id']; ?>&seat_id=<?php echo $_REQUEST['seat_id']; ?>"><?php echo CREATE; ?></a><b>
									</td>
								</tr>				
						</table>
					</td>
				</tr>
			</table>			
								
	<?php } ?>
</div>
			
<?php
require_once("includes/footer.php");
?>