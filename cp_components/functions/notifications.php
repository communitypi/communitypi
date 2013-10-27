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

$communitypi->getNotifications();
	
	
?>