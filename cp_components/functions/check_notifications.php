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

if ($communitypi->checkNotifications()) {
//	echo "hmm";
	$message = "Unread notifications.";
	$_SESSION['p_message'] = $message;
	$_SESSION['p_mood'] = 'neutral';
	//echo 'look<div class="global_message_mood_'.$_SESSION['p_mood'].'" id="global_message">'.$_SESSION['p_message'].'</div>';
	echo "true";
} else {
	echo "false";		
}
	
	
?>