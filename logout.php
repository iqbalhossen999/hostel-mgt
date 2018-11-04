<?php
require_once("includes/header.php");
session_destroy();
redirect("index.php");	
exit;
?>
