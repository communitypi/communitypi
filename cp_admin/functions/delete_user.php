<?php
//Main index file
include("../../communitypi.class.php");
$communitypi = new communitypi;
$deleteid = $_GET['id'];

//include header
include("../../cp_themes/".$communitypi->getSetting("theme")."/header.php");

	if($communitypi->userIsLoggedIn()) {
		include("../../cp_themes/".$communitypi->getSetting("theme")."/sidebar.php");
		if($communitypi->isAdmin($ses) || $communitypi->isRoot($ses)) {
			echo "Starting Delete Process!<br />";
			$communitypi->mysqlConnect();
			echo "Deleting Friend Requests... ";
			mysql_query("DELETE FROM `friendrequests_db` WHERE `uid` = '$deleteid' OR `friendid` = '$deleteid';");
			echo "Done!<br />";
			
			echo "Deleting Group Invites... ";
			mysql_query("DELETE FROM `group_invites_db` WHERE `uid` = '$deleteid' OR `fid` = '$deleteid';");
			echo "Done!<br />";
			
			echo "Deleting Group Memberships... ";
			mysql_query("DELETE FROM `group_members_db` WHERE `uid` = '$deleteid';");
			echo "Done!<br />";
			
			echo "Deleting Groups... ";
			mysql_query("DELETE FROM `groups_db` WHERE `owner` = '$deleteid';");
			echo "Done!<br />";
			
			echo "Deleting Group Messages... ";
			mysql_query("DELETE FROM `group_timeline_db` WHERE `uid` = '$deleteid' OR `reply_to` = '$deleteid';");
			echo "Done!<br />";
						
			echo "Deleting friendships... ";
			mysql_query("DELETE FROM `friends_db` WHERE `uid` = '$deleteid' OR `friend` = '$deleteid';");
			echo "Done!<br />";
			
			echo "Deleting ignored friend suggestions... ";
			mysql_query("DELETE FROM `ignored_friend_suggestions_db` WHERE `iid` = '$deleteid' OR `uid` = '$deleteid';");
			echo "Done!<br />";
			
			echo "Deleting messages... ";
			mysql_query("DELETE FROM `messages_db` WHERE `from` = '$deleteid' OR `to` = '$deleteid';");
			echo "Done!<br />";
			
			echo "Deleting Notifications... ";
			mysql_query("DELETE FROM `notifications_db` WHERE `uid` = '$deleteid';");
			echo "Done!<br />";
			
			echo "Deleting online... ";
			mysql_query("DELETE FROM `online_db` WHERE `uid` = '$deleteid';");
			echo "Done!<br />";
			
			echo "Deleting photos... ";
			mysql_query("DELETE FROM `photos_db` WHERE `owner` = '$deleteid';");
			echo "Done!<br />";
			
			echo "Deleting status updates... ";
			mysql_query("DELETE FROM `timeline_db` WHERE `uid` = '$deleteid' OR `reply_to` = '$deleteid';");
			echo "Done!<br />";
			
			echo "Deleting profile... ";
			mysql_query("DELETE FROM `profile_db` WHERE `uid` = '$deleteid';");
			echo "Done!<br />";
			
			echo "Deleting user account... ";
			mysql_query("DELETE FROM `users_db` WHERE `id` = '$deleteid';");
			echo "Done!<br />";
			
			echo "Done all!";
			
			
		} else {
		echo "<h1>Unauthorized!</h1>";
		}
	}
			
			
			//include footer
include("../../cp_themes/".$communitypi->getSetting("theme")."/footer.php");
?>