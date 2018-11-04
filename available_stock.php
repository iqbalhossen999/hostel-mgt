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
			
			//Build Hall Array
			$sql = "select id, name from ".DB_PREFIX."hall order by name asc";
			$hallArr = $dbObj->selectDataObj($sql);
			//echo '<pre>';print_r($hallArr);
			
			$hallId = array();
			$hallId[0] = SELECT_HALL_OPT;
			if(!empty($hallArr)){			
				foreach($hallArr as $item){
					$hallId[$item->id] = $item->name;
				}	
			}			
			$hallList_opt = formSelectElement($hallId, $hall_id, 'hall_id', 'onchange = processFunction("get_available_stock")');
			
			$action = 'view';
			

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
				<h1><?php echo AVAILABLE_STOCK; ?></h1>
			</td>
			<td class="usr_info">
				<?php echo welcomeMsg($cur_user_id); ?>
			</td>			
		</tr>
	</table>
	<?php
		if($action=="view"){
	?>
		<form action="available_stock.php" method="post" name="available_stock" id="available_stock" onsubmit="return checkDate();" >
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
					<td colspan="2">
						<div id="loaderContainer"></div>
						<div id="available_stock_view"></div>
					</td>
				</tr>
			</table>
		</form>
			
	<?php }?>
</div>
			
<?php
require_once("includes/footer.php");
?>