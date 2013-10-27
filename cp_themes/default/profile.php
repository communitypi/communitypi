<?php
//THEME PROFILE


echo '<div id="main_column"><div class="profile">';
$friendid = $communitypi->getProfileId($_GET['name']);
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



mysql_close();
echo "<div id=\"profile_username\"><h2>" . $communitypi->getProfileName($friendid) . "'s Profile</h2></div>";
if ( $uid != $friendid ) {
	$base = $communitypi->getSetting('baseURL');
	if ( $communitypi->isFriend($uid, $friendid) ) {
		echo "<div class=\"remove_friend\"><a href=\"" . $base . "call.php?f=friend&func=delete&fid=$friendid\">Remove from Friends</a></div>";
	} elseif ( $communitypi->isFriendRequested($uid, $friendid) ) {
		echo "<div class=\"requested_friend\">Awaiting Approval</div>";
	} elseif ( $communitypi->isFriendRequested($friendid, $uid) ) {
		echo "<div class=\"deny_friendship\"><a href=\"" . $base . "call.php?f=friend&func=deny&fid=$friendid\">Ignore</a></div>";
		echo "<div class=\"accept_friendship\"><a href=\"" . $base . "call.php?f=friend&func=add&fid=$friendid\">Accept Friendship</a></div>";
		
	} else {
		echo "<div class=\"request_friendship\"><a href=\"" . $base . "call.php?f=friend&func=request&fid=$friendid\">Request Friendship</a></div>";
	}
}

if ( $showinfo == 'no' ) {
	echo "<div id=\"profile_hidden\"><p>This users profile is hidden from you. Please login or request friendship.</p></div>";
} else {
	?>
	<div class="profile_info_container">
		<div class="profile_info_image"><img src="<?php echo $communitypi->getProfileImage($friendid, 200); ?>" width="100px" height="100px" /></div>
	<?php;
	echo "<div class=\"profile_info_info\"><p>";
	echo "<span class=\"field\">Birthday:</span> " . date("d \of F, Y",$communitypi->getProfileBirthday($friendid)) . "<br />";
	echo "<span class=\"field\">Age:</span> " . $communitypi->age($communitypi->getProfileBirthday($friendid)) . "<br />";
	echo "<span class=\"field\">Bio:</span> " . $communitypi->getProfileBio($friendid) . "<br />";
	echo "</p></div></div><div class=\"clear\"></div><br />";
	
	echo "<div class=\"profile_questions_container\">";
	//question 1
	$quest = $communitypi->getSetting('profile_quest1');
	$questans = $communitypi->getProfileInfo($friendid, 'quest1');
	if ( $questans != "" ) echo "<span class=\"field\">$quest</span><br /><div class=\"ans\">" . $questans . "</div><br />";
	
	//question 2
	$quest = $communitypi->getSetting('profile_quest2');
	$questans = $communitypi->getProfileInfo($friendid, 'quest2');
	if ( $questans != "" ) echo "<span class=\"field\">$quest</span><br /><div class=\"ans\">" . $questans . "</div><br />";
	
	//question 3
	$quest = $communitypi->getSetting('profile_quest3');
	$questans = $communitypi->getProfileInfo($friendid, 'quest3');
	if ( $questans != "" ) echo "<span class=\"field\">$quest</span><br /><div class=\"ans\">" . $questans . "</div><br />";
	
	//question 4
	$quest = $communitypi->getSetting('profile_quest4');
	$questans = $communitypi->getProfileInfo($friendid, 'quest4');
	if ( $questans != "" ) echo "<span class=\"field\">$quest</span><br /><div class=\"ans\">" . $questans . "</div><br />";
	
	echo "</div>";
	echo '<div class="profile_photos_container">';
	$communitypi->showPhotos($friendid, 5, 100, null, 'yes');
	echo '</div><div class="clear"></div>';
	if ( $communitypi->hasPhotos($friendid) ) {
		echo '<div class="more_photos"><a href="' . $communitypi->getSetting('baseURL') . 'photos/' . $communitypi->getUserInfo($friendid, "username") . '">View all ' . $communitypi->getProfileName($friendid) . '\'s photos</a></div>';
	}
	
	$communitypi->getTimeline($friendid, 20, false);
	
	echo '<div id="comment_reply">';
	echo '<div class="close"><img src="' . $communitypi->getSetting('baseURL') . 'cp_images/fancy_close.png" /></div>';
	echo '<div class="inner"></div>';
	echo '</div>';
	
	//echo "</div></div>";
}

echo "</div></div>";
?>

