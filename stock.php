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
		$hallList_opt = formSelectElement($hallId, $hall_id, 'hall_id', 'onchange = processFunction("get_stock")');
		
		$action = 'insert';
		break;
		
	case 'save':	
		$id = $_POST['id'];
		$product_id = $_POST['product_id'];
		$hall_id = $_POST['hall_id'];
		$issue_date = $_POST['issue_date'];
		
		//Insert into Stock Table
		$sql = "";
		if(!empty($product_id)){
			$sql .= "INSERT INTO ".DB_PREFIX."stock (hall_id, product_id, qty, unit_price, issue_date, created_by, created_datetime, updated_by, updated_datetime)
					VALUES ";
			foreach($product_id  as $key=>$val){
				$sql .= "('".$hall_id."', '".$val."', '".$_POST['qty_'.$val]."', '".$_POST['unit_price_'.$val]."', '".$issue_date."', '".$cur_user_id."', '".current_date_time()."', '".$cur_user_id."', '".current_date_time()."'), ";
				//echo $sql;exit;
				$sql1 = "select product_balance, avg_price, total_price from ".DB_PREFIX."balance where hall_id = '".$hall_id."' AND product_id ='".$val."'";
				$productList = $dbObj->selectDataObj($sql1);
				$product = $productList[0];
				$balance = $product->product_balance;
				$prev_total = $product->total_price;
				$total_balance = $balance + $_POST['qty_'.$val];
				$total_price = $prev_total + $_POST['total_'.$val];
				$avg_price = $_POST['avg_price_'.$val];
				
				$sql2 = " DELETE FROM ".DB_PREFIX."balance WHERE hall_id = '".$hall_id."' AND product_id = '".$val."'"; 
				$delete = $dbObj->executeData($sql2);
				
				$sql3 = " INSERT INTO ".DB_PREFIX."balance (hall_id, product_id, product_balance, avg_price, total_price) VALUES ('".$hall_id."', '".$val."', '".$total_balance."', '".$avg_price."', '".$total_price."')"; 
				$balance3 = $dbObj->executeData($sql3);
			}//foreach
		}//if
	
		$sql = rtrim($sql, ", ");
		$sql = $sql.";";
		
		//echo $sql;exit;
		
		//Now Insert into Stock Table
		$insert = $dbObj->executeData($sql);

		if(!$insert){
			$msg = STOCK_COULD_NOT_BE_CREATED;
			$action = 'insert';
		}else{
			$msg = STOCK_CREATED_SUCCESSFULLY;
			$url = 'view_stock.php?action=view&posted=true&start_date='.$issue_date.'&end_date='.$issue_date.'&hall_id='.$hall_id.'&msg='.$msg;
			redirect($url);
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
				<h1><?php echo ADD_STOCK; ?></h1>
			</td>
			<td class="usr_info">
				<?php echo welcomeMsg($cur_user_id); ?>
			</td>			
		</tr>
	</table>
	<?php
		if($action=="insert"){ 
	?>
	<form action="stock.php" method="post" name="stock" id="stock" onsubmit="return add_stock();">
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
				<td height="30" width="20%">
					<?php echo HALL_NAME; ?>:
				</td>
				<td>
					<?php echo $hallList_opt; ?>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<div id="loaderContainer"></div>
					<div id="stockinsert"></div>
				</td>
			</tr>
		</table>	
	<input type="hidden" name="action" value="save" />
	<input type="hidden" name="id" value="<?php echo $id; ?>" />
	<input type="hidden" name="page" id="page" value="<?php echo $page; ?>" />
</form>
			
	<?php }?>
</div>
			
<?php
require_once("includes/footer.php");
?>