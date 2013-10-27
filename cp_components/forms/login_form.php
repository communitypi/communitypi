<script language="javascript" type="text/javascript">
function passFocus(){document.getElementById('passtemp').style.display='none';document.getElementById('pass').style.display='inline';document.getElementById('password').focus()}function passBlur(){if(document.getElementById('password').value==""){document.getElementById('passtemp').style.display='inline';document.getElementById('pass').style.display='none'}}

</script>
<FORM id="login_form" name="login_form" action="<?php echo $this->getSetting('baseURL'); ?>login.php" method="post">
	<p>
		<?php
			if ($_GET['error'] == '1') {
				echo '<div id="login_error">Incorrect Username or Password!</div>';
			}
		?>
		<div id="login_form_elements_container"><label for="username">Username</label><input type="text" name="username" id="username" value="Username" onfocus="if(this.value=='Username'){this.value='';}" onblur="if(this.value==''){this.value='Username';}" /><span id="break"></span>
	<label for="password">Password</label><span id="passtemp"><input name="pass_temp" id="pass_temp" type="text" value="Password" onfocus="passFocus()" /></span>
	<span id="pass" style="display:none;"><input name="password" id="password" type="password" value="" onBlur="passBlur()" /></span><span id="break"></span>
	<input type="submit" id="submit" value="Login" /></div></p>
</FORM>
<div id="forgot_password"><a href="forgot_password">Forgot Your Password?</a></div>
<script>
document.getElementById('username').focus();
</script>