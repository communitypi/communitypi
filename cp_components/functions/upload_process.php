<?php
set_time_limit(0);

	if($this->userIsLoggedIn()) {
		$uploadedPhotos = array();
		foreach ($_FILES as $file) {
			if ( $file["type"] == "image/gif" || $file["type"] == "image/jpeg" || $file["type"] == "image/pjpeg" || $file["type"] == "image/png" && $file["size"] < $this->getSetting('maxuploadfilesize')) {
				//hopefully by now, the blankfields have gone!
		
				//move this file to our proccessing location
				$extension = substr($file['name'], -3);
				$tempfile = md5(time() . $file['name']);
				$newfile = 'cp_content/tmp/' . $tempfile . '.' . $extension;
				$move = move_uploaded_file($file['tmp_name'], $newfile);
				if (!$move) {
					//header("Location: " . );
					echo "File error!";
					exit();
				}
		 
				//lets gather some infomation about this image
				$filesize = filesize($newfile);
			
				//generate a batch id
				$batchid = md5($_SESSION['username'] . time());
			
				//make folder for this batch
				if (!file_exists("cp_content/photos/$batchid")) {
					mkdir("cp_content/photos/$batchid");
				}
			
				//time for thumbnails methinks
				createThumbnail($newfile, "cp_content/photos/" . $batchid . "/" . $tempfile . "600.png", 600);
				createThumbnail($newfile, "cp_content/photos/" . $batchid . "/" . $tempfile . "200.png", 200);
				createThumbnail($newfile, "cp_content/photos/" . $batchid . "/" . $tempfile . "100.png", 100);
				createThumb($newfile, "cp_content/photos/" . $batchid . "/" . $tempfile . "600s.png", 600);
				createThumb($newfile, "cp_content/photos/" . $batchid . "/" . $tempfile . "200s.png", 200);
				createThumb($newfile, "cp_content/photos/" . $batchid . "/" . $tempfile . "100s.png", 100);
				rename($newfile, "cp_content/photos/" . $batchid . "/" . $tempfile . "orig.png");
			
				list($imgwidth, $imgheight) = getimagesize("cp_content/photos/" . $batchid . "/" . $tempfile . "orig.png"); 
				//and finaly add it to the database
				$this->mysqlConnect();
				$filename = $batchid . "/" . $tempfile;
				$owner = $_SESSION['uid'];
				$time = time();
				mysql_query("INSERT INTO `photos_db` (`id`, `filename`, `batch`, `width`, `height`, `owner`, `time`, `size`, `title`, `desc`) VALUES (NULL, '$tempfile', '$batchid', '$imgwidth', '$imgheight', '$owner', '$time', '$filesize', '0', '0');");
			
				//get the id of this photo in the database
				$result = mysql_query("SELECT * FROM `photos_db` WHERE `filename`= '$tempfile';");
				while($row = mysql_fetch_array($result)) {
			  		$newid = $row['id'];
			  	
			 	}
				$this->mysqlClose();
				//echo '<img src="' . $this->getSetting('baseurl') . 'cp_content/photos/' . $batchid . '/' . $tempfile . '200.png" />';
			//header("Location: ".$this->getSetting('baseurl')."photos");
				$uploadedPhotos[] = $newid;
		
		
			} else {
				if ($file['name'] != '') {
					echo "oi! only images!";
				}
			}
		}
	} else {
		header("Location: " . $this->getSetting('baseurl'));
	}
		
	
	//funtion to make life more pleasent
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
?>