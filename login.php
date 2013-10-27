<?php
//Login.php
include("communitypi.class.php");
$communitypi = new communitypi;
$username = $_POST['username'];
$password = md5($_POST['password']);
$communitypi->mysqlConnect();
$result = mysql_query("SELECT * FROM `users_db` WHERE `username` = '$username' AND `password` = '$password' AND `active` = 1 AND `banned` = 0");
$data = mysql_fetch_assoc($result);
$rows = mysql_num_rows($result);
if ($rows == '1') {
	$_SESSION['username'] = $data['username'];
	$_SESSION['email'] = $data['email'];
	$_SESSION['loggedin'] = "yes";
	$_SESSION['uid'] = $data['id'];
	mysql_query("UPDATE `users_db` SET `lastlogin` =  UNIX_TIMESTAMP( ) WHERE `users_db`.`username` = '$username';");
	//header("Location: index.php");
	if(isset($_SERVER['HTTP_REFERER'])) {
		header("Location: " . $_SERVER['HTTP_REFERER']);
	} else {
		header("Location: index.php");
	}
} else {
	//if(isset($_SERVER['HTTP_REFERER'])) {
		//header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=1");
	//} else {
		header("Location: index.php?error=1");
	//}
}
$communitypi->mysqlClose();
?>