<?php 
add_required_class( 'Calendar.Class.php', MODEL );

$instructionMessageForTimeOffRequest = <<<END
<div class="request_time_off_message hide" id="request_time_off_message">
	To request time off please follow these simple steps:
	<ol>
		<li>Select the days you would like to have off.  You can do this by clicking on the days you would like off.</li>
		<li>Once you have clicked all of the days you would like off, click on the link below the calendar "Request Time Off."</li>
		<li>You will see a form pop up.  Please fill it out and hit the "Send Trade Request" button.  This will automatically send a message to the 
		appropriate people to approve your trade request.  A message will also be sent to you so you know the request went through just fine!</li>
	</ol>
	Once your request has been approved or denied, a message will be sent to you.  Hope you enjoy your time off!
</div>
END;

$instructionMessageForShiftTrade = <<<END
<div class="shift_trade_message hide" id="shift_trade_message">
	To propose a shift trade, follow these simple steps:
	<ol>
		<li>Select the <u>day</u> you would like to trade.  You can do this by clicking on the day you would like to trade.</li>
		<li>Next, select the <u>day</u> you would like to trade for.  You can do this by clicking on the day you would like to trade for.</li>
		<li>Once you have clicked the days you would like to swap, click on the link below the calendar "Propose a Shift Trade."</li>
		<li>You will see a form pop up.  A list of possible people to trade with.  Select the person you would like to swap with and fill out the form.
		This will automatically send a message to the appropriate people to approve your trade request.  A message will also be sent to you so you know the 
		request went through just fine!</li>
	</ol>
	Once your request has been approved or denied, a message will be sent to you.  Hope you enjoy your time off!
</div>
END;

$instructionShiftTradeSuccess = <<<END
<div class="greyBorders hide" id="shift_trade_success_message">
	Your message has been sent.  As soon as the other person accepts or rejects the trade request, we will let you know!  Also, if the trade request is accepted
	we will automatically message management so that it gets speedy approval.  We hope you enjoy your day off!
</div>
END;

$instructionShiftTradeProposalSuccess = <<<END
<div class="greyBorders hide" id="shift_trade_proposal_success_message">
	Thanks!  We have sent a message to both management and your colleague with your response.  Thanks for your speedy reply!
</div>
END;

$instructionMessageForScheduling = <<<END
<div class="schedule_message hide greyBorders" id="schedule_instructional_message">
	To schedule your shift, follow these simple steps:
	<ol>
		<li>Select the <u>day</u> you would like to schedule.  You can do this by clicking on the day you would like to schedule.</li>
		<li>Once you have clicked the days you would like to schedule, click on the link below the calendar "Submit and Save."</li>
		<li>You will see a form pop up.  A list of the days you would like to add to your schedule will show up.  We will also make sure that the days you 
		had picked can be put on to your schedule.  If they cannot, then we will show you which days could not be scheduled and remove them from your list.  
		Once you are happy with your schedule, please click the button that says "I would like to save my schedule"  and we will attempt to add those days to 
		you personal schedule.  This will automatically send a message to the appropriate people to approve your schedule.  A message will also be sent to 
		you so you know everything went through just fine!</li>
	</ol>
	Once your request has been approved or denied, a message will be sent to you.  Hope you enjoy your time off!
</div>
END;

$scheduleSuccessMessage = <<<END
<div class="schedule_message hide greyBorders" id="schedule_success_message">
	We have successfully submitted the dates you chose to schedule.  We have already sent a message to managment to get your schedule approved.  Once we know 
	the status of your new schedule then we will send you an update message.
</div>
END;

echo "<h3>Calendar for: </h3>";
echo '<img src="../resources/images/printer.png" id="calendar_icons icons_printer" />';
echo '<p id="dynamic_instructional_text">' . $instructionMessageForTimeOffRequest . $instructionMessageForShiftTrade . $instructionShiftTradeSuccess . 
	$instructionShiftTradeProposalSuccess . $instructionMessageForScheduling . $scheduleSuccessMessage . '</p>';

// this needs to be dynamically generated
echo <<<END
<div id="calendar_department_selector_container">
	<select name="calendar_deparment_selector" id="calendar_department_selector">
		<option>MSU</option>
		<option>TMU</option>
		<option>CCU</option>
		<option>PCU</option>
		<option>SCU</option>
	</select>
</div>
END;
echo <<<END
<div id="calendar_fliter_selector_container">
	<select name="calendar_filter_selector" id="calendar_filter_selector">
		<option value="filter_current_schedule">My current schedule</option>
		<option value="filter_days_off">My days off</option>
		<option value="filter_vacation">My vacation days</option>
		<option value="filter_proposed_shift_changes">Proposed shift changes</option>
		<option value="filter_required_events">Required events</option>
	</select>
</div>
END;
$calendar = new Calendar();
$calendar->buildCalendar();
echo $calendar->getOutput();
?>