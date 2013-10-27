<?php
	//this file loads users sent messsages
	//pass url parameter count (no. of messages to get)
	
	if (is_null($communitypi)) {
		include("../../communitypi.class.php");
		$communitypi = new communitypi;
	}


	$uid = $_SESSION['uid'];
	$count = $_GET['count'];
	if(!$count) {
			$count = 20; 
		}
	$communitypi->mysqlConnect();
	$result = mysql_query("SELECT * FROM `messages_db` WHERE `from`='$uid' ORDER BY `id` DESC LIMIT $count");
	while($row = mysql_fetch_array($result)) {
		echo '<div class="message_container">';
			echo '<div class="image">';
				echo '<img src="' . $communitypi->getProfileImage($row['to'], 100) . '" />';
			echo '</div>';
			echo '<div class="text">';
				echo '<div class="from">';
					echo $communitypi->getProfileInfo($row['to'], 'name');
				echo '</div>';
				echo '<div class="date">';
					echo date("d F Y", $row['time']);
				echo '</div>';
				echo '<div class="subject">';
					echo $row['subject'];
				echo '</div>';
				echo '<div class="body">';
					echo substr($row['message'], 0, 180);
					if ( strlen($row['message']) > 180 ) {
						echo "...";
					}
				echo '</div>';
				echo '<div class="body_full">';
					echo nl2br($row['message']);
					echo '<div class="clear"></div>';
				echo '</div>';
			echo '</div>';	
			echo '<div class="options">';
				echo '<form id="reply" action="'.$communitypi->getSetting('baseurl').'message_compose/" method="post"><input type="hidden" id="action" name="action" value="reply"><input id="messageId" name="messageId" type="hidden" value="'.$row['id'].'"><input type="submit" value="Reply"></form>';
				echo '<form id="delete" action="'.$communitypi->getSetting('baseurl')."call.php?f=message_functions".'" method="post"><input type="hidden" id="action" name="action" value="delete"><input id="messageID" name="messageId" type="hidden" value="'.$row['id'].'"><input type="submit" value="Delete"></form>';
				
		echo '</div><div class="clear"></div></div>';
		
		
	}
	
	if ( mysql_num_rows($result) > $count )
	{
		
	}
	
	$communitypi->mysqlClose();

?>
