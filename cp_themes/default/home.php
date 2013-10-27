<div id="main_column">
<div class="dashboard_title"><h2>Your Dashboard</h2></div><br>
	<?php
	//Welcome Notice
	if ($communitypi->getProfileSetting($uid, 'welcome') == '0') {
		$name = explode(" ", $communitypi->getProfileName($uid));
		$hello = $communitypi->randomHello();
		if ($hello == "Greetings") {
			$name[0] = "Wise one";
		}
		echo '<div id="welcome_msg">';
		
		echo '<span>' . $hello . ', ' . $name[0] . '. It seems that you are new to ' . $communitypi->getSetting('site_name') . ', Please take a few moments to setup your account and profile 
		<a href="' . $communitypi->getSetting('baseurl') . 'settings/profile_settings">Setup Now...</a> 
		</span> </div> <div class="clear"></div>';
	}
		?>
		
		
		<?php $communitypi->friendRequestGallery(); ?>
		<div class="clear"></div>
		
		

<?php $communitypi->groupInvitesGallery(); ?>

<?php $communitypi->eventsGallery(); ?>

		<?php $communitypi->mightKnowGallery(); ?>
		<div class="clear"></div>


	<div id="status_update_title"><h2>Status Update</h2></div>
	<?php
		include("cp_components/forms/status_update_form.php");
		//include("cp_components/functions/timeline.php");
		echo '<div id="timeline">';
		$communitypi->getTimeline($_SESSION['uid'], 20, true);
		echo '</div>';
	?>
	
	<?php if ( $communitypi->timelineUpdatesAvailable($_SESSION['uid'], true) ) { ?>
	<div id="more_timeline">
	<h4>Show more...</h4>
	<span class="desc">Display 20 more status updates from your friends</span>
	</div>
	<? } ?>

	<div id="comment_reply">
	<div class="close"><img src="<?php echo $communitypi->getSetting('baseURL'); ?>cp_images/fancy_close.png" /></div>
	<div class="inner"></div>
	</div>
	
	<div id="miniprofile">
	<div class="close"><img src="<?php echo $communitypi->getSetting('baseURL'); ?>cp_images/ajax-loader-2.gif" /></div>
	<div class="inner"></div>
	</div>
	
</div>