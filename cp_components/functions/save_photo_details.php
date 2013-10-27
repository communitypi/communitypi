<?php
	//post info to this file
	//photo0, photo1, etc. have photo IDs in DB, then if isset, get the following data.
	//photo0_title, photo1_title, etc. photo0_desc, photo1_desc, etc
	if($communitypi->userIsLoggedIn()) {
		$saveDetailsLoop = 0;
		while ( $_POST['photo' . $saveDetailsLoop] ) {
			//collect info about photo
			$id = $_POST['photo'.$saveDetailsLoop];
			$title = htmlentities($_POST['photo'.$saveDetailsLoop.'_title']);
			$desc = htmlentities($_POST['photo'.$saveDetailsLoop.'_desc']);
			
			//get rid of blank title (blank desc is fine)
			if ( $title == "" ) {
				$title = "Untitled";
			}
			
			//connect to database
			$communitypi->mysqlConnect();
			mysql_query("UPDATE `photos_db` SET `title` = '$title', `desc` = '$desc' WHERE `id` = '$id';");
			$communitypi->mysqlClose();	
			//add one to the loop number
			$saveDetailsLoop ++;
		}
		
		//finally return user to their photos page to see their new uploads
		header("Location: " . $communitypi->getSetting('baseurl')."photos");
	} else {
		header("Location: " . $communitypi->getSetting('baseurl'));
	}
?>