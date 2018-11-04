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
	
	<?php if($action=="view"){ 
		$sql = "select * from ".DB_PREFIX."hall order by id asc";
		$userGroupList = $dbObj->selectDataObj($sql);
		//$user = $userGroupList[0];
		/*echo '<pre>';
		print_r($user);*/
		foreach($userGroupList as $user){
	?>
		<div class="hall">
			<div id="hall_image">
				<?php if($user->image1 == ''){ ?>
				<img src="attach_file/home_unknow.png" height="100" width="150" />
				<?php }else{ ?>
				<img src="attach_file/<?php echo $user->image1; ?>" height="100" width="150" />
				<?php }//else ?>
			</div>
			<div id="hall_description">
				<h1 class="hallname"><?php echo $user->name; ?></h1>
				<p><strong>Details:</strong> <?php echo $user->short_description; ?></p>
			</div>
		</div>				
	<?php 
		}//foreach
	}//elseif ?>
</div>

<?php
require_once("includes/footer.php");
?>