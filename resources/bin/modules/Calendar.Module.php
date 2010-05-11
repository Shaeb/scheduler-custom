<?php 
add_required_class( 'Calendar.Class.php', MODEL );

echo "<h3>Calendar for: </h3>";
echo '<img src="../resources/images/printer.png" id="calendar_icons icons_printer" />';
echo '<p id="dynamic_instructional_text"></p>';

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