<?php 
	$status_message = "";
	if ($_GET['status_message']!="") $status_message = $_GET['status_message'];
?>
<div id="status_update_charRem">140</div>
<FORM id="status_update" name="status_update" action="<?php echo $communitypi->getSetting('baseURL'); ?>call.php?f=update_status&type=group" method="post" ><p>
	<textarea id="text" name="text" cols="60" rows="2" onkeydown="charRem(this, 'status_update_charRem', 140);" onkeyup="charRem(this, 'status_update_charRem', 140);"><?php echo $status_message; ?></textarea><br />
	<span type="text" id="shortenedUrl"></span>
	<input type="hidden" name="type" value="group" />
	<input type="hidden" name="gid" value="<?php echo $gid; ?>" />
	<input type="submit" id="submit" value="Post Message" /></p>
</FORM>

