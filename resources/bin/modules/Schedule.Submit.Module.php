<?php
// element schedule_shift_type must be dynamic in the future
?>
<div id="schedule_container">
	<div id="schedule_message_container">
		<h3>Your requested work schedule:</h3>
		<div id="schedule_icon_container"></div>
	</div>
	<div id="schedule_form_container">
		<form method="POST" action="../ajax/ValidateScheduleDatesModule/process" id="schedule_form">
			<input type="hidden" name="process" value="ajax"/>
			<input type="hidden" name="schedule_dates" id="schedule_dates_input" />
			<input type="hidden" name="schedule_shift_type" id="schedule_shift_type" value="2"/>
			<div id="schedule_results">
			<p>
				We are attempting to verify that the days you have selected can be scheudled.
				<img src="../resources/images/loading.gif" id="schedule_loading_image">
			</p>
			</div>
			<div id="schedule_action_buttons">
				<input type="submit" name="submit" value="I would like to save my schedule." id="schedule_submit" />
				<input type="submit" name="submit" value="I would like to cancel my submission." id="schedule_cancel" />
			</div>
		</form>
	</div>
</div>