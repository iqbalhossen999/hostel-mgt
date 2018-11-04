<?php 
header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=maintenence_report.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	header("Lacation: excel.htm?id=yes");
	print $data ;
	die();
