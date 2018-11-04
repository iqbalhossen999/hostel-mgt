<?php
require_once("includes/header.php");
switch($action){
	case 'view':	
	default:
		
		$action = 'view';
		
		break;
	
}//switch

require_once("includes/templates.php");
require_once("templates/top_menu.php");
require_once("templates/left_menu.php");
?>
<div id="right_column2">
	
	<?php if($action=="view"){ ?>
	
	<script>
	$(document).ready(function() {
		
		var options = {};
	
		var param = 'type_navigation';
		var value = 'dots_preview';
		
		$('.border_box').css({'marginBottom': '40px'});
		options['dots'] = true;
		options['preview'] = true;
		$('.box_skitter_large').skitter(options);
		
		// Highlight
		$('pre.code').highlight({source:1, zebra:1, indent:'space', list:'ol'});
		
	});
	</script>
		<div class="border_box">
			<div class="box_skitter box_skitter_large">
				<ul>
					<li><a href="#cube"><img src="images/slider/001.jpg" class="cube" /></a><div class="label_text"></div></li>
					<li><a href="#cubeRandom"><img src="images/slider/002.jpg" class="cubeRandom" /></a><div class="label_text"></div></li>
					<li><a href="#block"><img src="images/slider/003.jpg" class="block" /></a><div class="label_text"></div></li>
				</ul>
			</div>
		</div>
		<div id="main_container">
			<div id="dashbord"><?php echo BUP_HALL; ?></div>
			<div id="description_home">
					<p class="paragraph"><b style="font-weight:bolder">Contact: </b></br>Phone +88 02-8000261-4 Ext.671,</br>Fax+88 -02-8035903</br>Email-buphall@yahoo.com</br></br>
					Md. Abu Noman</br>Manager, BUP Students Hall</br></br>
					Moshiur Rahman</br>Hall Manager,<br />BUP Students Hall.<br />01710-848915/01767-454903.</br></br>
					Md.Ruhul Amin</br>Mobile: 01925-902591.</br></p>
			</div>
			<?php if(!empty($usr)){ ?>
			<div id="icon_set">
				<!--image gellary-->
				<div class="img">
					<a href="stock.php"><img src="images/icon_store.png" alt="Klematis" width="103" height="94" /></a><br />
					<p class="desc"><?php echo STORE_MANAGEMENT; ?></p>
				</div>
				<div class="img">
					<a href="#"><img src="images/icon_Configuration.png" alt="Klematis" width="103" height="94" /></a><br />
					<p class="desc"><?php echo CONFIGURATION; ?></p>
				</div>
				<div class="img">
					<a href="studentlist.php"><img src="images/icon_student.png" alt="Klematis" width="103" height="94" /></a><br />
					<p class="desc"><?php echo STUDENT_INFORMATION; ?></p>
				</div>
				<div class="img">
					<a href="studentlist.php"><img src="images/icon_bed.png" alt="Klematis" width="103" height="94" /></a><br />
					<p class="desc"><?php echo ROOM_BOOKING; ?></p>
				</div>
				<div class="img">
					<a href="hall.php"><img src="images/icon_hall.png" alt="Klematis" width="103" height="94" /></a><br />
					<p class="desc"><?php echo HALL_SETUP; ?></p>
				</div>
				<div class="img">
					<a href="product.php"><img src="images/icon_Food.png" alt="Klematis" width="103" height="94" /></a><br />
					<p class="desc"><?php echo PRODUCT_CATALOGE; ?></p>
				</div>
				<div class="img">
					<a href="#"><img src="images/icon_Finance.png" alt="Klematis" width="103" height="94" /></a><br />
					<p class="desc"><?php echo FINANCE; ?></p>
				</div>
				<div class="img">
					<a href="#"><img src="images/icon_reports.png" alt="Klematis" width="103" height="94" /></a><br />
					<p class="desc"><?php echo REPORTS; ?></p>
				</div>
				<div class="img">
					<a href="#"><img src="images/icon_messing.png" alt="Klematis" width="103" height="94" /></a><br />
					<p class="desc"><?php echo STUDENT_MESSING; ?></p>
				</div>
			</div>
			<?php }//if ?>
		</div>
						
	<?php }//elseif ?>
</div>

<?php
require_once("includes/footer.php");
?>