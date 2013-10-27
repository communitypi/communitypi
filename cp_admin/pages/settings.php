<div id="main_column">
<?php 

if($communitypi->userIsLoggedIn()) {
	if($communitypi->isRoot($ses)) { 
		echo "<h2>Site Settings</h2>";
		$communitypi->mysqlConnect();
		$query = mysql_query("SELECT * FROM  `settings_db`;");
		echo '<form action="' . $communitypi->getSetting('baseURL') . 'cp_admin/functions/update_site_settings.php?p=general" method="post">
			  <table>';
			while ($row = mysql_fetch_array($query)) {
				if ($row['name'] == 'theme') {
					echo '<tr><td>Theme: </td><td>';
					echo '<select name="theme">';
					$d = dir("../cp_themes");	
						while($entry=$d->read()) {
							if ($entry == '.' || $entry == '..' || $entry == '.htacess') {
							} else {
								$themedata = parse_ini_file("../cp_themes/" . $entry . "/themeinfo.ini");
								if ($row['value'] == $entry) { 
									echo '<option value="' . $entry .'" selected="selected">' . $themedata['name'] . '</option>';
								} else {
									echo '<option value="' . $entry .'">' . $themedata['name'] . '</option>';
								}
							}
						}
					echo "</select></td></tr>";
					$d->close();
				} else {
					
					if ($row['name'] == 'minage') {
					echo '<tr><td>Minimum Age: </td><td>';
					echo '<select name="minage">';
						for ($i = 0; $i <= 18; $i++) {
							if ($row['value'] == $i) {
								echo '<option value="' . $i . '" selected="selected">' . $i . '</option>';
							} else {
						    echo '<option value="' . $i . '">' . $i . '</option>';
							}
						}
					echo "</select> years
					</td></tr>";
					} else {
						
						if ($row['name'] == 'maxuploadfilesize') {
								echo '<tr><td>Max upload size: </td><td>';
								echo '<select name="maxuploadfilesize">';
							for ($i = 1; $i <= (ini_get('upload_max_filesize')); $i++) {
								if ($row['value'] == (($i * 1024) * 1024)) {
									echo '<option value="' . $i . '" selected="selected">' . $i . '</option>';
								} else {
						    		echo '<option value="' . (($i * 1024) * 1024) . '">' . $i . '</option>';
								}
							}
							echo "</select> megabytes";
							echo "</td>";
							echo "</tr>";
						} else {

				echo '<tr>';
				echo '<td>';
				echo $row['comment'];
				echo ': </td>';
				echo '<td>';
				echo '<input size="50" type="text" value="';
				echo $row['value'];
				echo '" name="';
				echo $row['name'];
				echo '" />';
				echo '</td>';
				echo '</tr>';
						}
					}
				}
				
			}
			
		echo '<tr><td></td><td><input type="submit" name="site_settings_submit" value="Save Settings..." /></td></tr>';
		echo "</table></form>";
		
		?>
		<h2>Default Avatar</h2>
		Photos are limited to <?php echo round((int)$communitypi->getSetting('maxuploadfilesize')/1000000); ?>MB
		<form id="photo_upload_form" action="<?php echo $communitypi->getSetting('baseURL'); ?>cp_admin/functions/update_site_settings.php?p=default_avatar" method="post" enctype="multipart/form-data" onsubmit="return validateUploadForm(this);">
		<div id="uploadFormError" ><?php echo $_GET['error']; ?></div>
		<div id="buttonContainer1" class="buttonContainer1"><input id="photo1" type="file" name="photo1"></div>
		<input type="hidden" id="photoUploadId" value="2">
		<br>
		
		<input id="photoUploadSubmit" type="submit" value="Upload">
		<div id="message"><img src="<?php echo $communitypi->getSetting('baseURL'); ?>cp_images/loader_icon.gif" />Uploading...</div>
		</form>

	<?php
	
	} else {
	echo "<h1>Unauthorised!</h1>";
}
}
?>
</div>