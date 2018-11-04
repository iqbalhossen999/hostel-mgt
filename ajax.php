<?php
require_once("includes/header.php");
//check for loggedin
$usr = $user->getUser();
if(empty($usr)){
	echo 'Not Logged In';	
	exit;
}

$cur_user_id = $usr[0]->id;
$cur_user_group_id = $usr[0]->group_id;
$action = $_REQUEST['action'];
$msg = '';
$reffer_page = http_reffer();


switch($action){
	
	case 'check_username_availability':	
		$username = $_REQUEST['username'];
			if($username == ''){
				echo "You must enter a Username.";
			}else{
				$query = "select username from ".DB_PREFIX."user where username = '".$username."'";
				$userArr = $dbObj->selectDataObj($query);
				
				if(empty($userArr)){
					echo $username." is available to create.";
				}else{
					echo $username." is already in use!";
				}//if
			}//if
	break;
	
	case 'get_product':
		$category_id = $_REQUEST['category_id'];
		
		$sql = "select id, name from ".DB_PREFIX."product WHERE category_id = '".$category_id."'";
		$productArr = $dbObj->selectDataObj($sql);
		$productId = array();
		$productId[0] = SELECT_PRODUCT_OPT;
		if(!empty($productArr)){			
			foreach($productArr as $item){
				$productId[$item->id] = $item->name;
			}	
		}
		$productList_opt = formSelectElement($productId, $product_id, 'product_id');
		
		echo $productList_opt;
	break;
	
	case 'get_year':
		//Build Year Array
		$yearArr = array();
		$yearArr[0] = SELECT_YEAR_OPT;
		for($i = 2012; $i <= date('Y'); $i++){
			$yearArr[$i] = $i;
		}
		$yearList_opt = formSelectElement($yearArr, $year, 'year', 'onchange = processFunction("get_setting")');
		
		echo $yearList_opt;
	break;
	
	case 'get_hall':
		$group_id=$_REQUEST['group_id'];
		
		if($group_id > '1'){
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
			
			$str = '<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td height="30" width="20%">'.SELECT_HALL_OPT.'</td>
							<td width="80%">'.$hallList_opt.'</td>
						</tr>
					</table>';
			
			echo $str;
		}
	break;
	
	case 'get_block':	
		$hall_id = $_REQUEST['hall_id'];
		
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
		if($reffer_page == 'floor.php'){
			$blockList_opt = formSelectElement($blockId, $block_id, 'block_id');
		}else{
			$blockList_opt = formSelectElement($blockId, $block_id, 'block_id', 'onchange = processFunction("get_floor")');	
		}
		
		echo $blockList_opt.'<span class="required_field"> *</span>';
		
	break;
	
	case 'get_floor':	
		$block_id = $_REQUEST['block_id'];
		
		//Build Floor Array
		$sql = "select id, name from ".DB_PREFIX."floor  WHERE block_id = '".$block_id."' order by name asc";
		$floorArr = $dbObj->selectDataObj($sql);
		$floorId = array();
		$floorId[0] = SELECT_FLOOR_OPT;
		if(!empty($floorArr)){			
			foreach($floorArr as $item){
				$floorId[$item->id] = $item->name;
			}	
		}
		$floorList_opt = formSelectElement($floorId, $floor_id, 'floor_id', 'onchange = processFunction("get_room")');
		echo $floorList_opt.'<span class="required_field"> *</span>';
		
	break;
	
	case 'get_room':	
		$floor_id = $_REQUEST['floor_id'];
		
		//Build Room Array
		$sql = "select id, name from ".DB_PREFIX."room  WHERE floor_id = '".$floor_id."' order by name asc";
		$roomArr = $dbObj->selectDataObj($sql);
		$roomId = array();
		$roomId[0] = SELECT_ROOM_OPT;
		if(!empty($roomArr)){			
			foreach($roomArr as $item){
				$roomId[$item->id] = $item->name;
			}	
		}			
		$roomList_opt = formSelectElement($roomId, $room_id, 'room_id', 'onchange = processFunction("get_seat")');
		echo $roomList_opt.'<span class="required_field"> *</span>';
		
	break;
	
	case 'get_seat':	
		$room_id = $_REQUEST['room_id'];
		$curr_seat_id = $_REQUEST['curr_seat_id'];
		
		//Build seat Array
		$sql = "select id, name, book from ".DB_PREFIX."seat WHERE room_id = '".$room_id."' order by name asc";
		$seatArr = $dbObj->selectDataObj($sql);
		//echo '<pre>';print_r($seatArr);exit;
		if(!empty($seatArr)){
			$str = '<div id="seatcontainer">';
							$total_seat = 0;
							foreach($seatArr as $seat){	
								if($curr_seat_id == $seat->id){
									$str .=	'<div name="booked" class="exist_book" id= "'.$seat->id.'"><p class="seat_name">'.$seat->name.'</p></div>';
								}elseif($seat->book == '0'){
									$str .=	'<div name="notbooked" class="seatno_notbooked" id= "'.$seat->id.'" onclick="assignSeat('.$seat->id.')"><p class="seat_name">'.$seat->name.'</p></div>';
									$total_seat++;
								}else{
									$str .=	'<div class="seatno_booked"><p class="seat_name">'.$seat->name.'</p></div>';
								}
							}//foreach
						$str .= '
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
			$str = '
				<div id="seatcontainer">
					<table width="100%" cellspacing="20" cellpadding="0" border="0">
						<tr>
							<td colspan="2">'.NO_SEAT_FOUND.'</td>
						</tr>
					</table>
				</div>';
		}
		echo $str;
		
	break;
	
	case 'get_stock':
	
		$hall_id = $_REQUEST['hall_id'];
		$issue_date = $_REQUEST['issue_date'];
	
		$sql2 = "select id as last_id from ".DB_PREFIX."product order by id desc limit 1";
		$p_id =  $dbObj->selectDataObj($sql2);
		$limit = $p_id [0]->last_id;
		
		$sql = "select p.id, p.name as p_name, pc.name as pc_name, u.name as u_name from ".DB_PREFIX."product as p, ".DB_PREFIX."product_category as pc, ".DB_PREFIX."unit as u where p.category_id = pc.id AND pc.unit_id = u.id order by p.id asc";
		$productArr = $dbObj->selectDataObj($sql);
		
		if(empty($hall_id)){
			$str = '<br /><br />
					<table width="100%" cellspacing="0" cellpadding="0" border="0" class="datagrid">
						<tr class="head">
							<td colspan="2"><strong>'.PLEASE_SELECT_YOUR_HALL.'</strong></td>
						</tr>
					</table>';
		}else if(empty($issue_date)){
			$str = '<br /><br />
					<table width="100%" cellspacing="0" cellpadding="0" border="0" class="datagrid">
						<tr class="head">
							<td colspan="2"><strong>'.PLEASE_SELECT_ISSUE_DATE.'</srtong></td>
						</tr>
					</table>';
		}else if(empty($productArr)){
			$str = '<br /><br />
					<table width="100%" cellspacing="0" cellpadding="0" border="0" class="datagrid">
						<tr class="head">
							<td colspan="2"><strong>'.NO_PRODUCT_FOUND.'</srtong></td>
						</tr>
					</table>';
		}else{
			$str = '<table width="100%" cellpadding="0" cellspacing="0" border="0" class="datagrid">
						<tr class="head">
							<td height="30" width="20%"><strong>'.PRODUCT_NAME.'</strong></td>
							<td width="20%"><strong>'.CATEGORY_NAME.'</strong></td>
							<td width="20%"><strong>'.QUANTITY.'</strong></td>
							<td width="20%"><strong>'.UNIT.'</strong></td>
							<td width="20%"><strong>'.PRICE.'</strong></td>
							<td width="20%"><strong>'.UNIT_PRICE.'</strong></td>
							<td width="20%"><strong>'.TOTAL_QUANTITY.'</strong></td>
							<td width="10%"><strong>'.AVG_PRICE.'</strong></td>
							<td width="10%"><strong>'.TOTAL_PRICE.'</strong></td>
						</tr>';
						
							if(!empty($productArr)){
								$in_total = 0;
								$field_ids = '';
								foreach($productArr as $product){
									$sql = "select product_balance, avg_price, total_price from ".DB_PREFIX."balance where hall_id = '".$hall_id."' AND product_id = '".$product->id."'";
									$pinfArr = $dbObj->selectDataObj($sql);
									$total_price = empty($pinfArr[0]->total_price) ? '0' : $pinfArr[0]->total_price;
									$in_total +=$total_price;			
									$field_ids .= $product->id.',';
								}
							
								$rownum = $net_total = 0;
								foreach($productArr as $product){
									$class = (($rownum%2)==0) ? ' class="even"' : ' class="odd"';
									$sql = "select product_balance, avg_price, total_price from ".DB_PREFIX."balance where hall_id = '".$hall_id."' AND product_id = '".$product->id."'";
									$pinfArr = $dbObj->selectDataObj($sql);
									$total_price = empty($pinfArr[0]->total_price) ? '0' : $pinfArr[0]->total_price;
									$balance = empty($pinfArr[0]->product_balance) ? '0' : $pinfArr[0]->product_balance;
									$avg_price = empty($pinfArr[0]->avg_price) ? '0' : $pinfArr[0]->avg_price;
									$total_price = empty($pinfArr[0]->total_price) ? '0' : $pinfArr[0]->total_price;
									$net_total +=$total_price;
								
					$str .=	'<tr '.$class.'>
								<td height="30"><input type="checkbox" name="product_id[]" id="product_'.$product->id.'" value="'.$product->id.'" onclick="enable_input('.$product->id.');" /> '.$product->p_name.'</td>
								<td>'.$product->pc_name.'</td>
								<td><input type="text" name="qty_'.$product->id.'" id="qty_'.$product->id.'" class="inputbox input_right" alt="Quantity" size="6" readonly="readonly" value="'.$qty.'" onkeyup="getTotalPrice(\'qty_'.$product->id.'\', \''.$product->id.'\', \''.$balance.'\', \''.$avg_price.'\', \''.$total_price.'\', \''.$in_total.'\', \''.$field_ids.'\', \'qty_'.$product->id.'\');" maxlength="4" /></td>
								<td>'.$product->u_name.'</td>
								<td><input type="text" name="total_'.$product->id.'" id="total_'.$product->id.'" class="inputbox input_right" alt="Total Price" size="6" readonly="readonly" value="'.$total.'" onkeyup="getTotalPrice(\'qty_'.$product->id.'\', \''.$product->id.'\', \''.$balance.'\', \''.$avg_price.'\', \''.$total_price.'\', \''.$in_total.'\', \''.$field_ids.'\', \'total_'.$product->id.'\');" /></td>
								<td><input type="text" name="unit_price_'.$product->id.'" id="unit_price_'.$product->id.'" class="inputbox input_right"" alt="Unit Price" size="6"  readonly="readonly" value="'.$unit_price.'" maxlength="7" /></td>
								<td><input type="text" name="balance_'.$product->id.'" id="balance_'.$product->id.'" class="inputbox input_right"" alt="Available Balance" size="6"  readonly="readonly" value="'.$balance.'" /></td>
								<td><input type="text" name="avg_price_'.$product->id.'" id="avg_price_'.$product->id.'" class="inputbox input_right" alt="Average Price" size="6"  readonly="readonly" value="'.$avg_price.'" /></td>
								<td><input type="text" name="total_price_'.$product->id.'" id="total_price_'.$product->id.'" class="inputbox input_right" alt="Total Price" size="6"  readonly="readonly" value="'.$total_price.'" /></td>
							</tr>';
								
									$rownum++;
								}//foreach
					$str .=	'<tr>
								<td colspan="9">
									<input type="submit" name="Submit" class="button" value="Save" />
									<a href='.$_SERVER['HTTP_REFERER'].'><input type="button" onclick="window.location='.$_SERVER['HTTP_REFERER'].'  name="cancel" class="button" value='.CANCEL.' /></a>
									<div style="float:right; margin-top:5px; margin-left:5px;"><b>'.NET_TOTAL_PRICE.': </b><input type="text" name="net_total" id="net_total" class="inputbox input_right" value="'.number_format($net_total,2).'" readonly="readonly" size= "8"/></div>
									
								</td>
							</tr>		
					</table>';
		}
		echo $str;
	}	
	break;
	
	case 'get_consume':
	
		$hall_id = $_REQUEST['hall_id'];
		$issue_date = $_REQUEST['issue_date'];
		
		$sql = "select p.id, p.name as p_name, pc.name as pc_name, u.name as u_name from ".DB_PREFIX."product as p, ".DB_PREFIX."product_category as pc, ".DB_PREFIX."unit as u where p.category_id = pc.id AND pc.unit_id = u.id order by p.name asc";
		$productArr = $dbObj->selectDataObj($sql);
		
		if(empty($hall_id)){
			$str = '<br /><br />
					<table width="100%" cellspacing="0" cellpadding="0" border="0" class="datagrid">
						<tr class="head">
							<td colspan="2"><strong>'.PLEASE_SELECT_YOUR_HALL.'</strong></td>
						</tr>
					</table>';
		}else if(empty($issue_date)){
			$str = '<br /><br />
					<table width="100%" cellspacing="0" cellpadding="0" border="0" class="datagrid">
						<tr class="head">
							<td colspan="2"><strong>'.PLEASE_SELECT_ISSUE_DATE.'</srtong></td>
						</tr>
					</table>';
		}else if(empty($productArr)){
			$str = '<br /><br />
					<table width="100%" cellspacing="0" cellpadding="0" border="0" class="datagrid">
						<tr class="head">
							<td colspan="2"><strong>'.NO_PRODUCT_FOUND.'</srtong></td>
						</tr>
					</table>';
		}else{
			$str = '<table width="100%" cellpadding="0" cellspacing="0" border="0" class="datagrid">
						<tr class="head">
							<td height="30" width="20%"><strong>'.PRODUCT_NAME.'</strong></td>
							<td width="20%"><strong>'.CATEGORY.'</strong></td>
							<td width="20%"><strong>'.QUANTITY.'</strong></td>
							<td width="20%"><strong>'.UNIT.'</strong></td>
							<td width="20%"><strong>'.UNIT_PRICE.'</strong></td>
							<td width="10%"><strong>'.TOTAL_QUANTITY.'</strong></td>
							<td width="10%"><strong>'.TOTAL_PRICE.'</strong></td>
							
						</tr>';
						
							if(!empty($productArr)){
								$rownum = $in_total = 0;
								$field_ids = '';
								foreach($productArr as $product){
									$sql = "select product_balance, avg_price, total_price from ".DB_PREFIX."balance where hall_id = '".$hall_id."' AND product_id = '".$product->id."'";
									$pinfArr = $dbObj->selectDataObj($sql);
									$balance = empty($pinfArr[0]->product_balance) ? '0' : $pinfArr[0]->product_balance;
									$avg_price = empty($pinfArr[0]->avg_price) ? '0' : $pinfArr[0]->avg_price;
									$ttl_price = view_number($balance * $avg_price);
									$in_total += $ttl_price;
									$field_ids .= $product->id.',';
								}
								
								foreach($productArr as $product){
									$class = (($rownum%2)==0) ? ' class="even"' : ' class="odd"';	
									$sql = "select product_balance, avg_price from ".DB_PREFIX."balance where hall_id = '".$hall_id."' AND product_id = '".$product->id."'";
									$pinfArr = $dbObj->selectDataObj($sql);
									$balance = empty($pinfArr[0]->product_balance) ? '0' : $pinfArr[0]->product_balance;
									$avg_price = empty($pinfArr[0]->avg_price) ? '0' : $pinfArr[0]->avg_price;
									$ttl_price = view_number($balance * $avg_price);
								
					$str .=	'<tr '.$class.'>
								<td height="30"><input type="checkbox" name="product_id[]" id="product_'.$product->id.'" value="'.$product->id.'" onclick="enable_input('.$product->id.');" /> <span id="productt_'.$product->id.'">'.$product->p_name.'</span></td>
								<td>'.$product->pc_name.'</td>
								<td><input type="text" name="qty_'.$product->id.'" id="qty_'.$product->id.'" class="inputbox input_right" alt="Quantity" size="6" readonly="readonly" value="'. $qty.'" onkeyup="getConsumePrice(\'qty_'.$product->id.'\', \''.$product->id.'\', \''.$balance.'\', \''.$field_ids.'\', \''.$ttl_price.'\', \''.$in_total.'\');" maxlength="4" /></td>
								<td id="unit_'.$product->id.'">'.$product->u_name.'</td>
								<td><input type="text" name="unit_price_'.$product->id.'" id="unit_price_'.$product->id.'" class="inputbox input_right" alt="Unit Price" size="6"  readonly="readonly" value="'.$avg_price.'" maxlength="7" /></td>
								<td><input type="text" name="balance_'.$product->id.'" id="balance_'.$product->id.'" class="inputbox input_right" alt="Unit Price" size="6"  readonly="readonly" value="'.$balance.'" maxlength="7" /></td>
								<td><input type="text" name="total_price_'.$product->id.'" id="total_price_'.$product->id.'" class="inputbox input_right" alt="Total Price" size="6"  readonly="readonly" value="'.$ttl_price.'" maxlength="7" /></td>
							  </tr>';
							  
									$rownum++;
								}//foreach
							}//if
					$str .=	'<tr>
								<td colspan="7">
									<input type="submit" name="Submit" class="button" value="Save" />
									<a href='.$_SERVER['HTTP_REFERER'].'><input type="button" onclick="window.location='.$_SERVER['HTTP_REFERER'].'  name="cancel" class="button" value='.CANCEL.' /></a>
									<div style="float:right; margin-top:5px;" ><b>'.NET_TOTAL_PRICE.': </b><input type="text" name="net_total" id="net_total" class="inputbox input_right" value="'.view_number($in_total).'" readonly="readonly" size= "8"/></div>
								</td>
							</tr>		
					</table>';
		}
		echo $str;
	break;
	
	case 'get_available_stock':
		$hall_id = $_REQUEST['hall_id'];
		
		if(empty($hall_id)){
			$str = '<br /><br />
					<table width="100%" cellspacing="0" cellpadding="0" border="0" class="datagrid">
						<tr class="head">
							<td colspan="2"><strong>'.PLEASE_SELECT_YOUR_HALL.'</strong></td>
						</tr>
					</table>';
		}else{
			$str = '<table width="100%" cellpadding="0" cellspacing="0" border="0" class="datagrid">			
						<tr class="head">
							<td height="30" width="20%"><strong>'.PRODUCT_NAME.'</strong></td>
							<td width="20%" align="right"><strong>'.TOTAL_BALANCE.'</strong></td>	
							<td width="20%" align="right"><strong>'.UNIT.'</strong></td>					
							<td width="20%" align="right"><strong>'.UNIT_PRICE.'</strong></td>
							<td width="20%" align="right"><strong>'.TOTAL_PRICE.'</strong></td>
						</tr>';
						
						
						
							$sql = "select p.id, p.name, u.name as u_name from ".DB_PREFIX."product as p, ".DB_PREFIX."product_category as pc, ".DB_PREFIX."unit as u where p.category_id = pc.id AND pc.unit_id = u.id order by p.name asc";
								$productList = $dbObj->selectDataObj($sql);
								if(!empty($productList)){
								$rownum = 0;	
								foreach($productList as $product){
									$class = (($rownum%2)==0) ? ' class="even"' : ' class="odd"';
									$sql2 = "select avg_price, total_price, product_balance from ".DB_PREFIX."balance where product_id = '".$product->id."' AND hall_id = ".$hall_id."";
									$balanceArr = $dbObj->selectDataObj($sql2);
									$balance = $balanceArr[0]->product_balance;
									$unit_price = view_number($balanceArr[0]->avg_price);
									$total_price = view_number($balanceArr[0]->total_price);
								if(empty($balance)){$balance = '0';}
								
					
				$str .=	'<tr '.$class.'>
							<td height="30">'.$product->name.'</td>
							<td align="right">'.view_number($balance).'</td>
							<td align="right">'.$product->u_name.'</td>
							<td align="right">'.$unit_price.'</td>
							<td align="right">'.$total_price.'</td>
						</tr>';
						 $in_total +=$total_price;
						$rownum++;
								 }//foreach
							 }//if 
								
						else{
				$srr .= '<tr>
							<td colspan="5">'.NO_PRODUCT_FOUND.'</td>
						</tr>
						';
			
						}//if			
				$str .=	'</table>';
				
				$str .=	'<br />';
				$str .=	'<div style="width:200; height:30px; padding:5px 5px 0px 0px;" align="right">';
				$str .=	'<b>'.NET_TOTAL_PRICE.':&nbsp;&nbsp;&nbsp;</b>'.view_number($in_total).'</div>';
		}
		echo $str;
	break;
	
	case 'check_student_order':
		$student_id = $cur_user_id;
		$issue_date = $_REQUEST['issue_date'];
   		$end_date = $_REQUEST['end_date'];
		
		if(empty($end_date) && !empty($issue_date)){
			$end_date = $issue_date;
		}
		
		if(empty($issue_date) && !empty($end_date)){
			$issue_date = $end_date;
		}
		
		$days = date_difference($issue_date, $end_date);

		$explode = explode('-', $issue_date);
		$start_day = $explode[2];
		$start_month = $explode[1];
		$start_year = $explode[0];
		//Check for if Issuing date is smaller then Current Date
		
		$current_date = current_date();

		if(($issue_date < $current_date) or ($end_date < $current_date)){
		 	$str = '<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr><td height="30">&nbsp;</td></tr>
						<tr>
							<td height="30" class="nodfound">'.ORDER_DATE_CAN_NOT_BE_SMALLER_THAN_CURRENT_DATE.'</td>
						</tr>
					</table>';
			echo $str;
		}else if(($issue_date > $end_date) or ($issue_date < $current_date)){
			$str = '<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tr><td height="30">&nbsp;</td></tr>
						<tr>
							<td height="30" class="nodfound">'.SMALLER_ORDER_DATE_COND.'</td>
						</tr>
					</table>';
			echo $str;
		}else{
			$sql = "SELECT status FROM ".DB_PREFIX."guest_meal";
			$g_meal = $dbObj->selectDataObj($sql);
			$g_status = $g_meal[0]->status;
		?>
				<table width="100%" cellpadding="0" cellspacing="0" border="0" class="datagrid">			
				<tr class="head">
					<td height="30" width="20%"><strong><?php echo ISSUE_DATE; ?></strong></td>
					<td width="20%" align="center"><strong><?php echo BREAKFAST; ?></strong></td>
					<td width="20%" align="center"><strong><?php echo LUNCH; ?></strong></td>
					<td width="20%" align="center"><strong><?php echo DINNER; ?></strong></td>			
				</tr>
				
				
				<?php
					$counter = 0;
					$per_day_sec = 0;
					$total_breakfast = $total_lunch = $total_dinner = $total_order = 0;
					for($i = 0; $i <= $days; $i++){		
						$class = (($i%2)==0) ? ' class="even"' : 'class="odd"';
						$counter++;						
						//Find out Date
						$ini_day_microtime = mktime(0, 0, 0, $start_month, $start_day, $start_year);
						$day_microtime = $ini_day_microtime + $per_day_sec; //24 Hours, 60 Minutes, 60 Seconds
						$target_date = get_date_from_sec($day_microtime);
						$per_day_sec += (24 * 60 * 60);
						$sql = "SELECT * FROM ".DB_PREFIX."meal WHERE student_id = '".$cur_user_id."' AND order_date = '".$target_date."'";
						$meal_hist = $dbObj->selectDataObj($sql);
						$active_bf = intval($meal_hist[0]->breakfast);
						$active_ln = intval($meal_hist[0]->lunch);
						$active_dn = intval($meal_hist[0]->dinner);
						
						$bf_disabled = $ln_disabled = $dn_disabled = $disabled_bf_input = $disabled_ln_input = $disabled_dn_input = '';
						$sql = "SELECT * FROM ".DB_PREFIX."time";
						$timeArr = $dbObj->selectDataObj($sql);
						
						$cur_date_time = current_date_time();
						$cur_date_time_exp = explode(' ', $cur_date_time);
						$this_time = $cur_date_time_exp[1];
						
						$sql = "ELECT DATE_ADD('".current_date()."', INTERVAL 1 DAY) as nextday";
						$ndArr = $dbObj->selectDataObj($sql);
						$next_day = $ndArr[0]->nextday;
						
						//Find if a student can order BF at this time
						$sql = "SELECT DATE_SUB('".$target_date.' '.$timeArr[0]->breakfast."', INTERVAL ".$timeArr[0]->bf_hour." HOUR) as bf";
						$bfArr = $dbObj->selectDataObj($sql);
						$bf_time = $bfArr[0]->bf;
						
						$bf_ordering_time = $target_date.' '.$this_time;
						
						if((current_date_time() > $bf_time)){
							$disabled_bf_input = '<input type="hidden" name="dis_bf_'.$target_date.'" value="disable" />';
							$bf_disabled = ' disabled="disabled" ';
							$bf_readonly = ' readonly="readonly" ';
						}
						
						//Find if a student can order Lunch at this time
						$sql = "SELECT DATE_SUB('".$target_date.' '.$timeArr[0]->lunch."', INTERVAL ".$timeArr[0]->ln_hour." HOUR) as ln";
						$lnArr = $dbObj->selectDataObj($sql);
						$ln_time = $lnArr[0]->ln;
						
						$ln_ordering_time = $target_date.' '.$this_time;
						
						if((current_date_time() > $ln_time) && ($target_date == current_date())){
							$disabled_ln_input = '<input type="hidden" name="dis_ln_'.$target_date.'" value="disable" />';
							$ln_disabled = ' disabled="disabled" ';
							$ln_readonly = ' readonly="readonly" ';
						}
						
						//Find if a student can order Dinner at this time
						$sql = "SELECT DATE_SUB('".$target_date.' '.$timeArr[0]->dinner."', INTERVAL ".$timeArr[0]->dn_hour." HOUR) as dn";
						$dnArr = $dbObj->selectDataObj($sql);
						$dn_time = $dnArr[0]->dn;
						
						$dn_ordering_time = $target_date.' '.$this_time;
						
						if((current_date_time() > $dn_time) && ($target_date == current_date())){
							$disabled_dn_input = '<input type="hidden" name="dis_dn_'.$target_date.'" value="disable" />';
							$dn_disabled = ' disabled="disabled" ';
							$dn_readonly = ' readonly="readonly" ';
						}
							
				?>
				<tr <?php echo $class; ?>>
					<input type="hidden" name="single_date['<?php echo $target_date; ?>']" value="<?php echo $target_date; ?>" />
					<td height="25"><?php echo $target_date; ?></td>
					<td align="center"><input type="checkbox" <?php if($active_bf != '0'){ echo ' checked="checked" ';} ?> <?php echo $bf_disabled; ?> onclick="active_cell(meal_break_<?php echo $counter ?>)" name="break_<?php echo $target_date ?>" > <?php if($g_status == '1'){ ?> <input type="text" size="2" style="text-align:center" name="multi_break_<?php echo $target_date ?>" id="meal_break_<?php echo $counter ?>" <?php if($active_bf == '0'){ echo ' readonly="readonly"';} ?> value="<?php if(!empty($active_bf)){echo $active_bf;} ?>" <?php echo $bf_readonly; ?> onkeyup=" isNUM('meal_break_<?php echo $counter ?>')"> <?php }// ?></td>
					<td align="center"><input type="checkbox" <?php if($active_ln != '0'){ echo ' checked="checked" ';} ?> <?php echo $ln_disabled; ?> onclick="active_cell(meal_lunch_<?php echo $counter ?>)" name="lunch_<?php echo $target_date ?>"> <?php if($g_status == '1'){ ?> <input type="text"  size="2" style="text-align:center" name="multi_lunch_<?php echo $target_date ?>" id="meal_lunch_<?php echo $counter ?>" <?php if($active_ln == '0'){ echo ' readonly="readonly"';} ?> value="<?php if(!empty($active_ln)){echo $active_ln;} ?>" <?php echo $ln_readonly; ?> onkeyup="isNUM('meal_lunch_<?php echo $counter ?>')"> <?php }// ?></td>
					<td align="center"><input type="checkbox" <?php if($active_dn != '0'){ echo ' checked="checked" ';} ?> <?php echo $dn_disabled; ?> onclick="active_cell(meal_dinner_<?php echo $counter ?>)" name="dinner_<?php echo $target_date ?>"> <?php if($g_status == '1'){ ?> <input type="text" size="2" style="text-align:center" name="multi_dinner_<?php echo $target_date ?>" id="meal_dinner_<?php echo $counter ?>" <?php if($active_dn == '0'){ echo ' readonly="readonly"';} ?>  value="<?php if(!empty($active_dn)){echo $active_dn;} ?>" <?php echo $dn_readonly; ?>onkeyup="isNUM('meal_dinner_<?php echo $counter ?>')"> <?php }// ?></td>
					<?php echo $disabled_bf_input.$disabled_ln_input.$disabled_dn_input; ?>
				</tr>
				<?php 
					}//foreach
				?>
				
				<tr>
					<td>
						<input type="hidden" name="g_status" value="<?php echo $g_status; ?>"/>
						<input type="submit" name="order" value="Order Now"/>
					</td>
				</tr>
			</table>
			<?php
			
		}
		
		//echo $str;
		
		break;
		
	case 'get_setting':
		
		$hall_id = $_REQUEST['hall_id'];
		$year = $_REQUEST['year'];
		
		if(!empty($hall_id)){
			$sql = "select * from ".DB_PREFIX."hall_charge WHERE hall_id='".$hall_id."' AND year = '".$year."'";	
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
			$total = $estab+$readm+$sd+$messad+$donation+$seatrent+$utencro+$maint+$crnpape+$inter+$conti;
		}//if
		if(!empty($year)){	
		$str = '
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="datagrid">
				<tr class="odd">
					<td width="20%" height="20">'.ESTAB.'</td>
					<td width="80%"><input type="text" name="estab" id="estab"  class="inputbox5" alt="estab" size="8" value="'.$estab.'" onkeyup="hallcharge(\'estab\')" /> '.TK.'</td>
				</tr>
				<tr class="even">
					<td height="20">'.RE_ADM.'</td>
					<td><input name="readm" id="readm" type="text" class="inputbox5" alt="Re-Adm" size="8" value="'.$readm.'" onkeyup="hallcharge(\'readm\')" /> '.TK.'</td>
				</tr>
				<tr class="odd">
					<td height="20">'.SD.'</td>
					<td><input name="sd" id="sd" type="text" class="inputbox5" alt="SD" size="8" value="'.$sd.'" onkeyup="hallcharge(\'sd\')" /> '.TK.'</td>
				</tr>
				<tr class="even">
					<td height="20">'.MESS_AD.'</td>
					<td><input name="messad" id="messad" type="text" class="inputbox5" alt="Mess Ad" size="8" value="'.$messad.'" onkeyup="hallcharge(\'messad\')" /> '.TK.'</td>
				</tr>
				<tr class="odd">
					<td height="20">'.DONATION.'</td>
					<td><input name="donation" id="donation" type="text" class="inputbox5" alt="Donation" size="8" value="'.$donation.'" onkeyup="hallcharge(\'donation\')" /> '.TK.'</td>
				</tr>
				<tr class="even">
					<td height="20">'.SEAT_RENT.'</td>
					<td><input name="seatrent" id="seatrent" type="text" class="inputbox5" alt="Seat Rent" size="8" value="'.$seatrent.'" onkeyup="hallcharge(\'seatrent\')" /> '.TK.'</td>
				</tr>
				<tr class="odd">
					<td height="20">'.UTEN_CRO.'</td>
					<td><input name="utencro" id="utencro" type="text" class="inputbox5" alt="Uten.&Cro" size="8" value="'.$utencro.'" onkeyup="hallcharge(\'utencro\')" /> '.TK.'</td>
				</tr>
				<tr class="even">
					<td height="20">'.MAINT.'</td>
					<td><input name="maint" id="maint" type="text" class="inputbox5" alt="Maint" size="8" value="'.$maint.'" onkeyup="hallcharge(\'maint\')" /> '.TK.'</td>
				</tr>
				<tr class="odd">
					<td height="20">'.CRNPAPE.'</td>
					<td><input name="crnpape" id="crnpape" type="text" class="inputbox5" alt="C.R/N.Pape" size="8" value="'.$crnpape.'" onkeyup="hallcharge(\'crnpape\')" /> '.TK.'</td>
				</tr>
				<tr class="even">
					<td height="20">'.INTER.'</td>
					<td><input name="inter" id="inter" type="text" class="inputbox5" alt="Inter" size="8" value="'.$inter.'" onkeyup="hallcharge(\'inter\')" /> '.TK.'</td>
				</tr>
				<tr class="odd">
					<td height="20">'.CONTI.'</td>
					<td><input name="conti" id="conti" type="text" class="inputbox5" alt="Conti" size="8" value="'.$conti.'" onkeyup="hallcharge(\'conti\')" /> '.TK.'</td>
				</tr>
				<tr class="even">
					<td height="20">'.TOTAL.'</td>
					<td><input name="total" id="total" type="text" class="inputbox5" alt="Total" size="8" readOnly="readOnly" value="'.number_format($total,2).'" /> '.TK.'</td>
				</tr>
				<tr class="odd">
					<td colspan="2" height="50">
						<input type="submit" name="Submit" class="button" value="Save" />
						<a href="'.$_SERVER['HTTP_REFERER'].'"><input type="button" onclick="window.location='.$_SERVER['HTTP_REFERER'].'"  name="cancel" class="cancel" value="'.CANCEL.'" /></a>
					</td>
				</tr>
			</table>';
			}//if
		if(!empty($hall_id)){echo $str;}
		break;
		
		case 'get_seat_setting':
		
		$seat_id = $_REQUEST['seat_id'];
		$hall_id = $_REQUEST['hall_id'];
		$year = $_REQUEST['year'];
		
		if(!empty($seat_id)){
			$sql = "select * from ".DB_PREFIX."seat_charge WHERE hall_id='".$hall_id."' AND year = '".$year."' AND seat_id = '".$seat_id."'";
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
		}
			
		if(!empty($year)){
		$str = '
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_details no_padding">
				<tr class="holder">
					<td width="20%" height="30">'.ESTAB.'</td>
					<td width="80%"><input type="text" name="estab" id="estab"  class="inputbox5" alt="estab" size="8" value="'.$estab.'" onkeyup="isNUM(\'estab\')" /> '.TK.'</td>
				</tr>
				<tr class="holder">
					<td height="30">'.RE_ADM.'</td>
					<td><input name="readm" id="readm" type="text" class="inputbox5" alt="Re-Adm" size="8" value="'.$readm.'" onkeyup="isNUM(\'readm\')" /> '.TK.'</td>
				</tr>
				<tr class="holder">
					<td height="30">'.SD.'</td>
					<td><input name="sd" id="sd" type="text" class="inputbox5" alt="SD" size="8" value="'.$sd.'" onkeyup="isNUM(\'sd\')" /> '.TK.'</td>
				</tr>
				<tr class="holder">
					<td height="30">'.MESS_AD.'</td>
					<td><input name="messad" id="messad" type="text" class="inputbox5" alt="Mess Ad" size="8" value="'.$messad.'" onkeyup="isNUM(\'messad\')" /> '.TK.'</td>
				</tr>
				<tr class="holder">
					<td height="30">'.DONATION.'</td>
					<td><input name="donation" id="donation" type="text" class="inputbox5" alt="Donation" size="8" value="'.$donation.'" onkeyup="isNUM(\'donation\')" /> '.TK.'</td>
				</tr>
				<tr class="holder">
					<td height="30">'.SEAT_RENT.'</td>
					<td><input name="seatrent" id="seatrent" type="text" class="inputbox5" alt="Seat Rent" size="8" value="'.$seatrent.'" onkeyup="isNUM(\'seatrent\')" /> '.TK.'</td>
				</tr>
				<tr class="holder">
					<td height="30">'.UTEN_CRO.'</td>
					<td><input name="utencro" id="utencro" type="text" class="inputbox5" alt="Uten.&Cro" size="8" value="'.$utencro.'" onkeyup="isNUM(\'utencro\')" /> '.TK.'</td>
				</tr>
				<tr class="holder">
					<td height="30">'.MAINT.'</td>
					<td><input name="maint" id="maint" type="text" class="inputbox5" alt="Maint" size="8" value="'.$maint.'" onkeyup="isNUM(\'maint\')" /> '.TK.'</td>
				</tr>
				<tr class="holder">
					<td height="30">'.CRNPAPE.'</td>
					<td><input name="crnpape" id="crnpape" type="text" class="inputbox5" alt="C.R/N.Pape" size="8" value="'.$crnpape.'" onkeyup="isNUM(\'crnpape\')" /> '.TK.'</td>
				</tr>
				<tr class="holder">
					<td height="30">'.INTER.'</td>
					<td><input name="inter" id="inter" type="text" class="inputbox5" alt="Inter" size="8" value="'.$inter.'" onkeyup="isNUM(\'inter\')" /> '.TK.'</td>
				</tr>
				<tr class="holder">
					<td height="30">'.CONTI.'</td>
					<td><input name="conti" id="conti" type="text" class="inputbox5" alt="Conti" size="8" value="'.$conti.'" onkeyup="isNUM(\'conti\')" /> '.TK.'</td>
				</tr>
				<tr>
					<td colspan="2" height="50">
						<input type="submit" name="Submit" class="button" value="Save" />
						<a href="'.$_SERVER['HTTP_REFERER'].'"><input type="button" onclick="window.location='.$_SERVER['HTTP_REFERER'].'"  name="cancel" class="cancel" value="'.CANCEL.'" /></a>
					</td>
				</tr>
			</table>';
		}else{
			$str = '
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="datagrid">
				<tr>
					<td height="30"></td>
				</tr>
				<tr class="head">
					<td height="30"><strong>'.PLEASE_SELECT_A_YEAR.'</strong></td>
				</tr>
			</table>';	
		}
		
			
		echo $str;
		break;
	
	default:
		echo DATA_NOT_FOUND;
	break;
}//switch

