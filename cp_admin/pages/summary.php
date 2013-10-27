<?php
$communitypi->mysqlConnect();
?>
<h4>You are running version <?php echo VERSION; ?>. <?php
$version = file_get_contents("http://update.communitypi.org/currentversion.txt");
if (VERSION < $version) {
	echo "Updates are available! Version: $version";
} else {
	echo "This is the current version.";
} 
?>
</h4>

<table cellspacing="10"><tr><td>

<h3>Statistics:</h3>
<table cellpadding="10">
<tr>
	<td>Users: </td>
	<td><?php
			$query = mysql_query("SELECT * FROM `users_db`;");
			echo mysql_num_rows($query);
		?>
	</td>
</tr>

<tr>
	<td>Status Updates: </td>
	<td><?php
			$query = mysql_query("SELECT * FROM `timeline_db`;");
			echo mysql_num_rows($query);
		?>
	</td>
</tr>

<tr>
	<td>Photos: </td>
	<td><?php
			$query = mysql_query("SELECT * FROM `photos_db`;");
			echo mysql_num_rows($query);
		?>
	</td>
</tr>

<tr>
	<td>Groups: </td>
	<td><?php
			$query = mysql_query("SELECT * FROM `groups_db`;");
			echo mysql_num_rows($query);
		?>
	</td>
</tr>

<tr>
	<td>Friend Requests: </td>
	<td><?php
			$query = mysql_query("SELECT * FROM `friendrequests_db`;");
			echo mysql_num_rows($query);
		?>
	</td>
</tr>

<tr>
	<td>Relations: </td>
	<td><?php
			$query = mysql_query("SELECT * FROM `friends_db`;");
			echo mysql_num_rows($query);
		?>
	</td>
</tr>
</table>
</td>
<td valign="top" style="border-left: 1px solid #000; padding-left: 20px;">

<h3>Active users:</h3>
<table cellpadding="10">
<tr>
 <td>24 hours: </td>
 <td>
 <?php
 $now = time();
 $ago = $now - 86400;
 $query = mysql_query("SELECT * FROM `users_db` WHERE `lastlogin` > '$ago'; ");
echo mysql_num_rows($query);
 
 ?>
 </td>
</tr>
<tr>
 <td>72 hours: </td>
 <td>
 <?php
 $now = time();
 $ago = $now - 259200;
 $query = mysql_query("SELECT * FROM `users_db` WHERE `lastlogin` > '$ago'; ");
echo mysql_num_rows($query);
 
 ?>
 </td>
</tr>
<tr>
 <td>1 week: </td>
 <td>
 <?php
 $now = time();
 $ago = $now - 604800;
 $query = mysql_query("SELECT * FROM `users_db` WHERE `lastlogin` > '$ago'; ");
echo mysql_num_rows($query);
 
 ?>
 </td>
</tr>
<tr>
 <td>2 weeks: </td>
 <td>
 <?php
 $now = time();
 $ago = $now - 1209600;
 $query = mysql_query("SELECT * FROM `users_db` WHERE `lastlogin` > '$ago'; ");
echo mysql_num_rows($query);
 
 ?>
 </td>
</tr>
<tr>
 <td>1 month: </td>
 <td>
 <?php
 $now = time();
 $ago = $now - 2629743;
 $query = mysql_query("SELECT * FROM `users_db` WHERE `lastlogin` > '$ago'; ");
echo mysql_num_rows($query);
 
 ?>
 </td>
</tr>
</table>
</td>
</tr>
</table>


<h3>Latest Users:</h3>
<table cellpadding="5">
<tr>
	<td><b>Username: </b></td>
	<td><b>Email: </b></td>
	<td><b>Date registed: </b></td>
</tr>

<?php
			$query = mysql_query("SELECT * FROM `users_db` ORDER BY `users_db`.`joined`  DESC LIMIT 10;");
			while ($row = mysql_fetch_array($query)) {
				echo "<tr>
							<td>{$row['username']}</td>
							<td>{$row['email']}</td>
							<td>" . date('F j, Y, g:i a', $row['joined']) . "</td>
						</tr>";
			}
				
?>
</table>