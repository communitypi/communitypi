<div id="content_nosidebar">
	<div id="description">
		<p><?php echo $communitypi->getSetting('site_desc'); ?></p>
	</div>
	<div id="login_register">
		<div id="login_register_row">
			<div id="login">
				<h3>Login</h3>
				<div align="center"><?php $communitypi->getLoginForm(); ?></div>
			</div>
			<div id="register">
				<h3>Register</h3>
				<?php $communitypi->getRegisterForm(); ?>
			</div>
		</div>
	</div>
</div>