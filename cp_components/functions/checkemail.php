<?php
//Check Email
$email = $_GET['email'];
if ($communitypi->isEmailAvailable($email)) {
	echo '1';
} else {
	echo '0';
}
?>