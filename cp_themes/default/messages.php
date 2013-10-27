<div id="main_column">
	<div class="messages_title"><h2>Messages</h2></div>
	
	<div class="message_nav">
		<div class="inbox">Inbox</div>
		<div class="sent">Sent</div>
		<div class="compose_message"><a href="<?php echo $communitypi->getSetting('baseurl') ?>message_compose">New Message</a></div>
	</div>
	
	<div class="clear"></div>
	
	
	<div class="messages">
	<?php
		//lets load the messages
		//$count = $_GET['count'];
		echo '<div id="messages">';
		include "cp_components/functions/messages.php";
		echo "</div>";
	?>
	</div>
	
	<?php 
	
	$communitypi->mysqlConnect();
	$uid = $_SESSION['uid'];
	$limit = $count + 1;
	$result = mysql_query("SELECT * FROM `messages_db` WHERE `to`='$uid' AND `deleted` = '0' ORDER BY `id` DESC LIMIT $limit");

	
	if ( mysql_num_rows($result) > $count ) { ?>
		<div id="more_messages">
		<h4>Show more...</h4>
		<span class="desc">Display 20 more messages from your friends</span>
		</div>
	<? } 
	
	$communitypi->mysqlClose();
	?>

</div>

<script>
	//script to change between inbox and sent to go here.
	jQuery(".message_nav .inbox").click(function() {
		$(".messages").load("<?php echo $communitypi->getSetting('baseurl'); ?>call.php?f=messages");
	});
	jQuery(".message_nav .sent").click(function() {
		$(".messages").load("<?php echo $communitypi->getSetting('baseurl'); ?>call.php?f=messages_sent");
	});
</script>
