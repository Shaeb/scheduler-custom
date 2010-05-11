<?php
?>
<div id="day_off_request_container">
	<div id="day_off_request_preview_message_container">
		<h3>Preview Message</h3>
		<h4>dynamic message title</h4>
		<p>
			this is the preview message body.  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut quis erat ut nunc eleifend fermentum non vel mauris. Sed bibendum aliquam odio, eu consectetur lacus tempor nec. Sed at sem libero, in rutrum urna. Morbi velit turpis, auctor quis porttitor quis, gravida quis arcu. Vivamus faucibus malesuada fringilla. 
		</p>
		<p class="day_off_request_preview_message_signature">First Name + Last Name</p>
	</div>
	<div id="day_off_request_message_form">
		<form method="POST" action="../ajax/DayOffRequest.Module/process">
			<input type="hidden" name="process" value="ajax"/>
			<label for="day_off_request_title">Message Title</label><input type="text" name="day_off_request_title" id="day_off_request_title"/>
			<br/>
			<label for="day_off_request_message">Message</label>
			<textarea name="day_off_request_title" id="day_off_request_title">I would like to propose a shift trade with you.</textarea>
			<br/>
			<input type="submit" name="submit" value="Send Trade Request"/>
		</form>
	</div>
</div>