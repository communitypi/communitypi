<?php
session_start();
/* Copyright (C) 2011  CommunityPi, Jordan Cook, Jake Wright, Alex Kiernan and Henry Cole

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


include 'cp_config.php';
$ses = $_SESSION['uid'];
$communitypi = new communitypi;
if ($track_user != 'off') {
	$uid = $_SESSION['uid'];
	$communitypi->mysqlConnect();
	mysql_query("INSERT INTO `online_db` (`uid`, `time`) VALUES ('$uid', UNIX_TIMESTAMP());");
	$communitypi->mysqlClose();
}



class communitypi {
	
	function createNotification($uid, $desc, $link, $imgref) {
		$this->mysqlConnect();
		$time = time();
		$query = mysql_query("INSERT INTO `notifications_db` (`time`, `uid`, `desc`, `link`, `imgref`, `read`) VALUES ('$time', '$uid', '$desc', '$link', '$imgref', '0');");
		$this->mysqlClose();	
	}
	
	//Checks to see if there are any unread notifications, returns true/false
	function checkNotifications() {
		$uid = $_SESSION['uid'];
		$this->mysqlConnect();
		$result = mysql_query("SELECT * FROM `notifications_db` WHERE `uid` = '$uid' AND `read` = 0;");
		if (mysql_num_rows($result) > 0) {
			return true;
		} else {
			return false;
		}
		$this->mysqlClose();
	}
	
	//returns a list of notifications for the current user
	function getNotifications() {
		$uid = $_SESSION['uid'];
		$this->mysqlConnect();
		//Get all unread notifications and at least 5.
		$result = mysql_query("SELECT * FROM `notifications_db` WHERE `uid` = '$uid' AND `read` = 0 ORDER BY `time` DESC;");
		if (mysql_num_rows($result) < 5) {
			$result = mysql_query("SELECT * FROM `notifications_db` WHERE `uid` = '$uid' ORDER BY `time` DESC LIMIT 5;");
		}
		//loop through each notification
		echo '<div class="notifications_results">';
		if (mysql_num_rows($result) == 0) {
			echo '<div class="no_notifications">No notifications</div>';
		}
		while ($row = mysql_fetch_array($result)) {
			//mark notification as read.
			$id = $row['id'];
			$query = mysql_query("UPDATE `notifications_db` SET `read` = '1' WHERE `id` = '$id';");
			//clear global message regarding unread notifications
			$_SESSION['p_mood'] = '';
			$_SESSION['p_message'] = '';
			$imgref_prefix = substr($row['imgref'], 0, 3);
			if ($imgref_prefix == 'uid') {
				$imgurl = $this->getProfileImage(substr($row['imgref'], 3), 60);
			} elseif ($imgref_prefix =='url') {
				$imgurl = substr($row['imgref'], 3);
			}
			echo '<a href="' . $row['link'] . '"><div class="notification">';
				echo '<div class="notification_image"><img src="' . $imgurl . '" /></div>';
				echo '<div class="notification_desc">' . $row['desc'] . '</div>';
				echo '<div class="notification_date">' . $this->timeSince($row['time']) . '</div>';
			echo '</a></div>';
		}
		echo '</div>';
		$this->mysqlClose();
	}
	

	function addFriend($friendid) {
		$time = time();
		$uid = $_SESSION['uid'];
		$this->mysqlConnect();
		$add = mysql_query("INSERT INTO `friends_db` (`uid`, `friend`, `time`) VALUES ('$uid', '$friendid', '$time');");
		
		$add2 = mysql_query("INSERT INTO `friends_db` (`uid`, `friend`, `time`) VALUES ('$friendid', '$uid', '$time');");
		
		$remreq = mysql_query("DELETE FROM `friendrequests_db` WHERE `friendrequests_db`.`uid` = $friendid;");
		
		$name = $this->getProfileInfo($uid, "name");
		$link = $this->getUserProfileURL($uid);
		$this->createNotification($friendid, "$name accepted your friend request", $link, "uid" . $uid);
	}
	
	function denyFriend($friendid) {
		$uid = $_SESSION['uid'];
		$this->mysqlConnect();
		$remreq = mysql_query("DELETE FROM `friendrequests_db` WHERE `friendrequests_db`.`uid` = $friendid;");
		//echo 'deny';
		$this->mysqlClose();
	}
	
	function delFriend($friendid) {
		$uid = $_SESSION['uid'];
		$this->mysqlConnect();
		$del = mysql_query("DELETE FROM `friends_db` WHERE uid = '$uid' AND friend = '$friendid';");
		echo mysql_error();
		$del2 = mysql_query("DELETE FROM `friends_db` WHERE uid = '$friendid' AND friend = '$uid';");
		echo mysql_error();
	}
	
		function isFriend($uid, $friendid) {
			$this->mysqlConnect();
			$result = mysql_query("SELECT * FROM `friends_db` WHERE `uid`='$uid' AND friend = '$friendid';");
			$rows = mysql_num_rows($result);
				if ($rows > '0') {
					return true;
				} else {
					return false;
				}
		$this->mysqlClose();
	}
	
	function requestFriend($friendid) {
		$this->mysqlConnect();
		$uid = $_SESSION['uid'];
		$result = mysql_query("SELECT * FROM `friendrequests_db` WHERE `uid` = '$friendid';");
		if ( mysql_num_rows($result) > 0 ) {
			$this->addFriend($friendid);
			$remreq = mysql_query("DELETE FROM `friendrequests_db` WHERE `friendrequests_db`.`uid` = $friendid;");
		} else {
			$add = mysql_query("INSERT INTO `friendrequests_db` (`uid`, `friendid`) VALUES ('$uid', '$friendid');");
			$name = $this->getProfileInfo($uid, "name");
			$link = $this->getUserProfileURL($uid);
			$this->createNotification($friendid, "$name sent a friend request", $link, "uid" . $uid);
			if ($this->getProfileInfo($friendid, "emailOnFriendRequest") == "1") {
				$this->sendEmail($friendid, $name . " wants to be friends", "$name sent you a friend request.\n$link");
			}
		}
	}
	
	function isFriendRequested($uid, $friendid) {
		//function to find if the user has requested to be friends with someone
		$this->mysqlConnect();
		$result = mysql_query("SELECT * FROM `friendrequests_db` WHERE `uid`='$uid' AND `friendid` = '$friendid';");
		if ( mysql_num_rows($result) > 0 ) {
			return true;
		} else {
			return false;
		}
	}

	
	function head() {
		echo "<script type='text/javascript' src='" . $this->getSetting('baseURL'). "cp_components/includes/jquery.js'></script>";
		echo "<script type='text/javascript' src='" . $this->getSetting('baseURL'). "cp_components/includes/javascript.js'></script>";
		echo '<link rel="stylesheet" href="' . $this->getSetting('baseURL'). 'cp_themes/'.$this->getSetting('theme').'/style.css" type="text/css" media="screen" />';
	}
	
	function getThemeDir() {
		return $this->getSetting('baseURL') . "cp_themes/" . $this->getSetting('theme') . "/";
	}
	
	function getUserSettingsURL($page=null) {
		//$page can be null or one of the settings pages from user_settings theme folder
		//by default, options are: profile_settings, privacy_settings, profile_picture, password_setting
		$base = $this->getSetting('baseURL');
		if ($page=='') {
			return $base . 'settings/profile_settings';
		} else {
			return $base . "settings/$page";
		}
	}
	
	function getUserProfileURL($uid) {
		return $this->getSetting('baseURL') . 'user/' . $this->getProfileUser($uid);
	}
	
	function getUploadPhotoURL() {
		return $this->getSetting('baseURL') . 'photo_upload/';
	}
	
	function getLogoutURL() {
	  return $this->getSetting('baseURL') . 'logout.php';	
	}
	
	//send an email to a given user ID, the beginning and end of a message will be added automatically by this function
	function sendEmail($uid, $subject, $message) {
		$sitename = $this->getSetting("site_name");
		$mailfrom = $this->getSetting("mailfrom");
		//$headers = "From: $sitename <$mailfrom>\r\n Reply-To: $sitename <$mailfrom>\r\n X-Mailer: CommunityPi";
		$headers = "From: $sitename <$mailfrom>";
		$message = "Hey " . $this->getProfileInfo($uid, "name") . ",\n\n" . $message . "\n\n" . $sitename; 
		$to = $this->getUserInfo($uid, "email");
		mail($to, $subject, $message, $headers);
	}
	
	
	function getProfileImage($uid, $size) {
		//sizes: 60, 100, 200, 500
		$this->mysqlConnect();
		$query = mysql_fetch_array(mysql_query("SELECT * FROM  `profile_db` WHERE `uid` = '$uid' LIMIT 1;"));
		
		
		$file = $this->getSetting("baseurl") . "cp_content/profileimages/" . md5($uid) . '_' . $query[profileupd] . '_' . $size . ".png";
				
		$filePath = $this->getSetting('path') . "cp_content/profileimages/" . md5($uid) . '_' . $query[profileupd] . '_' . $size . ".png";
		if (!file_exists($filePath)) {
			$file =  $this->getSetting("baseurl") . "cp_content/profileimages/" . md5('0') . '_' . $size . ".png";
		}
		
		return $file;
	}
	
	function getOnlineFriends() {
		$this->mysqlConnect();
		$uid = $_SESSION['uid'];
		$friends = mysql_query("SELECT * FROM `friends_db` WHERE uid='$uid'");
		$online_friends = 0;
		while($row = mysql_fetch_array($friends)) {
			$fid = $row['friend'];
				$result = mysql_query("SELECT * FROM `online_db` WHERE uid = '$fid' ORDER BY time DESC LIMIT 1");
				while($row = mysql_fetch_array($result)) {
					$away = time() - $row['time'];
					if ($away <= 120) {
						$online_friends = $online_friends + 1;
					echo '<div class="online_list"><a href="' . $this->getSetting('baseurl') . 'user/' . $this->getProfileUser($row['uid']) . '">' . $this->getProfileName($row['uid']) . '</a></div>';
					}
				}
			}

		
				if ($online_friends == 0) {
					echo '<div class="online_summary">No Friends are online</div>';
				} else {
					if ($online_friends == 1) {
						$cnt = "";
						$are = "is";
						} else {
							$cnt = "s";
							$are = "are";
						}

					echo '<div class="online_summary" style="font-style: italic; color: #999;">There ' . $are . ' ' . $online_friends . '  friend' . $cnt . ' online</div>';
				}
			$this->mysqlClose();
	}
	
	function timelineUpdatesAvailable($uid, $f) {
		//finds if the timeline would have any status updates or not, useful for people new to network with no friends
		//$f is boolean for friends or just user (dashboard or profile)
		//returns true or flase
		$this->mysqlConnect();
		//select correct sql query to get the right updates, user of friends
		if ( $f ) {
			$result = mysql_query("SELECT DISTINCT timeline_db.* FROM `friends_db` INNER JOIN timeline_db ON friends_db.uid=timeline_db.uid WHERE friends_db.uid='$uid' AND timeline_db.reply_to = '0' OR friends_db.friend='$uid' AND timeline_db.reply_to = '0' ORDER BY `timeline_db`.`time` DESC LIMIT 1");	
		} else {
			$result = mysql_query("SELECT * FROM `timeline_db` WHERE `uid`='$uid' ORDER BY `timeline_db`.`time` DESC LIMIT 1");
		}
		
		if ( mysql_num_rows($result) == 0 ) {
			return false;
		} else {
			return true;
		}
		
	}
	
	function sendMessage($from, $to, $subject, $message, $replyto = null) {
		$time = time();
		$this->mysqlConnect();
		$sql = mysql_query("INSERT INTO `messages_db` (`id`, `from`, `to`, `subject`, `message`, `time`, `read`, `replyto`) VALUES (NULL, '$from', '$to', '$subject', '$message', '$time', '0', '$replyto');");
		$name = $this->getProfileInfo($from, "name");
		$link = $this->getSetting('baseURL') . "messages";
		if ($this->getProfileInfo($to, "emailOnMessage") == "1") {
			$this->sendEmail($to, "$name sent you a message", "You have a new message, follow this link to read it.\n$link");
		}
		$this->createNotification($to, "$name sent you a message", $link, "uid" . $from);
		//$this->mysqlClose();
	}
	
	function deleteMessage($id) {
		$this->mysqlConnect();
		$query = mysql_query("UPDATE `messages_db` SET `deleted` = '1' WHERE `id` = '$id';");
		echo mysql_error();
		$this->mysqlClose();
	}
	
	function markMessageRead($id) {
		$this->mysqlConnect();
		$query = mysql_query("UPDATE `messages_db` SET `read` = '1' WHERE `id` = '$id';");
		echo mysql_error();
		$this->mysqlClose();
	}
	
	function getNumberUnreadMessages() {
		$uid = $_SESSION['uid'];
		$this->mysqlConnect();
		$result = mysql_query("SELECT * FROM `messages_db` WHERE `to` = '$uid' AND `deleted` = '0' AND `read` = '0';");
		return mysql_num_rows($result);
		$this->mysqlClose();
	}


	
	function getTimeline($uid, $count, $f) {
		//function to get the status update timeline
		//$uid is the userid
		//$count is the number of items to get
		//$f is a boolean value, true will get users friends updates, false will get only the users updates (for profile)
		
		$this->mysqlConnect();
		//select correct sql query to get the right updates, user of friends
		if ( $f ) {
		$result = mysql_query("SELECT DISTINCT timeline_db.* FROM `friends_db` INNER JOIN timeline_db ON friends_db.uid=timeline_db.uid WHERE friends_db.uid='$uid' AND timeline_db.reply_to = '0' OR friends_db.friend='$uid' AND timeline_db.reply_to = '0' ORDER BY `timeline_db`.`time` DESC LIMIT $count");	
		} else {
			$result = mysql_query("SELECT * FROM `timeline_db` WHERE `uid`='$uid' ORDER BY `timeline_db`.`time` DESC LIMIT $count");
		}
		while($row = mysql_fetch_array($result)) {
			$time = $row['time'];
			if (substr($row['action'], 0, 4) == "/me ") {
				$row['action'] = "<span class=\"me\">" . substr($row['action'], 4) . "</span>";
			}
			if (substr($row['action'], 0, 4) == "/my ") {
				$ext = "'s";
				$row['action'] =  "<span class=\"my\">" . substr($row['action'], 4) . "</span>";
			}
			
			
			
			$updates = $updates + 1;
			echo '<div class="status">';
			echo '<a name="status-' . $updates . '"></a>';
			if ( $f ) {
				$nameclass = 'miniprofile';
			} else {
				$nameclass = 'profilename';
			}
			echo '<span class="image"><img src="' . $this->getProfileImage($row['uid'], 60) . '" width="60px" height="60px" /></span><div class="event"><span class="name"><a href="' . $this->getUserProfileURL($row['uid']) . '" class="' . $nameclass . '" rel="' . $row['uid'] . '">' . $this->getProfileName($row['uid']) . $ext . '</a>&nbsp;</span><span class="action">' . $row['action'] . '</span></div>';
			echo '<div class="date">' . $this->timeSince($time);
				if ($row['type'] != '') {
					  echo ' via ' . $row['type'] . ' ';
					} else {
						echo ' via web ';
					}
			if ($row['reply_to'] == '0') {
				echo "<a onclick=\"showReplyBox('" . $row['id'] . "');\" class=\"timeline_reply_link\">Reply</a>";
			} else { 
				echo 'in reply to <a href="' . $this->getSetting('baseurl') . 'user/' .$this->statusUser($row['reply_to']) . '">' . $this->statusOwner($row['reply_to']) . '</a>';
			}
			$ext = '';
			$status = $row['id'];
			$comments = mysql_query("SELECT * FROM `timeline_db` WHERE `reply_to` = '$status' ORDER BY `time` ASC LIMIT 0, 20");
			$commentsc = "0";
			if(mysql_num_rows($comments)  == '0') {
				echo '</div></div>';
				$ext = '';
			} else {
				while($row = mysql_fetch_array($comments)) {
				
					if (substr($row['action'], 0, 4) == "/me ") {
						$row['action'] = "<span class=\"me\">" . substr($row['action'], 4) . "</span>";
					}
					if (substr($row['action'], 0, 4) == "/my ") {
						$ext = "'s";
						$row['action'] = "<span class=\"my\">" . substr($row['action'], 4) . "</span>";
			}
					$commentsc = $commentsc + 1;
					echo '</div><div class="comment"><span class="comment_image"><img src="' . $this->getProfileImage($row['uid'], 60) . '" width="30px" height="30px" /></span><div class="comment_event"><span class="comment_name"><a href="' . $this->getUserProfileURL($row['uid']) . '" class="' . $nameclass . '" rel="' . $row['uid'] . '">' . $this->getProfileName($row['uid']) . $ext . '</a>&nbsp;</span><span class="comment_action">' . $row['action'] . '</span></div>';
					echo '<div class="comment_date">' . $this->timeSince($row['time']);
					echo '</div>';
	
				}
				$ext = '';
			
				echo '</div><div class="clear"></div></div>';
				//echo '<div id="comment_reply"></div>';
	
			} 
		}
		
			  
		$this->mysqlClose();
	}
	
	function getProfileBirthday($uid) {
	$this->mysqlConnect();
		$result = mysql_query("SELECT * FROM `profile_db` WHERE `uid`='$uid' LIMIT 1");
			while($row = mysql_fetch_array($result))
			  {
			  	return $row['birth'];
			  }

			$this->mysqlClose();	
		
	}
	
	function statusOwner($mid) {
			$this->mysqlConnect();
		$result = mysql_query("SELECT * FROM `timeline_db` WHERE `id`='$mid' LIMIT 1");
			while($row = mysql_fetch_array($result))
			  {
			  	return $this->getProfileName($row['uid']);
			  }

			$this->mysqlClose();	
		}
		
			function statusUser($mid) {
			$this->mysqlConnect();
		$result = mysql_query("SELECT * FROM `timeline_db` WHERE `id`='$mid' LIMIT 1");
			while($row = mysql_fetch_array($result))
			  {
			  	return $this->getProfileUser($row['uid']);
			  }

			$this->mysqlClose();	
		}

		
		
	
	function getUidFromEmail($email) {
		$this->mysqlConnect();
		$result = mysql_query("SELECT * FROM `users_db` WHERE `email`='$email' LIMIT 1");
		while($row = mysql_fetch_array($result)) {
			  	return $row['id'];
			  }
		$this->mysqlClose();
	}
	
	function getUidFromUsername($username) {
		$this->mysqlConnect();
		$result = mysql_query("SELECT * FROM `users_db` WHERE `username`='$username' LIMIT 1");
		while($row = mysql_fetch_array($result)) {
			  	return $row['id'];
			  }
		$this->mysqlClose();
	}
	
	function getSetting($name) {
		$this->mysqlConnect();
			$result = mysql_query("SELECT * FROM `settings_db` WHERE `name`='$name' LIMIT 1");
			while($row = mysql_fetch_array($result))
			  {
			  	return $row['value'];
			  }

			$this->mysqlClose();	
	}
	
	function setSetting($name, $new) {
		$this->mysqlConnect();
			mysql_query("UPDATE `settings_db` SET `value` = '$new' WHERE `settings_db`.`name` ='$name';");
			$this->mysqlClose();	
	}
	
	function setUserInfo($uid, $info, $value) {
		$this->mysqlConnect();
		mysql_query("UPDATE `users_db` SET `$info` = '$value' WHERE `id` =$uid;");
				echo mysql_error();
		$this->mysqlClose();
	}
	
	function setProfileInfo($uid, $info, $value) {
		$this->mysqlConnect();
		//broken line -> mysql_query("UPDATE `profile_db` SET `$info` = `$value` WHERE `profile_db`.`uid` ='$uid';");
		mysql_query("UPDATE  `profile_db` SET  `$info` =  '$value' WHERE  `profile_db`.`uid` =$uid;");
			echo mysql_error();
		$this->mysqlClose();
	}
	
	function changePassword($uid, $pass) {
		//Since CommunityPi keeps the last 3 passwords, they must all be moved along.
		
		$this->mysqlConnect(); //connect to the database
		
		$current = mysql_query("SELECT * FROM `users_db` WHERE `id` =$uid;");
		
		while ($row = mysql_fetch_array($current)) {
			$query = mysql_query("UPDATE `users_db` SET `password` = '$pass', `password1` = '" . $row["password"] . "', `password2` = '" . $row["password1"] . "' WHERE `id` = '$uid';");	
		}
		
		$this->mysqlClose();
		
	}

	
	function getProfileInfo($uid, $info) {
		//gets any info from profile_db
			$this->mysqlConnect();
			$result = mysql_query("SELECT * FROM `profile_db` WHERE `uid`='$uid' LIMIT 1");
			while($row = mysql_fetch_array($result))
			  {
			  	return $row[$info];
			  }

			$this->mysqlClose();	
	}
	
	function getUserInfo($uid, $info) {
		//gets any info from users_db
		$this->mysqlConnect();
			$result = mysql_query("SELECT * FROM `users_db` WHERE `id`='$uid' LIMIT 1");
			while($row = mysql_fetch_array($result))
			  {
			  	return $row[$info];
			  }

			$this->mysqlClose();	
	}
	
	 function getLoginForm() {
        	include("cp_components/forms/login_form.php");
	 }
	 
	 function getRegisterForm() {
        	include("cp_components/forms/register_form.php");
	 }
	 
	 function getShortenURLForm() {
        	include("cp_components/forms/shorten_url_form.php");
	 }
	 
	 function getForm($form) {
	 	//gets $form from forms dir
	 	include ("cp_components/forms/" . $form . ".php");
	 }
	 
	 function getPhotoTimeline() {
	 	$this->mysqlConnect();
		//select correct sql query to get the right updates, user of friends
		$uid = $_SESSION['uid'];
		$count = '6';
		$result = mysql_query("SELECT DISTINCT photos_db.* FROM `friends_db` INNER JOIN photos_db ON friends_db.uid=photos_db.owner WHERE friends_db.uid='$uid' OR friends_db.friend='$uid' ORDER BY `photos_db`.`time` DESC LIMIT $count");	
		echo  mysql_error();
		if(mysql_num_rows($result)  == '0') {
			echo "<h4>There are no images to display.</h4>";
		}
		while ($row = mysql_fetch_array($result)) {
			$url = $base . 'cp_content/photos/' . $row['batch'] . '/' . $row['filename'] .'100s.png';
			$large = $base . 'cp_content/photos/' . $row['batch'] . '/' . $row['filename'] .'orig.png';

				echo "<div class=\"mini_photo_container\">";
				$link = $this->getPhotoLink($row['id'], $this->getProfileUser($row['owner']));
				echo "<a href=\"" . $link . "\"  title=\"" . $row['desc'] . "\"><img src=\"$url\" class=\"mini_photo\" align=\"left\" /></a>";
				echo "</div>";

		}

	 	
	 }
	 
	 
	
	 
        
	function getProfileName($uid) {
			$this->mysqlConnect();
			$result = mysql_query("SELECT * FROM `profile_db` WHERE `uid`='$uid' LIMIT 1");
			while($row = mysql_fetch_array($result))
			  {
			  	return $row['name'];
			  }

			$this->mysqlClose();	
		
	}
	
		function getProfileUser($uid) {
			$this->mysqlConnect();
			$result = mysql_query("SELECT * FROM `users_db` WHERE `id`='$uid' LIMIT 1");
			while($row = mysql_fetch_array($result))
			  {
			  	return $row['username'];
			  }

			$this->mysqlClose();	
		
	}
	
		function getProfileId($name) {
			$this->mysqlConnect();
			$result = mysql_query("SELECT * FROM `users_db` WHERE `username`='$name' LIMIT 1");
			while($row = mysql_fetch_array($result))
			  {
			  	return $row['id'];
			  }

			$this->mysqlClose();	
		
	}
	
		function getProfileBio($uid) {
			$this->mysqlConnect();
			$result = mysql_query("SELECT * FROM `profile_db` WHERE `uid`='$uid' LIMIT 1");
			while($row = mysql_fetch_array($result)) {
			  	return $row['bio'];
			  }

			$this->mysqlClose();	
		
	}
	
	function getProfileSetting($uid, $setting) {
			$this->mysqlConnect();
			$result = mysql_query("SELECT * FROM `profile_db` WHERE `uid`='$uid' LIMIT 1");
			while($row = mysql_fetch_array($result)) {
			  	return $row[$setting];
			  }

			$this->mysqlClose();	
		
	}
	function timeToDate($time) {
		$out = date("l, jS F o", $time);
		return $out;
	}
	function dateToTime($dd, $mm, $yyyy) {
		$time  = mktime(0, 0, 0, $mm, $dd, $yyyy);
		return $time;
	}
	function isUsernameAvailable($username) {
		$this->mysqlConnect();
		$result = mysql_query("SELECT * FROM `users_db` WHERE `username`='$username'");
		$rows = mysql_num_rows($result);
		if ($rows == '0') {
			return true;
		} else {
			return false;
		}
		$this->mysqlClose();
	}	
	
	function isEmailAvailable($email) {
		$this->mysqlConnect();
		$result = mysql_query("SELECT * FROM `users_db` WHERE `email`='$email'");
		$rows = mysql_num_rows($result);
		if ($rows == '0') {
			return true;
		} else {
			return false;
		}
		$this->mysqlClose();
	}
	
	function randomHello() {
		$hello = array("Hello", "Cor Blimey", "Bloody hell", "How do", "Yo", "Top of the morning to you", "Welcome", "Greetings");
		$hellos = array_rand($hello);
		return $hello[$hellos];
	}

		
		
	//Returns Time Since a Certain Unix Time Stamp
      function timeSince($original)  {
          $periods = array(
              array(60 * 60 * 24 * 365 , 'year'),  
              array(60 * 60 * 24 * 30 , 'month'),  
              array(60 * 60 * 24 * 7, 'week'),
              array(60 * 60 * 24 , 'day'), 
              array(60 * 60 , 'hour'), 
              array(60 , 'minute'),
            );
  
          $today = time();
 		  $since = $today - $original;			
          for ($i = 0, $j = count($periods); $i < $j; $i++)  
              {      
              $seconds = $periods[$i][0];  
              $name = $periods[$i][1];
              if (($count = floor($since / $seconds)) != 0) {
                    break;
                }
            }
              $output = ($count == 1) ? '1 '.$name : "$count {$name}s";
             if ($i + 1 < $j) {
                $seconds2 = $periods[$i + 1][0];
              $name2 = $periods[$i + 1][1];
  
                if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0) {
                    $output .= ($count2 == 1) ? ', 1 '.$name2 : ", $count2 {$name2}s";
                }
            }
            $suffix = " ago";
            if ($output == "0 minutes") {
            	$output = "Less than a minute";
            }
            if ($original < (time() - 518400)) {
            	$output = date("F j, Y", $original);
            	$suffix = "";
            }
            return $output . $suffix;
        }
        
        function age($original)  {
          $periods = array(
              array(60 * 60 * 24 * 365 , 'year'),  
              array(60 * 60 * 24 * 30 , 'month'),  
              array(60 * 60 * 24 * 7, 'week'),
              array(60 * 60 * 24 , 'day'), 
              array(60 * 60 , 'hour'), 
              array(60 , 'minute'),
            );
  
          $today = time();
 		  $since = $today - $original;			
          for ($i = 0, $j = count($periods); $i < $j; $i++)  
              {      
              $seconds = $periods[$i][0];  
              $name = $periods[$i][1];
              if (($count = floor($since / $seconds)) != 0) {
                    break;
                }
            }
              $output = ($count == 1) ? '1 '.$name : "$count {$name}s";
             if ($i + 1 < $j) {
                $seconds2 = $periods[$i + 1][0];
              $name2 = $periods[$i + 1][1];
  
                if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0) {
                    $output .= ($count2 == 1) ? ', 1 '.$name2 : ", $count2 {$name2}s";
                }
            }
            if ($output == "0 minutes") {
            	$output = "Less than a minute ago";
            } 
            
return $output;
        }

        
        function updateStatus($uid, $message, $reply, $type="web") {
        	
        	if ($reply > '0') { //if this is a reply to someone else's status, send that person a notification
        		//get the ID of the user to which this is a reply to
        		$this->mysqlConnect();
        		$result = mysql_query("SELECT * FROM `timeline_db` WHERE `id` = '$reply';");
        		while ($row = mysql_fetch_array($result)) {
        			$replyToId = $row['uid'];
        		}
        		$this->mysqlClose();
        		if ($replyToId != $uid) { //if you haven't commented on your own status, create the notification
        			$name = $this->getProfileInfo($uid, "name");
        			$link = $this->getUserProfileURL($replyToId);
        			$this->createNotification($replyToId, "$name commented on your status", $link, "uid" . $uid);
        			if ($this->getProfileInfo($replyToId, "emailOnComment") == "1") {
        				$this->sendEmail($replyToId, "$name commented on your status", "$name posted a comment on your status update. Click here to check it out.\n$link");
        			}
        		}
        		
        		//We must also notify anyone else who has commented on this status
        		//start by finding out who we are replying to
        		$nameRepTo = $this->getProfileInfo($replyToId, "name"); //get the name of the user to which this is a reply
        		$link = $this->getUserProfileURL($replyToId); //get the link to the users profile
        		$nameCurrent = $this->getProfileInfo($uid, "name"); //get the name of the user who is sending the reply
        		
        		$this->mysqlConnect(); //Connect to the database
        		$result = mysql_query("SELECT DISTINCT `uid` FROM `timeline_db` WHERE `reply_to` = '$reply';"); //Get all other statuses which were a reply to the status we are replying to, removing duplicates
        		$this->mysqlClose();
        		
        		while ($row = mysql_fetch_array($result)) { //loop through each other comment on the status
        			$notifyId = $row['uid']; //get the user ID of the other person who commented on this status
        			if ($notifyId != $replyToId && $notifyId != $_SESSION['uid']) { //make sure it's not the person who originally wrote the status (because we've just notified them) or the user who is actually writing this comment
        				if ($nameCurrent == $nameRepTo) {
        					$nameRepTo2_a = "their";
        					$nameRepTo2_b = "their";
        				} else {
        					$nameRepTo2_a = $nameRepTo . "'s";
        					$nameRepTo2_b = $nameRepTo . "\'s";
        					
        				}
        				
        				$this->createNotification($notifyId, "$nameCurrent commented on $nameRepTo2_a status", $link, "uid" . $uid);
        				if ($this->getProfileInfo($notifyId, "emailOnComment") == "1") {
        					$this->sendEmail($notifyId, "$nameCurrent commented on $nameRepTo2_a status", "$nameCurrent posted a comment on $nameRepTo2_a status update which you also commented on! Click here to check it out.\n$link");
        				}

        			}
        		}
        		
        	}
        	$this->mysqlConnect();
        	mysql_query("INSERT INTO `timeline_db` (`id`, `uid`, `action`, `time`, `reply_to`, `type`) VALUES (NULL, '$uid', '$message', UNIX_TIMESTAMP(), '$reply', '$type');");
        	$this->mysqlClose();
        }
        
        //Returns the current unix time stamp
        function currentTime() {
        	return time();
        }
        
        function timelineRssFeed($uid) {
        		$this->mysqlConnect();
		$result = mysql_query("SELECT * FROM `timeline_db` WHERE `uid`='$uid' ORDER BY `timeline_db`.`time` DESC");
		echo '<?xml version="1.0" ?><rss version="2.0"><channel><title>Status</title><description>' . $this->getProfileName($uid) . '\'s Status\'s</description><link>http://live.jakewright.net</link>';
			while($row = mysql_fetch_array($result))
			  {
				echo '<item><title>' . $this->getProfileName($uid) . '\'s Status - ' . $this->timeToDate($row[time]) . '</title><description>' . $this->getProfileName($uid) . ' ' . $row['action'] . '</description><link>http://socal.inozzo.com/bla/status.php?id=' . $row['id'] . '</link><pubDate>' . date('r', $row['time']) . '</pubDate></item>';
			  }

		$this->mysqlClose();
		echo "</channel></rss>";

        }
        
        function userIsLoggedIn() {
        	if($_SESSION['loggedin'] == "yes") {
        		return true;
        	} else {
        		return false;
        	}
        }
        
                
        function showPhotos($uid, $count, $size, $title=null, $profile='no') {
        	//$title is boolean to echo photo titles or not
        	$this->mysqlConnect();
        	$base = $this->getSetting('baseurl');
        	$result = mysql_query("SELECT * FROM `photos_db` WHERE `owner`='$uid' ORDER BY `time` DESC LIMIT $count");
        	if (mysql_num_rows($result) == 0) {
        		if ( $uid == $_SESSION['uid'] ) {
        			echo "No photos available.";
        		}
        	} else {
        	while($row = mysql_fetch_array($result))
			  {
			  	$url = $base . 'cp_content/photos/' . $row['batch'] . '/' . $row['filename'] . $size . 's.png';
			  	$large = $base . 'cp_content/photos/' . $row['batch'] . '/' . $row['filename'] . 'orig.png';
			  	echo "<div class=\"";
				if ($profile == 'yes') {
					echo 'profile_photo_container';
				} else {
					echo 'mini_photo_container';
				}			
				$link = $this->getSetting("baseurl") . 'photo/' . $this->getProfileUser($uid) . '/' . $row['id'];
				echo "\"><a href=\"" . $link . "\" title=\"" . $row['desc'] . "\"><img src=\"$url\" class=\"mini_photo\" /></a>";
			  	if ( $title ) {
			  		echo "<div class=\"mini_photo_title\">".$row['title']."</div>";
			  	}
			  	echo "</div>";
			  }
        	}

        	
        	$this->mysqlClose();
        }
        
        function hasPhotos($uid) {
        	$this->mysqlConnect();
        	$result = mysql_query("SELECT * FROM `photos_db` WHERE `owner` = '$uid';");
        	if (mysql_num_rows($result) > 0) {
        		return true;
        	} else {
        		return false;
        	}	
        }
        
        function createThumb($source,$dest, $thumb_size) {
		$size = getimagesize($source);
		$width = $size[0];
		$height = $size[1];
		if($width> $height) {
			$x = ceil(($width - $height) / 2 );
			$width = $height;
		} elseif ($height> $width) {
			$y = ceil(($height - $width) / 2);
			$height = $width;
		}
		$new_im = ImageCreatetruecolor($thumb_size,$thumb_size);
	    $ext = strtolower(substr($source, -3));
		if ($ext == "jpg" || $ext == "peg") {
			$im = imagecreatefromjpeg("$source");
		}
		if ($ext == "png") {
			$im = imagecreatefrompng("$source");
		}
		if ($ext == "gif") {
			$im = imagecreatefromgif("$source");
		}
		$white = imagecolorallocate($new_im, 255, 255, 255);
		imagefilledrectangle($new_im, 0, 0, $width, $height, $white);
		imagecopyresampled($new_im,$im,0,0,$x,$y,$thumb_size,$thumb_size,$width,$height);
		imagepng($new_im,$dest);
	}

        
        
        function createThumbnail($imagefile, $newfile, $thumbWidth) {
        	$ext = strtolower(substr($imagefile, -3));
        	if ($ext == "jpg" || $ext == "peg") {
        		$img = imagecreatefromjpeg("$imagefile");
        	}
        	if ($ext == "png") {
        		$img = imagecreatefrompng("$imagefile");
        	}
        	if ($ext == "gif") {
        		$img = imagecreatefromgif("$imagefile");
        	}
        	
      		$width = imagesx($img);
      		$height = imagesy($img);
      		$new_width = $thumbWidth;
      		$new_height = floor( $height * ( $thumbWidth / $width ) );
      		$tmp_img = imagecreatetruecolor( $new_width, $new_height );
      		imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
      		imagepng( $tmp_img, $newfile);
			}
			
			//Create a group, $image is string URL to image file
			function createGroup($name, $desc, $image, $slug=null) {
				$uid = $_SESSION['uid'];
				$time = time();
				//if a slug was not provided, stip out all non alphanumeric characters from the name and use that
				if ($slug == null) {
					$slug = preg_replace("/[^a-zA-Z0-9]/", "", $name);
				}
				
				$this->mysqlConnect();
				$query = mysql_query("INSERT INTO `groups_db` (`name`, `desc`, `owner`, `creation`, `image`, `slug`) VALUES ('$name',  '$desc', '$uid',  '$time',  '$image',  '$slug');");
				//Add user to this group
				$this->addUserToGroup($uid, $this->getGroupIDFromSlug($slug));
				//$this->mysqlClose();
				
			}
			
			//Delete a group and all related database records
			function deleteGroup($gid) {
				$this->mysqlConnect();
				$query = mysql_query("DELETE FROM `groups_db` WHERE `id` = '$gid';");
				$query = mysql_query("DELETE FROM `group_invites_db` WHERE `gid` = '$gid';");
				$query = mysql_query("DELETE FROM `group_members_db` WHERE `gid` = '$gid';");
				$query = mysql_query("DELETE FROM `group_timeline_db` WHERE `gid` = '$gid';");
			}
			
			//Invite a user to a group, $fid = ID of the user to be invited to the group with id $gid
			function inviteToGroup($fid, $gid) {
				$uid = $_SESSION['uid'];
				$time = time();
				$this->mysqlConnect();
				$query = mysql_query("INSERT INTO `group_invites_db` (`uid`, `fid`, `gid`, `time`) VALUES ('$uid', '$fid', '$gid', '$time');");
				$this->mysqlClose();
			}
			
			function addUserToGroup($uid, $gid) {
				$time = time();
				$this->mysqlConnect();
				$query = mysql_query("INSERT INTO `group_members_db` (`uid`, `gid`, `time`) VALUES ('$uid', '$gid', '$time');");
				$this->mysqlClose();	
			}
			
			function removeUserFromGroup($uid, $gid) {
				$this->mysqlConnect();
				$query = mysql_query("DELETE FROM `group_members_db` WHERE `uid` = '$uid' AND `gid` = '$gid';");
				$this->mysqlClose();
			}
			
			//Check if group name is available
			function isGroupNameAvailable($name) {
				$this->mysqlConnect();
				$result = mysql_query("SELECT * FROM `groups_db` WHERE `name` = '$name';");
				if (mysql_num_rows($result) == 0) {
					$this->mysqlClose();
					return true;
				} else {
					$this->mysqlClose();
					return false;
				}
			}
			
			function isMemberOfGroup($uid, $gid) {
				$this->mysqlConnect();
				$query = mysql_query("SELECT * FROM  `group_members_db` WHERE  `uid` = '$uid' AND  `gid` = '$gid' LIMIT 1");
				$count = mysql_num_rows($query);
				if ($count == 1) {
					return true;
				} else {
					return false;
				}
				$this->mysqlClose();
			}
			
			function isAdminOfGroup($uid, $gid) {
				$this->mysqlConnect();
				$query = mysql_query("SELECT * FROM `groups_db` WHERE `owner` = '$uid' AND `id` = '$gid' LIMIT 1;");
				if (mysql_num_rows($query) == 1) {
					return true;
				} else {
					return false;
				}
			}
			
			function listGroups() {
				$uid = $_SESSION['uid'];
				$this->mysqlConnect();
				$query = mysql_query("SELECT DISTINCT `gid` FROM  `group_members_db` WHERE `uid` = '$uid';");
				
				
				while ($row = mysql_fetch_array($query)) {
					$gid = $row['gid'];
					$result = mysql_query("SELECT * FROM `groups_db` WHERE `id` = '$gid';");
					while ($row = mysql_fetch_array($result)) {
						echo '<div class="group">
							  <div class="name"><a href="group/' . $row['slug'] . '">
							  ' . substr($row['name'], 0, 30) . '</a>
							  </div>
							  <div class="image">
							  <img src="
							  ' . $row['image'] . '
							  " align="left" />
							  <div class="desc">' . substr($row['desc'], 0, 100) . '</div>
							  </div>
							  <div class="count">
							  ' . $this->countMembersInGroup($row['id']) . ' members
							  </div>' . 
							  '</div>';
					}
				}
				$this->mysqlClose();
			}
			
			function countMembersInGroup($gid) {
				$this->mysqlConnect();
				$query = mysql_query("SELECT * FROM  `group_members_db` WHERE gid = '$gid'");
				$count = mysql_num_rows($query);
				return $count;
				$this->mysqlClose();
			}
			
			function getGroupInfo($gid) {
				$this->mysqlConnect();
				$query = mysql_query("SELECT * FROM  `groups_db` WHERE id = '$gid'");
				$result = mysql_fetch_array($query);
				$this->mysqlClose();
				return $result;
			}
			
			function getGroupMembers($gid) {
				$this->mysqlConnect();
				$query = mysql_query("SELECT * FROM `group_members_db` WHERE `gid` = '$gid' LIMIT 18;");
				while ($row = mysql_fetch_array($query)) {
					$results[] = $row['uid'];
				} 
				return $results;
			}
			
			function searchGroups($searchterms) {
				$this->mysqlConnect();
				$searchterms = '%' . $searchterms . '%';
				$query = mysql_query("SELECT * FROM `groups_db` WHERE `name` LIKE '$searchterms';"); 
				$i=0;
				while ($row = mysql_fetch_array($query)) {
					$results[$i]['id'] = $row['id'];
					$results[$i]['name'] = $row['name'];
					$results[$i]['desc'] = $row['desc'];
					$results[$i]['owner'] = $row['owner'];
					$results[$i]['creation'] = $row['creation'];
					$results[$i]['image'] = $row['image'];
					$results[$i]['slug'] = $row['slug'];
					$i++;
				} 
			
				return $results;
 		 
			}
			
			

			function getGroupIDFromSlug($slug) {
				$this->mysqlConnect();
				$query = mysql_query("SELECT id FROM  `groups_db` WHERE slug = '$slug'");
				$result = mysql_fetch_array($query);
				return $result[0][id];
				$this->mysqlClose();
			}
			
			function updateGroupStatus($uid, $gid, $message, $reply, $type="web") {
        	
        	if ($reply > '0') { //if this is a reply to someone else's status, send that person a notification
        		//get the ID of the user to which this is a reply to
        		$this->mysqlConnect();
        		$result = mysql_query("SELECT * FROM `group_timeline_db` WHERE `id` = '$reply';");
        		while ($row = mysql_fetch_array($result)) {
        			$replyToId = $row['uid'];
        		}
        		$this->mysqlClose();
        		if ($replyToId != $uid) { //if you haven't commented on your own status, create the notification
        			$name = $this->getProfileInfo($uid, "name");
        			$link = $this->getSetting('baseURL') . "group/" . $this->getGroupInfo($gid, "slug");
        			$this->createNotification($replyToId, "$name commented on your status", $link, "uid" . $uid);
        			if ($this->getProfileInfo($replyToId, "emailOnComment") == "1") {
        				$this->sendEmail($replyToId, "$name commented on your status", "$name posted a comment on your status update. Click here to check it out.\n$link");
        			}
        		}
        		
        		
        		
        		//We must also notify anyone else who has commented on this status
        		//start by finding out who we are replying to
        		$nameRepTo = $this->getProfileInfo($replyToId, "name"); //get the name of the user to which this is a reply
        		$link = $this->getSetting('baseURL') . "group/" . $this->getGroupInfo($gid, "slug");
        		$nameCurrent = $this->getProfileInfo($uid, "name"); //get the name of the user who is sending the reply
        		
        		$this->mysqlConnect(); //Connect to the database
        		$result = mysql_query("SELECT DISTINCT `uid` FROM `group_timeline_db` WHERE `reply_to` = '$reply';"); //Get all other statuses which were a reply to the status we are replying to, removing duplicates
        		$this->mysqlClose();
        		
        		while ($row = mysql_fetch_array($result)) { //loop through each other comment on the status
        			$notifyId = $row['uid']; //get the user ID of the other person who commented on this status
        			if ($notifyId != $replyToId && $notifyId != $_SESSION['uid']) { //make sure it's not the person who originally wrote the status (because we've just notified them) or the user who is actually writing this comment
        				if ($nameCurrent == $nameRepTo) {
        					$nameRepTo2_a = "their";
        					$nameRepTo2_b = "their";
        				} else {
        					$nameRepTo2_a = $nameRepTo . "'s";
        					$nameRepTo2_b = $nameRepTo . "\'s";
        					
        				}
        				
        				$this->createNotification($notifyId, "$nameCurrent commented on $nameRepTo2_a status", $link, "uid" . $uid);
        				if ($this->getProfileInfo($notifyId, "emailOnComment") == "1") {
        					$this->sendEmail($notifyId, "$nameCurrent commented on $nameRepTo2_a status", "$nameCurrent posted a comment on $nameRepTo2_a status update which you also commented on! Click here to check it out.\n$link");
        				}

        			}
        		}
        		
        	} else { //if this is not a reply (new message in group)
        	
        		//We must notify everyone in this group
        		$result = mysql_query("SELECT * FROM `group_members_db` WHERE `gid` = '$gid';");
        		while ($row = mysql_fetch_array($result)) {
        			$notifyId = $row['uid'];
        			if ( $notifyId != $uid ) {
        				$name = $this->getProfileInfo($uid, "name");
        				$groupInfo = $this->getGroupInfo($gid);
        				$group = $groupInfo['name'];
        				$link = $this->getSetting('baseurl') . "group/" . $groupInfo['slug'];
        				$this->createNotification($notifyId, "$name posted a new message to \"$group\"", $link, "uid" . $uid);
        				if ( $this->getProfileInfo($notifyId, "emailOnGroupMessage") == "1" ) {
        					$this->sendEmail($notifyId, "New group message", "$name posted a new message to the group \"$group\". Click here to check it out.\n$link"); 
        				}
        			}
        		}
        			
        	}
        	$this->mysqlConnect();
        	mysql_query("INSERT INTO `group_timeline_db` (`gid`, `uid`, `action`, `time`, `reply_to`, `type`) VALUES ('$gid', '$uid', '$message', UNIX_TIMESTAMP(), '$reply', '$type');");
        	$this->mysqlClose();
        }



			
			function getGroupTimeline($gid) {
				//function to get the timeline for a group
				$count = 20;
				$this->mysqlConnect();
				$result = mysql_query("SELECT * FROM `group_timeline_db` WHERE `gid` = '$gid' AND `reply_to` = '0' ORDER BY `group_timeline_db`.`time` DESC LIMIT $count;");
		
				while($row = mysql_fetch_array($result)) {
					$time = $row['time'];
					if (substr($row['action'], 0, 4) == "/me ") {
						$row['action'] = "<span class=\"me\">" . substr($row['action'], 4) . "</span>";
					}
					
					if (substr($row['action'], 0, 4) == "/my ") {
						$ext = "'s";
						$row['action'] =  "<span class=\"my\">" . substr($row['action'], 4) . "</span>";
					}
			
					$updates = $updates + 1;
					echo '<div class="status">';
					echo '<a name="status-' . $updates . '"></a>';
			
					echo '<span class="image"><img src="' . $this->getProfileImage($row['uid'], 60) . '" width="60px" height="60px" /></span><div class="event"><span class="name"><a href="' . $this->getUserProfileURL($row['uid']) . '" class="' . $nameclass . '" rel="' . $row['uid'] . '">' . $this->getProfileName($row['uid']) . $ext . '</a>&nbsp;</span><span class="action">' . $row['action'] . '</span></div>';
					echo '<div class="date">' . $this->timeSince($time);
					
					if ($row['type'] != '') {
						echo ' via ' . $row['type'] . ' ';
					} else {
						echo ' via web ';
					}
					
					if ($row['reply_to'] == '0') {
						echo "<a onclick=\"showGroupReplyBox('" . $row['id'] . "', '$gid');\" class=\"timeline_reply_link\">Reply</a>";
					} else { 
						echo 'in reply to <a href="' . $this->getSetting('baseurl') . 'user/' .$this->statusUser($row['reply_to']) . '">' . $this->statusOwner($row['reply_to']) . '</a>';
					}
					
					$ext = '';
					$status = $row['id'];
					$comments = mysql_query("SELECT * FROM `group_timeline_db` WHERE `reply_to` = '$status' ORDER BY `time` ASC LIMIT 0, 20");
					$commentsc = "0";
					if(mysql_num_rows($comments)  == '0') {
						echo '</div></div>';
						$ext = '';
					} else {
						while($row = mysql_fetch_array($comments)) {
							if (substr($row['action'], 0, 4) == "/me ") {
								$row['action'] = "<span class=\"me\">" . substr($row['action'], 4) . "</span>";
							}
							if (substr($row['action'], 0, 4) == "/my ") {
								$ext = "'s";
								$row['action'] = "<span class=\"my\">" . substr($row['action'], 4) . "</span>";
							}
							$commentsc = $commentsc + 1;
							echo '</div><div class="comment"><span class="comment_image"><img src="' . $this->getProfileImage($row['uid'], 60) . '" width="30px" height="30px" /></span><div class="comment_event"><span class="comment_name"><a href="' . $this->getUserProfileURL($row['uid']) . '" class="' . $nameclass . '" rel="' . $row['uid'] . '">' . $this->getProfileName($row['uid']) . $ext . '</a>&nbsp;</span><span class="comment_action">' . $row['action'] . '</span></div>';
							echo '<div class="comment_date">' . $this->timeSince($row['time']);
							echo '</div>';
	
						}
						$ext = '';
			
						echo '</div><div class="clear"></div></div>';
	
	
					} 
				}
			}

			
			
			//Get friends (mainly for friends page) of a particular user and show them in a particular "view"
			function getFriendsList($uid=null, $view=null)
			{
				//The grid view simply displays friends as pictures/names in boxes, it's up to CSS to wrap them into a grid shape.
				
				//Set variables if they are null
				if (!$uid) {
					$uid = $_SESSION['uid'];
				}
				if (!$view) {
					$view = "grid";
				}
				
				$this->mysqlConnect();
				$result = mysql_query("SELECT * FROM `friends_db` WHERE `uid` = '$uid';");
				
				if ($view == "grid") {
					echo '<div class="friends_grid">';
				}
				
				while ($row = mysql_fetch_array($result))
				{
					if ($view == "grid") {
						echo '<a href="' . $this->getUserProfileURL($row['friend']) . '"><div class="friend">';
							echo '<div class="friend_picture"><img src="' . $this->getProfileImage($row['friend'], 100) . '" width="100px" height="100px" /></div>';
							echo '<div class="friend_name">' . $this->getProfileName($row['friend']) . '</div>';
						echo '</a></div>';
					}
				}
				
				echo '<div class="clear"></div>';
				
			}
					
		function friendRequestGallery() {
			$this->mysqlConnect();
			$uid = $_SESSION['uid'];
			$query = mysql_query("SELECT * FROM `friendrequests_db` WHERE `friendid` = '$uid';"); 
			if (mysql_num_rows($query) > '0') {
				echo '<div class="friend_requests_gallery"><div class="friend_requests_title">Friend Requests</div>';
				while ($row = mysql_fetch_array($query)) {
					echo '<div class="friend_request">
						  	<a href="' . $this->getUserProfileURL($row['uid']) . '">
						  		<img src="' . $this->getProfileImage($row['uid'], '100') . '"></a>
						  	<a href="' . $this->getUserProfileURL($row['uid']) . '">
						  		<div class="name">' . $this->getProfileName($row['uid']) . '</div>
						  	</a>
						  </div>';
					}
				echo '<div class="clear"></div></div>';
			}
			$this->mysqlClose();
		}
				
				function messagesGallery() { 
					$this->mysqlConnect();
					$uid = $_SESSION['uid'];
					if ($this->getNumberUnreadMessages() == 0 ) {
						//no messages
					} else {
					$query = mysql_query("SELECT * FROM `messages_db` WHERE `to` = '$uid' ORDER BY time DESC LIMIT 5"); 
							echo '<h3>Messages</h3><p>';
							while ($row = mysql_fetch_array($query)) {
								if (trim($row['subject']) == '') {
									echo '<em>no subject</em> - From ' . $this->getProfileName($row['from']) . '<br />';
								} else {
								echo $row['subject'] . ' - From ' . $this->getProfileName($row['from']) . '<br />';
								}
							
						}
						echo '<a href="messages">Read All Messages</a>';
					}
					$this->mysqlClose();
				}
		
		
		function groupInvitesGallery() {
		}
		
		function eventsGallery() {
		}
		
		function mightKnow() {
 			//Connect to mysql
			$this->mysqlConnect();
			$uid = $_SESSION['uid'];

			//Get all friends into array of ids
			$query = mysql_query("SELECT uid,friend FROM `friends_db` WHERE `uid` = '$uid'");
			$i = 1;
			 while ($row = mysql_fetch_array($query)) {
			 		$data = $row['friend'];
			 	 	$friends[$i] = $data;
				 	$i++;
			 }
 			if ($friends) {
				foreach ($friends as $data) {
			 		$query = mysql_query("SELECT *  FROM `friends_db` WHERE `uid` = '$data' AND `friend` != '$uid'");
			 	 	while ($row = mysql_fetch_array($query)) {
			 	 		$fid = $row['friend'];
			 	 		if (!$this->isFriend($uid,$fid)) {
			 	 			$relation[ $fid ] = $relation[ $fid ] + 1;
			 	 		}
 	 	 			}
			 	}
 	
			}
			
			
			
			//For each possible suggestion...
			if ( sizeof($relation) > 0 ) { // (if there are any)
				foreach ($relation as $key => $value) {
					//Make sure the user hasn't ignored this suggestion previously
					$result = mysql_query("SELECT * FROM `ignored_friend_suggestions_db` WHERE `uid` = '$uid' AND `iid` = '$key';"); //Search the ignored_friend_suggestions_db table for entries of the current user ignoring this potential suggestion
					if ( mysql_num_rows($result) > 0 ) { //If we got any results from the above query...
						unset($relation[$key]); //...remove this friend suggetion from the array.
					}
					
					//Make sure they have more than one mutual friend
					$friends = mysql_query("SELECT * FROM `friends_db` WHERE `uid` = '$uid';");
					$min = mysql_num_rows($friends);
					if ($value < round(($min / 10), 0)) { //This is the minimum number of mutual friends required before the suggestion is made.
						unset($relation[$key]); //if the users have less than the minimum number of mutual friends in common, remove the suggestion from the array
					}
				}
			}
			
			$this->mysqlClose();
		 	@arsort($relation,SORT_NUMERIC);
			return $relation;
 		}
		 
		 //Prints a dashboard page gallery of people the user may know.
		 function mightKnowGallery() {
		 	if ($this->mightKnow()) {
				echo '<div class="might_know_gallery"><div class="might_know_title">People you might know</div>';
				$i = 1;
				foreach ($this->mightKnow() as $key => $value) {
					if ( $i <= 5) {
						echo '<div class="suggestion">
							<div class="ignore_suggestion"><a href="' . $base . 'call.php?f=friend&func=ignore_suggestion&fid=' . $key . '"><img class="ignore_btn" src="' . $base . 'cp_images/fancy_close.png" /></a></div>
							<a href="' .  $this->getUserProfileURL($key) . '">
							<img src="' . $this->getProfileImage($key,100) . '" width="100" height="100" /></a>
							<a href="' .  $this->getUserProfileURL($key) . '"><div class="name">' . $this->getProfileName($key) . '
							<div class="number_common_friends">
							' . $value;
						if ($value == 1) {
							echo ' mutual friend ';
						} else {
							echo ' mutual friends ';
						}
						echo '</div></div></a></div>';
		 				$i++;
					}
		
				}
				echo '<div class="clear"></div></div>';
			}
		}
		
		
		//Allows a user to remove a suggested friend if they don't know the person, where sid is the id of the user to be ignored (suggested id)
		function ignoreFriendSuggestion($sid) {
			$uid = $_SESSION['uid']; //get the user ID of the current user
			$this->mysqlConnect(); //connect to database
			$query = mysql_query("INSERT INTO `ignored_friend_suggestions_db` (`uid` , `iid`) VALUES ('$uid',  '$sid');"); //add the users ID and the ID of the user they wish to ignore into the database
			$this->mysqlClose(); //close connection to database
		}

		
		function getPhotoLink($id, $owner) {
			return $this->getSetting("baseurl") . 'photo/' . $owner . '/' . $id;
		}
			
			
		function isRoot($uid) {
			$this->mysqlConnect();
			$query = mysql_query("SELECT * FROM  `users_db` WHERE  `root` = '1' AND `id` = '$uid' LIMIT 1;");
			$result = mysql_num_rows($query);
			$this->mysqlClose();
			if ($result == '1') {
				return true;
			} else {
				return false;
			}
		}
		
		function isAdmin($uid) {		
			$this->mysqlConnect();
			$query = mysql_query("SELECT * FROM  `users_db` WHERE  `admin` = '1' AND `id` = '$uid' LIMIT 1;");
			$result = mysql_num_rows($query);
			$this->mysqlClose();
			if ($result == '1') {
				return true;
			} else {
				return false;
			}
		}
		
		function searchPeople($searchterms) {
			$this->mysqlConnect();
			$searchterms = '%' . $searchterms . '%';
			$query = mysql_query("SELECT `uid` FROM `profile_db` WHERE `name` LIKE '$searchterms';"); 
			while ($row = mysql_fetch_array($query)) {
				$results[] = $row['uid'];
			} 
			
			return $results;
 		 
		}
		 
				
				
				

		function mysqlConnect() {
			$con = mysql_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD);
			if (!$con)
			  {
			  die('Could not connect: ' . mysql_error());
			  }

			mysql_select_db(DB_NAME, $con);
			
		}
		
		
		function mysqlClose() {
			mysql_close();
		}
		
}

?>