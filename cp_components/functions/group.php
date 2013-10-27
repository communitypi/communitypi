<?php
//this function is used for groups
//parameters in url are func=function, the other information is posted from a form
//possible functions: create
$function = $_GET['func'];
$uid = $_SESSION['uid'];
$referer = $_SERVER['HTTP_REFERER'];

if ( $function == 'create' ) {
	$name = $_POST['gname'];
	$desc = $_POST['gdesc'];
	$image = $_POST['gimage'];
	$slug = $_POST['gslug'];
	//validate name
	if (strlen(preg_replace("/[^a-zA-Z0-9]/", "", $name)) < 1) {
		$message = "Invalid group name";
		$_SESSION['p_message'] = $message;
		$_SESSION['p_mood'] = 'sad';
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit();
	}
	//make sure groupname is available
	if (!$communitypi->isGroupNameAvailable($name)) {
		$message = "Group name unavailable";
		$_SESSION['p_message'] = $message;
		$_SESSION['p_mood'] = 'sad';
		header("Location: $referer");
		exit();
	}
	$communitypi->createGroup($name, $desc, $image, $slug);
	$message = "Group created!";
	$_SESSION['p_message'] = $message;
	$_SESSION['p_mood'] = 'happy';
	
} elseif ( $function == 'delete' ) {
	$gid = $_GET['gid'];
	if ($communitypi->isAdminOfGroup($uid, $gid)) {
		deleteGroup($gid);
	} else {
		$message = "You must be a group admin to delete this group.";
		$_SESSION['p_message'] = $message;
		$_SESSION['p_mood'] = 'sad';
		header("LOCATION $referer");
		exit();
	}
		$message = "Group deleted";
		$_SESSION['p_message'] = $message;
		$_SESSION['p_mood'] = 'neutral';
	header("LOCATION $referer");
	exit();

} elseif ( $function == 'join' ) {
	$gid = $_GET['gid'];
	$uid = $_SESSION['uid'];
	$communitypi->addUserToGroup($uid, $gid);
	$message = "Group joined!";
	$_SESSION['p_message'] = $message;
	$_SESSION['p_mood'] = 'happy';	
	header("LOCATION $referer");
	
} elseif ( $function == 'leave' ) {
	$gid = $_GET['gid'];
	$uid = $_SESSION['uid'];
	$communitypi->removeUserFromGroup($uid, $gid);
	$message = "You left the group";
	$_SESSION['p_message'] = $message;
	$_SESSION['p_mood'] = 'neutral';	
	header("LOCATION $referer");
	
}
	
header("Location: $referer");
	
?>