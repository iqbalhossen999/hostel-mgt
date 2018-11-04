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

if($cur_user_group_id == '2'){
	dashboard();
}//if

if($cur_user_group_id == '3' && $action != 'detail'){
	dashboard();
}//if

//Pagination
$limit = PAGE_LIMIT_DEFAULT;
$path = 'attach_file/';
$page = (empty($_REQUEST['page'])) ? 1 : $_REQUEST['page'];

switch($action){
	case 'view':	
	default:
		
		$sql = "select * from ".DB_PREFIX."prebooking WHERE accept = '1' order by id asc";
		$prebookingList = $dbObj->selectDataObj($sql);
		
		$action = 'view';
		$path_view = 'attach_file/';
		
		//Pagination 
		$total_rows = (!empty($prebookingList)) ? sizeof($prebookingList) : 0;
		$s = ($page - 1) * $limit;
		$total_page = $total_rows/$limit;
		
		break;
	
	case 'update':
		$id = $_REQUEST['id'];
		$seat_id = $_REQUEST['seat_id'];
		$sql = "select * from ".DB_PREFIX."prebooking WHERE id='".$id."'";	
		$studentArr = $dbObj->selectDataObj($sql);
		$student = $studentArr[0];

		if($student->seat_id != '0'){
			$sql = "select room_id, floor_id, block_id, hall_id from ".DB_PREFIX."seat WHERE id='".$student->seat_id."'";	
			$infoArr = $dbObj->selectDataObj($sql);
			$room_id = $infoArr[0]->room_id;
			$floor_id = $infoArr[0]->floor_id;
			$block_id = $infoArr[0]->block_id;
			$hall_id = $infoArr[0]->hall_id;
			
			//Build seat Array
			$sql = "select id, name, book from ".DB_PREFIX."seat WHERE room_id = '".$room_id."' order by name asc";
			$seatArr = $dbObj->selectDataObj($sql);
			
			$seat_pre = getNameById('prebooking',$id);
			$cur_seat_id = $seat_pre->seat_id;
			
					if(!empty($seatArr)){
							$seat_str = '<div id="seatcontainer">';
							$total_seat = 0;
							foreach($seatArr as $seat){	
								if($cur_seat_id == $seat->id){
									$seat_str .=	'<div name="booked" class="exist_book" id= "'.$seat->id.'"><p class="seat_name">'.$seat->name.'</p></div>';
	
								}elseif($seat->book == '0'){
									$seat_str .=	'<div name="notbooked" class="seatno_notbooked" id= "'.$seat->id.'" onclick="assignSeat('.$seat->id.')"><p class="seat_name">'.$seat->name.'</p></div>';
									$total_seat++;
								}else{
									$seat_str .=	'<div class="seatno_booked"><p class="seat_name">'.$seat->name.'</p></div>';
								}
							}//foreach
							
						}//if
						$seat_str .= '
						<input type="hidden" name="total_seat" id="total_seat" value="'.$total_seat.'" />
						<input type="hidden" name="seat_id" id="seat_id" value="" />
						
					</div>
					<div id="color_code">
						<p><img src="images/color_code1.gif" height="20" width="20" /> '.AVAILABLE.'</p>
						<p><img src="images/color_code2.gif" height="20" width="20" /> '.NOT_AVAILABLE.'</p>
						<p><img src="images/color_code3.gif" height="20" width="20" /> '.ALREADY_BOOKED.'</p>
					</div>
			';
		}else{
			$room_id= '';
			$floor_id= '';
			$block_id= '';
			$hall_id= '';
			$seat_str = '';
		}
			
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
		$roomList_opt = formSelectElement($roomId, $room_id, 'room_id', 'onchange = processFunction("get_seat")');
	
		$action = 'insert';
		break;
		
	case 'save':	
		$id = $_POST['id'];
		$hall_id = $_POST['hall_id'];
		$seat_id = $_POST['seat_id'];
		$prev_seat_id = $_POST['prev_seat_id'];
		
		
		$fields = array('hall_id' => $hall_id,
						'seat_id' => $seat_id,
						'updated_by' => $cur_user_id,
						'updated_datetime' => current_date_time()
						);
						
		$where = "id = '".$id."'";
		$update_status = $dbObj->updateTableData("prebooking", $fields, $where);	
		
		if(!$update_status){
			$msg = 'Cannot Update Prebooking List';	
			$action = 'insert';
		}else{
			$fields2 = array('book' => '1',
						'updated_by' => $cur_user_id,
						'updated_datetime' => current_date_time()
						);
						
			$where2 = "id = '".$seat_id."'";
			$update_status2 = $dbObj->updateTableData("seat", $fields2, $where2);	
			if(!$update_status){
				$msg = 'Cannot Update Assigned Seat No';	
				$action = 'insert';
			}else{
				$fields3 = array('book' => '0',
						'updated_by' => $cur_user_id,
						'updated_datetime' => current_date_time()
						);
						
				$where3 = "id = '".$prev_seat_id."'";
				$update_status3 = $dbObj->updateTableData("seat", $fields3, $where3);
				if(!$update_status3){
					$msg = 'Cannot Update Previous Seat No';	
					$action = 'insert';
				}else{
					$msg = 'Seat has been Successfully Assign to Student';
					$url = 'studentlist.php?action=view&page='.$page.'&msg='.$msg;
					redirect($url);
				}//else
			}//else
		}//else
		
		break;
	
	case 'detail':	
		if($cur_user_group_id == '1'){
			$id = $_REQUEST['id'];
		}else{
			$sql = "select id from ".DB_PREFIX."prebooking WHERE user_id='".$cur_user_id."'";	
			$idArr = $dbObj->selectDataObj($sql);
			$id = $idArr[0]->id;
		}
		
		
		$sql = "select * from ".DB_PREFIX."prebooking WHERE id='".$id."'";	
		$targetArr = $dbObj->selectDataObj($sql);
		$target = $targetArr[0];
		
		$hall = getNameById("hall", $target->hall_id);
		$seat = getNameById("seat", $target->seat_id);
		$room = getNameById("room", $seat->room_id);
		$floor = getNameById("floor", $room->floor_id);
		$block = getNameById("block", $floor->block_id);
		$session = getNameById("session", $target->session);
		if($target->gender == '0'){
			$gender = NOT_SELECTED;
		}else if($target->gender == '1'){
			$gender = MALE;
		}else if($target->gender == '2'){
			$gender = FEMALE;
		}
		
		if(empty($target->s_photo)){
			$student_photo = 'images/unknown.png';
		}else{
			$student_photo = $path.$target->s_photo;
		}
		
		if(empty($target->student_signature)){
			$student_signature = NO_SIGNATURE_ATTACHED;
		}else{
			$student_signature = '<img src="'.$path.$target->student_signature.'" height="40" width="150" alt="Signature of '.$target->name.'" title="Signature of '.$target->name.'" border="0" />';
		}
		
		if(empty($target->g_photo)){
			$guardian_photo = 'images/unknown.png';
		}else{
			$guardian_photo = $path.$target->g_photo;
		}
		
		if(empty($target->g_signature)){
			$guardian_signature = NO_SIGNATURE_ATTACHED;
		}else{
			$guardian_signature = '<img src="'.$path.$target->g_signature.'" height="40" width="150" alt="Signature of '.$target->g_name.'" title="Signature of '.$target->g_name.'" border="0" />';
		}
		
		$action = 'detail';
		break;
			
	case 'delete':	
		$id = $_REQUEST['id'];
		$sql = "select * from ".DB_PREFIX."prebooking WHERE id='".$id."'";	
		$prebookingList = $dbObj->selectDataObj($sql);
		$prebooking = $prebookingList[0];
		$name = $prebooking->name;
		$seat_id = $prebooking->seat_id;
		$user_id = $prebooking->user_id;
		
		$where1 = "id='".$seat_id."'";
		$where2 = "id='".$user_id."'";
		$where3 = "id='".$id."'";
		
		$fields1 = array('book' => '0');
		$success1 = $dbObj->updateTableData("seat", $fields1, $where1);
		if(!$success1){
			$msg = 'Could not update Seat Table';
			$url = 'studentlist.php?action=view&page='.$page.'&msg='.$msg;
			redirect($url);
		}else{
			$success2 = $dbObj->deleteTableData("user", $where2);
			if(!$success2){
				$msg = 'Could not Delete data from User Table';
				$url = 'studentlist.php?action=view&page='.$page.'&msg='.$msg;
				redirect($url);
			}else{
				$success3 = $dbObj->deleteTableData("prebooking", $where3);
				if(!$success3){
					$msg = $name.COULD_NOT_BE_DELETED;
				}else{
					$msg = $name.HAS_BEEN_DELETED;
				}//else
				$url = 'studentlist.php?action=view&page='.$page.'&msg='.$msg;
				redirect($url);
			}//else
		}//else
		
		break;
		
	case 'release':
		$id = $_REQUEST['id'];
		$seat_id = $_REQUEST['seat_id'];
		$getbook = getNameById('seat',$seat_id);
		$book = $getbook->book;
		
		if($book == 1){
			$booked = 0;
		}
		$fields = array('seat_id' => $booked,
						'updated_by' => $cur_user_id,
						'updated_datetime' => current_date_time()
						);
						
		$where = "id = '".$id."'";
		$update_status = $dbObj->updateTableData("prebooking", $fields, $where);
		
		if(!$update_status){
			$msg = $getbook->name." ".COULD_NOT_BE_RELEASED;
		}else{
			$msg = $getbook->name." ".HAS_BEEN_RELEASED;
		}
		
		$fields1 = array('book' => $booked,
						'updated_by' => $cur_user_id,
						'updated_datetime' => current_date_time()
						);
						
		$where1 = "id = '".$seat_id."'";
		$update_status1 = $dbObj->updateTableData("seat", $fields1, $where1);
		
		if(!$update_status1){
			$msg = $getbook->name." ".COULD_NOT_BE_RELEASED;
		}else{
			$msg = $getbook->name." ".HAS_BEEN_RELEASED;
		}
		$url = 'studentlist.php?action=view&page='.$page.'&msg='.$msg;
		redirect($url);
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
			<td><h1><?php echo STUDENT_LIST; ?></h1></td>	
			<td class="usr_info"><?php echo welcomeMsg($cur_user_id); ?></td>			
		</tr>
	</table>
	<?php if($action=="view"){ ?>
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
				<tr>
					<td>
						<table width="100%" cellpadding="0" cellspacing="0" border="0" class="datagrid">				
							<tr class="head">
								<td height="30" width="15%"><strong><?php echo SESSION; ?></strong></td>
								<td width="10%"><strong><?php echo DEPARTMENT; ?></strong></td>
								<td width="10%"><strong><?php echo REGISTRATION_NO; ?></strong></td>
								<td width="10%"><strong><?php echo NAME; ?></strong></td>
								<td width="10%"><strong><?php echo ROOM_NO; ?></strong></td>
								<td width="5%"><strong><?php echo BOOKED; ?></strong></td>
								<td width="10%"><strong><?php echo PHOTO; ?></strong></td>
								<td width="30%" align="center"><strong><?php echo ACTION; ?></strong></td>
							</tr>
							
							
							<?php			
							if(!empty($prebookingList)){	
								$maxPageLimit = (($s+$limit) > $total_rows) ? $total_rows : ($s+$limit);
								for($rownum = $s; $rownum <$maxPageLimit; $rownum++){		
									$class = (($rownum%2)==0) ? ' class="even"' : ' class="odd"';
									$seat_name = getNameById('seat', $prebookingList[$rownum]->seat_id);
									$room_name = getNameById('room',$seat_name->room_id);
									$booked_pic = ($seat_name->book == 1) ? 'tick.png' : 'inctive.png';
									$session = getNameById('session', $prebookingList[$rownum]->session);
							?>
									<tr <?php echo $class; ?>>
										<td><?php echo $session->name; ?></td>
										<td><?php echo $prebookingList[$rownum]->department; ?></td>
										<td><?php echo $prebookingList[$rownum]->registration_no; ?></td>
										<td><?php echo $prebookingList[$rownum]->name; ?></td>	
										<td><?php echo $room_name->name; ?></td>
										<!--<td><?php //echo $seat_name->name; ?></td>-->
										<td><img src="images/<?php echo $booked_pic; ?>" /></td>
										<td>
											<?php if($prebookingList[$rownum]->s_photo == ""){?>
												<img height="50" width="60" src="images/unknown.png" title="<?php echo $prebookingList[$rownum]->name;?>" />
											<?php }else { ?>
												<a id="example4" href="<?php echo $path.$prebookingList[$rownum]->s_photo ;?>" ><img height="50" width="60" src="<?php echo $path.$prebookingList[$rownum]->s_photo ;?>"  title="<?php echo $prebookingList[$rownum]->name;?>" /></a>
											<?php } ?>
										</td>
										<td align="center">
											<a class="details" href="studentlist.php?action=detail&id=<?php echo $prebookingList[$rownum]->id; ?>&page=<?php echo $page; ?>" title="Details">&nbsp;</a>
											<?php if($seat_name->book == 1){ ?>
											<a class="release" href="studentlist.php?action=release&id=<?php echo $prebookingList[$rownum]->id; ?>&seat_id=<?php echo $prebookingList[$rownum]->seat_id; ?>&page=<?php echo $page; ?>" onclick="return confirm('Are you sure you want to release the seat?');" title="Release seat">&nbsp;</a>	
											<?php }//if?>
											<a class="asign" href="studentlist.php?action=update&id=<?php echo $prebookingList[$rownum]->id; ?>&page=<?php echo $page; ?>" title="Assign seat">&nbsp;</a>			
											<a class="edit" href="form.php?action=update&id=<?php echo $prebookingList[$rownum]->id; ?>&page=<?php echo $page; ?>" title="Edit">&nbsp;</a>
											<a class="delete" href="studentlist.php?action=delete&id=<?php echo $prebookingList[$rownum]->id; ?>&page=<?php echo $page; ?>" onclick="return confirm('Are you sure you want to delete?');" title="Delete">&nbsp;</a>
										</td>
									</tr>
								<?php 
									}//for
								}else{ ?>
								<tr height="30">
									<td colspan="5"><?php echo EMPTY_DATA; ?></td>
								</tr>
								<?php 
								}
								if($total_page > 1){ ?>
								<tr height="50">
									<td colspan="5"><?php echo pagination($total_rows,$limit,$page,''); ?></td>
								</tr>
								<?php } ?>					
						</table>
					</td>
				</tr>
			</table>
	
	<?php  }elseif($action=="insert"){ ?>
	
				<form action="studentlist.php" method="post" name="studentlist" id="studentlist" onsubmit="return validateblock();">
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
						<tr>
							<td height="30" width="15%">
								<strong><?php echo STUDENT_NAME; ?>:</strong>
							</td>
							<td height="30">
								<strong><?php echo $student->name; ?></strong>
							</td>
						</tr>
						<tr>
							<td height="30" width="15%">
								<?php echo HALL_NAME; ?>:
							</td>
							<td height="30">
								<?php echo $hallList_opt; ?>
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
								</div>
							</td>
						</tr>
						<tr>
							<td height="30">
								<?php echo FLOOR_NAME; ?>:
							</td>
							<td height="30">
								<div id="loaderContainer"></div>
								<div id="floor_display">
									<?php echo $floorList_opt; ?>
								</div>
								</td>
						</tr>
						<tr>
							<td height="30">
								<?php echo ROOM_NO; ?>:
							</td>
							<td height="30">
								<div id="loaderContainer"></div>
								<div id="room_display">
									<?php echo $roomList_opt; ?>
								</div>
							</td>
						</tr>
						<tr>
							<td height="30" colspan="2">
								<div id="loaderContainer"></div>
								<div id="seat_display">
									<?php
									if(!empty($seat_str)){
										echo $seat_str;
									}
									?>
									
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<input type="submit" name="Submit" class="button" value="Save" />
								<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><input type="button" onclick="window.location=<?php echo $_SERVER['HTTP_REFERER']; ?>"  name="cancel" class="cancel" value="<?php echo CANCEL; ?>" /></a>
							</td>
						</tr>
					</table>	
					<input type="hidden" name="id" value="<?php echo $id; ?>" />
					<input type="hidden" name="action" value="save" />
					<input type="hidden" name="prev_seat_id" value="<?php echo $student->seat_id; ?>" />
					<input type="hidden" name="page" id="page" value="<?php echo $page; ?>" />
					<input type="hidden" name="curr_seat_id" id="curr_seat_id" value="<?php echo $cur_seat_id;?>" />
				</form>
	<?php }else if($action == "detail"){ ?>
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_details">
				<tr>
					<td height="50" colspan="2"><a class="gobackup" href="<?php echo $_SERVER['HTTP_REFERER']; ?>" title="Go Back">&nbsp;</a></td>
				</tr>
				<tr class="holder">
					<td height="30" colspan="3" align="center"><h1><?php echo strtoupper(STUDENT_PROFILE); ?></h1></td>
				</tr>
				<tr class="holder topholder">
					<td width="20%" height="30"><?php echo REGISTRATION_NO; ?>:</td>
					<td width="60%"><strong><?php echo $target->registration_no; ?></strong></td>
					<td width="20%" rowspan="5" align="right"><img src="<?php echo $student_photo; ?>" height="150" width="130" alt="Image of <?php echo $target->name; ?>" title="<?php echo $target->name; ?>" border="0" /></td>
				</tr>
				<tr class="holder">
					<td height="30"><?php echo STUDENT_NAME; ?>:</td>
					<td><strong><?php echo strtoupper($target->name); ?></strong></td>
				</tr>
				<tr class="holder">
					<td height="30"><?php echo DEPARTMENT; ?>:</td>
					<td><?php echo $target->course_name; ?></td>
				</tr>
				<tr class="holder">
					<td height="30"><?php echo ROLL_NO; ?>:</td>
					<td><?php echo $target->roll_no; ?></td>
				</tr>
				<tr class="holder">
					<td height="30"><?php echo SESSION; ?>:</td>
					<td><?php echo $session->name; ?></td>
				</tr>
				<tr class="holder">
					<td height="30"><?php echo GENDER; ?>:</td>
					<td><?php echo $gender; ?></td>
				</tr>
				<tr class="holder">
					<td height="30"><?php echo EMAIL; ?>:</td>
					<td colspan="2"><?php echo $target->email; ?></td>
				</tr>
				<tr class="holder">
					<td height="30"><?php echo MOBILE; ?>:</td>
					<td colspan="2"><?php echo $target->mobile; ?></td>
				</tr>
				<tr class="holder">
					<td height="30"><?php echo FATHER_NAME; ?>:</td>
					<td colspan="2"><strong><?php echo strtoupper($target->father_name); ?></strong></td>
				</tr>
				<tr class="holder">
					<td height="30"><?php echo DESIGNATION_OFFICE_ADDRESS; ?>:</td>
					<td colspan="2"><?php echo $target->f_office_address; ?></td>
				</tr>
				<tr class="holder">
					<td height="30"><?php echo PHONE_CELL_NO; ?>:</td>
					<td colspan="2"><?php echo OFFICE.' :'.$target->f_office_phone.str_repeat('&nbsp',20).MOBILE.' :',$target->father_mobile; ?></td>
				</tr>
				<tr class="holder">
					<td height="30"><?php echo MOTHER_NAME; ?>:</td>
					<td colspan="2"><strong><?php echo strtoupper($target->mother_name); ?></strong></td>
				</tr>
				<tr class="holder">
					<td height="30"><?php echo DESIGNATION_OFFICE_ADDRESS; ?>:</td>
					<td colspan="2"><?php echo $target->m_office_address; ?></td>
				</tr>
				<tr class="holder">
					<td height="30"><?php echo PHONE_CELL_NO; ?>:</td>
					<td colspan="2"><?php echo OFFICE.' :'.$target->m_office_phone.str_repeat('&nbsp',20).MOBILE.' :',$target->mother_mobile; ?></td>
				</tr>
				<tr class="holder">
					<td height="30"><?php echo PRESENT_ADDRESS; ?>:</td>
					<td colspan="2"><?php echo $target->present_address; ?></td>
				</tr>
				<tr class="holder">
					<td height="30"><?php echo PERMANENT_ADDRESS; ?>:</td>
					<td colspan="2"><?php echo $target->address; ?></td>
				</tr>
				<tr class="holder">
					<td height="50"><?php echo STUDENT_SIGNATURE; ?>:</td>
					<td colspan="2"><?php echo $student_signature; ?></td>
				</tr>
				
				<tr class="holder">
					<td height="30" colspan="3" align="center"><h1><?php echo strtoupper(LOCAL_GUARDIAN_INFO); ?></h1></td>
				</tr>
				<tr class="holder">
					<td height="30"><?php echo GUARDIAN_NAME; ?>:</td>
					<td><strong><?php echo strtoupper($target->g_name); ?></strong></td>
					<td width="20%" rowspan="4" align="right"><img src="<?php echo $guardian_photo; ?>" height="150" width="130" alt="Image of <?php echo $target->g_name; ?>" title="<?php echo $target->g_name; ?>" border="0" /></td>
				</tr>
				<tr class="holder">
					<td height="30"><?php echo DESIGNATION_OFFICE_ADDRESS; ?>:</td>
					<td><?php echo $target->g_office_address; ?></td>
				</tr>
				<tr class="holder">
					<td height="30"><?php echo PHONE_CELL_NO; ?>:</td>
					<td><?php echo OFFICE.' :'.$target->g_office_phone.str_repeat('&nbsp',20).MOBILE.' :',$target->g_mobile; ?></td>
				</tr>
				<tr class="holder">
					<td height="50"><?php echo GUARDIAN_SIGNATURE; ?>:</td>
					<td><?php echo $guardian_signature; ?></td>
				</tr>
				<tr class="holder">
					<td height="30" colspan="3" align="center"><h1><?php echo strtoupper(HALL_ADMISSION_INFO); ?></h1></td>
				</tr>
				<tr class="holder">
					<td height="30"><?php echo HALL_NAME; ?>:</td>
					<td colspan="2"><?php echo $hall->name; ?></td>
				</tr>
				<tr class="holder">
					<td height="30"><?php echo BLOCK_NAME; ?>:</td>
					<td colspan="2"><?php echo $block->name; ?></td>
				</tr>
				<tr class="holder">
					<td height="30"><?php echo FLOOR_NAME; ?>:</td>
					<td colspan="2"><?php echo $floor->name; ?></td>
				</tr>
				<tr class="holder">
					<td height="30"><?php echo ROOM_NO; ?>:</td>
					<td colspan="2"><?php echo $room->name; ?></td>
				</tr>
				<tr class="holder">
					<td height="30"><?php echo SEAT_NO; ?>:</td>
					<td colspan="2"><?php echo $seat->name; ?></td>
				</tr>
				<tr>
					<td height="50" colspan="2"><a class="gobackup" href="<?php echo $_SERVER['HTTP_REFERER']; ?>" title="Go Back">&nbsp;</a></td>
				</tr>
			</table>		
	<?php }//else if?>
</div>
			
<?php
require_once("includes/footer.php");
?>