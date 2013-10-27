<?php
//this function is used to add delete and request friends
//parameters in url are func=function and fid=friendid
//possible functions: add, request, deny, delete, is, ignore_suggestion
$function = $_GET['func'];
$uid = $_SESSION['uid'];
$friendid = $_GET['fid'];
$username = $_GET['username'];

if ( $friendid == "" ) {
	$friendid = $communitypi->getUidFromUsername($username);
}

if ( $function == 'request' ) {
	$communitypi->requestFriend($friendid);
	//echo "request friend";
} elseif ( $function == 'add' ) {
	$communitypi->addFriend($friendid);
	//echo "add friend";
} elseif ( $function == 'deny' ) {
	$communitypi->denyFriend($friendid);
} elseif ( $function == 'delete' ) {
	$communitypi->delFriend($friendid);
	//echo "delete friend";
} elseif ( $function == 'is' ) {
	if ( $communitypi->isFriend($uid, $friendid)) {
		echo '1';
	} else {
		echo '0';
	}
	//exit();
} elseif ( $function == 'ignore_suggestion' ) {
	$communitypi->ignoreFriendSuggestion($friendid);
}
	
header("Location: " . $_SERVER['HTTP_REFERER']);
	
?>