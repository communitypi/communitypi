<?php
//Main index file
include("communitypi.class.php");
$communitypi = new communitypi;

//include header
include("cp_themes/".$communitypi->getSetting("theme")."/header.php");

$code = mysql_real_escape_string($_GET['code']);
$communitypi->mysqlConnect();
$query = mysql_query("SELECT * FROM `password_reset_db` WHERE `code` = '$code';");
$data = mysql_fetch_array($query);
if (mysql_num_rows($query) == 1) {
mysql_query("DELETE FROM `password_reset_db` WHERE `code` = '$code' LIMIT 1;");
//generate random password
function createRandomPassword() {    $chars = "abcdefghijkmnopqrstuvwxyzAMCDERGHIJKLMNOPQRSTUVWXYZ023456789";    srand((double)microtime()*1000000);    $i = 0;    $pass = '' ;    while ($i <= 7) {        $num = rand() % strlen($chars);        $tmp = substr($chars, $num, 1);        $pass = $pass . $tmp;        $i++;    }    return $pass;}

$newpass = createRandomPassword();
$communitypi->changePassword($data['uid'], md5($newpass));
echo "<h1>Password Reset</h1>";
echo "Your password has been set to <strong>" . $newpass . "</strong> You may now login using your email and this password, and you can change your password by clicking 'Edit Profile' in the sidebar. ";
} else { 
	echo "<h2>Invalid Code, or code has expired</h2>";
}
?> 



<?php
//include footer
include("cp_themes/".$communitypi->getSetting("theme")."/footer.php");
?>