<?php
$curr_uri = $_SERVER['PHP_SELF'];
$curr_uri_arr = explode("/", $curr_uri);
$leng = sizeof($curr_uri_arr);
$curr_page = $curr_uri_arr[($leng-1)];

if($usr[0]->group_id == '3'){
	$sql = "select status from ".DB_PREFIX."prebooking WHERE user_id='".$usr[0]->id."'";	
	$studentArr = $dbObj->selectDataObj($sql);
	$std_status = $studentArr[0];
	$status = $std_status->status;
	
	if($status == '1'){
		$add_cond = 'studentlist.php?action=detail';
	}else{
		$add_cond = 'form.php';
	}//else
}//if
?>
<div id="leftMenu">
	<ul id="menu">
	<h3 id="top">&nbsp;</h3>
		<?php if(empty($usr)){?>
			<li <?php if($curr_page == 'dashboard.php') echo 'class="active_menu"';?>>
				<a href="dashboard.php" class="dashboard" rel="dashboard"><?php echo HOME; ?></a>
			</li>
			
			<li <?php if($curr_page == 'prebooking.php') echo 'class="active_menu"';?>>
				<a href="prebooking.php" class="prebooking" rel="prebooking"><?php echo PREBOOKING; ?></a>
			</li>
		<?php }else{ 
			if($usr[0]->group_id == '1'){
		?>
		<li <?php if($curr_page == 'hall.php' || $curr_page == 'block.php' || $curr_page == 'floor.php' || $curr_page == 'room.php' || $curr_page == 'seat.php' || $curr_page == 'session.php' || $curr_page == 'user.php' || $curr_page == 'pre_request.php' || $curr_page == 'studentlist.php' || $curr_page == 'form.php' || $curr_page == 'hallcharge.php' || $curr_page == 'room_facilities.php' || $curr_page == 'time_setup.php' || $curr_page == 'patern.php' || $curr_page == 'change_pass.php' || $curr_page == 'guest_meal_mgt.php') echo 'class="active_menu"';?>>
        	<a href="#" class="hall_management" rel="hall_management"><?php echo ABOUT_HALL; ?></a>
			<ul>
			 	<li <?php if($curr_page == 'hall.php' || $curr_page == 'block.php' || $curr_page == 'floor.php' || $curr_page == 'room.php' || $curr_page == 'seat.php') echo 'class="active_menu"';?>>
					<a href="hall.php" class="hall" rel="hall"><?php echo HALL; ?></a>
				</li>
				<li <?php if($curr_page == 'patern.php') echo 'class="active_menu"';?>>
					<a href="patern.php" class="patern" rel="patern"><?php echo ROOM_CATEGORY; ?></a>
				</li>
				<li <?php if($curr_page == 'session.php') echo 'class="active_menu"';?>>
					<a href="session.php" class="session" rel="session">Session</a>
				</li>
				<li <?php if($curr_page == 'user.php') echo 'class="active_menu"';?>>
					<a href="user.php" class="user" rel="user"><?php echo USER_MANAGEMENT; ?></a>
				</li>
				<li <?php if($curr_page == 'pre_request.php') echo 'class="active_menu"';?>>
					<a href="pre_request.php" class="pre_request" rel="pre_request"><?php echo PENDING_REQUEST; ?></a>
				</li>
				<li <?php if($curr_page == 'studentlist.php' || $curr_page == 'form.php') echo 'class="active_menu"';?>>
					<a href="studentlist.php" class="studentlist" rel="studentlist"><?php echo STUDENT_LIST; ?></a>
				</li>
				<li <?php if($curr_page == 'change_pass.php') echo 'class="active_menu"';?>>
					<a href="change_pass.php" class="change_pass" rel="change_pass"><?php echo CHANGE_PASS; ?></a>
				</li>
				<li <?php if($curr_page == 'hallcharge.php') echo 'class="active_menu"';?>>
					<a href="hallcharge.php" class="seat" rel="seat"><?php echo HALL_CHARGE; ?></a>
				</li>
				 <li <?php if($curr_page == 'guest_meal_mgt.php') echo 'class="active_menu"';?>>
					<a href="guest_meal_mgt.php" class="guest_meal_mgt" rel="guest_meal_mgt"><?php echo GUEST_MEAL_MANAGEMENT; ?></a>
				</li>
				<li <?php if($curr_page == 'time_setup.php') echo 'class="active_menu"';?>>
					<a href="time_setup.php" class="time_setup" rel="time_setup"><?php echo MEAL_BOOKING_TIME; ?></a>
				</li>
			</ul>
		</li>
		
		<li <?php if($curr_page == 'unit.php' || $curr_page == 'product.php' || $curr_page == 'product_category.php') echo 'class="active_menu"';?>>
        	<a href="course.php" class="course" rel="course"><?php echo STOCK_SETUP; ?></a>
			<ul>
			 	<li <?php if($curr_page == 'unit.php') echo 'class="active_menu"';?>>
					<a href="unit.php" class="unit" rel="unit"><?php echo UNIT; ?></a>
				</li>
				 <li <?php if($curr_page == 'product_category.php') echo 'class="active_menu"';?>>
					<a href="product_category.php" class="product_category" rel="product_category"><?php echo PRODUCT_CATEGORY; ?></a>
				</li>
	             <li <?php if($curr_page == 'product.php') echo 'class="active_menu"';?>>
					<a href="product.php" class="product" rel="product"><?php echo PRODUCT; ?></a>
				</li>
			</ul>
		</li>
		
		<li <?php if($curr_page == 'stock.php' || $curr_page == 'view_stock.php' || $curr_page == 'consume.php' || $curr_page == 'view_consume.php' || $curr_page == 'available_stock.php') echo 'class="active_menu"';?>>
			<a href="#" class="stock" rel="stock"><?php echo STOCK_MANAGEMENT; ?></a>
			<ul>
				<li <?php if($curr_page == 'stock.php') echo 'class="active_menu"';?>>
					<a href="stock.php" class="stock" rel="stock"><?php echo ADD_STOCK; ?></a>
				</li>
				<li <?php if($curr_page == 'view_stock.php') echo 'class="active_menu"';?>>
					<a href="view_stock.php" class="view_stock" rel="view_stock"><?php echo VIEW_STOCK; ?></a>
				</li>
				<li <?php if($curr_page == 'consume.php') echo 'class="active_menu"';?>>
					<a href="consume.php" class="consume" rel="consume"><?php echo CONSUME_STOCK; ?></a>
				</li>
				<li <?php if($curr_page == 'view_consume.php') echo 'class="active_menu"';?>>
					<a href="view_consume.php" class="view_consume" rel="view_consume"><?php echo VIEW_CONSUME; ?></a>
				</li>
				<li <?php if($curr_page == 'available_stock.php') echo 'class="active_menu"';?>>
					<a href="available_stock.php" class="available_stock" rel="available_stock"><?php echo AVAILABLE_STOCK; ?></a>
				</li>
			</ul>
		</li>
		
		<li <?php if(($curr_page == 'meal_order_view.php') || ($curr_page == 'view_order.php') || ($curr_page == 'individual_date_wise.php') || ($curr_page == 'date_wise_order.php')
				 || ($curr_page == 'date_wise.php') || ($curr_page == 'stock_rep.php') || ($curr_page == 'item_stock_rep.php') || ($curr_page == 'consump_rep.php') || ($curr_page == 'item_consump_rep.php') || ($curr_page == 'aval_rep.php') || ($curr_page == 'student_mess_report.php') || ($curr_page == 'report.php')) echo 'class="active_menu"';?>>
			<a href="#" class="course" rel="course"><?php echo REPORTS; ?></a>
			<ul>
				<li <?php if($curr_page == 'meal_order_view.php') echo 'class="active_menu"';?>>
					<a href="meal_order_view.php" class="meal_order_view" rel="meal_order_view"><?php echo INDIVIDUAL_DATE_ORDERS; ?></a>
				</li>
				<li <?php if($curr_page == 'view_order.php') echo 'class="active_menu"';?>>
					<a href="view_order.php" class="view_order" rel="view_order"><?php echo INDIVIDUAL_STUDENT_ORDERS; ?></a>
				</li>
				<li <?php if($curr_page == 'individual_date_wise.php') echo 'class="active_menu"';?>>
					<a href="individual_date_wise.php" class="individual_date_wise" rel="individual_date_wise"><?php echo INDIVIDUAL_STUDENT_REPORTS; ?></a>
				</li>
				<li <?php if($curr_page == 'date_wise_order.php') echo 'class="active_menu"';?>>
					<a href="date_wise_order.php" class="date_wise_order" rel="date_wise_order"><?php echo DATEWISE_ORDERS; ?></a>
				</li>
				<li <?php if($curr_page == 'date_wise.php') echo 'class="active_menu"';?>>
					<a href="date_wise.php" class="date_wise" rel="date_wise"><?php echo DATEWISE_STUDENT_REPORTS; ?></a>
				</li>
				<li <?php if($curr_page == 'stock_rep.php') echo 'class="active_menu"';?>>
					<a href="stock_rep.php" class="sotck_rep" rel="sotck_rep"><?php echo STOCK_REPORTS; ?></a>
				</li>
				<li <?php if($curr_page == 'item_stock_rep.php') echo 'class="active_menu"';?>>
					<a href="item_stock_rep.php" class="item_stock_rep" rel="item_stock_rep"><?php echo ITEMWISE_STOCK_REPORTS; ?></a>
				</li>
				<li <?php if($curr_page == 'consump_rep.php') echo 'class="active_menu"';?>>
					<a href="consump_rep.php" class="consump_rep" rel="consump_rep"><?php echo CONSUMPTION_REPORTS; ?></a>
				</li>
				<li <?php if($curr_page == 'item_consump_rep.php') echo 'class="active_menu"';?>>
					<a href="item_consump_rep.php" class="item_consump_rep" rel="item_consump_rep"><?php echo ITEMWISE_CONSUMPTION_REPORTS; ?></a>
				</li>
				<li <?php if($curr_page == 'aval_rep.php') echo 'class="active_menu"';?>>
					<a href="aval_rep.php" class="aval_rep" rel="aval_rep"><?php echo AVAILABLE_STOCK_REPORTS; ?></a>
				</li>
				<li <?php if($curr_page == 'student_mess_report.php') echo 'class="active_menu"';?>>
					<a href="student_mess_report.php" class="student_mess_report" rel="student_mess_report"><?php echo STUDENT_MESS_BILL_REPORT; ?></a>
				</li>
				<li <?php if($curr_page == 'report.php') echo 'class="active_menu"';?>>
					<a href="report.php" class="report" rel="report"><?php echo STUDENT_MONTHLY_REPORT; ?></a>
				</li>
			</ul>
		</li>
		<?php }else if($usr[0]->group_id == '3'){ ?>
		<li <?php if($curr_page == 'form.php') echo 'class="active_menu"';?>>
			<a href="<?php echo $add_cond;?>" class="form" rel="form"><?php echo ADMISSION_FORM; ?></a>
		</li>
		
		<li <?php if($curr_page == 'mealorder.php' || $curr_page == 'view_order.php') echo 'class="active_menu"';?>>
			<a href="#" class="course" rel="course"><?php echo MEAL_ORDER; ?></a>
			<ul>
				<li <?php if($curr_page == 'mealorder.php') echo 'class="active_menu"';?>>
					<a href="mealorder.php" class="mealorder" rel="mealorder"><?php echo ORDER; ?></a>
				</li>
				<li <?php if($curr_page == 'view_order.php') echo 'class="active_menu"';?>>
					<a href="view_order.php" class="view_order" rel="view_order"><?php echo VIEW_ORDER; ?></a>
				</li>
			</ul>
		</li>
		
		<li <?php if($curr_page == 'individual_date_wise.php') echo 'class="active_menu"';?>>
			<a href="individual_date_wise.php" class="individual_date_wise" rel="individual_date_wise"><?php echo MEAL_REPORT; ?></a>
		</li>
		<?php }else if($usr[0]->group_id == '2'){?>	
		<li <?php if($curr_page == 'meal_order_view.php') echo 'class="active_menu"';?>>
			<a href="meal_order_view.php" class="meal_order_view" rel="meal_order_view"><?php echo MEAL_ORDER_VIEW; ?></a>
		</li>
		<?php }//elseif 
			
		if($usr[0]->group_id == '3'){ ?>
			<li <?php if($curr_page == 'change_pass.php') echo 'class="active_menu"';?>>
				<a href="change_pass.php" class="change_pass" rel="change_pass"><?php echo CHANGE_PASS; ?></a>
			</li>
	<?php }//if
	 }//if ?>
	</ul>
	
		
	<h3 id="bottom">&nbsp;</h3>
	
</div>
