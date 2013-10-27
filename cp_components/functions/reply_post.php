<?php
if ($communitypi->userIsLoggedIn()) {
	$referrer = $_SERVER['HTTP_REFERER'];
	$status = htmlentities(trim($_POST['comment_text']));
	if($status == '' || $status == ' ' || substr($status, 0, 1) == ' ' || substr($status, 0, 6) == '&nbsp;' ) {
		$message = "Please enter a status first!";
		$_SESSION['p_message'] = $message;
		$_SESSION['p_mood'] = 'sad';
		header("Location: $referrer");
	} elseif (strlen($_POST['comment_text']) > 140) {
		$message = "Too many letters!";
		$_SESSION['p_message'] = $message;
		$_SESSION['p_mood'] = 'sad';
		header("Location: $referrer");
	} else {
		$status = ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\" rel=\"nofollow\" target=\"_blank\">\\0</a>", $status);
		if ($_POST['type'] == "group") {
			
			$uid = $_SESSION['uid'];
			$gid = $_POST['gid'];
			if ($communitypi->isMemberOfGroup($uid, $gid)) {
				$communitypi->updateGroupStatus($uid, $gid, $status, $_GET['id']);
			} else {
				$message = "You must be a member of this group";
				$_SESSION['p_message'] = $message;
				$_SESSION['p_mood'] = 'sad';
				header("Location: $referrer");
			}
			
			
		} else {
			$communitypi->updateStatus($_SESSION['uid'], $status, $_GET['id']);
		}
		$message = "Reply Posted!";
		$_SESSION['p_message'] = $message;
		$_SESSION['p_mood'] = 'happy';

		header("Location: $referrer");
	}
} else {
	header("Location: index.php");
}
?>
