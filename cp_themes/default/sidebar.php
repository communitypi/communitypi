<div id="sidebar">
	<div id="sidebar_avatar_container"><img src="<?php echo $communitypi->getProfileImage($_SESSION['uid'], 200); ?>" width="190" height="190" /></div>
	<h2><?php echo $communitypi->getProfileName($_SESSION['uid']); ?></h2>
	<ul>
		<li>
			<a href="<?php echo $communitypi->getUserSettingsURL();?>">Edit Profile</a>
		</li>
		<li>
			<a href="<?php echo $communitypi->getUserProfileURL($_SESSION['uid']); ?>">View Profile</a>
		</li>
		<li>
			<a href="<?php echo $communitypi->getSetting('baseurl'); ?>photos">Photos</a>
		</li>
	</ul>
	
	
	<h2>Places</h2>
	<ul>
		<li>
			<a href="<?php echo $communitypi->getSetting('baseurl'); ?>home">Home</a>
		</li>
		<li>
			<a href="<?php echo $communitypi->getSetting('baseurl'); ?>friends">Friends</a>
		</li>
		<li>
			<a href="<?php echo $communitypi->getSetting('baseurl'); ?>groups">Groups</a>
		</li>
		<li>
			<a href="<?php echo $communitypi->getSetting('baseurl'); ?>messages">Messages</a> (<?php echo $communitypi->getNumberUnreadMessages(); ?>)
		</li>
		<li>
			<a href="<?php echo $communitypi->getLogoutURL(); ?>">Logout</a>
		</li>
		
	</ul>
	
	<?php if($communitypi->isRoot($ses) || $communitypi->isAdmin($ses)) { ?>
	<h2>Administration</h2>
	<ul>
		<li>
			<a href="<?php echo $communitypi->getSetting('baseurl'); ?>cp_admin/">Site Summary</a>
		</li>
		<li>
			<a href="<?php echo $communitypi->getSetting('baseurl'); ?>cp_admin/users/manage">Manage Users</a>
		</li>
	<?php if($communitypi->isRoot($ses)) { ?>
				<ul>
		<li>
			<a href="<?php echo $communitypi->getSetting('baseurl'); ?>cp_admin/settings">Site Settings</a>
		</li>
		</ul>
					<?php } ?>
			<?php } ?>
	<h2>Online Friends</h2>
	<?php $communitypi->getOnlineFriends(); ?>
</div>