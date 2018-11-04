<?php
require_once("includes/header.php");

$action = $_REQUEST['action'];

require_once("includes/templates.php");
require_once("templates/top_menu.php");
require_once("templates/left_menu.php");
?>

<div id="right_column2">
	
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
	<div id="dashbord"><?php echo HALL_RULES; ?></div>
	
	<table width="100%" cellspacing="0" cellpadding="0" border="0" class="module_header">
		<tr>
			<td align="center" colspan="2" height="60"><h1><u><?php echo STUDENT_RULES; ?></u></h1></td>
		</tr>
		<tr>
			<td width="5%" valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_1;?></td>
			<td width="95%" valign="top" class="hall_rulse"><?php echo HALL_RULES_1;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_2;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_2;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_3;?></td>
			<td  valign="top" class="hall_rulse"><?php echo HALL_RULES_3;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_4;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_4;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_5;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_5;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_6;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_6;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_7;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_7;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_8;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_8;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_9;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_9;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_10;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_10;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_11;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_11;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_12;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_12;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_13;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_13;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_14;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_14;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_15;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_15;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_16;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_16;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_17;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_17;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_18;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_18;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_19;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_19;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_20;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_20;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_21;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_21;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_22;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_22;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_23;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_23;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_24;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_24;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_25;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_25;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_26;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_26;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_27;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_27;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_28;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_28;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_29;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_29;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_30;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_30;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_31;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_31;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_32;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_32;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_33;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_33;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_34;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_34;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_35;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_35;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_36;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_36;?></td>
		</tr>
		<tr>
			<td valign="top" class="hall_rulse" height="30" ><?php echo NUMBER_37;?></td>
			<td valign="top" class="hall_rulse"><?php echo HALL_RULES_37;?></td>
		</tr>
		<tr>
			<td align="center" class="hall_rulse" colspan="2" height="50"><?php echo HALL_STUDENT_COMMENT;?></td>
		</tr>
	</table>
	
	
</div>
			
<?php
require_once("includes/footer.php");
?>