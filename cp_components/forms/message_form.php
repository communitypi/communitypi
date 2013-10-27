<form id="compose_message_form" action="<?php echo $this->getSetting('baseurl'); ?>call.php?f=message_functions" onsubmit="return validateForm(this);" method="post">
	<input type="hidden" name="action" id="action" value="send">
	<input type="hidden" name="replyto" id="replyto" value="<?php if ( $_POST['action'] == "reply" ) echo $_POST['messageId']; ?>">
	<?php
		$to = "";
		$subject = "";
		$text = "";
		if ( $_POST['action'] == "reply" )
		{
			$this->mysqlConnect();
			$id = $_POST['messageId'];
			$result = mysql_query("SELECT * FROM messages_db WHERE id = $id;");
			while($row = mysql_fetch_array($result)) {
				$to = $this->getUserInfo($row['from'], "username");
				if (substr($row['subject'], 0, 4) == "RE: ")
				{
					$subject = $row['subject'];
				}
				else
				{
					$subject = "RE: ".$row['subject'];
				}
				
				$time = date('jS M Y G:i', $row['time']);
				$user = $this->getProfileInfo($row['from'], "name");
				$message = $row['message'];
				$text = "\n\n\nOn $time, $user wrote: \n".$message;
			}
			$this->mysqlClose();
		}

	?>
	
	<p>
		<div class="compose_message_form_container">
			<div class="compose_message_form_row">
				<div class="compose_message_form_cell_label">
					To:
				</div>
				<div class="compose_message_form_cell_input">
					<input type="text" id="compose_message_to" name="compose_message_to"  AUTOCOMPLETE=OFF onkeypress="return stopKeyCodes(event)" value="<?php echo $to; ?>">
					<div id="searchContacts"></div>
				</div>
			</div>
			
			<div class="compose_message_form_row">
				<div class="compose_message_form_cell_label">
					Subject:
				</div>
				<div class="compose_message_form_cell_input">
					<input type="text" id="compose_message_subject" name="compose_message_subject" value="<?php echo $subject; ?>">
				</div>
			</div>
			
		</div>
		<div class="compose_message_form_container">
			<div class="compose_message_form_cell_message">
								
				<textarea name="compose_message_message" id="compose_message_message"><?php echo $text; ?></textarea>
				
			</div>
			
		</div>
		<div id="compose_message_form_error"></div>
		<div class="compose_message_form_cell_submit"><input type="submit" id="compose_message_submit" name="compose_message_submit" value="Send"></div>
		
	</p>
</form>


<script>
	function validateForm(form) {
		error = "";
		document.getElementById('compose_message_form_error').innerHTML = error;
		
		username = document.getElementById('compose_message_to').value;
		
		
		$.get("<?php echo $this->getSetting("baseurl");?>call.php?f=friend&func=is&username=" + username, function(data){
				if (data == '0'){
					document.getElementById('compose_message_form_error').innerHTML = "You cannot send a message to that user.";
				}
			});
			
		if (form.compose_message_message.value == "") {
			error = "Please enter a message.";
		}
		
		if (error != "") {
			document.getElementById('compose_message_form_error').innerHTML = error;
		}
		
		if (document.getElementById('compose_message_form_error').innerHTML != "" ) {
			return false;
		}
	}
	
	function stopKeyCodes(e) {
		if (e.which == 13) {
			
			
			$("#searchContacts").show();
			var searchContacts = document.getElementById("searchContacts");
			var nodes = searchContacts.getElementsByTagName("span");
			document.getElementById('compose_message_to').value = nodes.item(cNode).innerHTML.split(" ",1);
			$("#searchContacts").hide();
			cNode = -1;
			nodes = 0;
		
		
		
			//selectUser($('#searchContacts li:eq('+cNode+')'));
			return false;
		}
		return true;
	}
	
	
	$("#compose_message_to").bind('keyup',function(e){
		if (!isDefined("cNode")) cNode = -1;
		if (!isDefined("nodes")) nodes = 0;
		if ( this.value == "" ) {
			$("#searchContacts").hide();
		} else if ( (e.which == 38) && (cNode > 0) && (e.which != 13) ) {
			cNode --;
			highlightNode();
		} else if ( (e.which == 40) && (cNode < (nodes - 1)) && (e.which != 13) ) {
			cNode ++;
			highlightNode();
		} else if ( (e.which != 38) && (e.which != 40) && (e.which != 13) )  { //if they continue to type, update the box
			//document.getElementById('searchContacts').innerHTML = ""; //clear the previous stuff first
			jQuery("#searchContacts").load("<?php echo $this->getSetting('baseurl');?>call.php?f=search_contacts&string=" + escape(this.value), function() { //load the new stuff
				nodes = $('#searchContacts li').size();
				if ( document.getElementById('searchContacts').innerHTML.indexOf("li") == -1 ) {
					$("#searchContacts").hide(); //if the box has no results, hide it.
					cNode = -1;
					nodes = 0;
				} else {
					$("#searchContacts").show(); //else, show the box in case it is hidden
					cNode = -1; //reset highlighed one to 0
					highlightNode();
				}

			}); 
		}
	});
	
	function selectUser(selection) {
		$("#searchContacts").show();
		document.getElementById('compose_message_to').value = selection.innerHTML.split(" ",1);
		$("#searchContacts").hide();
		cNode = -1;
		nodes = 0;
	}
	
	$("#compose_message_to").blur(function() {
		$("#searchContacts").delay(100).fadeOut();
		cNode = -1;
		nodes = 0;
	});
	
	
	function highlightNode() {
		$('#searchContacts li span').css('background-color', 'white');
		$('#searchContacts li span').css('color', 'black');
		$('#searchContacts li span:eq(' + cNode + ')').css('background-color', '#3875D7');
		$('#searchContacts li span:eq(' + cNode + ')').css('color', 'white');
	}
	
</script>