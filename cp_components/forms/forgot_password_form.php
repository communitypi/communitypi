<form action="<?php echo $this->getSetting('baseURL'); ?>call.php?f=reset_password" method="post">
	<p>
		<div class="forgot_password_form_container">
			<div class="forgot_password_form_row">
				<div class="forgot_password_form_cell_label">Email Address:</div>
				<div class="forgot_password_form_cell_input"><input type="text" name="email" /></div>
			</div>
			
			<div class="forgot_password_form_row">
				<div class="forgot_password_form_cell_label">Captcha Code:</div>
				<div class="forgot_password_form_cell_input">
					<input type="text" name="captcha" /><br>
					<img src="captcha.jpg" alt="Captcha Code" />
				</div>
			</div>
			
			<div class="forgot_password_form_cell_submit"><input type="submit" id="forgot_password_submit" name="submit" value="Reset" /></div>
			
			</div>
			
		</div>
	</p>
</form>