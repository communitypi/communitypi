<?php
//backend/admin area index page
include("communitypi.class.php");
$communitypi = new communitypi;
?>

<?php



?>

<html>
<head>

</head>
<body>
<form id="setting_general_form" action="<?php echo $this->getSetting('baseURL'); ?>cp_backend/setSettings_General.php" onsubmit="return validateForm(this);" method="post">
	<p>
		<div class="registration_form_container">
	
		</div>
		<div class="registration_form_cell_submit"><input type="submit" id="register_submit" name="register_submit" value="Register" /></div>
	</p>
</form>
</body>
</html>