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

if($cur_user_group_id == '2'){
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
		if($cur_user_group_id == '1'){
			$sql = "select * from ".DB_PREFIX."msg";
		}else{
			$sql = "select * from ".DB_PREFIX."msg WHERE created_by = '".$cur_user_id."'";
		}
		
		$msgList = $dbObj->selectDataObj($sql);
		$action = 'view';
		
		//Pagination 
		if(!empty($msgList)){
			$total_rows = sizeof($msgList);
		}else{
			$total_rows =0;
		}
		//find start
		$s = ($page - 1) * $limit;
		$total_page = $total_rows/$limit;
		
		break;
		
	case 'subject':
		$id = $_REQUEST['id'];
		$sql = "select m.subject, r.created_by, r.created_datetime, r.msg from ".DB_PREFIX."msg as m, ".DB_PREFIX."reply as r where m.id= r.msg_id and m.id= '".$id."' order by r.id asc";
		$msgArr = $dbObj->selectDataObj($sql);
		$sub = $msgArr[0]->subject;
		
		$action = 'subject';

		break;
		
	case 'create':
	
		$id = '';
		$subject = '';
		$msg = '';
	
		$action = 'insert';
		break;
		
	case 'save':	
		$id = $_POST['id'];
		$subject = $_POST['subject'];
		$message = $_POST['message'];
			
		$fields = array('subject' => $subject,
					'created_by' => $cur_user_id,
					'created_datetime' => current_date_time(),
					'updated_by' => $cur_user_id,
					'updated_datetime' => current_date_time()
					);
		
		$inserted = $dbObj->insertTableData("msg", $fields);	
		
		$insert_id = $dbObj->Insert_ID();
		$fields2 = array(
				'msg_id' => $insert_id,
				'msg' => $message,						
				'created_by' => $cur_user_id,
				'created_datetime' => current_date_time()
				);
		$inserted2 = $dbObj->insertTableData("reply", $fields2);	
		if(!$inserted2){
			$msg = COULD_NOT_BE_SEND;	
			$action = 'insert';
		}else{
			$msg = SEND_SUCCESSFULLY;
			$url = 'complain.php?action=view&msg='.$msg;
			redirect($url);
		}
		
		break;
	
	case 'reply':	
		$id = $_POST['id'];
		$message = $_POST['message'];
	
		$fields = array(
				'msg_id' => $id,
				'msg' => $message,						
				'created_by' => $cur_user_id,
				'created_datetime' => current_date_time()
				);
		$inserted = $dbObj->insertTableData("reply", $fields);	
		if(!$inserted){
			$msg = COULD_NOT_BE_SEND;	
			$action = 'insert';
		}else{
			$msg = 'Reply successfully saved for Complain No '.$id;
			$url = 'complain.php?action=view&msg='.$msg;
			redirect($url);
		}
		
		break;

	case 'delete':	
		$id = $_REQUEST['id'];
		
		//Delete from Message Table
		$where = "id='".$id."'";	
		$delete = $dbObj->deleteTableData("msg", $where);	
		
		if($delete){
			//Delete from Reply table
			$where1 = "msg_id='".$id."'";
			$success1 = $dbObj->deleteTableData("reply", $where1);	
			
			if(!$success1){
				$msg = COULD_NOT_BE_DELETED;
			}else{
				$msg = 'Complain No '.$id.' has been Closed seccessfully';
			}
			$url = 'complain.php?action=view&msg='.$msg;
			redirect($url);
		}
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
				<h1><?php echo COMPLAIN; ?></h1>
			</td>	
			<td class="usr_info">
				<?php echo welcomeMsg($cur_user_id); ?>
			</td>			
		</tr>
	</table>
	<?php
		if($action=="view"){
	?>
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
				<tr>
					<td>
						<table width="100%" cellpadding="0" cellspacing="0" border="0" class="datagrid">
								<tr class="footer">
									<td colspan="3" style=" background:#EEEEEE;">
										<b><a href="complain.php?action=create"><?php echo CREATE; ?></a></b>
									</td>
								</tr>				
							<tr class="head">
								<td height="30" width="15%">
									<strong><?php echo MESSAGE_NO; ?></strong>
								</td>
								<td width="75%">
									<strong><?php echo SUBJECT; ?></strong>
								</td>
								<td width="20%">
									<strong><?php echo ACTION; ?></strong>
								</td>
							</tr>
							
							
							<?php			
							if(!empty($msgList)){	
								$maxPageLimit = (($s+$limit) > $total_rows) ? $total_rows : ($s+$limit);
								for($rownum = $s; $rownum <$maxPageLimit; $rownum++){		
									$class = (($rownum%2)==0) ? ' class="even"' : ' class="odd"';
							?>
									<tr <?php echo $class; ?>>
										<td height="30">
											# <?php echo $msgList[$rownum]->id; ?> 
										</td>	
										<td>
											<a href="complain.php?action=subject&id=<?php echo $msgList[$rownum]->id; ?>" class="msg_anchor"><?php echo $msgList[$rownum]->subject; ?></a>
										</td>				
										<td>								
											<a class="delete" href="complain.php?action=delete&id=<?php echo $msgList[$rownum]->id; ?>" onclick="return confirm('Are you sure you want to Close this Complain?');" title="Delete">&nbsp;</a>
										</td>
									</tr>
								<?php 
									}//for
								}else{ ?>
								<tr height="30">
									<td colspan="3"><?php echo NO_COMPLAIN_FOUND; ?></td>
								</tr>
								<?php 
								}
								if($total_page > 1){ ?>
								<tr height="50">
									<td colspan="3"><?php echo pagination($total_rows,$limit,$page,''); ?></td>
								</tr>
								<?php } ?>	
								<tr class="footer">
									<td colspan="3"><b><a href="complain.php?action=create"><?php echo CREATE; ?></a></b></td>
								</tr>				
						</table>
					</td>
				</tr>
			</table>
				
	<?php }else if($action=="subject"){ ?>
		<link rel="stylesheet" href="editor/jquery.wysiwyg.css" type="text/css"/>
		<link type="text/css" href="editor/help/lib/ui/jquery.ui.all.css" rel="stylesheet"/>
		<script type="text/javascript" src="editor/help/lib/jquery.js"></script>
		<script type="text/javascript" src="editor/jquery.wysiwyg.js"></script>
		<script type="text/javascript" src="editor/help/lib/ui/jquery.ui.core.js"></script>
		<script type="text/javascript" src="editor/help/lib/ui/jquery.ui.widget.js"></script>
		<script type="text/javascript" src="editor/help/lib/ui/jquery.ui.mouse.js"></script>
		<script type="text/javascript">
		(function($) {
			$(document).ready(function() {
				$('#wysiwyg').wysiwyg();
			});
		})(jQuery);
		</script>
		<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
				<tr>
					<td>
						<span style="line-height:50px; font-weight:bold; font-size:16px;"><?php echo SUBJECT.': #'.$id.' -'.$sub; ?></span>
						<table width="100%" cellpadding="0" cellspacing="0" border="0" class="datagrid">			
							<tr>
								<td height="30" colspan="3">
									<a class="gobackup" href="<?php echo $_SERVER['HTTP_REFERER']; ?>" title="Go Back">&nbsp;</a>
								</td>
							</tr>
							<tr class="head">
								<td height="30" width="20%"><strong><?php echo ISSUED_BY; ?></strong></td>
								<td width="50%" align="center"><strong><?php echo MESSAGE; ?></strong></td>
								<td width="30%" align="center"><strong><?php echo ISSUED_TIME; ?></strong></td>
							</tr>
							
							
							<?php			
							if(!empty($msgArr)){	
								foreach($msgArr as $item){		
									$class = (($rownum%2)==0) ? ' class="even"' : ' class="odd"';
									$created_by = getNameById('user', $item->created_by);								
							?>
									<tr <?php echo $class; ?>>
										<td><?php echo $created_by->full_name; ?></td>
										<td><?php echo $item->msg; ?></td>			
										<td><?php echo dateTimeConvertion($item->created_datetime); ?></td>
									</tr>
								<?php 
									$rownum++;
									}//for
								}else{ ?>
								<tr height="30">
									<td colspan="3"><?php echo EMPTY_DATA; ?></td>
								</tr>
								<?php 
								}
								?>
								<tr >
									<td colspan="3">
										<form action="complain.php" method="post" name="complain" id="complain" onsubmit="return validate_reply();">
										<table width="100%" cellpadding="0" cellspacing="0" border="0">
											<tr height="30">
												<td height="10">&nbsp;</td>
											</tr>
											<tr class="head" height="30">
												<td><strong><?php echo REPLY_TO_THIS_MESSAGE; ?></strong></td>
											</tr>
											<tr>
												<td style="padding:10px;" align="center">
													<textarea id="wysiwyg" rows="5" name="message" cols="78" accesskey="Message" style=""></textarea>
												</td>
											</tr>
											<tr>
												<td align="center"><input type="submit" name="Submit" class="button" value="Update Complain" /></td>
											</tr>		
										</table>	
											<input type="hidden" name="id" value="<?php echo $id; ?>" />
											<input type="hidden" name="action" value="reply" />
										</form>
									</td>
								</tr>
								<tr>
								<td height="30" colspan="3">
									<a class="gobackup" href="<?php echo $_SERVER['HTTP_REFERER']; ?>" title="Go Back">&nbsp;</a>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

	<?php }else if($action=="insert"){	?>

		<form action="complain.php" method="post" name="msg" id="msg" onsubmit="return validatemsg();">
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
				<tr>
					<td height="30" width="20%">
						<?php echo SUBJECT; ?>:
					</td>
					<td width="80%">
						<input name="subject" id="subject" type="text" class="inputbox" alt="Subject" size="36" value="" />
						<span class="required_field">*</span>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo MESSAGES; ?>:
					</td>
					<td>
						<textarea name="message" id="message1" type="text" class="inputbox" alt="Messages" cols="27" rows="5"></textarea>
						<span class="required_field">*</span>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="submit" name="Submit" class="button" value="Save" />
						<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><input type="button" onclick="window.location='<?php echo $_SERVER['HTTP_REFERER']; ?>'"  name="cancel" class="cancel" value="<?php echo CANCEL; ?>" /></a>
					</td>
				</tr>		
			</table>	
			<input type="hidden" name="id" value="<?php echo $id; ?>" />
			<input type="hidden" name="action" value="save" />
		</form>
			
	<?php }?>
</div>
			
<?php
require_once("includes/footer.php");
?>