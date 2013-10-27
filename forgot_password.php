<?php
//Main index file
include("communitypi.class.php");
$communitypi = new communitypi;

//include header
include("cp_themes/".$communitypi->getSetting("theme")."/header.php");
?> 

<?php 

if ($_SESSION['forgot_msg'] == 'email_sent') {
	$_SESSION['forgot_msg'] = '';
	echo "<h1>Email Sent!</h1>
		  <p>We have dispatched an email to the address that you provided. It should be with you within the next few minutes. If you do not see an email, please check your Junk/Spam folder. If you still do not see it, please contact a site administrator or start the process again.</p>";
} else {
if ((3 - $_SESSION['password_reset_tries']) != 0) {
	?>
<h1>Forgot Your Password?</h1>
<p>If you have forgotten your username and/or password, you need to use this form to recover your account details and regain control  over your account.</p>

<p>Please provide the email address that you had associated with your account. This is usualy the email address you signed up with (check your accounts).</p>

<p>You have <?php echo (3 - $_SESSION['password_reset_tries']); ?> tries left.</p>

<?php 
if ($_SESSION['forgot_error']) {
	echo '<p>' . $_SESSION['forgot_error'] . '</p>';
	$_SESSION['forgot_error'] = '';
}
?>



<?php
	} else {
?>
	<h2>You have used all your tries, please try again in a few hours.</h2>
	
<?php
	}
}
?>


<?php
//include footer
include("cp_themes/".$communitypi->getSetting("theme")."/footer.php");
?>