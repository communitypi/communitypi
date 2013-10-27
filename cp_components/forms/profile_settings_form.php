<div class="tab_container">
	<div id="tab1" class="tab_content">
		<form id="profile_settings_form" action="<?php echo $this->getSetting('baseURL') . 'cp_components/functions/update_user_settings.php?p=details'; ?>" onsubmit="return validateForm(this);" method="post">
		
				<div class="profile_settings_form_container">
					<div class="profile_settings_form_row">
						<div class="profile_settings_form_cell_label">Name:</div>
						<div class="profile_settings_form_cell_input">
							<input type="text" id="profile_settings_name" name="profile_settings_name" value="<?php echo $this->getProfileInfo($_SESSION['uid'], 'name'); ?>" />
							<span id="register_name_error" class="field_message"></span>
						</div>
					</div>
					
					<div class="profile_settings_form_row">
						<div class="profile_settings_form_cell_label">Email:</div>
						<div class="profile_settings_form_cell_input"><input type="text" id="profile_settings_email" name="profile_settings_email" value="<?php echo $this->getUserInfo($_SESSION['uid'], 'email'); ?>" onblur="checkEmail(this.value);"  /><span id="profile_settings_email_error" class="field_message"></span></div>
					</div>
					
					<div class="profile_settings_form_row">
						<div class="profile_settings_form_cell_label">Bio:</div>
						<div class="profile_settings_form_cell_textarea"><textarea id="profile_settings_bio" name="profile_settings_bio" cols="60" rows="2" onkeydown="charRem(this, 'bio_charRem', <?php echo $this->getSetting('bio_char_limit'); ?>);sendUpdate(event);" onkeyup="charRem(this, 'bio_charRem', <?php echo $this->getSetting('bio_char_limit'); ?>);"><?php echo $this->getProfileInfo($_SESSION['uid'], 'bio'); ?></textarea><span id="profile_settings_email_bio" class="field_message"></span></div>
					</div>
				
					<div class="profile_settings_form_row">
						<div class="profile_settings_form_cell_label">Date of birth:</div>
						<div class="profile_settings_form_cell_select">
							<select id="profile_settings_birth_month" name="profile_settings_birth_month">
								<?php $usermonth = date(F,$this->getProfileInfo($_SESSION['uid'],'birth')); ?>
								<option value="January" <?php if ( $usermonth == January ) echo "SELECTED"; ?> >January</option>
								<option value="February" <?php if ( $usermonth == February ) echo "SELECTED"; ?> >February</option>
								<option value="March" <?php if ( $usermonth == March ) echo "SELECTED"; ?> >March</option>
								<option value="April" <?php if ( $usermonth == April ) echo "SELECTED"; ?> >April</option>
								<option value="May" <?php if ( $usermonth == May ) echo "SELECTED"; ?> >May</option>
								<option value="June" <?php if ( $usermonth == June ) echo "SELECTED"; ?> >June</option>
								<option value="July" <?php if ( $usermonth == July ) echo "SELECTED"; ?> >July</option>
								<option value="August" <?php if ( $usermonth == August ) echo "SELECTED"; ?> >August</option>
								<option value="September" <?php if ( $usermonth == September ) echo "SELECTED"; ?> >September</option>
								<option value="October" <?php if ( $usermonth == October ) echo "SELECTED"; ?> >October</option>
								<option value="November" <?php if ( $usermonth == November ) echo "SELECTED"; ?> >November</option>
								<option value="December" <?php if ( $usermonth == December ) echo "SELECTED"; ?> >December</option>
							</select>
						</div>
						
						<div class="profile_settings_form_cell_select">
							<select id="profile_settings_birth_day" name="profile_settings_birth_day">
							
								<?php 
								$userday =  date(d,$this->getProfileInfo($_SESSION['uid'],'birth'));
								$select = "";
									for ( $i = 1; $i < 32; $i += 1) {
										if ( $i == $userday ) {
											$select = " SELECTED ";
										}
										echo "<option value=\"" . $i . "\"$select>" . $i . "</option>";
										$select = '';
									} 
										?>
							</select>
						</div>
						
						<div class="profile_settings_form_cell_select">
							<select id="profile_settings_birth_year" name="profile_settings_birth_year">
		      						<?php
										// Number of years to go back
										$yearRange = 100;
										// Generate Options
										$thisYear = date('Y');
										$startYear = ($thisYear - $yearRange);
										$useryear = date(Y,$this->getProfileInfo($_SESSION['uid'],'birth'));
										$select = "";
										foreach (range($thisYear, $startYear) as $year) {
											if ( $useryear == $year ) {
												$select = " SELECTED ";
											}
											print "<option value=\"" . $year . "\"$select>" . $year . "</option>";
											$select = "";
										}
									?>
		    				</select>
						</div>
						
					</div>
					
					<div class="profile_settings_form_row">
						<div class="profile_settings_form_cell_quest"><?php echo $this->getSetting('profile_quest1'); ?></div>
						<div class="profile_settings_form_cell_questans">
							<textarea id="profile_settings_quest1" name="profile_settings_quest1" cols="60" rows="2" onkeydown="charRem(this, 'bio_charRem', <?php echo $this->getSetting('bio_char_limit'); ?>);sendUpdate(event);" onkeyup="charRem(this, 'bio_charRem', <?php echo $this->getSetting('bio_char_limit'); ?>);"><?php echo $this->getProfileInfo($_SESSION['uid'], 'quest1'); ?></textarea><span id="profile_settings_email_quest1" class="field_message"></span>
						</div>
					</div>
					
					<div class="profile_settings_form_row">
						<div class="profile_settings_form_cell_quest"><?php echo $this->getSetting('profile_quest2'); ?></div>
						<div class="profile_settings_form_cell_questans"><textarea id="profile_settings_quest2" name="profile_settings_quest2" cols="60" rows="2" onkeydown="charRem(this, 'bio_charRem', <?php echo $this->getSetting('bio_char_limit'); ?>);sendUpdate(event);" onkeyup="charRem(this, 'bio_charRem', <?php echo $this->getSetting('bio_char_limit'); ?>);"><?php echo $this->getProfileInfo($_SESSION['uid'], 'quest2'); ?></textarea><span id="profile_settings_email_quest2" class="field_message"></span></div>
					</div>
					
					<div class="profile_settings_form_row">
						<div class="profile_settings_form_cell_quest"><?php echo $this->getSetting('profile_quest3'); ?></div>
						
						<div class="profile_settings_form_cell_questans"><textarea id="profile_settings_quest3" name="profile_settings_quest3" cols="60" rows="2" onkeydown="charRem(this, 'bio_charRem', <?php echo $this->getSetting('bio_char_limit'); ?>);sendUpdate(event);" onkeyup="charRem(this, 'bio_charRem', <?php echo $this->getSetting('bio_char_limit'); ?>);"><?php echo $this->getProfileInfo($_SESSION['uid'], 'quest3'); ?></textarea><span id="profile_settings_email_quest3" class="field_message"></span></div>
					</div>
					
					<div class="profile_settings_form_row">
						<div class="profile_settings_form_cell_quest"><?php echo $this->getSetting('profile_quest4'); ?></div>
						
						<div class="profile_settings_form_cell_questans"><textarea id="profile_settings_quest4" name="profile_settings_quest4" cols="60" rows="2" onkeydown="charRem(this, 'bio_charRem', <?php echo $this->getSetting('bio_char_limit'); ?>);sendUpdate(event);" onkeyup="charRem(this, 'bio_charRem', <?php echo $this->getSetting('bio_char_limit'); ?>);"><?php echo $this->getProfileInfo($_SESSION['uid'], 'quest4'); ?></textarea><span id="profile_settings_email_quest4" class="field_message"></span></div>
					</div>
		
				</div>
				<div class="profile_settings_form_cell_submit"><input type="submit" id="profile_details_submit" name="profile_settings_submit" value="Save" /></div>
				
		</form>	
	</div>


	<div id="tab2" class="tab_content">
		<form name="privacy_settings" action="<?php echo $this->getSetting('baseURL'); ?>cp_components/functions/update_user_settings.php?p=privacy" method="post">
			
			<?php
				$uid = $_SESSION['uid'];
				$publicprof = $this->getProfileInfo($uid, 'publicprof');
				$userprof = $this->getProfileInfo($uid, 'userprof');
				if ($publicprof == '1') {
					$checked = 'public';
				} elseif ($userprof == '1') {
					$checked = 'members';
				} elseif ($publicprof == '0' && $userprof == '0') {
					$checked = 'private';
				}
			?>
			
			<input type="radio" name="privacy" value="public" <?php if ($checked=='public') echo 'checked'; ?>>Public - profile viewable by anyone<br>
			<input type="radio" name="privacy" value="members" <?php if ($checked=='members') echo 'checked'; ?>>Members - profile viewable by all logged in users<br>
			<input type="radio" name="privacy" value="private" <?php if ($checked=='private') echo 'checked'; ?>>Private - profile viewable by friends only<br>

			

			<div class="profile_settings_form_cell_submit"><input type="submit" id="profile_privacy_submit" name="profile_privacy_submit" value="save" /></div>

		</form>



	</div>
	<div id="tab3" class="tab_content">

		Photos are limited to <?php echo round((int)$this->getSetting('maxuploadfilesize')/1000000); ?>MB
		<form id="photo_upload_form" action="<?php echo $this->getSetting('baseURL'); ?>cp_components/functions/update_user_settings.php?p=photo" method="post" enctype="multipart/form-data" onsubmit="return validateUploadForm(this);">
		<div id="uploadFormError" ><?php echo $_GET['error']; ?></div>
		<div id="buttonContainer1" class="buttonContainer1"><input id="photo1" type="file" name="photo1"></div>
		<input type="hidden" id="photoUploadId" value="2">
		<div id="uploadButtons"></div>
		<br />
		<input id="photoUploadSubmit" type="submit" value="Upload">
		<div id="message"><img src="<?php echo $this->getSetting('baseURL'); ?>cp_images/loader_icon.gif" />Uploading...</div>
		</form>


	</div>
	<div id="tab4" class="tab_content">

		<form name="password_settings" action="<?php echo $this->getSetting('baseURL'); ?>cp_components/functions/update_user_settings.php?p=password" method="post">
			Current password:
			<input type="password" id="password_settings_current_password" name="oldpass" /><br />
			
			New Password:
			<input type="password"id="password_settings_new_password" name="newpass1" /><br />
			
			Confirm Password:
			<input type="password" id="password_settings_confirm_password" name="newpass2" /><br />
			
			<input type="submit" id="password_settings_submit" name="password_settings_submit" value="Save" /><br />
		</form> 


	</div>
	
	<div id="tab5" class="tab_content">
		<form name="notification_settings" action="<?php echo $this->getSetting('baseURL'); ?>cp_components/functions/update_user_settings.php?p=notification" method="post">
			Choose which notifications you would like to be emailed about:<br /><br />
			<?php $uid = $_SESSION['uid'];	?>
			Friend Request <input type="checkbox" name="emailOnFriendRequest" value="1" <?php if ( $this->getProfileInfo($uid, "emailOnFriendRequest") == "1" ) echo 'checked="yes"'; ?> /><br />
			Comment on Status <input type="checkbox" name="emailOnComment" value="1" <?php if ( $this->getProfileInfo($uid, "emailOnComment") == "1" ) echo 'checked="yes"'; ?> /><br />
			Group Message <input type="checkbox" name="emailOnGroupMessage" value="1" <?php if ( $this->getProfileInfo($uid, "emailOnGroupMessage") == "1" ) echo 'checked="yes"'; ?> /><br />
			Message Recieved <input type="checkbox" name="emailOnMessage" value="1" <?php if ( $this->getProfileInfo($uid, "emailOnMessage") == "1" ) echo 'checked="yes"'; ?> /><br /><br />
			<input type="submit" id="notification_settings_submit" name="notification_settings_submit" value="Save" />
		</form>
	</div>


</div>

<script language="javascript" type="text/javascript">

$(document).ready(function() {

//When page loads...
	$(".tab_content").hide(); //Hide all content
	$("ul.tabs li:first").addClass("active").show(); //Activate first tab
	$(".tab_content:first").show(); //Show first tab content

	//On Click Event
	$("ul.tabs li").click(function() {

		$("ul.tabs li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content").hide(); //Hide all tab content

		var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active ID content
		return false;
	});

});	
	
	function validateForm(form) {
		if(checkName(form.profile_settings_name.value)==false){return false;}
		if(checkEmail(form.profile_settings_email.value)==false){return false;}
	}
	

	
	function checkName(string) {
		var error = "";
		if (string == "") { error = "Please enter your name";}
		document.getElementById('profile_settings_name_error').innerHTML = error;
		if (error!=""){return false;}else{return true;}
	}
	
	function checkEmail(string) {
		var error = "";
		var profile_settings_emailFilter = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/;
		if (!(profile_settings_emailFilter.test(string))) { error = "Enter a valid email address";}
		var illegalChars= /[\(\)\<\>\,\;\:\\\/\"\[\]]/
		if (string.match(illegalChars)) { error = "Enter a valid email address";}
		document.getElementById('profile_settings_email_error').innerHTML = error;
		if (error!=""){return false;}else{return true;}
	}

</script>
