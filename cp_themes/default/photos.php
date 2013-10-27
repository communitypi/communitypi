<div id="main_column">
	<div class="photos_title">
		<?php
		$name = $_GET['name'];
		
		if ( $name ) {
			$photouid = $communitypi->getProfileId($name);
			echo "<h2>".$communitypi->getProfileName($photouid)."'s Photos</h2>";
		} else {
			$photouid = $_SESSION['uid'];
			echo "<h2>Your Photos</h2>";
		}
		
		?>
	</div>
	
	<div class="uploadPhotosLink"><a href="<?php echo $communitypi->getUploadPhotoURL() ?>">Upload Photos</a></div>
	
	<div class="clear"></div>
	<div class="photo_container">
	<?php
		if (!$_GET['page']) {
			$page = 0;
			$nextpage = 2;
		} else {
			$page = ($_GET['page'] - 1)*16;
			$nextpage = $_GET['page'] + 1;
			$previouspage = $_GET['page'] - 1;
		}
		$communitypi->showPhotos($photouid, $page.", 16", 200, true);
		echo "<div class=\"clear\"></div>";
		if ( $page != 0 ) {
			echo "<div class=\"prev_page\"><a href=\"&page=".$previouspage."\">Previous</a></div>";
		}
		$communitypi->mysqlConnect();
		$count = $page.", 17";
		$result = mysql_query("SELECT * FROM `photos_db` WHERE `owner`='$photouid' LIMIT $count");
        	if (mysql_num_rows($result) == 17) {
        		echo "<div class=\"next_page\"><a href=\"".$communitypi->getSetting('baseURL')."photos/&page=".$nextpage."\">Next</a></div>";
        	}
        $communitypi->mysqlClose();
		echo "<div class=\"clear\"></div>";
	?>
	</div>
</div>