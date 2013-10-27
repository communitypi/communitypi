<?php
//Main index file
include("communitypi.class.php");
$communitypi = new communitypi;

if ((3 - $_SESSION['password_reset_tries']) == 0) {
	header("Location: forgot_password.php");
} else {
	if ($_POST['email'] == '') {
		$_SESSION['forgot_error'] = 'No email given';
		$_SESSION['password_reset_tries']++;
		header("Location: forgot_password.php");
	} else {
		if ($_SESSION['captcha'] != $_POST['captcha']) {
			$_SESSION['forgot_error'] = 'Incorrect Captcha Code';
			header("Location: forgot_password.php");
		} else {
			//All information correct, check email
			if ($communitypi->getUidFromEmail($_POST['email'])) {
				//email is in database
				$communitypi->mysqlConnect();
				$uid = $communitypi->getUidFromEmail($_POST['email']);
				$code = md5($uid . '_' . rand() . '_' . time());
				$message = "Some one has requested that the password for your account should be reset.\nIf you requested this reset, please continue. If not, please contact the administrator.\n\n You can reset your password at " . $communitypi->getSetting('baseURL') . "email_code.php?code=" . $code;
				$communitypi->sendEmail($uid, "Password Reset", $message);
				$query = mysql_query("INSERT INTO `password_reset_db` (`id`, `uid`, `code`, `time`) VALUES (NULL, '$uid', '$code', UNIX_TIMESTAMP());");
				$_SESSION['forgot_msg'] = 'email_sent';
				header("Location: forgot_password.php");
				$communitypi->mysqlClose();
				
			} else {
				//email not in database
				$_SESSION['forgot_error'] = 'Email Address not found';
				$_SESSION['password_reset_tries']++;
				header("Location: forgot_password.php");
			}
			
			
		}
	}
}
		



?>