<?php 
$usr = $user->getUser();
if(empty($usr)){
	$title = LOG_IN;
	$go_to = 'login.php';
}else{
	$title = LOG_OUT;
	$go_to = 'logout.php';
}
?>
<div id="topBar">
	<div id="topmenu">
		<div id="log_page"><a href="<?php echo $go_to; ?>" id="home_icon"><?php echo $title;?></a></div>
		<div id="separate"><img src="images/separetor.gif" /></div>
		<div id="home_page"><a href="index.php" id="home_icon"><?php echo HOME;?></a></div>	
	</div>	
</div>