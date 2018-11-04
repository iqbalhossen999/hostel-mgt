<?php
require_once("includes/header.php");

$action = $_REQUEST['action'];

//Pagination
$limit = PAGE_LIMIT_DEFAULT;


switch($action){
	
	case 'detail':
	default:	
		$id = $_REQUEST['id'];
		
		$sql = "select * from ".DB_PREFIX."notice WHERE id='".$id."'";	
		$noticeList = $dbObj->selectDataObj($sql);		
		$notice = $noticeList[0];
		$action = 'detail';
		$msg = $_REQUEST['msg'];
		
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
				<h1><?php echo NOTICE_MANAGEMENT; ?></h1>
			</td>		
		</tr>
	</table>
			
	<?php if($action=="detail"){?>
		<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
			<tr>
				<td height="30" width="20%">
					<?php echo ISSUE_DATE; ?>:
				</td>
				<td width="80%">
					<?php echo dateConvertion($notice->issue_date); ?>
				</td>
			</tr>
			<tr>
				<td height="30">
					<?php echo SUBJECT; ?>:
				</td>
				<td>
					<?php 
					echo $notice->subject;
					?>
				</td>
			</tr>
			<tr>
				<td height="30">
					<?php echo DESCRIPTION; ?>:
				</td>
				<td>
					<?php 
					echo $notice->description;
					?>
				</td>
			</tr>
			<tr>
				<td height="30">
					<?php echo ATTACH_FILE; ?>:
				</td>
				<td>
					<?php if(!empty($notice->attached_file)){
								$ext_expl = explode(".", $notice->attached_file);
								$name = $ext_expl[0];
								$ext = $ext_expl[sizeof($ext_expl)-1];
								if($ext == 'pdf'){?>
								<a target="_blank" href="<?php echo 'attach_file/'.$notice->attached_file;?>" ><img src="images/pdf.png" title="Click here to view this file" /></a>
								<?php } ?>
								<a href="libraries/download.php?file=<?php echo $notice->attached_file;?>" ><img src="images/download.png" title="Click this icon to Download" /></a>
					<?php }//if ?>
				</td>
			</tr>
		</table>	
	<?php } ?>
</div>
			
<?php
require_once("includes/footer.php");
?>