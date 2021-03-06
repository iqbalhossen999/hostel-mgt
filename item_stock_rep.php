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
		
			$posted = $_REQUEST['posted'];
			if($posted == "true"){
				$hall_id = intval($_REQUEST['hall_id']);
				$product_id = intval($_REQUEST['product_id']);
				$category_id = intval($_REQUEST['category_id']);

				$added_cond = '';
				if(!empty($start_date) && !empty($end_date)){
					$added_cond .= " AND s.issue_date BETWEEN '".$start_date."' AND '".$end_date."' ";
				}
				$added_cond .= ($hall_id == 0) ? '' : " AND s.hall_id = '".$hall_id."' ";
				$added_cond .= ($product_id == 0) ? '' : " AND s.product_id = '".$product_id."' ";
				$added_cond .= ($category_id == 0) ? '' : " AND pc.id = '".$category_id."' ";
				
				$ext_url = '&posted=true';
				if(!empty($start_date) && !empty($end_date)){
					$ext_url .= '&start_date='.$start_date.'&end_date='.$end_date;
				}
				
				if(!empty($hall_id)){
					$ext_url .= '&hall_id='.$hall_id;
				}
				
				if(!empty($category_id)){
					$ext_url .= '&category_id='.$category_id;
				}
				
				if(!empty($product_id)){
					$ext_url .= '&product_id='.$product_id;
				}				
				
				$sql = "select s.id, s.issue_date, s.hall_id, s.product_id, s.qty, s.unit_price  from ".DB_PREFIX."stock as s, ".DB_PREFIX."product_category as pc,  ".DB_PREFIX."product as p, ".DB_PREFIX."hall as h WHERE p.id = s.product_id AND p.category_id = pc.id AND h.id = s.hall_id ".$added_cond." ORDER BY s.issue_date, h.name, pc.name, p.name asc";
				$stockList = $dbObj->selectDataObj($sql);
				
				$sql = "select SUM(s.qty) as total_qty, SUM(s.unit_price*s.qty) as total_price from ".DB_PREFIX."stock as s, ".DB_PREFIX."product_category as pc,  ".DB_PREFIX."product as p, ".DB_PREFIX."hall as h WHERE p.id = s.product_id AND p.category_id = pc.id AND h.id = s.hall_id ".$added_cond."";
				$totalStock = $dbObj->selectDataObj($sql);
				
				//echo $totalStock[0]->total_price;
			}
				
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
			
			//Build Product Array
			$sql = "select id, name from ".DB_PREFIX."product order by name asc";
			$productArr = $dbObj->selectDataObj($sql);
			$productId = array();
			$productId[0] = SELECT_PRODUCT_OPT;
			if(!empty($productArr)){			
				foreach($productArr as $item){
					$productId[$item->id] = $item->name;
				}	
			}			
			$productList_opt = formSelectElement($productId, $product_id, 'product_id');
		
			//Pagination 
			if(!empty($stockList)){
				$total_rows = sizeof($stockList);
			}else{
				$total_rows =0;
			}
			//find start
			$s = ($page - 1) * $limit;
			$total_page = $total_rows/$limit;
			$action = 'view';

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
				<h1><?php echo ITEMWISE_STOCK_REPORTS; ?></h1>
			</td>
			<td class="usr_info">
				<?php echo welcomeMsg($cur_user_id); ?>
			</td>			
		</tr>
	</table>
	<?php if($action=="view"){ ?>
			
			<form action="item_stock_rep.php" method="post" name="view_stock" id="view_stock" onsubmit="return checkProduct();">
				<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
					<tr>
						<td height="30"><?php echo SELECT_PRODUCT_OPT; ?>:</td>
						<td colspan="3">
							<?php echo $productList_opt; ?>&nbsp;<span class="required_field">*</span>
						</td>
					</tr>
					<tr>
						<td height="30"><?php echo HALL_NAME; ?>:</td>
						<td colspan="3"><?php echo $hallList_opt; ?></td>
					</tr>
					<tr>
						<td height="30" width="20%"><?php echo START_DATE; ?>:</td>
						<td width="30%">
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
						<td width="20%"><?php echo END_DATE; ?>:</td>
						<td width="30%">
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
						<td height="50" colspan="4">
							<input type="submit" name="submit" class="button" value="Generate Stock Report"/>
						</td>
					</tr>
				</table>
				<input type="hidden" name="action" value="view" />
				<input type="hidden" name="posted" value="true" />
			</form>
			
	<?php if($posted == 'true'){ ?>
	
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
				<tr>
					<td><a href="item_stock_rep_report.php"><img src="images/excel.png" height="24" width="24" alt="save the report" title="save the report" style="padding-bottom:10px;"/></a><br /></td>
				<tr>
				<tr>
					<td colspan="4">
						<table width="100%" cellpadding="0" cellspacing="0" border="0" class="datagrid">			
							<tr class="head">
								<td height="30" width="20%"><strong><?php echo DATE; ?></strong></td>
								<td width="20%"><strong><?php echo HALL; ?></strong></td>
								<td width="10%" align="right"><strong><?php echo QUANTITY; ?></strong></td>
								<td width="10%" align="right"><strong><?php echo UNIT; ?></strong></td>	
								<td width="20%" align="right"><strong><?php echo UNIT_PRICE; ?></strong></td>
								<td width="20%" align="right"><strong><?php echo TOTAL_PRICE; ?></strong></td>
								
							</tr>
							<?php
								if($cur_user_group_id == '1'){
								//$downloadTitle[0] = "Order Report" ."\n";	
									//For downloading Reports as XLS format
								$downloadTitle[0] = 'Item Stock Report' ."\n";

								$arr[0]['date'] = 'Date';
								$arr[0]['hall'] = 'Hall';
								$arr[0]['quantity'] = 'Quantity';
								$arr[0]['unit'] = 'Unit';
								$arr[0]['unit_price'] = 'Unit Price';
								$arr[0]['total_price'] = 'Total Price';
								}
							?>
							
							<?php			
							if(!empty($stockList)){	
								$total_price = 0;
								$r = 1;
								foreach($stockList as $stock){
									$product = getNameById('product', $stock->product_id);
									$hall = getNameById('hall', $stock->hall_id);
									$category = getNameById('product_category', $product->category_id);
									$unit = getNameById('unit', $category->unit_id);
									$total_price = ($stock->qty * $stock->unit_price);
									$arr[$r]['date'] = $stock->issue_date;
									$arr[$r]['hall'] = $hall->name;
									$arr[$r]['quantity'] = view_number($stock->qty);
									$arr[$r]['unit'] = $unit->name;
									$arr[$r]['unit_price'] = view_number($stock->unit_price);
									$arr[$r]['total_price'] = view_number($total_price);
									$r++;
								}
								$rownum = 0;
								$maxPageLimit = (($s+$limit) > $total_rows) ? $total_rows : ($s+$limit);
								for($rownum = $s; $rownum <$maxPageLimit; $rownum++){
									$sl = $rownum+1;	
									$class = (($rownum%2)==0) ? ' class="even"' : ' class="odd"';
									$product = getNameById('product', $stockList[$rownum]->product_id);
									$hall = getNameById('hall', $stockList[$rownum]->hall_id);
									$category = getNameById('product_category', $product->category_id);
									$unit = getNameById('unit', $category->unit_id);
									$total_price = ($stockList[$rownum]->qty * $stockList[$rownum]->unit_price);
							?>
									<tr <?php echo $class; ?>>
										<td height="30"><?php echo $stockList[$rownum]->issue_date;?></td>
										<td><?php echo $hall->name;?></td>
										<td align="right"><?php echo $stockList[$rownum]->qty; ?></td>
										<td align="right"><?php echo $unit->name; ?></td>
										<td align="right"><?php echo view_number($stockList[$rownum]->unit_price); ?></td>
										<td align="right"><?php echo view_number($total_price);?></td>			
									</tr>
								<?php	}//for?>
									<tr>
										<td height="30" width="50%" colspan="3" align="right">
											<strong><?php echo TOTAL_QUANTITY; ?>:&nbsp;</strong>
											<?php 
												echo view_number($totalStock[0]->total_qty);
												
											?>
										</td>
										<td width="50%" colspan="3" align="right"><strong><?php echo NET_TOTAL_PRICE; ?>:&nbsp;</strong>
											<?php echo view_number($totalStock[0]->total_price);?>
										</td>
									</tr>
								<?php }else{ ?>
								<tr height="30">
									<td colspan="6"><?php echo NO_STOCK_FOUND; ?></td>
								</tr>
								<?php 
								}
								if($total_page > 1){ ?>
								<tr height="50">
									<td colspan="6"><?php echo pagination($total_rows,$limit,$page,$ext_url); ?></td>
								</tr>
								<?php }//if
									$arr[$r+1]['date'] = '';
									$arr[$r+1]['hall'] = 'Total Quantity';
									$arr[$r+1]['quantity'] = view_number($totalStock[0]->total_qty);
									$arr[$r+1]['unit'] = $unit->name;
									$arr[$r+1]['unit_price'] = 'Net Total Price';
									$arr[$r+1]['total_price'] = view_number($totalStock[0]->total_price);
									/*echo '<pre>';
									print_r($arr);*/
								 ?>				
						</table>
					</td>
				</tr>
			</table>
	
	<?php  }//if submitted
	
		$_SESSION['item_stock_rep'] = '';
		$_SESSION['item_stock_rep'][0] = $downloadTitle; 
		$_SESSION['item_stock_rep'][1] = $arr;
		
	}//if action == view?>
</div>
			
<?php
require_once("includes/footer.php");
?>