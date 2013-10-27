<?php
	//This file sends a message, replies to a message or deletes a message
	
	$action = $_POST['action'];
	if ($action == null) {
		$action = $_GET['action'];
	}
	
	if ( $action == "send" ) {
	
		$to = $communitypi->getUidFromUsername($_POST['compose_message_to']);
		$subject = $_POST['compose_message_subject'];
		$message = $_POST['compose_message_message'];
		$from = $_SESSION['uid'];
		$replyto = $_POST['replyto'];
		
		//make sure the to field is not blank
		if ($to == "")
		{
			$message = "Please enter a recipient";
			$_SESSION['p_message'] = $message;
			$_SESSION['p_mood'] = 'sad';
			$location = $communitypi->getSetting('baseurl')."message_compose/";
			header("Location: $location");
			exit();
		}
		
		//make sure that the person they're sending the message to is one of their friends
		if ($communitypi->isFriend($from, $to) == false)
		{
			$message = "You may only send messages to friends";
			$_SESSION['p_message'] = $message;
			$_SESSION['p_mood'] = 'sad';
			$location = $communitypi->getSetting('baseurl')."message_compose/";
			header("LOCATION: $location");
			exit();
		}
		
		//make sure the message is not empty
		if ($message == "")
		{
			$message = "Message is empty";
			$_SESSION['p_message'] = $message;
			$_SESSION['p_mood'] = 'sad';
			$location = $communitypi->getSetting('baseurl')."message_compose/";
			header("LOCATION: $location");
			exit();
		}
		
		//if all is well, send the message using the function from the class and take the user back to the message page and give them a confirmation
		$communitypi->sendMessage($from, $to, htmlentities($subject), htmlentities($message), $replyto);
		$message = "Message Sent";
		$_SESSION['p_message'] = $message;
		$_SESSION['p_mood'] = 'happy';
		$location = $communitypi->getSetting('baseurl')."messages/";
		header("LOCATION: $location");
		exit();
	}
	elseif ( $action == "delete" )
	{
		$id = $_POST['messageId'];
		$communitypi->deleteMessage($id);
		$message = "Message Deleted";
		$_SESSION['p_message'] = $message;
		$_SESSION['p_mood'] = 'neutral';
		$location = $communitypi->getSetting('baseurl')."messages/";
		header("LOCATION: $location");
		exit();
	}
	
	elseif ( $action == "read" )
	{
		$id = $_GET['messageId'];
		$communitypi->markMessageRead($id);
	}

?>
