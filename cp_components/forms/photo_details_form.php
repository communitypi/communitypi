<form id="photo_details_form" action="<?php echo $this->getSetting('baseURL'); ?>call.php?f=save_photo_details" method="post">
	<input type="submit" value="Save" id="photo_details_save">
	<div class="clear"></div>
	<?php
		//include("call.php?f=upload_process");
		include("cp_components/functions/upload_process.php");
		//include("cp_components/forms/status_update_form.php");
		$photoDetailsLoop = 0;
		foreach ( $uploadedPhotos as $id ) {
			
			$this->mysqlConnect();

			$result = mysql_query("SELECT * FROM `photos_db` WHERE `id`='$id' LIMIT 1");
			while($row = mysql_fetch_array($result)) {
			  	$batchid = $row['batch'];
			  	$filename = $row['filename'];
			 }

				
			//$batchid = mysql_query("SELECT `batch` FROM `photos_db` WHERE `id` = '$id';");
			//$filename = mysql_query("SELECT `filename` FROM `photos_db` WHERE `id` = '$id';");
			
	 		$photo = $this->getSetting('baseURL') . "cp_content/photos/" . $batchid . "/" .  $filename . "200s.png";
	 		echo "<div class=\"photo_container\">";
	 		echo "<input type=\"hidden\" name=\"photo".$photoDetailsLoop."\" value=\"".$id."\">";
	 		//echo "<div class=\"image_name\">".$_FILES[$photoDetailsLoop]."</div>";
	 		echo "<div class=\"image\"><img src=\"" . $photo . "\" /></div>";
	 		echo "<div class=\"row\">Title:</div><div class=\"row\"><input type=\"text\" name=\"photo".$photoDetailsLoop."_title\"></div>";
	 		echo "<div class=\"row\">Description:</div><div class=\"row\"><textarea name=\"photo".$photoDetailsLoop."_desc\"></textarea></div>";
	 		echo "</div>";
	 		$this->mysqlClose();
	 		$photoDetailsLoop = $photoDetailsLoop + 1;
		}
		unset($value);
	?>
	
	
</form>