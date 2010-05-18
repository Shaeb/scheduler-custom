<?php
add_required_class("Scaffold.Class.php", SCAFFOLD);

$application = ApplicationController::getInstance();
$user = $application->getUser();
$database = $application->getDatabaseConnection();
$factory = ScaffoldFactory::getInstance($database);
$scaffold = $factory->buildScaffoldObject("ShiftTypes");
$shiftTypes = $scaffold->find("all");
foreach($shiftTypes as $type) {
	$selectOptions .= '<option value="' . $type->shiftTypeId . '">' . "{$type->description}</option>";
} 
?>
<div id="day_off_request_container">
	<div id="day_off_request_preview_message_container">
		<h3>Your requested days off:</h3>
		<div id="day_off_request_icon_container"></div>
	</div>
	<div id="day_off_request_message_container">
		<form method="POST" action="../ajax/RequestTimeOffModule/process" id="day_off_request_message_form">
			<input type="hidden" name="process" value="ajax"/>
			<label for="day_off_request_title">Message Title</label><input type="text" name="day_off_request_title" id="day_off_request_title"/>
			<br/>
			<select name="day_off_type">
<?php
				echo $selectOptions;
?>
			</select>
			<label for="day_off_request_message">Message</label>
			<textarea name="day_off_request_message" id="day_off_request_message">I would like to propose a shift trade with you.</textarea>
			<br/>
			<input type="submit" name="submit" value="Send Trade Request" id="day_off_request_submit" />
		</form>
	</div>
</div>