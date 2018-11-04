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
switch($action){

	case 'view':
		default:
			$posted = $_REQUEST['posted'];
			
			if($posted == "true"){
				$year = $_REQUEST['year'];
				$month = $_REQUEST['month'];
				$hall_id = $_REQUEST['hall_id'];

				$sql_year = "SELECT distinct(id) sess_id, session_year FROM ".DB_PREFIX."session where session_year ='".$year."'";
				$sess_yearArr = $dbObj->selectDataObj($sql_year);
				$sess_id = $sess_yearArr[0]->sess_id;
				 
				$sql = "select p.user_id ,p.hall_id, p.session, p.registration_no, p.name, p.course_name, s.estab, s.readm, s.sd, s.messad, s.donation, s.seatrent, s.utencro, s.maint, s.crnpape, s.inter, s.conti from ".DB_PREFIX."prebooking as p, ".DB_PREFIX."seat_charge as s where p.seat_id != 0 AND s.seat_id = p.seat_id AND  p.session = '".$sess_id."' AND p.hall_id = '".$hall_id."'";
				$mess_report	= $dbObj->selectDataObj($sql);
				
				//Pagination 
				$total_rows = (!empty($mess_report)) ? sizeof($mess_report) : 0;
				$s = ($page - 1) * $limit;
				$total_page = $total_rows/$limit;
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
			
			$yearArr = array();
			for($i = 2012; $i <= date('Y'); $i++){
				$yearArr[$i] = $i;
			}
			$yearList_opt = formSelectElement($yearArr, $year, 'year');
			
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
				<h1><?php echo STUDENT_MONTHLY_REPORT; ?></h1>
			</td>
			<td class="usr_info">
				<?php echo welcomeMsg($cur_user_id); ?>
			</td>			
		</tr>
	</table>
	<?php
		if($action=="view"){
	?>
		<form action="report.php" method="post" name="report" id="report" onsubmit="return validateStudent_mess_bill();" >
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">	
				<tr>
					<td height="30" width="20%"><?php echo YEAR; ?>:</td>
					<td width="80%"><?php echo $yearList_opt; ?></td>
				</tr>
				<tr>
					<td height="30"><?php echo MONTH; ?>:</td>
					<td><?php echo monthList($month); ?></td>
				</tr>
				<tr>
					<td height="30"><?php echo HALL_NAME; ?>:</td>
					<td><?php echo $hallList_opt; ?></td>
				</tr>	
				<tr>
					<td colspan="2" height="30">
						<input type="submit" name="submit" class="button" value="View Report"/>
					</td>
				</tr>	
			</table>
			<input type="hidden" name="action" value="view" />
			<input type="hidden" name="posted" value="true" />
		</form>
	<?php 
		if($posted == 'true'){ 
	?>
	<div style="width:700px; overflow-x:scroll;">
		<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
			<tr>
				<td><a href="student_monthly_report_download.php"><img src="images/excel.png" height="24" width="24" alt="save the report" title="save the report" style="padding-bottom:10px;"/></a><br /></td>
			<tr>
			<tr>
				<td colspan="2">
					<h1><?php echo STUDENT_MONTHLY_REPORT; ?></h1>
				</td>
			</tr>	
				<tr>
					<td>
						<table width="100%" cellpadding="0" cellspacing="0" border="0" class="datagrid">				
							<tr class="head">
								<td height="30" width="10%">
									<strong><?php echo SL_NO; ?></strong>
								</td>
								<td width="10%">
									<strong><?php echo REGISTRATION_NO; ?></strong>
								</td>
								<td width="10%">
									<strong><?php echo STUDENT_NAME; ?></strong>
								</td>
								<td width="5%">
									<strong><?php echo COURSE_NAME; ?></strong>
								</td>
								
								<td width="5%">
									<strong><?php echo ESTAB; ?></strong>
								</td>
								<td width="5%">
									<strong><?php echo RE_ADM; ?></strong>
								</td>
								<td width="5%">
									<strong><?php echo SD; ?></strong>
								</td>
								<td width="5%">
									<strong><?php echo MESS_AD; ?></strong>
								</td>
								<td width="5%">
									<strong><?php echo DONATION; ?></strong>
								</td>
								<td width="5%">
									<strong><?php echo SEAT_RENT; ?></strong>
								</td>
								<td width="5%">
									<strong><?php echo UTEN_CRO; ?></strong>
								</td>
								<td width="5%">
									<strong><?php echo MAINT; ?></strong>
								</td>
								<td width="5%">
									<strong><?php echo CRNPAPE; ?></strong>
								</td>
								<td width="5%">
									<strong><?php echo INTER; ?></strong>
								</td>
								<td width="5%">
									<strong><?php echo CONTI; ?></strong>
								</td>
								<td width="5%">
									<strong><?php echo MESS_BILL; ?></strong>
								</td>
								<td width="5%">
									<strong><?php echo TOTAL; ?></strong>
								</td>
							</tr>
						
							<?php
							//For downloading Reports as XLS format
							//if group_id == 1 --->>> Only Super Admin can download the reprot
								$downloadTitle[0] = 'Student Monthly Bill Report'."\n";
								
								$arr[0]['sl'] = 'Sl No';
								$arr[0]['registration_no'] = 'Registration No';
								$arr[0]['name'] = 'Student Name';
								$arr[0]['course_name'] = 'Course Name';
								$arr[0]['estab'] = 'Estab';
								$arr[0]['readm'] = 'Re_Adm';
								$arr[0]['sd'] = 'SD';
								$arr[0]['messad'] = 'Mess_Ad';
								$arr[0]['donation'] = 'Donation';
								$arr[0]['seatrent'] = 'Seat Rent';
								$arr[0]['utencro'] = 'Utencro';
								$arr[0]['maint'] = 'Maint';
								$arr[0]['crnpape'] = 'Crnpape';
								$arr[0]['inter'] = 'Inter';
								$arr[0]['conti'] = 'Conti';
								$arr[0]['total'] = 'Mess Bill';
								$arr[0]['net_total'] = 'Total';
						
							 ?>
						
							<?php			
							if(!empty($mess_report)){
								$rownum = $net_total_price = $ttl = $estab_total = $readm_total = $sd_total = $messad_total = $donation_total = $seatrent_total = $utencro_total = $maint_total = $crnpape_total = $inter_total = $conti_total = $net_total = 0;
								$r = 1;
								foreach($mess_report as $report){
									$sql = "select sum(breakfast_cost + lunch_cost + dinner_cost) as total from ".DB_PREFIX."meal  where student_id = '".$report->user_id."' AND order_date >= '$year-$month-01' AND order_date <= '$year-$month-31'";
									$messBill= $dbObj->selectDataObj($sql);
									$arr[$r]['sl'] = $r;
									$arr[$r]['registration_no'] = $report->registration_no;
									$arr[$r]['name'] = $report->name;
									$arr[$r]['course_name'] = $report->course_name;
									$arr[$r]['estab'] = view_number($report->estab);
									$arr[$r]['readm'] = view_number($report->readm);
									$arr[$r]['sd'] = view_number($report->sd);
									$arr[$r]['messad'] = view_number($report->messad);
									$arr[$r]['donation'] = view_number($report->donation);
									$arr[$r]['seatrent'] = view_number($report->seatrent);
									$arr[$r]['utencro'] = view_number($report->utencro);
									$arr[$r]['maint'] = view_number($report->maint);
									$arr[$r]['crnpape'] = view_number($report->crnpape);
									$arr[$r]['inter'] = view_number($report->inter);
									$arr[$r]['conti'] = view_number($report->conti);
									$arr[$r]['total'] = view_number($messBill[0]->total);
									
									$estab_total += $report->estab;
									$readm_total += $report->readm;
									$sd_total += $report->sd;
									$messad_total += $report->messad;
									$donation_total += $report->donation;
									$seatrent_total += $report->seatrent;
									$utencro_total += $report->utencro;
									$maint_total += $report->maint;
									$crnpape_total += $report->crnpape;
									$inter_total += $report->inter;
									$conti_total += $report->conti;
									$ttl += $messBill[0]->total;
									$net_total = $report->estab + $report->readm + $report->sd + $report->messad +  $report->donation + $report->seatrent +  $report->utencro + $report->maint + $report->crnpape + $report->inter + $report->conti + $messBill[0]->total;
									$net_total_price += $net_total;
									$arr[$r]['net_total'] = view_number($net_total);
									$r++;
								}	
							
								$sl = ($limit*$page)-($limit-1);	
								$maxPageLimit = (($s+$limit) > $total_rows) ? $total_rows : ($s+$limit);
								for($rownum = $s; $rownum <$maxPageLimit; $rownum++){	
								$class = (($rownum%2)==0) ? ' class="even"' : ' class="odd"';
								$sql = "select sum(breakfast_cost + lunch_cost + dinner_cost) as total from ".DB_PREFIX."meal  where student_id = '".$mess_report[$rownum]->user_id."' AND order_date >= '$year-$month-01' AND order_date <= '$year-$month-31'";
								$messBill= $dbObj->selectDataObj($sql);
							?>
								
							<tr <?php echo $class; ?>>
								<td height="30"><?php echo $sl; ?> </td>	
								<td><?php echo $mess_report[$rownum]->registration_no; ?></td>				
								<td><?php echo $mess_report[$rownum]->name; ?></td>
								<td><?php echo $mess_report[$rownum]->course_name;?></td>
								<td align="right"><?php echo view_number($mess_report[$rownum]->estab); ?></td>
								<td align="right"><?php echo view_number($mess_report[$rownum]->readm); ?></td>
								<td align="right"><?php echo view_number($mess_report[$rownum]->sd); ?></td>
								<td align="right"><?php echo view_number($mess_report[$rownum]->messad);?></td>
								<td align="right"><?php echo view_number($mess_report[$rownum]->donation);?></td>
								<td align="right"><?php echo view_number($mess_report[$rownum]->seatrent); ?></td>
								<td align="right"><?php echo view_number($mess_report[$rownum]->utencro); ?></td>
								<td align="right"><?php echo view_number($mess_report[$rownum]->maint); ?></td>
								<td align="right"><?php echo view_number($mess_report[$rownum]->crnpape); ?></td>
								<td align="right"><?php echo view_number($mess_report[$rownum]->inter); ?></td>
								<td align="right"><?php echo view_number($mess_report[$rownum]->conti);?></td>
								<td align="right"><?php echo view_number($messBill[0]->total);?></td>
								<td align="right">
									<?php
										$net_total = $mess_report[$rownum]->estab + $mess_report[$rownum]->readm + $mess_report[$rownum]->sd + $mess_report[$rownum]->messad +  $mess_report[$rownum]->donation + $mess_report[$rownum]->seatrent +  $mess_report[$rownum]->utencro + $mess_report[$rownum]->maint + $mess_report[$rownum]->crnpape + $mess_report[$rownum]->inter + $mess_report[$rownum]->conti + $messBill[0]->total;
										 echo view_number($net_total); 
									?>
								</td>
							</tr>
							
							<?php 
									$sl++;
								$s++;
								}//for
							?>
							<tr height="50">
								<td colspan="4" align="right" style="padding:10px;"><strong><?php echo 'Net Total:'; ?></strong></td>
								<td align="right"><?php echo view_number($estab_total); ?>&nbsp;</td>
								<td align="right"><?php echo view_number($readm_total); ?>&nbsp;</td>
								<td align="right"><?php echo view_number($sd_total); ?>&nbsp;</td>
								<td align="right"><?php echo view_number($messad_total); ?>&nbsp;</td>
								<td align="right"><?php echo view_number($donation_total); ?>&nbsp;</td>
								<td align="right"><?php echo view_number($seatrent_total); ?>&nbsp;</td>
								<td align="right"><?php echo view_number($utencro_total); ?>&nbsp;</td>
								<td align="right"><?php echo view_number($maint_total); ?>&nbsp;</td>
								<td align="right"><?php echo view_number($crnpape_total); ?>&nbsp;</td>
								<td align="right"><?php echo view_number($inter_total); ?>&nbsp;</td>
								<td align="right"><?php echo view_number($conti_total); ?>&nbsp;</td>
								<td align="right"><?php echo view_number($ttl); ?>&nbsp;</td>
								<td align="right"><?php echo view_number($net_total_price); ?>&nbsp;</td>
							</tr>
						
					<?php }else{ ?>
							
							<tr height="30">
								<td colspan="3">
									<?php echo EMPTY_DATA; ?>
								</td>
							</tr>
							
						<?php 
						}//else
						if($total_page > 1){ ?>
							
						<tr height="50">
							<td colspan="17">
								<?php echo pagination($total_rows,$limit,$page,'&posted='.$posted.'&year='.$year.'&month='.$month.'&hall_id='.$hall_id); ?>
							</td>
						</tr>
							<?php }//if
								$arr[$r+1]['sl'] = "\n";
								$arr[$r+1]['sl'] = '';
								$arr[$r+1]['registration_no'] = '';
								$arr[$r+1]['name'] = '';
								$arr[$r+1]['course_name'] = 'Total Bill: ';
								$arr[$r+1]['estab'] = view_number($estab_total);
								$arr[$r+1]['readm'] = view_number($readm_total);
								$arr[$r+1]['sd'] = view_number($sd_total);
								$arr[$r+1]['messad'] = view_number($messad_total);
								$arr[$r+1]['donation'] = view_number($donation_total);
								$arr[$r+1]['seatrent'] = view_number($seatrent_total);
								$arr[$r+1]['utencro'] = view_number($utencro_total);
								$arr[$r+1]['maint'] = view_number($maint_total);
								$arr[$r+1]['crnpape'] = view_number($crnpape_total);
								$arr[$r+1]['inter'] = view_number($inter_total);
								$arr[$r+1]['conti'] = view_number($conti_total);
								$arr[$r+1]['total'] = view_number($ttl);
								$arr[$r+1]['net_total'] = view_number($net_total_price);
								 ?>			
					</table>
				</td>
			</tr>
		</table>
	</div>
	<?php 
		}//if true
	?>	
	<?php 
		
		$_SESSION['student_monthly_report'] = '';
		$_SESSION['student_monthly_report'][0] = $downloadTitle; 
		$_SESSION['student_monthly_report'][1] = $arr;
		
		}//if view
	?>	
</div>
			
<?php
require_once("includes/footer.php");
?>