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
				$type_id = intval($_REQUEST['type_id']);
				
				$added_cond = '';
				if(!empty($start_date) && !empty($end_date)){
					$added_cond .= " AND c.issue_date BETWEEN '".$start_date."' AND '".$end_date."' ";
				}
				$added_cond .= ($hall_id == 0) ? '' : " AND c.hall_id = '".$hall_id."' ";
				$added_cond .= ($product_id == 0) ? '' : " AND c.product_id = '".$product_id."' ";
				$added_cond .= ($category_id == 0) ? '' : " AND pc.id = '".$category_id."' ";
				$added_cond .= ($type_id == 0) ? '' : " AND c.type_id = '".$type_id."' ";
				
				$ext_url = '&posted=true';
				if(!empty($start_date) && !empty($end_date)){
					$ext_url .= '&start_date='.$start_date.'&end_date='.$end_date;
				}
				
				if(!empty($hall_id)){
					$ext_url .= '&hall_id='.$hall_id;
				}
				
				if(!empty($product_id)){
					$ext_url .= '&product_id='.$product_id;
				}
				
				if(!empty($category_id)){
					$ext_url .= '&category_id='.$category_id;
				}
				
				if(!empty($type_id)){
					$ext_url .= '&type_id='.$type_id;
				}				
				
				$sql = "select c.id, c.issue_date, c.hall_id, c.product_id, c.qty, c.unit_price, c.type_id from ".DB_PREFIX."consume as c, ".DB_PREFIX."product_category as pc,  ".DB_PREFIX."product as p, ".DB_PREFIX."hall as h WHERE p.id = c.product_id AND p.category_id = pc.id AND h.id = c.hall_id ".$added_cond." ORDER BY c.issue_date, h.name, c.type_id, pc.name, p.name asc";
				$consumeList = $dbObj->selectDataObj($sql);
				
				$sql = "select SUM(c.qty) as total_qty, SUM(c.unit_price) as total_unit_price, SUM(c.total_price) as total_price from ".DB_PREFIX."consume as c, ".DB_PREFIX."product_category as pc,  ".DB_PREFIX."product as p, ".DB_PREFIX."hall as h WHERE p.id = c.product_id AND p.category_id = pc.id AND h.id = c.hall_id ".$added_cond."";
				$totalConsume = $dbObj->selectDataObj($sql);
				
				//echo $totalConsume[0]->total_price;

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
			
			//Build Meal Type Array
			$typeArr = array(
							'0' => 'Select Meal Type',
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
			
			//Pagination 
			if(!empty($consumeList)){
				$total_rows = sizeof($consumeList);
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

				<h1><?php echo ITEMWISE_CONSUMPTION_REPORTS; ?></h1>
			</td>
			<td class="usr_info">
				<?php echo welcomeMsg($cur_user_id); ?>
			</td>			
		</tr>
	</table>
	<?php if($action=="view"){ ?>
			
			<form action="item_consump_rep.php" method="post" name="consump_rep" id="consump_rep" onsubmit="return checkProduct();">
				<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
					<tr>
						<td height="30"><?php echo SELECT_PRODUCT_OPT; ?>:</td>
						<td colspan="3"><?php echo $productList_opt; ?>&nbsp;<span class="required_field">*</span></td>
					</tr>
					<tr>
						<td height="30"><?php echo SELECT_MEAL_TYPE; ?>:</td>
						<td colspan="3"><?php echo $typeList_opt; ?></td>
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
							<input type="submit" name="submit" class="button" value="Generate Consumption Report"/>
						</td>
					</tr>
				</table>
				<input type="hidden" name="action" value="view" />
				<input type="hidden" name="posted" value="true" />
			</form>
			
	<?php if($posted == 'true'){ ?>
	
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
				<tr>
					<td><a href="item_consume_report_download.php"><img src="images/excel.png" height="24" width="24" alt="save the report" title="save the report" style="padding-bottom:10px;"/></a><br /></td>
				<tr>
				<tr>
					<td>
						<table width="100%" cellpadding="0" cellspacing="0" border="0" class="datagrid">			
							<tr class="head">
								<td height="30" width="15%"><strong><?php echo DATE; ?></strong></td>
								<td width="15%"><strong><?php echo HALL; ?></strong></td>
								<td width="10%"><strong><?php echo SELECT_MEAL_TYPE; ?></strong></td>
								<td width="5%" align="right"><strong><?php echo QUANTITY; ?></strong></td>
								<td width="5%" align="right"><strong><?php echo UNIT; ?></strong></td>	
								<td width="15%" align="right"><strong><?php echo UNIT_PRICE; ?></strong></td>
								<td width="15%" align="right"><strong><?php echo TOTAL_PRICE; ?></strong></td>
							</tr>
							
							<?php
										//For downloading Reports as XLS format
							//if group_id == 1 --->>> Only Super Admin can download the reprot
								if($cur_user_group_id == '1'){
								$downloadTitle[0] = 'Item Consumption Report'."\n";
								$arr[0]['issue_date'] = 'Date';
								$arr[0]['hall_name'] = 'Hall Name';
								$arr[0]['mealType'] = 'Meal Type';
								$arr[0]['qty'] = 'Quantity';
								$arr[0]['unit_name'] = 'Unit';
								$arr[0]['unit_price'] = 'Unit Price';
								$arr[0]['total_price'] = 'Total Price';
						
							} ?>
							
							<?php			
							if(!empty($consumeList)){
								$total_price = 0;
								$r = 1;
								foreach($consumeList as $consume){
									$product = getNameById('product', $consume->product_id);
									$hall = getNameById('hall', $consume->hall_id);
									$category = getNameById('product_category', $product->category_id);
									$unit = getNameById('unit', $category->unit_id);
									$total_price = ($consume->qty * $consume->unit_price);
									if($consume->type_id == '1'){
										$mealType = BREAKFAST;
									}else if($consume->type_id == '2'){
										$mealType = LUNCH;
									}else if($consume->type_id == '3'){
										$mealType = DINNER;
									}
									$arr[$r]['issue_date'] = $consume->issue_date;
									$arr[$r]['hall_name'] = $hall->name;
									$arr[$r]['mealType'] = $mealType;
									$arr[$r]['qty'] = $consume->qty;
									$arr[$r]['unit_name'] = $unit->name;
									$arr[$r]['unit_price'] = $consume->unit_price;
									$arr[$r]['total_price'] =$total_price;
									$r++;
								}	
								$maxPageLimit = (($s+$limit) > $total_rows) ? $total_rows : ($s+$limit);
								for($rownum = $s; $rownum <$maxPageLimit; $rownum++){		
									$class = (($rownum%2)==0) ? ' class="even"' : ' class="odd"';
									$product = getNameById('product', $consumeList[$rownum]->product_id);
									$hall = getNameById('hall', $consumeList[$rownum]->hall_id);
									$category = getNameById('product_category', $product->category_id);
									$unit = getNameById('unit', $category->unit_id);
									$total_price = ($consumeList[$rownum]->qty * $consumeList[$rownum]->unit_price);
									if($consumeList[$rownum]->type_id == '1'){
										$mealType = BREAKFAST;
									}else if($consumeList[$rownum]->type_id == '2'){
										$mealType = LUNCH;
									}else if($consumeList[$rownum]->type_id == '3'){
										$mealType = DINNER;
									}
							?>
									<tr <?php echo $class; ?>>
										<td height="30"><?php echo $consumeList[$rownum]->issue_date; ?></td>
										<td><?php echo $hall->name; ?></td>
										<td><?php echo $mealType;?></td>
										<td align="right"><?php echo $consumeList[$rownum]->qty; ?></td>
										<td align="right"><?php echo $unit->name;?></td>	
										<td align="right"><?php echo view_number($consumeList[$rownum]->unit_price); ?></td>	
										<td align="right"><?php echo view_number($total_price);?></td>			
									</tr>
								<?php 
										$s++;
									}//for
									?>
									<tr>
										<td height="30" width="50%" colspan="4" align="right">
											<strong><?php echo TOTAL_QUANTITY; ?>:&nbsp;</strong>
											<?php 
												echo view_number($totalConsume[0]->total_qty);
												
											?>
										</td>
										<td width="50%" colspan="3" align="right"><strong><?php echo NET_TOTAL_PRICE; ?>:&nbsp;</strong>
											<?php echo view_number($totalConsume[0]->total_price);?>
										</td>
									</tr>	
								
								<?php }else{ ?>
								<tr height="50">
									<td colspan="7"><?php echo NO_CONSUME_DATA_FOUND; ?></td>
								</tr>
								<?php 
								}
								if($total_page > 1){ ?>
								<tr height="50">
									<td colspan="7"><?php echo pagination($total_rows,$limit,$page,$ext_url); ?></td>
								</tr>
								<?php }//if
									$arr[$r+1]['issue_date'] = '';
									$arr[$r+1]['hall_name'] = '';
									$arr[$r+1]['mealType'] = 'Total Quantity';
									$arr[$r+1]['qty'] = view_number($totalConsume[0]->total_qty);
									$arr[$r+1]['unit_name'] = $unit->name;
									$arr[$r+1]['unit_price'] = 'Net Total Price';
									$arr[$r+1]['total_price'] = view_number($totalConsume[0]->total_price);
								 ?>				
						</table>
					</td>
				</tr>
			</table>
	
	<?php  }//if submitted
	
		$_SESSION['item_consume_report'] = '';
		$_SESSION['item_consume_report'][0] = $downloadTitle; 
		$_SESSION['item_consume_report'][1] = $arr;
	}//if action == view?>
</div>
			
<?php
require_once("includes/footer.php");
?>