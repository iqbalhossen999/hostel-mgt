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

$path = 'attach_file/';

switch($action){
	case 'view':	
	default:
		
		$sql = "select * from ".DB_PREFIX."notice order by issue_date asc";
		$targetList = $dbObj->selectDataObj($sql);
		$action = 'view';
	
		//Pagination 
		if(!empty($targetList)){
			$total_rows = sizeof($targetList);
		}else{
			$total_rows =0;
		}
		//find start
		$s = ($page - 1) * $limit;
		$total_page = $total_rows/$limit;
		
		break;
		
	case 'update':
	case 'create':
	
		if(!empty($_REQUEST['id'])){
			$id = $_REQUEST['id'];
			$sql = "select * from ".DB_PREFIX."notice WHERE id='".$id."'";	
			$targetList = $dbObj->selectDataObj($sql);
			$notice = $targetList[0];
			$subject = $notice->subject;
			$description = $notice->description;
			$issue_date = $notice->issue_date;
			
		}else{
			$id = '';
			$subject = '';
			$description = '';
			$attached_file = '';
			$issue_date = '';
		}
		
		$action = 'insert';
		break;
		
	case 'save':	
		$id = $_POST['id'];
		$subject = $_POST['subject'];
		$description = $_POST['description'];
		$attached_file = $_POST['attached_file'];
		$issue_date = $_POST['issue_date'];
		
		//Upload file
		$uploaded = upload_file($_FILES['attached_file'], $path, $username, 'pdf, doc, docx, xls, xlsx, image, jpeg, png');	
		
		if($uploaded['error_counter'] != '0'){
			$msg = implode("<br />",$uploaded['error']);
			$url = 'notice.php?action=create&msg='.$msg;
			redirect($url);
		}
		
		if(!empty($id)){
			$fields = array('subject' => $subject,
						'description' => $description,
						'issue_date' => $issue_date,
						'updated_by' => $cur_user_id,
						'updated_datetime' => current_date_time()
						);
						
						//Check if the a new file has been uploaded
						if($uploaded['file'][0]['file_name'] != ''){
							$fields['attached_file'] = $uploaded['file'][0]['file_name'];
						}//if		
			
			$where = "id = '".$id."'";
			$update_status = $dbObj->updateTableData("notice", $fields, $where);	
			
			if(!$update_status){
				$msg = $subject.COULD_NOT_BE_UPDATED;		
				$action = 'insert';
			}else{
				$msg = $subject.HAS_BEEN_UPDATED;
				$url = 'notice.php?action=view&page='.$page.'&id='.$id.'&msg='.$msg;
				redirect($url);
			}
		}else{
			$fields = array('subject' => $subject,
						'description' => $description,
						'issue_date' => $issue_date,
						'attached_file' => ($uploaded['uploaded'][0] == '')?'':$uploaded['uploaded'][0],
						'created_by' => $cur_user_id,
						'created_datetime' => current_date_time(),
						'updated_by' => $cur_user_id,
						'updated_datetime' => current_date_time()
						);
			
			$inserted = $dbObj->insertTableData("notice", $fields);	
			if(!$inserted){
				$msg = $subject.COULD_NOT_BE_CREATED;	
				$action = 'insert';
			}else{
				$msg = $subject.CREATED_SUCCESSFULLY;
				$url = 'notice.php?action=view&msg='.$msg;
				redirect($url);
			}
		}
		break;
	case 'detail':	
		$id = $_REQUEST['id'];
		
		$sql = "select * from ".DB_PREFIX."notice WHERE id='".$id."'";	
		$targetList = $dbObj->selectDataObj($sql);		
		$notice = $targetList[0];

		$action = 'detail';
		
		break;

	case 'delete':	
		$id = $_REQUEST['id'];
		
		$sql = "select * from ".DB_PREFIX."notice WHERE id='".$id."'";	
		$targetList = $dbObj->selectDataObj($sql);
		$notice = $targetList[0];
		$subject = $notice->subject;
			
		$where = "id='".$id."'";	
		$success = $dbObj->deleteTableData("notice", $where);	
		
		if(!$success){
			$msg = $subject." ".COULD_NOT_BE_DELETED;
		}else{
			$msg = $subject." ".DELETED_SUCCESSFULLY;
		}
		
		$url = 'notice.php?action=view&page='.$page.'&msg='.$msg;
		redirect($url);
		break;

}//switch
	
require_once("includes/templates.php");
require_once("templates/top_menu.php");
require_once("templates/left_menu.php");

?>

<div id="right_column">
	<?php if(!empty($msg)){ ?>
		<table id="system_message">
			<tr>
				<td><?php echo $msg; ?></td>
			</tr>
		</table>
	<?php } ?>
	<table width="100%" cellspacing="0" cellpadding="0" border="0" class="module_header">
		<tr>
			<td>
				<h1><?php echo NOTICE_MANAGEMENT; ?></h1>
			</td>	
			<td class="usr_info">
				<?php echo welcomeMsg($cur_user_id); ?>
			</td>			
		</tr>
	</table>
	<?php if($action=="view"){ ?>
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
				<tr>
					<td>
						<table width="100%" cellpadding="0" cellspacing="0" border="0" class="datagrid">
								<tr class="footer">
									<td colspan="6" style=" background:#EEEEEE;">
										<b><a href="notice.php?action=create"><?php echo CREATE; ?></a></b>
									</td>
								</tr>				
							<tr class="head">
								<td height="30" width="10%"><strong><?php echo SL_NO; ?></strong></td>
								<td width="20%"><strong><?php echo ISSUE_DATE; ?></strong></td>
								<td width="20%"><strong><?php echo SUBJECT; ?></strong></td>
								<td width="10%"><strong><?php echo ATTACHED_FILE; ?></strong></td>
								<td width="20%"><strong><?php echo ACTION; ?></strong></td>
							</tr>
							
							<?php
							if(!empty($targetList)){	
								$maxPageLimit = (($s+$limit) > $total_rows) ? $total_rows : ($s+$limit);
								for($rownum = $s; $rownum <$maxPageLimit; $rownum++){		
									$class = (($rownum%2)==0) ? ' class="even"' : ' class="odd"';
							?>
									<tr <?php echo $class; ?>>
										<td height="30"><?php echo $targetList[$rownum]->id; ?></td>
										<td><?php echo dateConvertion($targetList[$rownum]->issue_date); ?></td>
										<td><?php echo $targetList[$rownum]->subject; ?> </td>	
										<td>
										<?php if(!empty($targetList[$rownum]->attached_file)){
												$ext_expl = explode(".", $targetList[$rownum]->attached_file);
												$name = $ext_expl[0];
												$ext = $ext_expl[sizeof($ext_expl)-1];
												if($ext == 'pdf'){?>
												<a target="_blank" href="<?php echo $path.$targetList[$rownum]->attached_file;?>" ><img src="images/pdf.png" title="Click here to view this file" /></a>
												<?php } ?>
											<a href="libraries/download.php?file=<?php echo $targetList[$rownum]->attached_file;?>" ><img src="images/download.png" title="Click this icon to Download" /></a>
										<?php }//if ?>
										</td>
										<td>								
											<a class="details" href="notice.php?action=detail&id=<?php echo $targetList[$rownum]->id; ?>" title="details">&nbsp;</a>
											<a class="edit" href="notice.php?action=update&page=<?php echo $page;?>&id=<?php echo $targetList[$rownum]->id; ?>" title="Edit">&nbsp;</a>
											<a class="delete" href="notice.php?action=delete&id=<?php echo $targetList[$rownum]->id; ?>" onclick="return confirm('Are you sure you want to delete?');" title="Delete">&nbsp;</a>
										</td>
									</tr>
								<?php 
									}//for
								}else{ ?>
								<tr height="30">
									<td colspan="5"><?php echo EMPTY_DATA; ?></td>
								</tr>
								<?php 
								}
								if($total_page > 1){ ?>
								<tr height="50">
									<td colspan="5"><?php echo pagination($total_rows,$limit,$page,''); ?></td>
								</tr>
								<?php } ?>	
								<tr class="footer">
									<td colspan="6">
										<b><a href="notice.php?action=create"><?php echo CREATE; ?></a></b>
									</td>
								</tr>				
						</table>
					</td>
				</tr>
			</table>
				
	<?php }else if($action=="insert"){ ?>
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
				
				<form action="notice.php" method="post" name="notice" id="notice" onsubmit="return validateUserGroup();" enctype="multipart/form-data">
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_content">
						<tr>
							<td height="30" width="20%">
								<?php echo NOTICE_ISSUE_DATE; ?>
							</td>
							<td width="80%">
								<input name="issue_date" id="issue_date" type="text" class="inputbox readonly" readonly="readonly" alt="Issue Date" size="18" value="<?php echo $issue_date; ?>" />
								<img id="f_rangeStart_triggerm" src="date/src/css/img/calendar.gif" title="Pick a Date" />
								<img id="f_clearRangeStart" src="date/src/css/img/no.png" title="Clear Date" onClick="return makeEmpty('issue_date')" height="16" width="16" />
								<script type="text/javascript">
								RANGE_CAL_1 = new Calendar({
									inputField: "issue_date",
									dateFormat: "%Y-%m-%d",
									trigger: "f_rangeStart_triggerm",
									bottomBar: true,
									onSelect: function(){
										var date = Calendar.intToDate(this.selection.get());
										this.hide();
									}
								});
								</script>
							</td>
						</tr>
						<tr>
							<td height="30"><?php echo SUBJECT; ?>:</td>
							<td>
								<input name="subject" id="subject" type="text" class="inputbox" alt="Subject" size="36" value="<?php echo $subject; ?>" />
								<span class="required_field">*</span>
							</td>
						</tr>
						<tr>
							<td height="30"><?php echo ATTACH_FILE; ?>:</td>
							<td>
								<input name="attached_file" id="attached_file" type="file" class="inputbox" alt="image" size="23" />
							</td>
						</tr>
						<tr>
							<td height="30" colspan="2"><?php echo DESCRIPTION; ?>:</td>
						</tr>
						<tr>
							<td colspan="2">
								<textarea id="wysiwyg" rows="5" name="description" cols="78" accesskey="Description" style=""><?php echo $description; ?></textarea>
							</td>
						</tr>
						
						<tr>
							<td colspan="2" height="50">
								<input type="submit" name="Submit" class="button" value="Save" />
								<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><input type="button" onclick="window.location='<?php echo $_SERVER['HTTP_REFERER']; ?>'"  name="cancel" class="cancel" value="<?php echo CANCEL; ?>" /></a>
							</td>
						</tr>		
					</table>	
					<input type="hidden" name="id" value="<?php echo $id; ?>" />
					<input type="hidden" name="action" value="save" />
					<input type="hidden" name="page" id="page" value="<?php echo $page; ?>" />
				</form>
			
	<?php }else if($action=="detail"){?>
	
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="module_details">
				<tr>
					<td height="30" colspan="3">
						<a class="gobackup" href="<?php echo $_SERVER['HTTP_REFERER']; ?>" title="Go Back">&nbsp;</a>
					</td>
					<tr class="holder topholder">
						<td height="30" class="holder topholder"><strong><?php echo SUBJECT; ?>:</strong></td>
						<td><strong><?php echo strtoupper($notice->subject);?></strong></td>
					</tr>
					<tr class="holder">
						<td height="30" width="20%"><?php echo ISSUE_DATE; ?>:</td>
						<td width="80%"><?php echo dateConvertion($notice->issue_date); ?></td>
					</tr>
					<tr class="holder">
						<td height="30"><?php echo DESCRIPTION; ?>:</td>
						<td><?php echo $notice->description;?></td>
					</tr>
					<tr class="holder">
						<td height="30"><?php echo ATTACH_FILE; ?>:</td>
						<td>
							<?php 
							if(empty($notice->attached_file)){
							?>
								<?php echo NO_FILE_ATTACHED; ?>
							<?php }else{
								if(!empty($notice->attached_file)){
									$ext_expl = explode(".", $notice->attached_file);
									$name = $ext_expl[0];
									$ext = $ext_expl[sizeof($ext_expl)-1];
									if($ext == 'pdf'){?>
									<a target="_blank" href="<?php echo $path.$notice->attached_file ;?>" ><img src="images/pdf.png" title="Click here to view this file" /></a>
									<?php } ?>
								<a href="libraries/download.php?file=<?php echo $notice->attached_file;?>" ><img src="images/download.png" title="Click this icon to Download" /></a>
							<?php 
								}//if 
							}//else	
							?>
						</td>
					</tr>
					<td height="30" colspan="3">
						<a class="gobackup" href="<?php echo $_SERVER['HTTP_REFERER']; ?>" title="Go Back">&nbsp;</a>
					</td>
				</tr>
			</table>
	<?php } ?>
</div>
			
<?php
require_once("includes/footer.php");
?>
