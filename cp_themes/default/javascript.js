$(document).ready(function() {
	$('#global_message').fadeIn('slow');
	setTimeout(function(){$('#global_message').fadeOut('slow',function(){})},3000);
	
	$('#global_message').live('ready', function() {
		$(this).fadeIn('slow');
		setTimeout(function(){$(this).fadeOut('slow',function(){})},3000);
	});
	
});


