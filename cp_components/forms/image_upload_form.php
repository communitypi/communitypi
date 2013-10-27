<?php
$communitypi = new communitypi;
?>
Photos are limited to <?php echo round((int)$communitypi->getSetting('maxuploadfilesize')/1000000); ?>MB
<form id="photo_upload_form" action="<?php echo $communitypi->getSetting('baseURL'); ?>photo_details" method="post" enctype="multipart/form-data" onsubmit="return validateUploadForm(this);">
<div id="uploadFormError" ></div>
<div id="buttonContainer1" class="buttonContainer1"><input id="photo1" type="file" name="photo1"></div>
<input type="hidden" id="photoUploadId" value="2">
<div id="uploadButtons"></div>
<br />
<input id="addAnother" type="button" value="Add another" onClick="addPhotoUpload(); return false">
<input id="photoUploadSubmit" type="submit" value="Upload">
<div id="message"><img src="<?php echo $communitypi->getSetting('baseURL'); ?>cp_images/loader_icon.gif" />uploading...</div>
</form>


<script>
function validateUploadForm(form) {
	if ( form.photo1.value == "" )
	{
		document.getElementById('uploadFormError').innerHTML = "Please choose a file to upload";
		return false;
	}
	else
	{
		$("#message").fadeIn("slow");
		return true;
	}
	
	
}

function addPhotoUpload() {
	var id = document.getElementById("photoUploadId").value;
	if ( id == '6' ) 
	{
		$("#uploadButtons").append("<div id=\"enoughUploads\" class=\"buttonContainer\">I think that's enough for now</div>");
		 $("#enoughUploads").slideDown("slow");
		
	} 
	else if ( id < 6 )
	{
		$("#uploadButtons").append("<div id=\"buttonContainer" + id + "\" class=\"buttonContainer\"><input type=\"file\" name=\"photo" + id + "\"></div>");
	}
	
	 $("#buttonContainer" + id).slideDown("slow");
	id = ( id - 1 ) + 2;
	document.getElementById("photoUploadId").value = id;
}
</script>
