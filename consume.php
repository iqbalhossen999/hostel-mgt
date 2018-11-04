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

switch($action){
	case 'update':
	case 'create':
	default:

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
		$hallList_opt = formSelectElement($hallId, $hall_id, 'hall_id', 'onchange = processFunction("get_consume")');
		
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
		
		$action = 'insert';
		break;
		
	case 'save':	
		$id = $_POST['id'];
		$product_id = $_POST['product_id'];
		$hall_id = $_POST['hall_id'];
		$issue_date = $_POST['issue_date'];
		$type_id = $_POST['type_id'];
		
		if($type_id == '1'){
			$type_item = "breakfast";
			$update_item = "breakfast_cost";
		}else if($type_id == '2'){
			$type_item = "lunch";
			$update_item = "lunch_cost";
		}else if($type_id == '3'){
			$type_item = "dinner";
			$update_item = "dinner_cost";
		}
		
		//Insert into Consume Table
		$sql = "";
		if(!empty($product_id)){
			$net_ttl_price = 0;
			$sql .= "INSERT INTO ".DB_PREFIX."consume (hall_id, type_id, product_id, qty, unit_price, total_price, issue_date, created_by, created_datetime, updated_by, updated_datetime)
					VALUES ";
			foreach($product_id  as $key=>$val){
				$ttl_price = $_POST['qty_'.$val] * $_POST['unit_price_'.$val];
				$net_ttl_price += $ttl_price;
				$sql .= "('".$hall_id."', '".$type_id."', '".$val."', '".$_POST['qty_'.$val]."', '".$_POST['unit_price_'.$val]."','".$ttl_price."', '".$issue_date."', '".$cur_user_id."', '".current_date_time()."', '".$cur_user_id."', '".current_date_time()."'), ";
				
				$sql1 = "select product_balance, total_price from ".DB_PREFIX."balance where hall_id = '".$hall_id."' AND product_id ='".$val."'";
				$productList = $dbObj->selectDataObj($sql1);
				$product = $productList[0];
				$balance = $product->product_balance;
				$prev_total_price = $product->total_price;
				$total_balance = $balance - $_POST['qty_'.$val];
				$new_total_price = $prev_total_price - ($_POST['qty_'.$val]*$_POST['unit_price_'.$val]);
				
				$sql3 = " UPDATE ".DB_PREFIX."balance SET product_balance = '".$total_balance."', total_price = '".$new_total_price."' WHERE hall_id = '".$hall_id."' AND product_id = '".$val."'";
				$balance3 = $dbObj->executeData($sql3);
			}//foreach
		}//if
		
		$sql = rtrim($sql, ", ");
		$sql = $sql.";";
		
		//Now Insert into consume Table
		$insert = $dbObj->executeData($sql);
		
		if(!$insert){
			$msg = STOCK_COULD_NOT_BE_CREATED;
			$action = 'insert';
		}else{
			//Find out total student who has order for meal on that date
			$sql = "select id, breakfast_cost, lunch_cost, dinner_cost from ".DB_PREFIX."meal where order_date = '".$issue_date."' AND hall_id = '".$hall_id."' AND ".$type_item." != '0'";
			$totalOrderArr = $dbObj->selectDataObj($sql);
			$totalOrder = sizeof($totalOrderArr);
			$prev_individual_bill = $totalOrderArr[0]->$update_item;
			
			//Individual Student Bill
			$new_individual_bill = ($net_ttl_price/$totalOrder);
			$new_bill = $prev_individual_bill + $new_individual_bill;
			
			$sql = " UPDATE ".DB_PREFIX."meal SET ".$update_item." = '".$new_bill."' WHERE hall_id = '".$hall_id."' AND order_date = '".$issue_date."' AND ".$type_item." != '0'";
			$update_bill = $dbObj->executeData($sql);
			
			if(!$update_bill){
				$msg = 'Could not update meal table';
				$action = 'insert';
			}else{
				$msg = STOCK_CONSUMED_SUCCESSFULLY;
				$url = 'view_consume.php?action=view&posted=true&type_id='.$type_id.'&start_date='.$issue_date.'&hall_id='.$hall_id.'&msg='.$msg;
				redirect($url);
			}
		}//else
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
				<h1><?php echo CONSUME_STOCK; ?></h1>
			</td>	
			<td class="usr_info">
				<?php echo welcomeMsg($cur_user_id); ?>
			</td>			
		</tr>
	</table>				
<?php 
	if($action=="insert"){ 
?>
	<form action="consume.php" method="post" name="consume" id="consume" onsubmit="return validate();">
		<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
			<tr>
				<td height="30" width="20%">
					<?php echo SELECT_ISSUE_DATE; ?>:
				</td>
				<td height="30">
					<input name="issue_date" id="issue_date" type="text" class="inputbox readonly" readonly="readonly" alt="Issue Date" size="18" />
					<img id="f_rangeStart_triggerm" src="date/src/css/img/calendar.gif" title="Pick a Date" />
					<img id="f_clearRangeStart" src="date/src/css/img/no.png" title="Clear Date" onClick="return makeEmpty('issue_date')" height="16" width="16" />
					<script type="text/javascript">
					
					RANGE_CAL_1 = new Calendar({
						inputField: "issue_date",
						dateFormat: "%Y-%m-%d",
						trigger: "f_rangeStart_triggerm",
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
				<td height="30"><?php echo SELECT_MEAL_TYPE; ?>:</td>
				<td><?php echo $typeList_opt; ?></td>
			</tr>
			<tr>
				<td height="30"><?php echo HALL_NAME; ?>:</td>
				<td><?php echo $hallList_opt; ?></td>
			</tr>
			<tr>
				<td colspan="2">
					<div id="loaderContainer"></div>
					<div id="consumeinsert"></div>
				</td>
			</tr>
		</table>	
		<input type="hidden" name="action" value="save" />
	</form>
			
	<?php }?>
</div>
			
<?php
require_once("includes/footer.php");
?>