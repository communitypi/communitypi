<?php 
//Lopurl API
// SET $url to the url you want to lop
  if($_POST['url']) {
    $url = $_POST['url'];
$file = "http://ur.cx/api/create.php?url=$url"; 
$fh = fopen($file, 'r'); 
$contents = fread($fh, 30); 
$contentsf = rtrim($contents);
echo "<script>textbox = document.getElementById('text'); textbox.value = textbox.value + '" . $contentsf. "'; </script>";
fclose($fh); 
}
?>
<form method="post" name="srturl" action="" onsubmit="this.action = 'index.php?status_message=' + document.getElementById('text').value;">
<input type="text" id="txtbxShortenUrl" name="url"/>
</form>