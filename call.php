<?php
//Call Functions

include("communitypi.class.php");
$communitypi = new communitypi;

if ($_GET['f']!=""){
	$file = $_GET['f'];
	include("cp_components/functions/".$file.".php");
}

?>