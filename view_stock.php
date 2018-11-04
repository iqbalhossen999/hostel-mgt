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

if(empty($end_date)){
	$end_date = $start_date;
}

if(empty($start_date)){
	$start_date = $end_date;
}
switch($action){

	case 'view':
		default:
			$hall_id = $_REQUEST['hall_id'];
			$posted = $_REQUEST['posted'];
			
			$sql = "select s.id, s.issue_date, s.product_id, s.qty, s.unit_price  from ".DB_PREFIX."stock as s WHERE s.hall_id = '".$hall_id."' AND s.issue_date between '".$start_date."' AND '".$end_date."' ";
			$stockList = $dbObj->selectDataObj($sql);
			
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
			
			$action = 'view';

		break;
	
	case 'update':
		$id = $_REQUEST['id'];
		$hall_id = $_REQUEST['hall_id'];
		
		$sql = "select b.product_balance, b.avg_price, b.total_price, p.id as product_id, p.name as p_name, pc.name as pc_name, u.name as u_name, s.id, s.qty, s.unit_price from ".DB_PREFIX."product as p,  ".DB_PREFIX."product_category as pc, ".DB_PREFIX."unit as u, ".DB_PREFIX."stock as s, ".DB_PREFIX."balance as b where p.id = b.product_id AND b.product_id = s.product_id AND p.category_id = pc.id AND pc.unit_id = u.id and p.id = s.product_id and s.id = '".$id."' order by p.name asc";
		$productArr = $dbObj->selectDataObj($sql);
		$stock = $productArr[0];
		$product_id = $stock->product_id;
		$qty = $stock->qty;
		$unit_price = $stock->unit_price;
		$name = $stock->p_name;
		$pc_name = $stock->pc_name;
		$u_name = $stock->u_name;
		$total_price = $qty * $unit_price;
		$balance = $stock->product_balance;
		$avg_price = $stock->avg_price;
		$balance_total_price = $stock->total_price;
		
		$action = 'insert';
		break;
		
	case 'save':	
		$id = $_POST['id'];
		$qty = $_POST['qty'];
		$unit_price = $_POST['unit_price'];
		$new_total_price = $_POST['total'];
		$prev_qty = $_POST['prev_qty'];
		$prev_total_price = $_POST['prev_total_price'];
		$avg_price = $_POST['avg_price'];
		$hall_id = $_POST['hall_id'];
		$product_id = $_POST['product_id'];
		
		$sql = "select * from ".DB_PREFIX."balance where product_id = '".$product_id."' AND hall_id = '".$hall_id."'";
		$balanceArr = $dbObj->selectDataObj($sql);
		$balance = $balanceArr[0];
		
		$cur_product_balance = $balance->product_balance;
		$cur_total_price = $balance->total_price;
		
		$final_balance = $cur_product_balance - $prev_qty + $qty;
		$final_total = $cur_total_price - $prev_total_price + $new_total_price;
		
		$fields = array(
					'qty' => $qty,
					'unit_price' => $unit_price,
					'updated_by' => $cur_user_id,
					'updated_datetime' => current_date_time()
					);
					
		$where = "id = '".$id."'";
		$update_status = $dbObj->updateTableData("stock", $fields, $where);	
		
		if(!$update_status){
			$msg = 'Could not update Stock Table';	
			$action = 'insert';
		}else{
			$fields1 = array(
						'product_balance' => $final_balance,
						'avg_price' => $avg_price,
						'total_price' => $final_total
						);
			$where1 = "product_id = '".$product_id."' AND hall_id = '".$hall_id."'";
			$update_status1 = $dbObj->updateTableData("balance", $fields1, $where1);
			if(!$update_status1){
				$msg = 'Could not update Balance Table';	
				$action = 'insert';
			}else{
				$msg = STOCK_HAS_BEEN_UPDATED;
				$url = 'view_stock.php?action=view&page='.$page.'&posted=true&start_date='.$start_date.'&end_date='.$end_date.'&msg='.$msg.'&hall_id='.$hall_id;
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
				<h1><?php echo VIEW_STOCK; ?></h1>
			</td>
			<td class="usr_info">
				<?php echo welcomeMsg($cur_user_id); ?>
			</td>			
		</tr>
	</table>
	<?php
		if($action=="view"){
	?>
			<form action="view_stock.php" method="post" name="view_stock" id="view_stock" onsubmit="return checkDate();" >
				<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
					<tr>
						<td height="30" width="10%">
							<?php echo HALL_NAME; ?>:
						</td>
						<td height="30">
							<?php echo $hallList_opt; ?>
						</td>
					</tr>
					<tr>
						<td height="30" width="10%">
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
						<td>
							<input type="submit" name="submit" class="button" value="View Stock"/>
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
					<td colspan="4">
						<table width="100%" cellpadding="0" cellspacing="0" border="0" class="datagrid">			
							<tr class="head">
								<td height="30" width="15%"><strong><?php echo DATE; ?></strong></td>
								<td width="15%"><strong><?php echo PRODUCT_NAME; ?></strong></td>
								<td width="15"><strong><?php echo CATEGORY_NAME; ?></strong></td>
								<td width="10%"><strong><?php echo QUANTITY; ?></strong></td>
								<td width="10%"><strong><?php echo UNIT; ?></strong></td>
								<td width="10%"><strong><?php echo UNIT_PRICE; ?></strong></td>
								<td width="15%"><strong><?php echo TOTAL_PRICE; ?></strong></td>
								<td width="10%"><strong><?php echo ACTION; ?></strong></td>							
							</tr>
							
							
							<?php			
							if(!empty($stockList)){
								$maxPageLimit = (($s+$limit) > $total_rows) ? $total_rows : ($s+$limit);
								for($rownum = $s; $rownum <$maxPageLimit; ){		
									$class = (($rownum%2)==0) ? ' class="even"' : ' class="odd"';
									$product = getNameById('product', $stockList[$rownum]->product_id);
									$category = getNameById('product_category', $product->category_id);
									$unit = getNameById('unit', $category->unit_id);
								    $total_price = ($stockList[$rownum]->qty * $stockList[$rownum]->unit_price);
							?>
							
									<tr <?php echo $class; ?>>
										<td height="30">
											<?php echo $stockList[$rownum]->issue_date; ?> 
										</td>
										<td>
											<?php echo $product->name; ?> 
										</td>
										<td>
											<?php echo $category->name; ?> 
										</td>	
										<td align="center">
											<?php echo $stockList[$rownum]->qty; ?> 
										</td>
										<td>
											<?php echo $unit->name; ?> 
										</td>	
										<td align="center">
											<?php echo $stockList[$rownum]->unit_price; ?>
										</td>	
										<td align="center">
											<?php echo number_format($total_price,2); ?>
										</td>			
										<td>								
											<a class="edit" href="view_stock.php?action=update&id=<?php echo $stockList[$rownum]->id; ?>&start_date=<?php echo $start_date;?>&end_date=<?php echo $end_date;?>&hall_id=<?php echo $hall_id; ?>" title="Edit">&nbsp;</a>
										</td>
									</tr>
									<?php 
									 $in_total +=$total_price;
										$rownum++;?>
								
								<?php 
									}//for
									?>
							<?PHP 	}else{ ?>
								<tr height="30">
									<td colspan="8"><?php echo NO_STOCK_FOUND; ?></td>
								</tr>
								<?php 
								}//else
								?>
						</table>
						<br />
							<?php if(!empty($stockList)){ ?>
								<div id="in_total">
									<?php echo '<b>'.TOTAL_PRICE.':&nbsp;&nbsp;&nbsp;</b>'.number_format($in_total,2);?>
								</div>
							<?php }//if?>
					</td>
				</tr>
			</table>
	
	<?php 
		}//if submitted
	}else if($action=="insert"){ ?>
	<form action="view_stock.php" method="post" name="view_stock" id="view_stock" onsubmit="return validateblock();">
		<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
			<tr>
				<td colspan="2">
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="datagrid">
						<tr class="head">
							<td height="30" width="14%"><strong><?php echo PRODUCT_NAME; ?></strong></td>
							<td width="14%"><strong><?php echo CATEGORY_NAME; ?></strong></td>
							<td width="12%"><strong><?php echo QUANTITY; ?></strong></td>
							<td width="12%"><strong><?php echo UNIT; ?></strong></td>
							<td width="12%"><strong><?php echo TOTAL_PRICE; ?></strong></td>
							<td width="12%"><strong><?php echo UNIT_PRICE; ?></strong></td>
							<td width="12%"><strong><?php echo BALANCE; ?></strong></td>
							<td width="12%"><strong><?php echo AVG_PRICE; ?></strong></td>							
						</tr>	
						<tr>
							<td height="30"><?php echo $name; ?></td>
							<td><?php echo $pc_name; ?></td>
							<td><input type="text" name="qty" id="qty" class="inputbox input_right" alt="Quantity" size="6" value="<?php echo $qty; ?>" onkeyup="getTotalPrice2('<?php echo $qty; ?>','<?php echo $balance; ?>','<?php echo $total_price; ?>','<?php echo $avg_price; ?>', '<?php echo $balance_total_price; ?>', 'qty');" maxlength="10" /></td>
							<td><?php echo $u_name; ?></td>
							<td><input type="text" name="total" id="total" class="inputbox input_right" alt="Total Price" size="6" value= "<?php echo $total_price; ?>" onkeyup="getTotalPrice2('<?php echo $qty; ?>','<?php echo $balance; ?>','<?php echo $total_price; ?>','<?php echo $avg_price; ?>', '<?php echo $balance_total_price; ?>', 'total');" maxlength="10"/></td>
							<td><input type="text" name="unit_price" id="unit_price" class="inputbox input_right" alt="Unit Price" size="6" readonly="readonly" value="<?php echo $unit_price; ?>" /></td>
							<td><input type="text" name="balance" id="balance" class="inputbox input_right" alt="Available Balance" size="6"  readonly="readonly" value="<?php echo $balance; ?>" /></td>
							<td><input type="text" name="avg_price" id="avg_price" class="inputbox input_right" alt="Average Price" size="6"  readonly="readonly" value="<?php echo $avg_price; ?>" /></td>	
						</tr>
						<tr>
							<td colspan="2">
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
	<input type="hidden" name="prev_qty" value="<?php echo $qty;?>" />
	<input type="hidden" name="prev_total_price" value="<?php echo $total_price;?>" />
	<input type="hidden" name="hall_id" value="<?php echo $hall_id;?>" />
	<input type="hidden" name="product_id" value="<?php echo $product_id;?>" />
</form>
			
	<?php }?>
</div>
			
<?php
require_once("includes/footer.php");
?>