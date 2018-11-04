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
	case 'view':	
	default:

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
		$hallList_opt = formSelectElement($hallId, $hall_id, 'hall_id', 'onchange = processFunction("get_year")');
		
		//Build Year Array
		$yearArr = array();
		$yearArr[0] = SELECT_YEAR_OPT;
		for($i = 2012; $i <= date('Y'); $i++){
			$yearArr[$i] = $i;
		}
		$yearList_opt = formSelectElement($yearArr, $year, 'year', 'onchange = processFunction("get_setting")');
		
		$action = 'view';
		break;
	
	case 'save':	

		$hall_id = $_POST['hall_id'];
		$year = $_POST['year'];
		$estab = $_POST['estab'];
		$readm = $_POST['readm'];
		$sd = $_POST['sd'];
		$messad = $_POST['messad'];
		$donation = $_POST['donation'];
		$seatrent = $_POST['seatrent'];
		$utencro = $_POST['utencro'];
		$maint = $_POST['maint'];
		$crnpape = $_POST['crnpape'];
		$inter = $_POST['inter'];
		$conti = $_POST['conti'];
		
		//Delete Existing Data of same Hall & same year from Hall Charge Table & Seat Charge Table
		$where = "hall_id = '".$hall_id."' AND year = '".$year."'";	
		$delete = $dbObj->deleteTableData("hall_charge", $where);
		$delete2 = $dbObj->deleteTableData("seat_charge", $where);
		
		//NOw insert into hall charge table
		$fields = array(
						'hall_id' => $hall_id,
						'`year`' => $year,
						'estab' => $estab,
						'sd' => $sd,
						'readm' => $readm,
						'sd' => $sd,
						'messad' => $messad,
						'donation' => $donation,
						'seatrent' => $seatrent,
						'utencro' => $utencro,
						'maint' => $maint,
						'crnpape' => $crnpape,
						'inter' => $inter,
						'conti' => $conti,
						'created_by' => $cur_user_id,
						'created_datetime' => current_date_time()
						);
						
		$setting = $dbObj->insertTableData("hall_charge", $fields);
		
		
		//Fetch all seat of same hall from seat table
		$sql = "select id from ".DB_PREFIX."seat WHERE hall_id = '".$hall_id."'";
		$seatArr = $dbObj->selectDataObj($sql);
		$total_seat = count($seatArr);
		//echo '<pre>';print_r($seatArr);
		if(!empty($seatArr)){
			$total_inserted = 0;
			foreach($seatArr as $item){
				$fields = array();
				$fields = array(
						'hall_id' => $hall_id,
						'year' => $year,
						'seat_id' => $item->id,
						'estab' => $estab,
						'sd' => $sd,
						'readm' => $readm,
						'sd' => $sd,
						'messad' => $messad,
						'donation' => $donation,
						'seatrent' => $seatrent,
						'utencro' => $utencro,
						'maint' => $maint,
						'crnpape' => $crnpape,
						'inter' => $inter,
						'conti' => $conti,
						'created_by' => $cur_user_id,
						'created_datetime' => current_date_time()
						);
				$insert_seat_data = $dbObj->insertTableData("seat_charge", $fields);
				if(!$insert_seat_data){
					$msg = 'Could not execute data while inserting on Seat Charge!';
					$action = 'view';
				}
				
				$total_inserted += 1;
			}//foreach
		}//if
		
		if($total_inserted != $total_seat){
			$msg = 'Could not update setting';	
			$action = 'view';
		}else{
			$msg = 'Setting has been changed successfully';
			$url = 'hallcharge.php?action=view&msg='.$msg;
			redirect($url);
		}
		
		break;

}//switch


require_once("includes/templates.php");
require_once("templates/top_menu.php");
require_once("templates/left_menu.php");
?>

<div id="right_column">
	<?php if(!empty($msg)){ ?>
		<table id="system_message">
			<tr>
				<td>
					<?php echo $msg; ?>
				</td>
			</tr>
		</table>
	<?php } ?>
	<table width="100%" cellspacing="0" cellpadding="0" border="0" class="module_header">
		<tr>
			<td>
				<h1><?php echo HALL_CHARGE; ?></h1>
			</td>	
			<td class="usr_info">
				<?php echo welcomeMsg($cur_user_id); ?>
			</td>			
		</tr>
	</table>
	<?php if($action=="view"){ ?>
		<form action="hallcharge.php" method="post" name="seatsetting" id="seatsetting" onsubmit="return validateseat();">
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
				<tr>
					<td height="30" width="20%"><?php echo HALL_NAME; ?>:</td>
					<td width="80%"><?php echo $hallList_opt; ?></td>
				</tr>
				
				<tr>
					<td height="30"><?php echo YEAR; ?>:</td>
					<td>
						<div id="loaderContainer"></div>
						<div id="year_display"><?php echo $yearList_opt; ?></div>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<div id="loaderContainer"></div>
						<div id="setting_display"></div>
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