<?php
	//takes param 'string'
	$string = $_GET['string'];
	$uid = $_SESSION['uid'];
	$communitypi->mysqlConnect();
	//$result = mysql_query("SELECT * FROM `users_db` FULL JOIN `profile_db` ON `users_db`.`id`=`profile_db`.`uid` WHERE `users_db`.`username` LIKE '$string%' ORDER BY `users_db`.`username`;");
	//$result = mysql_query("(SELECT * FROM `users_db`, `profile_db` WHERE `users_db`.`username` LIKE '$string%' OR `profile_db`.`name` LIKE '$string%' ORDER BY `users_db`.`username`;");
	//$result = mysql_query("(SELECT * FROM `users_db` WHERE `username` LIKE '$string%') UNION (SELECT * FROM `profile_db` WHERE `name` LIKE '$string%');");
	$result = mysql_query("SELECT * FROM `users_db` LEFT JOIN `profile_db` ON users_db.id = profile_db.uid WHERE users_db.username LIKE '$string%' OR profile_db.name LIKE '$string%';");
	

		echo '<div class="search_contacts"><ul>';
	
	while($row = mysql_fetch_array($result)) {
		
		if ( $communitypi->isFriend($uid, $row['id']) ) {
			echo '<li class="row"><span onclick="selectUser(this);">'.$row['username'].' ('.$communitypi->getProfileInfo($row['id'], 'name').')</span></li>';
		}
		
	}

		echo '</ul></div>';
	
?>