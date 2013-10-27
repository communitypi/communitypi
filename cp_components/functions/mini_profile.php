<?php
include("../../communitypi.class.php");
$communitypi = new communitypi;
$friendid = $_GET['id'];
echo "<h4>" . $communitypi->getProfileName($friendid) . " (" . $communitypi->getProfileUser($friendid) . ")</h4>";
if ( $publicprof == '1' ) { 
	$showinfo = 'yes';
} else {
	if ($communitypi->userIsLoggedIn()) {
		if ( $userprof = '1' ) {
			$showinfo = 'yes';
		} elseif ( $communitypi->isFriend($uid, $friendid) ) {
			$showinfo = 'yes';
		} elseif ($uid == $friendid) {
 			$showinfo = 'yes';
 		} else {
			$showinfo = 'no';
		}
	} else {
		$showinfo = 'no';
	}
}

if ( $showinfo == 'no' ) {
	echo "<div id=\"profile_hidden\"><h4>This users profile is hidden from you. Please login or request friendship.</h4></div>";
} else {
	echo "Birthday: " . date("j\<\s\u\p\>S\<\/\s\u\p\> \of F, Y",$communitypi->getProfileBirthday($friendid)) . "<br />";
	echo "Age: " . $communitypi->age($communitypi->getProfileBirthday($friendid)) . "<br />";
	$bio = $communitypi->getProfileSetting($friendid, 'bio');
	if ( strlen($bio) > 140 ) {
		$suffix = "...";
	} else {
		$suffix = "";
	}
	echo "Bio: " . substr($bio, 0, 140) . $suffix;
}
?>