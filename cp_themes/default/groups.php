<div id="main_column">
	<div class="groups_title"><h2>Groups</h2></div>
	<div class="create_group_button">Create Group</div>
	<div class="clear"></div>
	<span class="create_group_form_container">
		<div class="close"><img src="<?php echo $communitypi->getSetting('baseURL'); ?>cp_images/fancy_close.png" /></div>
		<h2>Create Group</h2>
		<?php $communitypi->getForm("create_group_form"); ?>
	</span>
	
		<form action="<?php $communitypi->getSetting('baseurl') ?>search" method="post">
			Find new groups: <input type="text" name="searchterms" />
			<input type="hidden" name="search_what" value="groups" />
			<input type="submit" value="Search" />
		</form>
		<br>
		
		
	<?php
		$communitypi->listGroups();
	?>
</div>