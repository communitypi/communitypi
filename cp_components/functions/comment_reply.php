<form name="comment_reply_form" id="comment_reply_form" action="<?php echo $_GET['baseurl']; ?>call.php?f=reply_post&id=<?php echo $_GET['id']; ?>" method="post">
<h2 style="margin-top: -20px;">Post Reply</h2>
<div id="reply_charRem">140</div>
<textarea id="comment_text" name="comment_text" cols="60" rows="2" style="width: 440px;" onkeydown="charRem(this, 'reply_charRem', 140);sendUpdate(event);" onkeyup="charRem(this, 'reply_charRem', 140);"></textarea><br />
<input type="submit" id="submit_reply" value="Post Comment" onclick="document.getElementById('comment_reply_form').style.display = 'none';" /></p>
</form>

