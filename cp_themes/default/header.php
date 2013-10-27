<?php
$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0]; 
$starttime = $mtime;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title><?php echo $communitypi->getSetting('site_name'); ?></title>
		<?php $communitypi->head(); ?>
		<script type='text/javascript' src='<?php echo $communitypi->getThemeDir(); ?>javascript.js'></script>
		<script type='text/javascript' src='<?php echo $communitypi->getThemeDir(); ?>miniprofile.js'></script>
		
	</head>
	
	<body>
	<div id="header_wrapper">
		<div id="header">
			<div class="left">
				<h1><a href="<?php echo $communitypi->getSetting('baseURL'); ?>"><?php echo $communitypi->getSetting('site_name'); ?></a></h1>
			</div>
			<div class="right">
				<?php if($communitypi->userIsLoggedIn()) { ?>
					<span id="notifications">
						<div id="notifications_button" class="notifications_button_<?php if ($communitypi->checkNotifications()) { echo 'red'; } ?>">Notifications</div>
						<span id="notifications_container"></span>
						<span id="check_notifications"></span>
					</span>
				<?php } ?>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	
	<div id="content">