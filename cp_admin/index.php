<?php
//Main index file
include("../communitypi.class.php");
$communitypi = new communitypi;


//include header
include("../cp_themes/".$communitypi->getSetting("theme")."/header.php");

//Message Handling
if ($_SESSION['p_message'] != "") {
	$messagemood = $_SESSION['p_mood'];
	echo '<div class="global_message_mood_'.$messagemood.'" id="global_message">'.$_SESSION['p_message'].'</div><script>document.write("<style>#global_message{display:none;}</style>");</script>';
	$_SESSION['p_mood'] = '';
	$_SESSION['p_message'] = '';
}

$mode = $_GET['mode'];

//see if we need to include a page, if not, show home page

//parameter meanings
//q = private page which needs the user to be logged in


	if($communitypi->userIsLoggedIn()) {
		include("../cp_themes/".$communitypi->getSetting("theme")."/sidebar.php");
		if($communitypi->isAdmin($ses) || $communitypi->isRoot($ses)) {
		
			if ($mode == '') {
				include("pages/summary.php");
			} else {
				include("pages/" . $mode . ".php");
			}
			
		} else { 
			include("../cp_themes/".$communitypi->getSetting("theme")."/404.php");
		}
	} else {
		include("../cp_themes/".$communitypi->getSetting("theme")."/404.php");
	}
		

//include footer
include("../cp_themes/".$communitypi->getSetting("theme")."/footer.php");
?>