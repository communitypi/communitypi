<?php 
//if ($_GET['status'] == '1') {
	//echo "<div class=\"message\" style=\"width: 510px; background: #A8EC55; padding: 4px; margin: 4px;\">Status Updated!</div>";
//} 
?>
<?php 
//if ($_GET['status'] == '2') {
	//echo "<div class=\"message\" style=\"width: 510px; background: #FF1119; padding: 4px; margin: 4px;\">Too Many Letters!</div>";
//} 

$status_message = "";
if ($_GET['status_message']!="") $status_message = $_GET['status_message'];

?>
<div id="status_update_charRem">140</div>
<FORM id="status_update" name="status_update" action="call.php?f=update_status" method="post" ><p>
	<textarea id="text" name="text" cols="60" rows="2" onkeydown="charRem(this, 'status_update_charRem', 140);" onkeyup="charRem(this, 'status_update_charRem', 140);"><?php echo $status_message; ?></textarea><br />
	<span type="text" id="shortenedUrl"></span>
	<input type="button" value="Shorten URL" class="btnShortenUrl" />
	<input type="submit" id="submit" value="Update" /></p>
</FORM>
<div id="shorten_url">
<span class="close"><img src="<?php echo $communitypi->getSetting('baseURL'); ?>cp_images/fancy_close.png" /></span>
<span class="shorten_url_inner">
<h3 style="margin-top: -30px;">Type url, hit enter</h3>

<?php $communitypi->getShortenURLForm(); ?>
</span>


</div>

