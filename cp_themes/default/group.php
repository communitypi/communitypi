<div id="main_column">
<?php
$name = $_GET['name'];
$gid = $communitypi->getGroupIDFromSlug($name);
$data = $communitypi->getGroupInfo($gid);
$uid = $_SESSION['uid'];

if ( $name == '' ) {
	include("cp_themes/" . $communitypi->getSetting('theme') . "/404.php");
} else {
	if ( $data['name'] == '' ) {
		include("cp_themes/" . $communitypi->getSetting('theme') . "/404.php");
	} else {

		echo '<div class="group_image"><img src="' . $data['image'] . '" /></div>';
	
		if ( $communitypi->isMemberOfGroup($uid, $gid) ) {
			if (!$communitypi->isAdminOfGroup($uid, $gid)) {
				echo '<div class="leave_group_button"><a href="' . $communitypi->getSetting('baseURL') . 'call.php?f=group&func=leave&gid=' . $gid . '">Leave Group</a></div>';
			}
		} else {
			echo '<div class="join_group_button"><a href="' . $communitypi->getSetting('baseURL') . 'call.php?f=group&func=join&gid=' . $gid . '">Join Group</a></div>';
		}
	
		echo '<h2>' . $data['name'] . '</h2>';
		echo '<span>' . $data['desc'] . '</span>';
	
		echo '<div class="clear"></div>';
	
		if ($communitypi->isAdminOfGroup($uid, $gid)) {
			echo '<h3>Group Administration</h3>';
			echo '<a href="' . $communitypi->getSetting('baseURL') . 'call.php?f=group&func=delete&gid=' . $gid . '">Delete group</a>';
		}
	
		echo '<h3>Group Members</h3>';
		echo '<div class="group_members">';
		$members = $communitypi->getGroupMembers($gid);
		if ($members) {
			foreach ($members as $id) {
				$imgurl = $communitypi->getProfileImage($id, 60);
				echo '<div class="member">';
					echo '<a href="' . $communitypi->getUserProfileURL($id) . '">';
					echo '<div class="image"><img src="' . $imgurl . '" /></div></a>';
					//echo '<div class="name">' . $communitypi->getProfileName($uid) . '</div>';
				echo '</div>';
						  
			}
		} else {
			echo "No members";
		}
		echo '</div><div class="clear"></div>';
	
		echo '<h3>Group Messages</h3>';
		if ( $communitypi->isMemberOfGroup($uid, $gid) ) {
			include("cp_components/forms/group_status_update_form.php");
		}
		echo '<div id="group_timeline">';
		$communitypi->getGroupTimeline($gid);
		echo '</div><div class="clear"></div><br>';

		//if ( $communitypi->isMemberOfGroup($uid, $gid) ) {

			echo '<div id="comment_reply">
				<div class="close"><img src="' . $communitypi->getSetting('baseURL') . 'cp_images/fancy_close.png" /></div>
				<div class="inner"></div>
			</div>
				
			<div id="miniprofile">
				<div class="close"><img src="' . $communitypi->getSetting('baseURL') . 'cp_images/ajax-loader-2.gif" /></div>
				<div class="inner"></div>
			</div>';

		//}

	}
}


?>


	
</div>