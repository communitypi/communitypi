<?php
include("../../communitypi.class.php");
$communitypi = new communitypi;

if($communitypi->userIsLoggedIn()) {

	//$uid = $_SESSION['uid'];
	
	//This page takes the url parameter 'p'
	//p, standing for page, can either be 'default_avatar' (more coming soon)

	$page = $_GET['p'];
	$referer = $_SERVER['HTTP_REFERER'];

	if ( $page == 'default_avatar' ) {
		foreach ($_FILES as $file) { 
			if ( $file["type"] == "image/gif" || $file["type"] == "image/jpeg" || $file["type"] == "image/pjpeg" || $file["type"] == "image/png" && $file["size"] < $communitypi->getSetting('maxuploadfilesize')) {
				$extension = substr($file['name'], -3);
				$tempfile = md5(time() . $file['name']);
				$newfile = '../../cp_content/tmp/' . $tempfile . '.' . $extension;
				$move = move_uploaded_file($file['tmp_name'], $newfile);
				if (!$move) {
					echo "File error!";
					exit();
				}
				
				
				//lets gather some infomation about this image
				$filesize = filesize($newfile);
				$communitypi->createThumb($newfile, "../../cp_content/profileimages/" . md5('0') . '_' . "600.png", 600);
				$communitypi->createThumb($newfile, "../../cp_content/profileimages/" . md5('0') . '_' . "200.png", 200);
				$communitypi->createThumb($newfile, "../../cp_content/profileimages/" . md5('0') . '_' . "100.png", 100);
				$communitypi->createThumb($newfile, "../../cp_content/profileimages/" . md5('0') . '_' . "60.png", 60);
				$settings = $communitypi->getSetting('baseURL') . "cp_admin/settings";
				$_SESSION['p_mood'] = 'happy';
				$_SESSION['p_message'] = 'Default avatar updated!';
				header("Location: $referer");
				echo "Upload complete!";
			
			} else {
				echo "File error!";
			}
		}	
		
	} elseif ( $page == 'general' ) {
		if (isset($_POST['site_settings_submit'])) {
			
			//get all info from form
			$theme = $_POST['theme'];
			$baseurl = $_POST['baseurl'];
			$site_name = $_POST['site_name'];
			$site_desc = $_POST['site_desc'];
			$mailfrom = $_POST['mailfrom'];
			$path = $_POST['path'];
			$profile_quest1 = $_POST['profile_quest1'];
			$profile_quest2 = $_POST['profile_quest2'];
			$profile_quest3 = $_POST['profile_quest3'];
			$profile_quest4 = $_POST['profile_quest4'];
			$bio_char_limit = $_POST['bio_char_limit'];
			$minage = $_POST['minage'];
			$maxuploadfilesize = (int)$_POST['maxuploadfilesize'];
			

			if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $mailfrom)) {
				//continue
			} else {
				//Not Valid Email
				
				$message = "Email invalid";
				$_SESSION['p_message'] = $message;
				$_SESSION['p_mood'] = 'sad';
				header("Location: $referer");
				exit();
			}
			
			$communitypi->setSetting("theme", $theme);
			$communitypi->setSetting("baseurl", $baseurl);
			$communitypi->setSetting("site_name", $site_name);
			$communitypi->setSetting("site_desc", $site_desc);
			$communitypi->setSetting("mailfrom", $mailfrom);
			$communitypi->setSetting("path", $path);
			$communitypi->setSetting("profile_quest1", $profile_quest1);
			$communitypi->setSetting("profile_quest2", $profile_quest2);
			$communitypi->setSetting("profile_quest3", $profile_quest3);
			$communitypi->setSetting("profile_quest4", $profile_quest4);
			$communitypi->setSetting("bio_char_limit", $bio_char_limit);
			$communitypi->setSetting("minage", $minage);
			$communitypi->setSetting("maxuploadfilesize", $maxuploadfilesize);

			
			
			
			//finally take back to settings page with message
				$message = "Site settings updated.";
				$_SESSION['p_message'] = $message;
				$_SESSION['p_mood'] = 'happy';
				header("Location: $referer");
		
		}
	}
}