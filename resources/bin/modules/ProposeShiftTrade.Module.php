<?php
?>
<div id="shift_trade_request_container">
	<form method="POST" action="../ajax/ProposeShiftTradeModule/process" id="shift_trade_proposal_form">
		
		<div id="shift_trade_icon_container">
		<h3>Day you are trading:</h3>
		<div id="shift_trade_trading_icon_container"></div>
		<h3>Day you are trading for:</h3>
		<div id="shift_trade_trading_for_icon_container"></div>
		</div>
		<div id="propose_shift_trade_request" class="step_1">
			<h3>Step 1: Select a person to trade with:</h3>
			<p id="shift_trade_match_container">
				Possible Matches:
				<img src="../resources/images/loading.gif" />
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
				<input type="submit" name="propose_shift_trade_submit" id="propose_shift_trade_submit" value="Send Trade Request"/>
			</p>
		</div>
	</form>
	<div id="propose_shift_trade_alternate_actions">
		<ul>
			<li><a href="PickADifferentDayPage">Pick a Different Day to Trade</a></li>
			<li><a href="PickADifferentDayForPage">Pick a Different Day to Trade For</a></li>
			<li><a href="StartOverPage">Start Over</a></li>
			<li><a href="CancelPage">Cancel Trade</a></li>
		</ul>
	</div>
</div>