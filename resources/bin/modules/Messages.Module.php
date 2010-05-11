<?php
function getNewMessages(){
	$output = "<dl>";
	// in reality, this would have a message id associated with it
	$title = '<dt class="message_title">Message from User X @04/01/2010 01:00</dt>';
	for($i = 0; $i < 5; ($i++)){
			$output .= "{$title}<dd>Hey dude, your fly is open.</dd>";
	}
	$output .= "</dl>";
	return $output;
}
$title = "Messages";
$message = "[dynamic instructional messages]";
?>
<h2><?php echo $title; ?></h2>
<div id="messages_dynamic_message"><?php echo $message; ?></div>
<select name="messages_sort" id="message_sort">
	<option value="all" selected="true">All Messages</option>
	<option value="new">New Messages</option>
	<option value="time">Time</option>
	<option value="department">Department</option>
	<option value="management">Management</option>
</select>
<ul class="tabs">
	<li><a href="../pages/ViewAllMessagesPage">All Messages</a></li>
	<li><a href="../pages/ViewNewMessagesPage">New Messages</a></li>
	<li><a href="../pages/ViewStarredMessagesPage">Starred Messages</a></li>
	<li><a href="../pages/ViewSentMessagesPage">Sent Messages</a></li>
</ul>
<div class="panes">
	<div><?php echo getNewMessages(); ?></div>
	<div>2. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc ac odio at felis malesuada pulvinar pellentesque eget justo. Donec blandit gravida semper.</div>
	<div>3. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc ac odio at felis malesuada pulvinar pellentesque eget justo. Donec blandit gravida semper.</div>
	<div>
		<p>4. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc ac odio at felis malesuada pulvinar pellentesque eget justo. Donec blandit gravida semper.</p>
		<p><img src="../resources/images/page_word.png" /><img src="../resources/images/page_word.png" /><img src="../resources/images/page_word.png" /><img src="../resources/images/page_word.png" /></p>
	</div>
</div>
<div id="messages_pagination">
	<ul>
		<li><a href="../page/MessagesPage/1">1</a></li>
		<li><a href="../page/MessagesPage/2">2</a></li>
		<li><a href="../page/MessagesPage/3">3</a></li>
		<li><a href="../page/MessagesPage/4">4</a></li>
		<li><a href="../page/MessagesPage/5">5</a></li>
	</ul>
</div>
<hr/>
<p>
	<input type="button" name="messages_compose_message_button" id="messages_compose_message_button" value="Compose a Message"/>
</p>