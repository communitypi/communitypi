<?php
$i_user = $_GET['user'];
$i_id = substr($_GET['id'], 1);

echo '<div id="main_column">';
$friendid = $communitypi->getProfileId($i_user);

if($friendid == '') {
	echo "<div id=\"user_not_found\"><h2>User not found</h2></div>";
	include("cp_themes/".$communitypi->getSetting("theme")."/footer.php");
	exit();
}	
$uid = $_SESSION['uid'];

$communitypi->mysqlConnect();
$result = mysql_query("SELECT * FROM `profile_db` WHERE `uid`='$friendid'");
		
while($row = mysql_fetch_array($result)) {
	$publicprof = $row['publicprof'];
	$userprof = $row['userprof'];
}


if ( $publicprof == '1' ) { 
	$showinfo = 'yes';
} else {
	if ($communitypi->userIsLoggedIn()) {
		if ( $userprof == '1' ) {
			$showinfo = 'yes';
		} elseif ( $communitypi->isFriend($uid, $friendid) || $communitypi->isFriendRequested($friendid, $uid) ) {
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


if ($showinfo == 'no') {
	echo "<h2>You are not authorised to view this image!</h2>";
} else {
		$result = mysql_query("SELECT * FROM `photos_db` WHERE `id`='$i_id' AND `owner` = '$friendid'");
	if (mysql_num_rows($result) == '0') {
		include 'cp_themes/'.$communitypi->getSetting("theme").'/404.php';
	} else {
		
while($row = mysql_fetch_array($result)) {
	echo "<h2>" . $communitypi->getProfileName($friendid) . "'s Photo</h2>";
	echo "<h3 style='margin:2px;'>" . $row['title'] . "</h3>";
	echo "<h5 style='margin:2px;'>" . $row['desc'] . "</h5>";
	$large = 'cp_content/photos/' . $row['batch'] . '/' . $row['filename'] .'600.png';
	if ($row['width'] < 500) {
		$width = $row['width'];
		$large = 'cp_content/photos/' . $row['batch'] . '/' . $row['filename'] .'orig.png';
	} else {
		$width = 600;
	}
	echo '<img src="' . $communitypi->getSetting('baseurl') . $large . '" width="' . $width . '" align="center">';
	}
}
	


	
	
	
	
	
	
}
?>
