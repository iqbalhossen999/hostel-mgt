<?php
require_once("includes/header.php");

$usr = $user->getUser();

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
<div id="main_container">
	<?php if($usr[0]->group_id == '1'){ ?>
		<div id="icon_set">
			<!--image gellary-->
			<div class="img">
				<a href="stock.php"><img src="images/icon_store.png" alt="Klematis" width="103" height="94" /></a><br />
				<p class="desc"><?php echo STOCK_MANAGEMENT; ?></p>
			</div>
			<div class="img">
				<a href="pre_request.php"><img src="images/icon_student.png" alt="Klematis" width="103" height="94" /></a><br />
				<p class="desc"><?php echo PENDING_REQUEST; ?></p>
			</div>
			<div class="img">
				<a href="studentlist.php"><img src="images/icon_bed.png" alt="Klematis" width="103" height="94" /></a><br />
				<p class="desc"><?php echo STUDENT_INFORMATION; ?></p>
			</div>
			<div class="img">
				<a href="hall.php"><img src="images/icon_hall.png" alt="Klematis" width="103" height="94" /></a><br />
				<p class="desc"><?php echo HALL_SETUP; ?></p>
			</div>
			<div class="img">
				<a href="product.php"><img src="images/icon_Food.png" alt="Klematis" width="103" height="94" /></a><br />
				<p class="desc"><?php echo PRODUCT_CATELOG; ?></p>
			</div>

		</div>
	<?php }//if ?>
</div>

<?php }//elseif ?>
</div>

<?php
require_once("includes/footer.php");
?>