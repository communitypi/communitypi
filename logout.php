<?php
session_start();
//Logout.php
	$_SESSION['username'] = false;
	$_SESSION['email'] = false;
	$_SESSION['loggedin'] = false;
	$_SESSION['uid'] = false;
	header("Location: index.php");
	?>