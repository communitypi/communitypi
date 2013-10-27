<?php

if ($communitypi->userIsLoggedIn()) {
	$referrer = $_SERVER['HTTP_REFERER'];
		$status = htmlentities(trim($_POST['text']));
		if($status == '' || $status == ' ' || substr($status, 0, 1) == ' ' || substr($status, 0, 6) == '&nbsp;' ) {
			$message = "Please enter a status first!";
			$_SESSION['p_message'] = $message;
			$_SESSION['p_mood'] = 'sad';
			
			header("Location: $referrer");
		} elseif (strlen($_POST['text']) > 140) {
			$message = "Too many letters!";
			$_SESSION['p_message'] = $message;
			$_SESSION['p_mood'] = 'sad';
			header("Location: $referrer");
		} else {
			if ($_POST['type'] == "group") {
				$uid = $_SESSION['uid'];
				$gid = $_POST['gid'];
				if ($communitypi->isMemberOfGroup($uid, $gid)) {
					$communitypi->updateGroupStatus($uid, $gid, $status, '0');
				} else {
					$message = "You must be a member of this group";
					$_SESSION['p_message'] = $message;
					$_SESSION['p_mood'] = 'sad';
					header("Location: $referrer");
				}
			} else {
				$communitypi->updateStatus($_SESSION['uid'], $status, '0');
			}
			
			$message = "Status updated!";
			$_SESSION['p_message'] = $message;
			$_SESSION['p_mood'] = 'happy';
			header("Location: $referrer");
		}	
	
} else {
	header("Location: home");
}
?>

