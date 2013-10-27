<?php
$communitypi->mysqlConnect();
$query = mysql_query("SELECT * FROM `users_db`;");
$count = mysql_num_rows($query);
$results = 10;

function roundUp($number) { 
    $increments = 1 / 10; 
    return (ceil($number * $increments) / $increments); 
} 

$pages = roundUp($count) / 10;
?>
<table width="600">
	<tr>
		<td><b>Username: </td>
		<td><b>Name: </td>
		<td><b>Last Login: </td>
		<td><b>Ban: </td>
		<td><b>Delete: </td>
	</tr>
	
<?php
$query = mysql_query("SELECT * FROM `users_db`;");
while ($row = mysql_fetch_array($query)) {
	echo "<tr>
			<td>{$row['username']}</td>
			<td>{$communitypi->getProfileName($row['id'])}</td>
			<td>" . date('F j, Y', $row['lastlogin']) . "</td>";
				echo "<td>";
				if ($row['banned'] == '0') {
					echo '<a href="' . $communitypi->getSetting("baseurl") . 'cp_admin/functions/ban_user.php?id=' . $row['id'] . '">Ban</a>';
				} else {
					echo '<a href="functions/unban_user.php?id=' . $row['id'] . '"Unban</a>';
				}
				echo "</td>";
			echo '<td><a href="' . $communitypi->getSetting("baseurl") . 'cp_admin/functions/delete_user.php?id=' . $row['id'] . '">Delete</a></td>';
		echo "</tr>";
}

?>
</table>