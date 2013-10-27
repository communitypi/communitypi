<?php
include("communitypi.class.php");
$communitypi = new communitypi;
//register.php
$referer = $_SERVER['HTTP_REFERER'];
$username = strtolower($_POST['register_username']);
$name = $_POST['register_name'];
$email = $_POST['register_email'];
	$birthmonth = $_POST['register_birth_month'];
	$birthday = $_POST['register_birth_day'];
	$birthyear = $_POST['register_birth_year'];
//convert birthday to dd-month yyyy format and then into epoch time
$birth = strtotime($birthday . "-" . $birthmonth . " " . $birthyear);
$pass1 = md5($_POST['register_password']);
$pass2 = md5($_POST['register_password_confirm']);

if (isset($_POST['register_submit'])) {
	//Continue
	if(strlen($username) >= 4 && strlen($username) <= 20 && substr($username, 0, 1) != "_" && !is_int(substr($username, 0, 1)) && $communitypi->isUsernameAvailable($username)) {
		//ok
	} else {
		//echo "Username not valid";
		$message = "Username not valid";
		$_SESSION['p_message'] = $message;
		$_SESSION['p_mood'] = 'sad';
		header("Location: $referer");
		exit();
	}
	if($name != '') {
		//continue
	} else {
		//echo "Name is empty";
		$message = "Name is empty";
		$_SESSION['p_message'] = $message;
		$_SESSION['p_mood'] = 'sad';
		header("Location: $referer");
		exit();
	}
	
	if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email) && $communitypi->isEmailAvailable($email)) {
		//continue
	} else {
		//echo "Not Valid Email";
		$message = "Invalid email";
		$_SESSION['p_message'] = $message;
		$_SESSION['p_mood'] = 'sad';
		header("Location: $referer");
		exit();
	}
	
	//make sure user is old enough
	$today = time();
	$minage = (int)$communitypi->getSetting('minage');
	$minagesec = 60 * 60 * 24 * 365.25 * $minage;
	$maxbirthyear = $today - $minagesec;
	if ( $maxbirthyear < $birth ) {
		$message = "Too young";
		$_SESSION['p_message'] = $message;
		$_SESSION['p_mood'] = 'sad';
		header("Location: $referer");
	}
	
	
	
	if($pass1 == $pass2) {
		//continue
	} else {
		//echo "Passwords dont match";
		$message = "Passwords do not match";
		$_SESSION['p_message'] = $message;
		$_SESSION['p_mood'] = 'sad';
		header("Location: $referer");
		exit();
	}
	if ($_POST['captcha'] == $_SESSION['captcha']) {
		//continue
	} else {
		//echo "Spam Buster Incorrect!";
		$message = "Spam buster incorrect";
		$_SESSION['p_message'] = $message;
		$_SESSION['p_mood'] = 'sad';
		header("Location: $referer");
		exit();
	}
	$communitypi->mysqlConnect();
	mysql_query("INSERT INTO `users_db` (`id`, `username`, `email`, `password`, `password1`, `password2`, `active`, `banned`, `joined`, `lastlogin`) VALUES (NULL, '$username', '$email', '$pass1', '$pass2', '$pass1', '1', '0', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());");
	$id = mysql_insert_id();
mysql_query("INSERT INTO `profile_db` (`pid`, `uid`, `name`, `birth`, `bio`, `quest1`, `quest2`, `quest3`, `quest4`, `publicprof`, `userprof`, `welcome`) VALUES (NULL, '$id', '$name', $birth, 'No biography yet', '', '', '', '', '0', '1', '0');");
	$_SESSION['username'] = $username;
	$_SESSION['email'] = $email;
	$_SESSION['loggedin'] = "yes";
	$_SESSION['uid'] = $id;
$communitypi->mysqlClose();
echo mysql_error();
$message = "Registration Successful";
$_SESSION['p_message'] = $message;
$_SESSION['p_mood'] = 'happy';
header("Location: $referer");
} else {
	header("Location: index.php");
}

?>
		
	