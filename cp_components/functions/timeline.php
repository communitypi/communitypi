<?php

$track_user = 'off';
if (is_null($communitypi)) {
	include("../../communitypi.class.php");
	$communitypi = new communitypi;
}

if ( $communitypi->userIsLoggedIn() ) {
	//continue
} else {
	header("LOCATION: " . $communitypi->getSetting('baseURL'));
}


$uid = $_SESSION['uid'];
$count = $_GET['count'];
if(!$count) {
	$count = 20; 
}



$communitypi->getTimeline($uid, $count, true);
	
	
?>