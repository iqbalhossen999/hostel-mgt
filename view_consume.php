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

$start_date = $_REQUEST['start_date'];
$end_date = $_REQUEST['end_date'];

switch($action){

	case 'view':
		default:
			$posted = $_REQUEST['posted'];
			$hall_id = $_REQUEST['hall_id'];
			$type_id = $_REQUEST['type_id'];
			
			
			if($type_id !='0'){
				if($end_date == ''){
					$sql = "select * from ".DB_PREFIX."consume WHERE hall_id = ".$hall_id." AND type_id = ".$type_id." AND issue_date = '".$start_date."'";
				}else{
					$sql = "select * from ".DB_PREFIX."consume WHERE hall_id = ".$hall_id." AND type_id = ".$type_id." AND issue_date between '".$start_date." 00:00:00' AND '".$end_date." 23:59:59'";
				}
			}else{
				if($end_date == ''){
					$sql = "select * from ".DB_PREFIX."consume WHERE hall_id = ".$hall_id." AND issue_date = '".$start_date."'";
				}else{
					$sql = "select * from ".DB_PREFIX."consume WHERE hall_id = ".$hall_id." AND issue_date between '".$start_date." 00:00:00' AND '".$end_date." 23:59:59'";
				}
			}
			//echo $sql; exit;
			$consumeList = $dbObj->selectDataObj($sql);
			
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
			
			$typeArr = array(
							'1' => 'Breakfast',
							'2' => 'Lunch',
							'3' => 'Dinner'
							);

			$typeId = array();
			$typeId[0] = SELECT_MEAL_TYPE;
			foreach($typeArr as $key=>$val){
			$typeId[$key] = $val;
			}
			$typeList_opt = formSelectElement($typeId, $type_id, 'type_id');

			
			
			$action = 'view';

		break;
	
	case 'update':
		$id = $_REQUEST['id'];
		$hall_id = $_REQUEST['hall_id'];
		$sql = "select p.id, p.name as p_name, pc.name as pc_name, u.name as u_name, c.id, c.qty, c.unit_price from ".DB_PREFIX."product as p,  ".DB_PREFIX."product_category as pc, ".DB_PREFIX."unit as u, ".DB_PREFIX."consume as c where p.category_id = pc.id AND pc.unit_id = u.id and p.id = c.product_id and c.id = '".$id."' order by p.name asc";
		$productArr = $dbObj->selectDataObj($sql);	
		$consume = $productArr[0];
		$product_id = $consume->product_id;
		$qty = $consume->qty;
		$unit_price = $consume->unit_price;
		$name = $consume->p_name;
		$pc_name = $consume->pc_name;
		$u_name = $consume->u_name;
		//$total_price = $qty * $unit_price;
		
		$action = 'insert';
		break;
		
	case 'save':
		$id = $_POST['id'];
		$hall_id = $_REQUEST['hall_id'];
		$qty = $_POST['qty'];
		$unit_price = $_POST['unit_price'];
		
		
		$sql = "select * from ".DB_PREFIX."consume WHERE id = ".$id." AND hall_id = ".$hall_id."";	
		$productArr = $dbObj->selectDataObj($sql);
		$existList = $productArr[0];
		$existqty = $existList->qty;
		$product_id = $existList->product_id;
		
		$sql = "select * from ".DB_PREFIX."balance WHERE product_id = ".$product_id." AND hall_id = ".$hall_id."";
		$productArr1 = $dbObj->selectDataObj($sql);
		$existbalanceList = $productArr1[0];
		$existbalance = $existbalanceList->product_balance;
		
		
		$finalbalance = ($existbalance + $existqty) - $qty;
		
		$fields1 = array('product_balance' => $finalbalance);
		$where1 = "product_id = '".$product_id."' AND hall_id = '".$hall_id."'";
		$update_status1 = $dbObj->updateTableData("balance", $fields1, $where1);	
		
		if(!$update_status1){
			$msg = COULD_NOT_BE_UPDATED;	
			$action = 'insert';
		}else{
			$fields = array(
					'qty' => $qty,
					'unit_price' => $unit_price,
					'updated_by' => $cur_user_id,
					'updated_datetime' => current_date_time()
					);
					
			$where = "id = '".$id."'";
			$update_status = $dbObj->updateTableData("consume", $fields, $where);	
			
			if(!$update_status){
				$msg = COULD_NOT_BE_UPDATED;	
				$action = 'insert';
			}else{
				$msg = HAS_BEEN_UPDATED;
				$url = 'view_consume.php?action=view&page='.$page.'&posted=true&start_date='.$start_date.'&end_date='.$end_date.'&msg='.$msg.'&hall_id='.$hall_id;
				//echo $url;exit;
				redirect($url);
			}
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
				<h1><?php echo VIEW_CONSUMPTION; ?></h1>
			</td>
			<td class="usr_info">
				<?php echo welcomeMsg($cur_user_id); ?>
			</td>			
		</tr>
	</table>
	<?php
		if($action=="view"){
	?>
			
			<form action="view_consume.php" method="post" name="view_consume" id="view_consume" onsubmit="return checkDate();" >
				<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
					<tr>
						<td height="30" width="11%">
							<?php echo HALL_NAME; ?>:
						</td>
						<td height="30" colspan="3">
							<?php echo $hallList_opt; ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo SELECT_MEAL_TYPE; ?>
						</td>
						<td>
							<?php echo $typeList_opt;?>
						</td>
					</tr>
					<tr>
						<td height="30" width="11%">
							<?php echo START_DATE; ?>:
						</td>
						<td height="30">
							<input name="start_date" id="start_date" type="text" class="inputbox readonly" readonly="readonly" alt="Start Date" size="18" value="<?php echo $start_date; ?>" />
							<img id="f_rangeStart_triggerm_start" src="date/src/css/img/calendar.gif" title="Pick a Date" />
							<img id="f_clearRangeStart" src="date/src/css/img/no.png" title="Clear Date" onClick="return makeEmpty('start_date')" height="16" width="16" />
							<script type="text/javascript">
							RANGE_CAL_1 = new Calendar({
								inputField: "start_date",
								dateFormat: "%Y-%m-%d",
								trigger: "f_rangeStart_triggerm_start",
								bottomBar: true,
								onSelect: function(){
								var date = Calendar.intToDate(this.selection.get());
									this.hide();
								}
							});
							</script>
						</td>
						<td height="30" width="10%">
							<?php echo END_DATE; ?>:
						</td>
						<td height="30">
							<input name="end_date" id="end_date" type="text" class="inputbox readonly" readonly="readonly" alt="End Date" size="18" value="<?php echo $end_date; ?>" />
							<img id="f_rangeStart_triggerm_end" src="date/src/css/img/calendar.gif" title="Pick a Date" />
							<img id="f_clearRangeStart" src="date/src/css/img/no.png" title="Clear Date" onClick="return makeEmpty('end_date')" height="16" width="16" />
							<script type="text/javascript">
							RANGE_CAL_1 = new Calendar({
								inputField: "end_date",
								dateFormat: "%Y-%m-%d",
								trigger: "f_rangeStart_triggerm_end",
								bottomBar: true,
								onSelect: function(){
								var date = Calendar.intToDate(this.selection.get());
									this.hide();
								}
							});
							</script>
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<input type="submit" name="submit" class="button" value="View Consume"/>
						</td>
					</tr>
				</table>
				<input type="hidden" name="action" value="view" />
				<input type="hidden" name="posted" value="true" />
			</form>
	<?php 
	if($posted == 'true'){ ?>
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
				<tr>
					<td>
						<table width="100%" cellpadding="0" cellspacing="0" border="0" class="datagrid">			
							<tr class="head">
								<td height="30" width="20%"><strong><?php echo DATE; ?></strong></td>
								<td width="15%"><strong><?php echo PRODUCT_NAME; ?></strong></td>
								<td width="15"><strong><?php echo CATEGORY_NAME; ?></strong></td>
								<td width="5%"><strong><?php echo QUANTITY; ?></strong></td>
								<td width="10%"><strong><?php echo UNIT; ?></strong></td>
								<td width="10%"><strong><?php echo UNIT_PRICE; ?></strong></td>
								<td width="15%"><strong><?php echo TOTAL_PRICE; ?></strong></td>
								<td width="10%"><strong><?php echo ACTION; ?></strong></td>							
							</tr>
							
							<?php			
							if(!empty($consumeList)){	
								$maxPageLimit = (($s+$limit) > $total_rows) ? $total_rows : ($s+$limit);
								for($rownum = $s; $rownum <$maxPageLimit; ){		
									$class = (($rownum%2)==0) ? ' class="even"' : ' class="odd"';
									$product = getNameById('product', $consumeList[$rownum]->product_id);
									$category = getNameById('product_category', $product->category_id);
									$unit = getNameById('unit', $category->unit_id);
									$total_price = ($consumeList[$rownum]->qty * $consumeList[$rownum]->unit_price);
							?>
									<tr <?php echo $class; ?>>
										<td>
											<?php echo $consumeList[$rownum]->issue_date; ?> 
										</td>
										<td width="10%">
											<?php echo $product->name; ?> 
										</td>
										<td>
											<?php echo $category->name; ?> 
										</td>	
										<td align="center">
											<?php echo $consumeList[$rownum]->qty; ?> 
										</td>
										<td>
											<?php echo $unit->name; ?> 
										</td>	
										<td align="center">
											<?php echo $consumeList[$rownum]->unit_price; ?>
										</td>	
										<td align="center">
											<?php echo $total_price; ?>
										</td>			
										<td>								
											<a class="edit" href="view_consume.php?action=update&id=<?php echo $consumeList[$rownum]->id; ?>&start_date=<?php echo $start_date;?>&end_date=<?php echo $end_date;?>&hall_id=<?php echo $hall_id; ?>" title="Edit">&nbsp;</a>
										</td>
									</tr>
									<?php 
									 $in_total +=$total_price;
										$rownum++;?>
								<?php 
									}//for
								}else{ ?>
								<tr height="30">
									<td colspan="6">
										<?php echo EMPTY_DATA; ?>
									</td>
								</tr>
								<?php 
								}//else
									?>			
						</table>
						<br />
							<?php if(!empty($consumeList)){ ?>
								<div id="in_total">
									<?php echo '<b>'.TOTAL_PRICE.':&nbsp;&nbsp;&nbsp;</b>'.$in_total;?>
								</div>
							<?php }//if?>
					</td>
				</tr>
			</table>
	
	<?php 
		}//if submitted
	?>
	<?php
		}else if($action=="insert"){ 
	?>
	<form action="view_consume.php" method="post" name="view_consume" id="view_consume" onsubmit="return validateblock();">
		<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
			<tr>
				<td colspan="2">
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="datagrid">
						<tr class="head">
							<td height="30" width="20%"><strong><?php echo PRODUCT_NAME; ?></strong></td>
							<td width="20%"><strong><?php echo CATEGORY_NAME; ?></strong></td>
							<td width="20%"><strong><?php echo QUANTITY; ?></strong></td>
							<td width="20%"><strong><?php echo UNIT; ?></strong></td>
							<td width="20%"><strong><?php echo UNIT_PRICE; ?></strong></td>						
						</tr>
				
						<tr>
							<td height="30"><?php echo $name; ?></td>
							<td><?php echo $pc_name; ?></td>
							<td><input type="text" name="qty" id="qty_<?php echo $product_id; ?>" class="inputbox input_right" alt="Quantity" size="6" value="<?php echo $qty; ?>" onkeyup="getTotalPrice('<?php echo 'qty_'.$product_id; ?>', '<?php echo $product_id; ?>');" maxlength="4" /></td>
							<td><?php echo $u_name; ?></td>
							<td><?php echo $unit_price; ?></td>
						</tr>
						<tr>
							<td colspan="5">
								<input type="submit" name="Submit" class="button" value="Save" />
								<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><input type="button" onclick="window.location='<?php echo $_SERVER['HTTP_REFERER']; ?>'"  name="cancel" class="cancel" value="<?php echo CANCEL; ?>" /></a>
							</td>
						</tr>		
					</table>
				</td>
			</tr>
		</table>	
	<input type="hidden" name="action" value="save" />
	<input type="hidden" name="id" value="<?php echo $id; ?>" />
	<input type="hidden" name="page" id="page" value="<?php echo $page; ?>" />
	<input type="hidden" name="start_date" value="<?php echo $start_date;?>" />
	<input type="hidden" name="end_date" value="<?php echo $end_date;?>" />
	<input type="hidden" name="hall_id" value="<?php echo $hall_id;?>" />
</form>
			
	<?php }?>
</div>
			
<?php
require_once("includes/footer.php");
?>