<?php
?>
<div id="send_a_message_container">
	<div id="send_a_message_preview_message_container">
		<h3>Preview Message</h3>
		<h4>dynamic message title</h4>
		<p>
			this is the preview message body.  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut quis erat ut nunc eleifend fermentum non vel mauris. Sed bibendum aliquam odio, eu consectetur lacus tempor nec. Sed at sem libero, in rutrum urna. Morbi velit turpis, auctor quis porttitor quis, gravida quis arcu. Vivamus faucibus malesuada fringilla. 
		</p>
		<p class="send_a_message_preview_message_signature">First Name + Last Name</p>
	</div>
	<div id="send_a_message_message_form">
		<form method="POST" action="../ajax/SendAMessage.Module/process">
			<input type="hidden" name="process" value="ajax"/>
			<label for="send_a_message_to">To</label><input type="text" name="send_a_message_to" id="send_a_message_to"/>
			<label for="send_a_message_title">Message Title</label><input type="text" name="send_a_message_title" id="send_a_message_title"/>
			<br/>
			<label for="send_a_message_message">Message</label>
			<textarea name="send_a_message_title" id="send_a_message_title">I would like to propose a shift trade with you.</textarea>
			<br/>
			<input type="submit" name="preview" id="send_a_message_preview_button" value="Preview Your Message"/>
			<input type="submit" name="submit" id="send_a_message_button" value="Send Your Message"/>
		</form>
	</div>
</div>