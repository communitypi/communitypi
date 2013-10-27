<?php
include("../../communitypi.class.php");
$communitypi = new communitypi;

if($communitypi->userIsLoggedIn()) {

	$uid = $_SESSION['uid'];
	
	//This page takes the url parameter 'p'
	//p, standing for page, can either be 'details' 'privacy' 'picture' or 'password'
	
	if ( $_GET['p'] == 'details' ) {
		//update name, email, bio and birth to database tables profile_db and users_db
		
		if (isset($_POST['profile_settings_submit'])) {
		
			//get all info from form
			$name = htmlentities($_POST['profile_settings_name']);
			$email = $_POST['profile_settings_email'];
			$bio = htmlentities(substr($_POST['profile_settings_bio'],0,$communitypi->getSetting('bio_char_limit')));
				$birthmonth = $_POST['profile_settings_birth_month'];
				$birthday = $_POST['profile_settings_birth_day'];
				$birthyear = $_POST['profile_settings_birth_year'];
			//convert birthday to dd-month yyyy format and then into epoch time
			$birth = strtotime($birthday . "-" . $birthmonth . " " . $birthyear);
			$quest1 = htmlentities(substr($_POST['profile_settings_quest1'],0,$communitypi->getSetting('bio_char_limit')));
			$quest2 = htmlentities(substr($_POST['profile_settings_quest2'],0,$communitypi->getSetting('bio_char_limit')));
			$quest3 = htmlentities(substr($_POST['profile_settings_quest3'],0,$communitypi->getSetting('bio_char_limit')));
			$quest4 = htmlentities(substr($_POST['profile_settings_quest4'],0,$communitypi->getSetting('bio_char_limit')));

			
			//if a field is empty, update setting with same value as before
			if(trim($name) == '') {
				$name = $communitypi->getProfileInfo($uid, 'name');
			}
			
			if($email == '') {
				$email = $communitypi->getUserInfo($uid, 'email');
			}
			
			if($bio == '') {
				$bio = $communitypi->getProfileInfo($uid, 'bio');
			}
			
			if($birth == '') {
				$birth = $communitypi->getProfileInfo($uid, 'name');
			}
			
			if($quest1 == '') {
				$quest1 = $communitypi->getProfileInfo($uid, 'quest1');
			}
			
			if($quest2 == '') {
				$quest2 = $communitypi->getProfileInfo($uid, 'quest2');
			}
			
			if($quest3 == '') {
				$quest3 = $communitypi->getProfileInfo($uid, 'quest3');
			}
			
			if($quest4 == '') {
				$quest4 = $communitypi->getProfileInfo($uid, 'quest4');
			}
			
		
			if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email)) {
				//continue
			} else {
				//Not Valid Email
				$settingsURL = $communitypi->getUserSettingsURL();
				header("Location: $settingsURL&message=Email+not+valid&messagemood=sad");
				exit();
			}
			
			$today = time();
			$minage = (int)$communitypi->getSetting('minage');
			$minagesec = 60 * 60 * 24 * 365.25 * $minage;
			$maxbirthyear = $today - $minagesec;
			if ( $maxbirthyear < $birth ) {
				$settingsURL = $communitypi->getUserSettingsURL();
				header("Location: $settingsURL&message=Too+young&messagemood=sad");
				exit();
			}
			
			$communitypi->setProfileInfo($uid, 'name', $name);
			$communitypi->setUserInfo($uid, 'email', $email);
			$communitypi->setProfileInfo($uid, 'bio', $bio);
			$communitypi->setProfileInfo($uid, 'birth', $birth);
			$communitypi->setProfileInfo($uid, 'quest1', $quest1);
			$communitypi->setProfileInfo($uid, 'quest2', $quest2);
			$communitypi->setProfileInfo($uid, 'quest3', $quest3);
			$communitypi->setProfileInfo($uid, 'quest4', $quest4);
			if ($communitypi->getProfileSetting($uid, 'welcome') == '0') {
				$communitypi->setProfileInfo($uid, 'welcome', '1');
			}

			
			//echo $name . $email . $bio . $birth . $quest1 . $quest2 . $quest3 . $quest4;
			
			
			//finally take back to profile with message
			$profile = $communitypi->getUserProfileURL($uid);
			header("Location: $profile&message=Profile+Updated&messagemood=happy");
		
		}
		
		
	} elseif ( $_GET['p'] == 'privacy' ) {
		$privacy = $_POST['privacy'];
		if ($privacy == "public") {
			$communitypi->setProfileInfo($uid, 'publicprof', '1');
			$communitypi->setProfileInfo($uid, 'userprof', '1');			
		} elseif ($privacy == "members") {
			$communitypi->setProfileInfo($uid, 'publicprof', '0');
			$communitypi->setProfileInfo($uid, 'userprof', '1');
		} else {
			$communitypi->setProfileInfo($uid, 'publicprof', '0');
			$communitypi->setProfileInfo($uid, 'userprof', '0');
		}
		
		$profile = $communitypi->getUserProfileURL($uid);
		header("Location: $profile&message=Profile+Updated&messagemood=happy");

		
	} elseif ( $_GET['p'] == 'photo' ) {
		 foreach ($_FILES as $file) { 
		if ( $file["type"] == "image/gif" || $file["type"] == "image/jpeg" || $file["type"] == "image/pjpeg" || $file["type"] == "image/png" && $file["size"] < $communitypi->getSetting('maxuploadfilesize')) {
			$extension = substr($file['name'], -3);
				$tempfile = md5(time() . $file['name']);
				$newfile = '../../cp_content/tmp/' . $tempfile . '.' . $extension;
				$move = move_uploaded_file($file['tmp_name'], $newfile);
				if (!$move) {
					//header("Location: " . );
					echo "File error!";
					exit();
				}
				
				$uid = $_SESSION['uid'];
				$now = time();
				$communitypi->mysqlConnect();
				$query = mysql_query("UPDATE  `profile_db` SET  `profileupd` = '$now' WHERE  `profile_db`.`pid` = '$uid';");
				if (!$query) {
					echo mysql_error();
				}

				//lets gather some infomation about this image
				$filesize = filesize($newfile);
				$communitypi->createThumb($newfile, "../../cp_content/profileimages/" . md5($uid) . '_' . $now . '_' . "600.png", 600);
				$communitypi->createThumb($newfile, "../../cp_content/profileimages/" . md5($uid) . '_' . $now . '_' . "200.png", 200);
				$communitypi->createThumb($newfile, "../../cp_content/profileimages/" . md5($uid) . '_' . $now . '_' . "100.png", 100);
				$communitypi->createThumb($newfile, "../../cp_content/profileimages/" . md5($uid) . '_' . $now . '_' . "60.png", 60);
		$profile = $communitypi->getUserProfileURL($uid);
			$_SESSION['p_mood'] = 'happy';
			$_SESSION['p_message'] = 'Profile Photo Updated!';
		header("Location: $profile");
		echo "Upload complete!";
		
		} else {
			echo "File error!";
		}
		 }
		
	} elseif ( $_GET['p'] == 'password' ) {
		$oldpass = md5($_POST['oldpass']);
		$newpass1 = md5($_POST['newpass1']);
		$newpass2 = md5($_POST['newpass2']);
		
		//Verify new password
		if ( $newpass1 != $newpass2 ) {
			$settingsURL = $communitypi->getUserSettingsURL();
			//echo "$settingsURL&message=Passwords+do+not+match&messagemood=sad";
			$message = "Passwords do not match";
			$_SESSION['p_message'] = $message;
			$_SESSION['p_mood'] = 'sad';
			header("Location: $settingsURL");
			exit();
		}
		
		//Validate old password
		$communitypi->mysqlConnect();
		//echo $uid;
		$query = mysql_query("SELECT * FROM `users_db` WHERE `id` = '$uid'");
		
		while($row = mysql_fetch_array($query)) {
			if ( $row['password'] != $oldpass ) {
				$settingsURL = $communitypi->getUserSettingsURL();
				$communitypi->mysqlClose();
				$message = "Current password incorrect";
				$_SESSION['p_message'] = $message;
				$_SESSION['p_mood'] = 'sad';
				header("Location: $settingsURL");
				exit();
			}
		}
		
		$communitypi->mysqlClose();
		
		//Set new password
		$communitypi->changePassword($uid, $newpass1);
		$profile = $communitypi->getUserProfileURL($uid);
		$message = "Password updated";
		$_SESSION['p_message'] = $message;
		$_SESSION['p_mood'] = 'happy';
		header("Location: home");
		header("Location: $profile");
		
	} elseif ($_GET['p'] == 'notification') {
		//get information from form
		$emailOnFriendRequest = $_POST['emailOnFriendRequest'];
		$emailOnComment = $_POST['emailOnComment'];
		$emailOnGroupMessage = $_POST['emailOnGroupMessage'];
		$emailOnMessage = $_POST['emailOnMessage'];
		
		if ( $emailOnFriendRequest != "1" ) $emailOnFriendRequest = "0";
		if ( $emailOnComment != "1" ) $emailOnComment = "0";
		if ( $emailOnGroupMessage != "1" ) $emailOnGroupMessage = "0";
		if ( $emailOnMessage != "1" ) $emailOnMessage = "0";
		
		$communitypi->setProfileInfo($uid, 'emailOnFriendRequest', $emailOnFriendRequest);
		$communitypi->setProfileInfo($uid, 'emailOnComment', $emailOnComment);
		$communitypi->setProfileInfo($uid, 'emailOnGroupMessage', $emailOnGroupMessage);
		$communitypi->setProfileInfo($uid, 'emailOnMessage', $emailOnMessage);
		
		
		$settingsURL = $communitypi->getUserSettingsURL();
		$message = "Notification Settings Updated";
		$_SESSION['p_message'] = $message;
		$_SESSION['p_mood'] = 'happy';
		header("Location: $settingsURL");
	}
	
	
} else {
	header("Location: $communitypi->getSetting('baseURL')");
}
	
?>