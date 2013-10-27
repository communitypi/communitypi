this.profilePreview = function(){	
	/* CONFIG */
		
		xOffset = 10;
		yOffset = 30;
		
		
	/* END CONFIG */
	/*
	jQuery("a.mminiprofile").live('hover', function(e) {
	//$("a.miniprofile").hover(function(e){
		$("body").append("<p id='miniprofile'><img src='http://social.inozzo.com/communitypi/cp_images/ajax-loader-1.gif' /></p>");
		$('#miniprofile').load('cp_components/functions/mini_profile.php?id=' + this.rel);								 
		$("#miniprofile")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset) + "px")
			.fadeIn("fast");						
    },
	function(){
		this.title = this.t;	
		$("#miniprofile").remove();
    });	
	$("a.miniprofile").mousemove(function(e){
		$("#miniprofile")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset) + "px");
	});	
	
	*/
	jQuery("a.miniprofile").live('mouseover mousemove mouseout', function(e) {
			if (e.type == 'mouseover') {
				$("body").append("<p id='miniprofile'><img src='cp_images/loader_icon.gif' /></p>");
				$('#miniprofile').load('cp_components/functions/mini_profile.php?id=' + this.rel);
				$("#miniprofile")
					.css("top",(e.pageY - xOffset) + "px")
					.css("left",(e.pageX + yOffset) + "px")
					.fadeIn("fast");
			} else if (e.type =='mousemove') {
				$("#miniprofile")
					.css("top",(e.pageY - xOffset) + "px")
					.css("left",(e.pageX + yOffset) + "px");
			} else {
				$("#miniprofile").fadeOut("fast");
				$('#miniprofile').innerHTML = "<img src='cp_images/ajax-loader-2.gif' />";
			}
		});
		
		jQuery(".comment_name a.profilename").live('mouseover mousemove mouseout', function(e) {
			if (e.type == 'mouseover') {
				$("body").append("<p id='miniprofile'><img src='cp_images/loader_icon.gif' /></p>");
				$('#miniprofile').load('../cp_components/functions/mini_profile.php?id=' + this.rel);
				$("#miniprofile")
					.css("top",(e.pageY - xOffset) + "px")
					.css("left",(e.pageX + yOffset) + "px")
					.fadeIn("fast");
			} else if (e.type =='mousemove') {
				$("#miniprofile")
					.css("top",(e.pageY - xOffset) + "px")
					.css("left",(e.pageX + yOffset) + "px");
			} else {
				$("#miniprofile").fadeOut("fast");
				$('#miniprofile').innerHTML = "<img src='cp_images/ajax-loader-2.gif' />";
			}
		});


		
};


// starting the script on page load
$(document).ready(function(){
	profilePreview();
});