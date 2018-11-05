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
$order_date = $_REQUEST['order_date'];

if($cur_user_group_id == '3'){
	dashboard();
}//if

switch($action){

	case 'view':
	default:
	
	if(isset($_POST['submit'])){
		$posted = $_REQUEST['posted'];
		$hall_id = $_REQUEST['hall_id'];
		
		if($hall_id != '0'){
			$hall_added_cond = " AND p.hall_id = '".$hall_id."' ";
		}
		
		$sql = "select m.student_id, m.breakfast, m.lunch, m.dinner, p.registration_no, p.name from ".DB_PREFIX."meal as m, ".DB_PREFIX."prebooking as p, ".DB_PREFIX."user as u where m.student_id= u.id AND u.id=p.user_id AND p.user_id= m.student_id ".$hall_added_cond." AND m.hall_id = p.hall_id AND m.order_date = '".$order_date."' group by m.student_id  order by p.registration_no asc";
		$mealList = $dbObj->selectDataObj($sql);
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
				<h1><?php echo INDIVIDUAL_DATE_ORDERS; ?></h1>
			</td>
			<td class="usr_info">
				<?php echo welcomeMsg($cur_user_id); ?>
			</td>			
		</tr>
	</table>
	
	<?php if($action=="view"){ ?>
		
		<form action="meal_order_view.php" method="post" name="meal_order_view" id="meal_order_view" onsubmit="return validateMealOrderView();" >
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">	
				
				<tr>
					<td height="30" width="20%">
						<?php echo HALL_NAME; ?>:
					</td>
					<td width="80%">
						<?php 
						if($cur_user_group_id == '1'){
							echo $hallList_opt;
						}else{
							$hallNam = getNameById('hall', $usr[0]->hall_id);
							echo '<b>'.$hallNam->name.'</b><input type="hidden" name="hall_id" id="hall_id" value="'.$usr[0]->hall_id.'" />';
						}?>
					</td>
				</tr>
				<tr>
					<td height="30">
						<?php echo ISSUE_DATE; ?>:
					</td>
					<td>
						<input name="order_date" id="order_date" type="text" class="inputbox readonly" readonly="readonly" alt="Issue Date" size="18" value="<?php echo $order_date; ?>" />
						<img id="f_rangeStart_triggerm_start" src="date/src/css/img/calendar.gif" title="Pick a Date" />
						<img id="f_clearRangeStart" src="date/src/css/img/no.png" title="Clear Date" onClick="return makeEmpty('start_date')" height="16" width="16" />
						<script type="text/javascript">
							RANGE_CAL_1 = new Calendar({
								inputField: "order_date",
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
				</tr>
				<tr>
					<td colspan="2">
						<input type="submit" name="submit" class="button" value="View Report"/>
					</td>
				</tr>		
			</table>
			<input type="hidden" name="action" value="view" />
			<input type="hidden" name="posted" value="true" />
		</form>
		
		<?php if($posted == 'true'){ ?>
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
				<tr>
					<td colspan="4">
						<table width="100%" cellpadding="0" cellspacing="0" border="0" class="datagrid">			
							<tr class="head">
								<td height="30" width="15%"><strong><?php echo REGISTRATION_NO; ?></strong></td>
								<td width="15%"><strong><?php echo STUDENT_NAME; ?></strong></td>
								<td width="15%" align="center"><strong><?php echo BREAKFAST; ?></strong></td>
								<td width="15%" align="center"><strong><?php echo LUNCH; ?></strong></td>
								<td width="15%" align="center"><strong><?php echo DINNER; ?></strong></td>	
								<td width="15%" align="center"><strong><?php echo INDIVIDUAL_TOTAL_ORDER; ?></strong></td>				
							</tr>
							<?php
								//For downloading Reports as XLS format
							//if group_id == 1 --->>> Only Super Admin can download the reprot
							if($cur_user_group_id == '1'){
								$downloadTitle[0] = 'Individual Date Order Report of '.$order_date."\n";
								$arr[0]['registration_no'] = 'Registration No';
								$arr[0]['name'] = 'Student Name';
								$arr[0]['breakfast'] = 'Breakfast';
								$arr[0]['lunch'] = 'Lunch';
								$arr[0]['dinner'] = 'Dinner';
								$arr[0]['ondate_order'] = 'Individual Total Order';
								
							} ?>
							
							<?php
							if(!empty($mealList)){	
								$rownum = $total_bf = $total_ln = $total_dn = 0;
								$total_breakfast = 0;
								$total_lunch = 0;
								$total_dinner = 0;								
								foreach($mealList as $target){	
									$class = (($rownum%2)==0) ? ' class="even"' : ' class="odd"';
									$individual_total_order = 0;
									$sl = $rownum + 1;
									//echo "serial".$sl;
									$breakfast = $mealList[$rownum]->breakfast;
									$lunch = $mealList[$rownum]->lunch;
									$dinner = $mealList[$rownum]->dinner;
									?>
									<tr <?php echo $class; ?>>
										<td height="30">
											<?php echo $mealList[$rownum]->registration_no;
											$arr[$sl]['registration_no'] = $mealList[$rownum]->registration_no; ?> 
										</td>
										<td>
											<?php echo $mealList[$rownum]->name;
											$arr[$sl]['name'] = $mealList[$rownum]->name; ?> 
										</td>
										<td align="center">
											<?php 
											echo $breakfast; 
											
											$individual_total_order += $breakfast;
											$arr[$sl]['breakfast'] = $mealList[$rownum]->breakfast;
											?>
										</td>
										<td align="center">
											<?php 
											echo $lunch;
											
											$individual_total_order += $lunch;
											$arr[$sl]['lunch'] = $mealList[$rownum]->lunch; 
											?>
										</td>
										<td align="center">
											<?php 
											echo $dinner;
											
											$individual_total_order += $dinner;
											$arr[$sl]['dinner'] = $mealList[$rownum]->dinner; 
											?>
										</td>
										<td align="center">
											<?php echo $individual_total_order;
											$arr[$sl]['individual_total_order'] = $individual_total_order; ?>
										</td>
										
									</tr>
									<?php
									$rownum++;
									$total_breakfast+= $breakfast;
									$total_lunch+= $lunch;
									$total_dinner+= $dinner;	
									$total_order += $individual_total_order;
								}//foreach
								
								$arr[$rownum+1]['registration_no'] = '';
								$arr[$rownum+1]['name'] = 'Total Order:';
								$arr[$rownum+1]['breakfast'] = $total_breakfast;
								$arr[$rownum+1]['lunch'] = $total_lunch;
								$arr[$rownum+1]['dinner'] = $total_dinner;
								$arr[$rownum+1]['ondate_order'] = $total_order;
								
								?>
								<tr height="30" class="meal_total">
									<td colspan="2">
										<strong><?php echo TOTAL_ORDER; ?>:</strong>
									</td>
									<td align="center">
										<strong><?php echo $total_breakfast; ?></strong>	
									</td>
									<td align="center">
										<strong><?php echo $total_lunch; ?></strong>	
									</td>
									<td align="center">
										<strong><?php echo $total_dinner; ?></strong>	
									</td>
									<td align="center">
										<strong><?php echo $total_order; ?></strong>	
									</td>
								</tr>		
								<?php
							}else{ ?>
								<tr height="30">
									<td colspan="5">
										<?php echo NO_ORDER_FOUND; ?>
									</td>
								</tr>
								<?php 
							}
							?>			
						</table>
					</td>
				</tr>
			</table>
			<input type="hidden" name="date" value="<?php echo $issue_date;?>" />
			<?php 
		}//if submitted
		?>	
		<?php 
		
		$_SESSION['meal_order'] = '';
		$_SESSION['meal_order'][0] = $downloadTitle; 
		$_SESSION['meal_order'][1] = $arr;
	}?>
</div>

<?php
require_once("includes/footer.php");
?>