<div id="main_column">
	<div id="friends">
		<div id="friends_title"><h2>Your Friends</h2></div>
		<br>
		<form action="<?php $communitypi->getSetting('baseurl') ?>search" method="post">
			Find new friends: <input type="text" name="searchterms" />
			<input type="hidden" name="search_what" value="friends" />
			<input type="submit" value="Search" />
		</form>
		<br>
		<?php $communitypi->getFriendsList(); ?>
	</div>
</div>


		<?php
		$i = 0;
//			$communitypi->mysqlConnect();
//			$result = mysql_query("SELECT * FROM `friends_db` WHERE `uid`='$uid'");
//			while($row = mysql_fetch_array($result))
//			  {
//			  	$i++;
//			  	echo '<div class="friend" onclick="window.location = \'' . $communitypi->getUserProfileURL($row['friend']) . '\'">';
//			  	echo '<div class="friend_picture">';
//			  	echo '<img src="' .  $communitypi->getProfileImage($row['friend'], 100) . '" width="50" height="50" />';
//			  	echo '</div>';
//			  	echo '<div class="friend_name">';
//			  	echo $communitypi->getProfileName($row['friend']);
//			  	echo '</div>';
//			  	echo '<div class="friend_bio">';
//			  	echo substr($communitypi->getProfileBio($row['friend']), 0, 65) . "...";
//			  	echo '</div>';
//			  	echo '</div>';
//			  	
//			  }
//			  echo '<span class="friend_count">You have ' . $i . ' friends</span>';
//
//			$communitypi->mysqlClose();

			


		
	?>

