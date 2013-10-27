<?php
/* Copyright (C) 2011  CommunityPi, Jordan Cook, Jake Wright, Alex Kiernan and Henry Cole

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

//Main index file
include("communitypi.class.php");
$communitypi = new communitypi;

?>

<?php
//include header
include("cp_themes/".$communitypi->getSetting("theme")."/header.php");

//Message Handling
if ($_SESSION['p_message'] != "") {
	$messagemood = $_SESSION['p_mood'];
	echo '<div class="global_message_mood_'.$messagemood.'" id="global_message">'.$_SESSION['p_message'].'</div><script>document.write("<style>#global_message{display:none;}</style>");</script>';
	$_SESSION['p_mood'] = '';
	$_SESSION['p_message'] = '';
}

//see if we need to include a page, if not, show home page

//parameter meanings
//q = private page which needs the user to be logged in

if ($_GET['q']!=""){
	if($communitypi->userIsLoggedIn()) {
		$file = $_GET['q'];
		if (!file_exists('cp_themes/'.$communitypi->getSetting("theme").'/'.$file.'.php')) {
			//echo "<h1>Error: 404 - Page Not Found</h1>";
			if ($communitypi->userIsLoggedIn()) include("cp_themes/".$communitypi->getSetting("theme")."/sidebar.php");
			include 'cp_themes/'.$communitypi->getSetting("theme").'/404.php';
		} else {
			if ($communitypi->userIsLoggedIn()) include("cp_themes/".$communitypi->getSetting("theme")."/sidebar.php");
			include 'cp_themes/'.$communitypi->getSetting("theme").'/'.$file.'.php';
		}
	} elseif ($_GET['q'] == 'forgot_password') {
		include ("cp_themes/".$communitypi->getSetting("theme")."/forgot_password.php");
	} else {
		include("cp_themes/".$communitypi->getSetting("theme")."/home_private.php");
	}
}else{
	
	if($communitypi->userIsLoggedIn()) {
		include("cp_themes/".$communitypi->getSetting("theme")."/sidebar.php");
		include("cp_themes/".$communitypi->getSetting("theme")."/home.php");
	} else {
		include("cp_themes/".$communitypi->getSetting("theme")."/home_private.php");
	}
}

//include footer
include("cp_themes/".$communitypi->getSetting("theme")."/footer.php");
?>