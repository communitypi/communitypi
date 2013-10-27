<?php
//Check Username
$username = $_GET['username'];
if ($communitypi->isUsernameAvailable($username)) {
	echo '1';
} else {
	echo '0';

}
?>