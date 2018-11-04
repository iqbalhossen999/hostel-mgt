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
			$posted = $_REQUEST['posted'];
			if($posted == "true"){
				$hall_id = $_REQUEST['hall_id'];
				
				$sql = "select p.id, p.name, u.name as u_name from ".DB_PREFIX."product as p, ".DB_PREFIX."product_category as pc, ".DB_PREFIX."unit as u where p.category_id = pc.id AND pc.unit_id = u.id order by p.name asc";
				$productList = $dbObj->selectDataObj($sql);
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
				<h1><?php echo AVAILABLE_STOCK_REPORTS; ?></h1>
			</td>
			<td class="usr_info">
				<?php echo welcomeMsg($cur_user_id); ?>
			</td>			
		</tr>
	</table>
	
	<?php if($action=="view"){ ?>
	
		<form action="aval_rep.php" method="post" name="aval_rep" id="aval_rep" onsubmit="return aval_report();" >
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
				<tr>
					<td height="30" width="10%"><?php echo HALL_NAME; ?>:</td>
					<td width="90%"><?php echo $hallList_opt; ?></td>
				</tr>
				<tr>
					<td colspan="2" height="50">
						<input type="submit" name="submit" class="button" value="Generate Available Stock Report"/>
					</td>
				</tr>
			</table>
			<input type="hidden" name="posted" id="posted" value="true" />
		</form>
		
		<?php if($posted == 'true'){ ?>
		
			<table width="100%" cellspacing="0" cellpadding="0" border="0" class="module_content">	
				<tr>
					<td><a href="aval_rep_report.php"><img src="images/excel.png" height="24" width="24" alt="save the report" title="save the report" style="padding-bottom:10px;"/></a><br /></td>
				<tr>
				<tr>
					<td>
			<?php 	if(empty($hall_id)){ ?>
						<table width="100%" cellspacing="0" cellpadding="0" border="0" class="datagrid">
							<tr class="head">
								<td colspan="2"><strong><?php echo PLEASE_SELECT_YOUR_HALL; ?></strong></td>
							</tr>
						</table>
		<?php 		}else{ ?>
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="datagrid">			
								<tr class="head">
									<td height="30" width="20%"><strong><?php echo PRODUCT_NAME; ?></strong></td>
									<td width="20%" align="right"><strong><?php echo BALANCE; ?></strong></td>	
									<td width="20%" align="center"><strong><?php echo UNIT; ?></strong></td>
									<td width="20%" align="right"><strong><?php echo AVG_UNIT_PRICE; ?></strong></td>	
									<td width="20%" align="right"><strong><?php echo TOTAL_PRICE; ?></strong> </td>				
								</tr>
						<?php 
							$downloadTitle[0] = 'Available Stock Report' ."\n";

							$arr[0]['product_name'] = 'Product Name';
							$arr[0]['balance'] = 'Balance';
							$arr[0]['unit'] = 'Unit';
							$arr[0]['avg_unit_price'] = 'Average Unit Price';
							$arr[0]['total_price'] = 'Total Price';
						?>
					<?php 			
						if(!empty($productList)){	
							$rownum = $in_total = 0;
							foreach($productList as $product){
								$class = (($rownum%2)==0) ? ' class="even"' : ' class="odd"';
								$sl = $rownum+1;
								$sql = "select product_balance, avg_price from ".DB_PREFIX."balance where product_id = '".$product->id."' AND hall_id = ".$hall_id."";
								$balanceArr = $dbObj->selectDataObj($sql);
								$balance = $balanceArr[0]->product_balance;
								$avg_price = $balanceArr[0]->avg_price;
								$total_price = $balance * $avg_price;
								$in_total += $total_price;
								if(empty($balance)){$balance = '0.00';}
					?>					
								<tr <?php echo $class; ?>>
									<td height="30">
									<?php 
										echo $product->name; 
										$arr[$sl]['product_name'] = $product->name;
									?>
									</td>
									
									<td align="right">
									<?php 
										echo view_number($balance);
										$arr[$sl]['total_balance'] = view_number($balance);
									?>
									</td>
									
									<td align="center">
									<?php 
										echo $product->u_name;
										$arr[$sl]['unit'] = $product->u_name;
									?>
									</td>
									
									<td align="right">
									<?php 
										echo view_number($avg_price);
										$arr[$sl]['avg_unit_price'] = view_number($avg_price);
									?>
									</td>	
									
									<td align="right"> 
									<?php 
										echo view_number($total_price); 
										$arr[$sl]['total_price'] = view_number($total_price);
									?>
									</td>
								</tr>
								
							<?php 
										
								$rownum++;
							}//foreach
							?>
								<tr>		
									<td colspan="5" align="right" style="padding-top:5px;">
										<strong><?php echo NET_TOTAL_PRICE;?>:</strong>&nbsp;&nbsp;
										<?php echo view_number($in_total);?>
									</td>
								</tr>
							<?php
							$arr[$rownum+1]['product_name'] = '';
							$arr[$rownum+1]['balance'] = '';
							$arr[$rownum+1]['unit'] = '';
							$arr[$rownum+1]['avg_unit_price'] = 'Net Total Price';
							$arr[$rownum+1]['total_price'] = view_number($in_total);
							
						}else{ ?>
								<tr>
									<td colspan="5" height="50"><?php echo NO_PRODUCT_FOUND; ?></td>
								</tr>
					<?php }//else ?>			
					</table>

		<?php	}//if not empty hall_id  ?>
				</td>
			</tr>
		</table>
		<?php }//if posted == true 
			$_SESSION['available_stock_report'] = '';
			$_SESSION['available_stock_report'][0] = $downloadTitle; 
			$_SESSION['available_stock_report'][1] = $arr;
		
	}//if action == view?>
</div>
			
<?php
require_once("includes/footer.php");
?>