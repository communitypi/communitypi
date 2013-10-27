<html>
<head>
<title>CommunityPi Installation</title>
<link rel="stylesheet" href="style.css" type="text/css" media="screen" />
</head>
<body>
<div id="container">

<?php if (false) { ?>
	<h1>CommunityPi required PHP</h1>
<?php } ?>

<?php
	include '../cp_config.php'; //Database information
	
	
		
	$step = $_GET['step']; //current step of the installation
	
	if ($step != "5") {
		$con = mysql_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD);
		if (!$con) {
			echo '<h1>Thank you for choosing <span class="green">CommunityPi</span></h1>';
			echo "<br>Before you begin, please fill out the information in cp_config.php.";
			exit();
		}
		
		if (!mysql_select_db(DB_NAME, $con)) {
			echo '<h1>Thank you for choosing <span class="green">CommunityPi</span></h1>';
			echo "Please make sure the database name is correct in cp_config.php.";
			exit();
		}
		
		if (mysql_num_rows(mysql_query("SHOW TABLES LIKE 'settings_db'"))) {
			if (mysql_num_rows(mysql_query("SELECT * FROM `settings_db` WHERE `name` = 'baseurl' and `value` != ''"))) {
				echo '<h1>Thank you for choosing <span class="green">CommunityPi</span></h1>';
				echo "It looks like CommunityPi is already installed.";
				exit();
			}
		}
	}
	
	if (!$step) { //if we haven't started yet
		
		echo '<h1>Thank you for choosing <span class="green">CommunityPi</span></h1>';
		
		$con = mysql_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD);
		if (!$con) {
			echo "<br>Before you begin, please fill out the information in cp_config.php.";
			exit();
		}
		
		if (!mysql_select_db(DB_NAME, $con)) {
			echo "Please make sure the database name is correct in cp_config.php.";
			exit();
		}
		

		
		echo '<form action="index.php?step=1" method="post"><input class="submit" type="submit" value="Let\'s get Started" /></form>';
		
	} elseif ($step == "1") {
		echo '<h1>Step 1: <span class="green">Requirements</span></h1>';
		
		//echo 'Connect to Database';
		
		$con = mysql_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD);
		if (!$con) {
			die('Could not connect to database ' . mysql_error($con));
			$continue = false;
			exit();
		}
		
		$php_version    = phpversion();
		$mysql_version  = mysql_get_server_info();
		
		if (version_compare($php_version, '5.0.0', '>=')) $phpcheck = true;
		if (version_compare($mysql_version, '5.0.0', '>=')) $mysqlcheck = true;
		//if (in_array("mod_rewrite", apache_get_modules())) $modrewritecheck = true;
		
		if ($phpcheck) {
			echo 'PHP 5 or later <img src="images/tick-icon.png" /><br>';
		} else {
			echo 'PHP 5 or later <img src="images/close-icon.png" />';
		}
		
		if ($mysqlcheck) {
			echo 'MySQL 5 or later <img src="images/tick-icon.png" />';
		} else {
			echo 'MySQL 5 or later <img src="images/close-icon.png" />';
		}
		
//		if ($modrewritecheck) {
//			echo 'Apache mod_rewrite module <img src="images/tick-icon.png" />';
//		} else {
//			echo 'Apache mod_rewrite module <img src="images/close-icon.png" />';
//		}
		
		if ($phpcheck && $mysqlcheck) {
			echo '<form action="index.php?step=2" method="post"><input class="submit" type="submit" value="Continue" /></form>';
		}
		
	} elseif ($step == '2') {
		echo '<h1>Step 2: <span class="green">Database Configuration</span></h1>';
		
		$continue = true;
		
		echo 'Connect to Database';
		
		$con = mysql_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD);
		if ($con) {
			echo '<img src="images/tick-icon.png" /><br>';
		} else {
			echo '<img src="images/close-icon.png" /><br>';
			die('Could not connect to database: ' . mysql_error());
			$continue = false;
		}
		
		echo 'Select Database';
		
		if (mysql_select_db(DB_NAME, $con)) {
			echo '<img src="images/tick-icon.png" /><br>';
		} else {
			echo '<img src="images/close-icon.png" />';
			$continue = false;
		}
		
		echo 'Create tables';
		$queries = explode(";", file_get_contents("sql.txt"));
		if (!$queries) {
			echo "Failed to get MySQL queries";
			$continue = false;
		}
		
		foreach ($queries as $query) {
			if ($query) {
				if (!mysql_query($query)) {
					echo '<img src="images/close-icon.png" /><br>MySQL error: ' . mysql_error();
					$continue = false;
				}
			}
		}			
		
		
		if ($continue) {
			echo '<img src="images/tick-icon.png" /><br>';
			echo '<form action="index.php?step=3" method="post"><input class="submit" type="submit" value="Continue" /></form>';
		} else {
			echo '<br><br>Looks like some errors occured. Continue only if you know what you\'re doing.';
			echo '<form action="index.php?step=3" method="post"><input class="submit" type="submit" value="Ignore Errors" /></form>';
		}
			
	} elseif ($step == '3') {
		echo '<h1>Step 3: <span class="green">Create User Account</span></h1>This will be your user account with administrative access!<br><br>';
		
		echo '<div>' . $_SESSION['user_error'] . '</div>';
		$_SESSION['user_error'] = "";
		
		echo '<form id="form3" action="index.php?step=3b" onsubmit="return validateForm(this);" method="post">
			Username <span class="red">*</span><br>
			<input type="text" name="username" onkeyup="checkUsername(this);" onblur="checkUsername(this);" />
			<span id="username_error" class="field_message"></span><br>
			Name <span class="red">*</span><br>
			<input type="text" name="name" onblur="checkName(this.value);" />
			<span id="name_error" class="field_message"></span><br>
			Email Address <span class="red">*</span><br>
			<input type="text" name="email" onblur="checkEmail(this.value);" />
			<span id="email_error" class="field_message"></span><br>
			Date of Birth <span class="red">*</span><br>
			<select name="birth_month">
				<option value="January">January</option>
				<option value="February">February</option>
				<option value="March">March</option>
				<option value="April">April</option>
				<option value="May">May</option>
				<option value="June">June</option>
				<option value="July">July</option>
				<option value="August">August</option>
				<option value="September">September</option>
				<option value="October">October</option>
				<option value="November">November</option>
				<option value="December">December</option>
			</select>	
			<select name="birth_day">';	
				for ( $i = 1; $i < 32; $i += 1) {
					echo "<option value=\"" . $i . "\">" . $i . "</option>";
				} 

			echo '</select>
			<select name="birth_year">';
				// Number of years to go back
				$yearRange = 100;
				// Generate Options
				$thisYear = date('Y');
				$startYear = ($thisYear - $yearRange);
				foreach (range($thisYear, $startYear) as $year) {
					print "<option value=\"" . $year . "\">" . $year . "</option>";
				}
    		echo '</select><br>
    		Password <span class="red">*</span><br>
    		<input id="password" type="password" name="password" onkeyup="checkPassword(this.value);" onblur="checkPassword(this.value);" />
    		<span id="password_error" class="field_message"></span><span id="password_strength"></span><br>
    		Confirm Password <span class="red">*</span><br>
    		<input type="password" name="password_confirm" onblur="checkPasswordConfirm(this.value);" />
    		<span id="password_confirm_error" class="field_message"></span><br>
    		
			<input class="submit" type="submit" value="Create" /></form>';	
			
	} elseif ($step =='3b') {
		echo '<h1>Step 3: <span class="green">Create User Account</span></h1>';
		
		$continue = true;
		
		$username = strtolower($_POST['username']);
		$name = $_POST['name'];
		$email = $_POST['email'];
		
		$birthmonth = $_POST['birth_month'];
		$birthday = $_POST['birth_day'];
		$birthyear = $_POST['birth_year'];
		$birth = strtotime($birthday . "-" . $birthmonth . " " . $birthyear);
		
		$pass1 = md5($_POST['password']);
		$pass2 = md5($_POST['password_confirm']);
		
		//Validate username
		if (strlen($username) < 4 || strlen($username) >20 || substr($username, 0, 1) == "_" || is_int(substr($username, 0, 1))) {
			$_SESSION['user_error'] = "Invalid username";
			//$referer = $_SERVER['HTTP_REFERER'];
			echo "Looks like you entered an invalid username. :(";
			echo '<form action="index.php?step=3" method="post"><input class="submit" type="submit" value="Go back" /></form>';
			exit();
		}
		
		//Validate Name
		if ($name == "") {
			$_SESSION['user_error'] = "Invalid Name";
			//$referer = $_SERVER['HTTP_REFERER'];
			echo "Looks like you entered an invalid name. :(";
			echo '<form action="index.php?step=3" method="post"><input class="submit" type="submit" value="Go back" /></form>';
			exit();
		}
		
		//Validate email
		if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email)) {
			$_SESSION['user_error'] = "Invalid Email";
			echo "Looks like you entered an invalid email address. :(";
			echo '<form action="index.php?step=3" method="post"><input class="submit" type="submit" value="Go back" /></form>';
			exit();
		}
		
		if ($pass1 != $pass2) {
			$_SESSION['user_error'] = "Passwords do not match";
			echo "Those passwords did not match. :(";
			echo '<form action="index.php?step=3" method="post"><input class="submit" type="submit" value="Go back" /></form>';			exit();
		}
		
		//Create the user
		
		echo "Creating user account $username";
		
		$con = mysql_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD);
		if (!$con) {
			echo '<img src="images/close-icon.png" /><br>';
			die('Could not connect to database: ' . mysql_error());
			$continue = false;
			exit();
		}
		
		if (!mysql_select_db(DB_NAME, $con)) {
			echo '<img src="images/close-icon.png" /><br>Could not find database';
			$continue = false;
			exit();
		}
		
		if(!mysql_query("INSERT INTO `users_db` (`username`, `email`, `password`, `password1`, `password2`, `active`, `banned`, `joined`, `lastlogin`, `root`, `admin`, `mobile`) VALUES ('$username', '$email', '$pass1', '$pass1', '$pass1', '1', '0', UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), '1', '1', '0');")) {
			echo '<img src="images/close-icon.png" />';
			echo "<br>" . mysql_error() . "<br>";
			$continue = false;
			exit();
		}
		
		$id = mysql_insert_id();
				
		if(!mysql_query("INSERT INTO `profile_db` (`uid`, `name`, `birth`, `bio`, `quest1`, `quest2`, `quest3`, `quest4`, `publicprof`, `userprof`, `welcome`) VALUES ('$id', '$name', $birth, 'No biography yet', '', '', '', '', '0', '1', '0');")) {
			echo '<img src="images/close-icon.png" />';
			echo "<br>" . mysql_error() . "<br>";
			$continue = false;
			exit();
		}
		
		if ($continue == true) {
			$_SESSION['username'] = $username;
			$_SESSION['email'] = $email;
			$_SESSION['loggedin'] = "yes";
			$_SESSION['uid'] = $id;
		
			echo '<img src="images/tick-icon.png" />';
		
			echo '<form action="index.php?step=4" method="post"><input class="submit" type="submit" value="Continue" /></form>';
		} else {
			echo "<br>Could not create user account<br>";
		}
		
	} elseif ($step =='4') {
		echo '<h1>Step 4: <span class="green">Site Settings</span></h1>';
		
		$con = mysql_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD);
		if (!$con) {
			die('Could not connect to database: ' . mysql_error());
			$continue = false;
			exit();
		}
		
		
		if (!mysql_select_db(DB_NAME, $con)) {
			echo 'Error finding database.';
			$continue = false;
			exit();
		}

		
		$query = mysql_query("SELECT * FROM  `settings_db`;");
		
		echo '<form action="index.php?step=4b" method="post"><table><input type="hidden" name="theme" value="default"';
			while ($row = mysql_fetch_array($query)) {
				if ($row['name'] == 'theme') {
					//do nothing
				} elseif ($row['name'] == 'minage') {
					echo '<tr><td>Minimum Age: </td><td>';
					echo '<select name="minage">';
					for ($i = 0; $i <= 18; $i++) {
						if ($row['value'] == $i) {
							echo '<option value="' . $i . '" selected="selected">' . $i . '</option>';
						} else {
						    echo '<option value="' . $i . '">' . $i . '</option>';
						}
					}
					echo "</select> years
					</td></tr>";
				} else {
					if ($row['name'] == 'maxuploadfilesize') {
						echo '<tr><td>Max upload size: </td><td>';
						echo '<select name="maxuploadfilesize">';
						for ($i = 1; $i <= (ini_get('upload_max_filesize')); $i++) {
							if ($row['value'] == (($i * 1024) * 1024)) {
								echo '<option value="' . $i . '" selected="selected">' . $i . '</option>';
							} else {
						    	echo '<option value="' . (($i * 1024) * 1024) . '">' . $i . '</option>';
							}
						}
						echo "</select> megabytes";
						echo "</td>";
						echo "</tr>";
					} else {

						echo '<tr>';
						echo '<td>';
						echo $row['comment'];
						echo ': </td>';
						echo '<td>';
						echo '<input size="50" type="text" value="';
						if ($row['name'] == "baseurl") {
							$pageURL = 'http';
 							if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 							$pageURL .= "://";
 							if ($_SERVER["SERVER_PORT"] != "80") {
  								$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 							} else {
 								$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 							}
 							echo dirname(dirname($pageURL)) . "/"; 
						} else if ($row['name'] == "path") {
							echo $_SERVER["DOCUMENT_ROOT"] . "/";
						} else {
							echo $row['value'];
						}
						echo '" name="';
						echo $row['name'];
						echo '" />';
						echo '</td>';
						echo '</tr>';
					}
				}
			}

		echo '<tr><td></td><td><input class="submit" type="submit" name="site_settings_submit" value="Save Settings" /></td></tr>';
		echo "</table></form>";
		
	} elseif ($step == '4b') {
		echo '<h1>Step 4: <span class="green">Site Settings</span></h1>';
		
		
		//get all info from form
//		$theme = $_POST['theme'];
//		$baseurl = $_POST['baseurl'];
//		$site_name = $_POST['site_name'];
//		$site_desc = $_POST['site_desc'];
//		$mailfrom = $_POST['mailfrom'];
//		$path = $_POST['path'];
//		$profile_quest1 = $_POST['profile_quest1'];
//		$profile_quest2 = $_POST['profile_quest2'];
//		$profile_quest3 = $_POST['profile_quest3'];
//		$profile_quest4 = $_POST['profile_quest4'];
//		$bio_char_limit = $_POST['bio_char_limit'];
//		$minage = $_POST['minage'];
//		$maxuploadfilesize = (int)$_POST['maxuploadfilesize'];

		$con = mysql_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD);
		if (!$con) {
			die('Could not connect to database: ' . mysql_error());
			exit();
		}
		
		
		if (!mysql_select_db(DB_NAME, $con)) {
			echo 'Error finding database.';
			exit();
		}
		
		$query = mysql_query("SELECT * FROM `settings_db`;");
		while ($row = mysql_fetch_array($query)) {
			$name = $row['name'];
			$value = $_POST[$name];
			if ($name == "baseurl" && substr($value, -1) != "/") $value = $value . "/";
			if ($name == "path" && substr($value, -1) != "/") $value = $value . "/";
			
			if (!mysql_query("UPDATE `settings_db` SET `value` = '$value' WHERE `name` ='$name';")) {
				echo "Error saving settings";
				exit();
			}
		}
		
		echo "Site settings saved successfully.";
		echo '<form action="index.php?step=5" method="post"><input class="submit" type="submit" value="Continue" /></form>';

	} elseif ($step == '5') {
		echo '<h1>Installation <span class="green">Complete</span></h1>';
		
		echo "Congratulations, CommunityPi is ready to use!";
		echo '<form action="../" method="post"><input class="submit" type="submit" value="Visit Site" /></form>';
		
	}
?>



<script language="javascript" type="text/javascript">

	function validateForm(form) {
		if(checkUsername(form.username)==false){return false;}
		if(checkName(form.name.value)==false){return false;}
		if(checkEmail(form.email.value)==false){return false;}
		if(checkPassword(form.password.value)==false){return false;}
		if(checkPasswordConfirm(form.password_confirm.value)==false){return false;}
	}
	
	function checkUsername(field) {
		var string = field.value
		var error = "";
		var illegalChars = /\W/;
		if (illegalChars.test(string)) { error = "Numbers, letters and underscores only"; }
		if (!string.charAt(0).match(/^[a-zA-Z]$/)) { error = "Username must start with a letter"; }
		if ((string.length < 4) || (string.length > 20)) { error = "Must be between 4 and 20 characters"; }
		if (string == "") { error = "Please enter a username";}
		document.getElementById('username_error').innerHTML = error;
		if (error!=""){return false;}else{return true;}
	}

	
	function checkName(string) {
		var error = "";
		if (string == "") { error = "Please enter your name";}
		document.getElementById('name_error').innerHTML = error;
		if (error!=""){return false;}else{return true;}
	}
	
	function checkEmail(string) {
		var error = "";
		var register_emailFilter = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/;
		if (!(register_emailFilter.test(string))) { error = "Enter a valid email address";}
		var illegalChars= /[\(\)\<\>\,\;\:\\\/\"\[\]]/
		if (string.match(illegalChars)) { error = "Enter a valid email address";}
		document.getElementById('email_error').innerHTML = error;
		if (error!=""){return false;}else{return true;}
	}
	
	function checkPassword(string) {
		var error = "";
		if (string.length < 6) { error = "Too short";}
		document.getElementById('password_error').innerHTML = error;
		if (error==""){
			checkPasswordStrength(string);
		}
		if (error!=""){return false;}else{return true;}
	}
	
	function checkPasswordStrength(password) {
		var desc = new Array();
        desc[0] = "Very Weak";
        desc[1] = "Weak";
        desc[2] = "Medium";
        desc[3] = "Medium";
        desc[4] = "Medium";
        desc[5] = "Strong";
        desc[6] = "Very Strong";
        var score   = 0;
        //if password bigger than 6 give 1 point
        if (password.length > 6) score++;
        //if password has both lower and uppercase characters give 1 point      
        if ( ( password.match(/[a-z]/) ) && ( password.match(/[A-Z]/) ) ) score++;
        //if password has at least one number give 1 point
        if (password.match(/\d+/)) score++;
        //if password has at least one special caracther give 1 point
        if ( password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/) ) score++;
        //if password has upcase, lowercase, number and symbol, give 1 point
        if ( (password.match(/[a-z]/)) && (password.match(/[A-Z]/)) && (password.match(/\d+/)) && (password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)) ) score++;
        //if password bigger than 12 give another 1 point
        if (password.length > 11) score++;
        
		document.getElementById('password_error').innerHTML = '<span id="password_score' + score + '">' + desc[score] + '</span>';
	}
	
	function checkPasswordConfirm(string) {
		var error = "";
		if (string != document.getElementById('password').value) {error = "The passwords do not match";}
		document.getElementById('password_confirm_error').innerHTML = error;
		if (error!=""){return false;}else{return true;}
	}
	
</script>

</div>
<div id="footer"><a target="_blank" href="http://communitypi.org">CommunityPi.org</a></div>
</body>
</html>