<?php
add_required_class("Message.Class.php", MODEL);
add_required_class("Scaffold.Class.php", SCAFFOLD);

$application = ApplicationController::getInstance();
$user = $application->getUser();
$database = $application->getDatabaseConnection();
$factory = ScaffoldFactory::getInstance($database);

function getAllMessages($factory,$user){
	$options = array();
	$options["conditions"] = array("recipient" => $user->id);
	$messageRecipients = $factory->buildScaffoldObject("MessageRecipients");
	$received = $messageRecipients->find("all",$options);
	$output = '<div id="messages_' . $user->id . '">';
	foreach($received as $item){
		$message = $item->getRelatedObjectForKey("messageId");
		$title = '<h4 class="message_title">' . "'{$message->title}'</h4>";
		$output .= "<p> from {$user->username} @{$message->sent}</p><p>{$message->message}</p>";
	}
	$output .= "</div><hr/>";
	return $output;
}

function getSentMessages(){
	$message = new Message(ApplicationController::getInstance());
	$messages = $message->findMessages();
	$output = '<div id="messages_' . $message->getId() . '">';
	foreach($messages as $new){
		$user = $new->getRelatedObjectForKey("sentBy");
		$title = '<h4 class="message_title">' . "'{$new->title}'</h4>";
		$output .= "<p> from {$user->username} @{$new->sent}</p><p>{$new->message}</p>";
	}
	$output .= "</div><hr />";
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
<div class="panes" id="messages"">
	<div><?php echo getAllMessages($factory,$user);?></div>
	<div>2. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc ac odio at felis malesuada pulvinar pellentesque eget justo. Donec blandit gravida semper.</div>
	<div>3. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc ac odio at felis malesuada pulvinar pellentesque eget justo. Donec blandit gravida semper.</div>
	<div>
		<?php echo getSentMessages(); ?>
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