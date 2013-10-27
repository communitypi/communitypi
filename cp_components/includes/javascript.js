$(document).ready(function()
  {
  	count = 20;
  	//baseurl = "http://social.inozzo.com/";
  	
  	$.get("baseurl.php", function(data){
		baseurl = data;
	});
  	
  	var updateInterval = 15000;
  	
	var refreshId = setInterval(function() 
   {
      $('#timeline').load('cp_components/functions/timeline.php');
      
      $('#check_notifications').load(baseurl + 'cp_components/functions/check_notifications.php');
	  if ($('#check_notifications').html() == "true") {
      		jQuery('#notifications_button').css("background-color", "red");
      }
      
   }, updateInterval); 
   
 
   
   //Update span with notifications when you click button
   jQuery('#notifications_button').toggle(function(){
   		jQuery('#notifications_container').load(baseurl + 'cp_components/functions/notifications.php');
   		jQuery('#notifications_button').css("background-color", "#fff");
   		jQuery('#notifications_button').css("border-color", "#434343");
   		jQuery('#notifications_button').css("color", "#000");
   		jQuery('#notifications_button').css("border-bottom", "1px solid #fff");
   		jQuery('#notifications_container').css("display", "inline");
   }, function() {
   		jQuery('#notifications_button').css("background-color", "inherit");
   		jQuery('#notifications_button').css("border-color", "#fff");
   		jQuery('#notifications_button').css("color", "inherit");
   		jQuery('#notifications_button').css("border-top", "none");
   		jQuery('#notifications_container').css("display", "none");
   });
   
   jQuery('#notifications').mouseleave(function(){
   		if (jQuery('#notifications_container').is(":visible")) {
   			jQuery('#notifications_button').trigger('click');
   		}
   });
   
   
   
   jQuery('#status_update .btnShortenUrl').click(function(){
    	jQuery("#shorten_url").css("top", ( jQuery(window).height() - jQuery("#shorten_url").height() ) / 2 + "px");
    	jQuery("#shorten_url").css("left", ( jQuery(window).width() - jQuery("#shorten_url").width() ) / 2 + "px");
		jQuery("#shorten_url").fadeIn("fast");
		jQuery("#page_dim").fadeTo("fast", 0.7);
		document.getElementById("txtbxShortenUrl").focus();
	});
	
	jQuery("#shorten_url .close").click(function() {
		jQuery("#shorten_url").fadeOut("fast");
		jQuery("#page_dim").fadeOut("fast");
	});
	
	jQuery("#more_timeline").click(function() {
		count = count + 20;
		clearInterval(refreshId);
		$("#timeline").load("cp_components/functions/timeline.php?count=" + count);
		$(document).ready();
	});
	
	jQuery("#more_messages").click(function() {
		count = count + 20;
		$("#messages").load("../cp_components/functions/messages.php?count=" + count);
	});
	
	jQuery("#page_dim").click(function() {
		jQuery("#comment_reply").fadeOut("fast");
		jQuery("#page_dim").fadeOut("fast");
		jQuery("#shorten_url").fadeOut("fast");
		jQuery(".create_group_form_container").fadeOut("fast");
	});
	
	   
	
	jQuery("#comment_reply .close").click(function() {
		jQuery("#comment_reply").fadeOut("fast");
		jQuery("#page_dim").fadeOut("fast");
	});
	
	//jQuery(".body_full").hide(); THIS LINE IS DONE WITH CSS, IF YOU FIND A WAY TO BIND THIS EVENT TO DYNAMIC CONTENT, PLEASE LET ME KNOW. JAKE.
		
	jQuery(".message_container .text").live('click', function() {
		if($(".body", this).is(':visible')) {
			$(".body_full", this).show();
			$(".body", this).hide();
			messageId = $(this).attr('id');
			$.get("../call.php?f=message_functions&action=read&messageId=" + messageId);
			$(".unread", this).attr('class', 'read');
		} else {
			$(".body_full", this).hide();
			$(".body", this).show();
		}
	});
	
	jQuery(".create_group_button").click(function() {
		jQuery(".create_group_form_container").css("top", ( jQuery(window).height() - jQuery(".create_group_form_container").height() ) / 2 + "px");
		jQuery(".create_group_form_container").css("left", ( jQuery(window).width() - jQuery(".create_group_form_container").width() ) / 2 + "px");
		jQuery(".create_group_form_container").fadeIn("fast");
		jQuery("#page_dim").fadeTo("fast", 0.7);
		document.getElementById("gname").focus();
	});
	
	jQuery(".create_group_form_container .close").click(function() {
		jQuery(".create_group_form_container").fadeOut("fast");
		jQuery("#page_dim").fadeOut("fast");
	});
		
});

function showReplyBox(id) {
	   	jQuery("#comment_reply .inner").load(baseurl + "cp_components/functions/comment_reply.php?id=" + id + "&baseurl=" + baseurl);
    	jQuery("#comment_reply").css("top", ( jQuery(window).height() - jQuery("#comment_reply").height() ) / 2 + "px");
    	jQuery("#comment_reply").css("left", ( jQuery(window).width() - jQuery("#comment_reply").width() ) / 2 + "px");
		jQuery("#comment_reply").fadeIn("fast");
		jQuery("#page_dim").fadeTo("fast", 0.7);
		document.getElementById("comment_text").focus();
	}
	
	function showGroupReplyBox(id, gid) {
	   	jQuery("#comment_reply .inner").load(baseurl + "cp_components/forms/group_status_reply_form.php?id=" + id + "&gid=" + gid + "&baseurl=" + baseurl);
    	jQuery("#comment_reply").css("top", ( jQuery(window).height() - jQuery("#comment_reply").height() ) / 2 + "px");
    	jQuery("#comment_reply").css("left", ( jQuery(window).width() - jQuery("#comment_reply").width() ) / 2 + "px");
		jQuery("#comment_reply").fadeIn("fast");
		jQuery("#page_dim").fadeTo("fast", 0.7);
		document.getElementById("comment_text").focus();
	}
	
	
	
	
function charRem(field, result,	limit) {
	if (field.value.length > limit)
	field.value =field.value.substring(0, limit);
	else
	document.getElementById(result).innerHTML = limit - field.value.length;
}

function isDefined(variable) {
	if ( typeof( window[ variable ] ) != "undefined" ) {
   		return true;
   	} 
	else {
   		return false;
   }
}
