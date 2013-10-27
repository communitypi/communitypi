//<!--RESET_PASSWORD FORM-->
//<FORM id="reset_password_form" action="<?php $communitypi->getSetting('baseURL'); ?>call.php?f=reset_password" method="post">
//	<p>
//		<?php
//			if ($_GET['error'] == '1') {
//				echo '<div id="login_error">User does not exist!</div>';
//			}
//		?>
//		<div id="reset_password_form_elements_container"><label for="email">Email Address</label>
//		<input type="text" name="email" id="email" />
//		<input type="submit" id="submit" value="Reset" /></div>
//	</p>
//</FORM>