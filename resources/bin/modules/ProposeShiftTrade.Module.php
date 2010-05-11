<?php
?>
<div id="shift_trade_request_container">
	<div id="propose_shift_trade_request" class="step_1">
		<h3>Step 1: Select a person to trade with:</h3>
		<p>
			Possible Matches:
			<label>MiMi</label><input type="radio" name="possible_match" value="MiMi"/>
			<label>Nate</label><input type="radio" name="possible_match" value="Nate"/>
			<label>Mike</label><input type="radio" name="possible_match" value="Mike"/>
			<label>Linda</label><input type="radio" name="possible_match" value="Linda"/>
			<label>Carl</label><input type="radio" name="possible_match" value="Carl"/>
		</p>
	</div>
	<div id="send_shift_trade_request" class="step_2">
		<h3>Step 2: Send the Trade Request:</h3>
		<p>
			<label for="message_title">Title</label><input type="text" name="message_title" id="message_title" value="title..." />
		</p>
		<p>
			<label for="message_text">Message</label><textarea cols="15" rows="15" id="message_text" name="message_text">enter you message here</textarea>
		</p>
		<p>
			<input type="submit" name="submit" value="Send Trade Request"/>
		</p>
	</div>
	<div id="propose_shift_trade_alternate_actions">
		<ul>
			<li><a href="PickADifferentDayPage">Pick a Different Day to Trade</a></li>
			<li><a href="PickADifferentDayForPage">Pick a Different Day to Trade For</a></li>
			<li><a href="StartOverPage">Start Over</a></li>
			<li><a href="CancelPage">Cancel Trade</a></li>
		</ul>
	</div>
</div>