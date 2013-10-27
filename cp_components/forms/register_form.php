<font color="red"><?php echo $_GET['register_error']; ?></font>
<form id="registration_form" action="<?php echo $this->getSetting('baseURL'); ?>register.php" onsubmit="return validateForm(this);" method="post">
	<p>
		<div class="registration_form_container">
			<div class="registration_form_row">
				<div class="registration_form_cell_label">Username:</div>
				<div class="registration_form_cell_input">
					<input type="text" id="register_username" name="register_username" value="" onkeyup="checkUsername(this);" onblur="checkUsername(this);" />
					<span id="register_username_error" class="field_message"></span><span id="register_username_available"></span>
				</div>
			</div><div class="registration_form_row">
				<div class="registration_form_cell_label">Name:</div>
				<div class="registration_form_cell_input"><input type="text" id="register_name" name="register_name" value="" onblur="checkName(this.value);" /><span id="register_first_name_error" class="field_message"></span></div>
			</div><div class="registration_form_row">
				<div class="registration_form_cell_label">Email:</div>
				<div class="registration_form_cell_input"><input type="text" id="register_email" name="register_email" value="" onblur="checkEmail(this.value);" /><span id="register_email_error" class="field_message"></span></div>
			</div>
			
			<div class="registration_form_row">
				<div class="registration_form_cell_label">Date of birth:</div>
				<div class="registration_form_cell_input">
				<select id="register_birth_month" name="register_birth_month">
						<option value="January">January</option>
						<option value="February">February</option>
						<option value="March">March</option>
						<option value="April">April</option>
						<option value="May">May</option>
						<option value="June">June</option>
						<option value="July">July</option>
						<option value="August">August</option>
						<option value="September">September</option>
						<option value="October">October</option>
						<option value="November">November</option>
						<option value="December">December</option>
					</select>	
					<select id="register_birth_day" name="register_birth_day">
					
						<?php 
							for ( $i = 1; $i < 32; $i += 1) {
								echo "<option value=\"" . $i . "\">" . $i . "</option>";
							} 
						?>
					</select>
					<select id="register_birth_year" name="register_birth_year">
      						<?php
								// Number of years to go back
								$yearRange = 100;
								// Generate Options
								$thisYear = date('Y');
								$startYear = ($thisYear - $yearRange);
								foreach (range($thisYear, $startYear) as $year) {
									print "<option value=\"" . $year . "\">" . $year . "</option>";
								}
							?>
    				</select>
    				</div>
			</div>
			
			
			<div class="registration_form_row">
				<div class="registration_form_cell_label">Password:</div>
				<div class="registration_form_cell_input"><input type="password" id="register_password" name="register_password" value="" onkeyup="checkPassword(this.value);" onblur="checkPassword(this.value);" /><span id="register_password_error" class="field_message"></span><span id="register_password_strength"></span></div>
			</div><div class="registration_form_row">
				<div class="registration_form_cell_label">Confirm Password:</div>
				<div class="registration_form_cell_input"><input type="password" id="register_password_confirm" name="register_password_confirm" value="" onblur="checkPasswordConfirm(this.value);" /><span id="register_password_confirm_error" class="field_message"></span></div>
				</div><div class="registration_form_row">
				<div class="registration_form_cell_label">Spam Buster:</div>
				<div class="registration_form_cell_input"><input type="text" id="captcha" name="captcha" /><br /><iframe src="<?php echo $this->getSetting("baseurl"); ?>captcha.jpg" name="captchaimg" width="150" height="50" frameborder="no"></iframe>
				</div>

			</div>
		</div>
		<div class="registration_form_cell_submit"><input type="submit" id="register_submit" name="register_submit" value="Register" /></div>
	</p>
</form>

<script language="javascript" type="text/javascript">
	
	
	function validateForm(form) {
		if(checkUsername(form.register_username)==false){return false;}
		if(checkName(form.register_name.value)==false){return false;}
		if(checkEmail(form.register_email.value)==false){return false;}
		if(checkPassword(form.register_password.value)==false){return false;}
		if(checkPasswordConfirm(form.register_password_confirm.value)==false){return false;}
	}
	
	function checkUsername(field) {
		var string = field.value
		var error = "";
	
		var illegalChars = /\W/;
		if (illegalChars.test(string)) { error = "Numbers, letters and underscores only"; }
		if (!string.charAt(0).match(/^[a-zA-Z]$/)) { error = "Username must start with a letter"; }
		if ((string.length < 4) || (string.length > 20)) { error = "Must be between 4 and 20 characters"; }
		if (string == "") { error = "Please enter a username";}
		if (error!=""){document.getElementById('register_username_error').innerHTML = error;}else{checkUsernameAvailability(string);}
		
		if (error!=""){return false;}else{return true;}
	}
	
	function checkUsernameAvailability(string) {
		//if (document.getElementById('register_username_error').innerHTML==""){
			$.get("<?php $base = $this->getSetting("baseurl"); echo $base;?>call.php?f=checkusername&username=" + string, function(data){
				if (data==1){document.getElementById('register_username_error').innerHTML='<span id="register_username_available">Available</span>';}else{document.getElementById('register_username_error').innerHTML='<span id="register_username_notavailable">Not available</span>';}
			});
			
		//}else{document.getElementById('register_username_available').innerHTML="";}
	}
	
	function checkName(string) {
		var error = "";
		if (string == "") { error = "Please enter your name";}
		document.getElementById('register_first_name_error').innerHTML = error;
		if (error!=""){return false;}else{return true;}
	}
	
	function checkEmail(string) {
		var error = "";
		$.get("<?php echo $this->getSetting("baseurl");?>call.php?f=checkemail&email=" + string, function(data){
				if (data==0){document.getElementById('register_email_error').innerHTML = "User with this email already exists";}
			});
		var register_emailFilter = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/;
		if (!(register_emailFilter.test(string))) { error = "Enter a valid email address";}
		var illegalChars= /[\(\)\<\>\,\;\:\\\/\"\[\]]/
		if (string.match(illegalChars)) { error = "Enter a valid email address";}
		document.getElementById('register_email_error').innerHTML = error;
		if (error!=""){return false;}else{return true;}
	}
	
	function checkPassword(string) {
		var error = "";
		if (string.length < 6) { error = "Too short";}
		document.getElementById('register_password_error').innerHTML = error;
		if (error==""){
			checkPasswordStrength(string);
		}
		if (error!=""){return false;}else{return true;}
	}
	
	function checkPasswordStrength(password) {
		var desc = new Array();
        desc[0] = "Very Weak";
        desc[1] = "Weak";
        desc[2] = "Medium";
        desc[3] = "Medium";
        desc[4] = "Medium";
        desc[5] = "Strong";
        desc[6] = "Very Strong";
        var score   = 0;
        //if password bigger than 6 give 1 point
        if (password.length > 6) score++;
        //if password has both lower and uppercase characters give 1 point      
        if ( ( password.match(/[a-z]/) ) && ( password.match(/[A-Z]/) ) ) score++;
        //if password has at least one number give 1 point
        if (password.match(/\d+/)) score++;
        //if password has at least one special caracther give 1 point
        if ( password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/) ) score++;
        //if password has upcase, lowercase, number and symbol, give 1 point
        if ( (password.match(/[a-z]/)) && (password.match(/[A-Z]/)) && (password.match(/\d+/)) && (password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)) ) score++;
        //if password bigger than 12 give another 1 point
        if (password.length > 11) score++;
        
		document.getElementById('register_password_error').innerHTML = '<span id="register_password_score' + score + '">' + desc[score] + '</span>';
	}
	
	function checkPasswordConfirm(string) {
		var error = "";
		if (string != document.getElementById('register_password').value) {error = "The passwords do not match";}
		document.getElementById('register_password_confirm_error').innerHTML = error;
		if (error!=""){return false;}else{return true;}
	}
</script>