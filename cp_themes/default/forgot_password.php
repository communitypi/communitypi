<div id="content_nosidebar">

<?php 

if ($_SESSION['forgot_msg'] == 'email_sent') {
	$_SESSION['forgot_msg'] = '';
	echo "<h1>Email Sent!</h1>
		  <p>We have dispatched an email to the address that you provided. It should be with you within the next few minutes. If you do not see an email, please check your Junk/Spam folder. If you still do not see it, please contact a site administrator or start the process again.</p>";
} else {
if ((3 - $_SESSION['password_reset_tries']) != 0) {
	?>
<h1>Forgot Your Password?</h1>



<p>You have <?php echo (3 - $_SESSION['password_reset_tries']); ?> tries left.</p>



<?php $communitypi->getForm("forgot_password_form"); ?>

<?php
	} else {
?>
	<h3>You have used all of your tries, please try again in a few hours.</h3>
	
<?php
	}
}
?>

</div>