<?php
add_required_class("Message.Class.php", MODEL);
add_required_class("Scaffold.Class.php", SCAFFOLD);
//dates_data	2010-5-3|2010-5-10|2010-5-17|2010-5-24|2010-5-31
//day_off_request_message	I would like to propose a shift trade with you.
//day_off_request_title	title
//day_off_type	3
//process	ajax

$dates = $_REQUEST["dates_data"];
$title = $_REQUEST["day_off_request_title"];
$text = $_REQUEST["day_off_request_message"];
$dayOffType = $_REQUEST["day_off_type"];
$recipients = array();
$attachments = null;

$regex = "/\|/";
$datesData = preg_split($regex,$dates);
$format = "Y-m-d";
$datesOutput = array();
$message = new Message(ApplicationController::getInstance());

//$this->firstDay = date_create("{$this->year}-{$this->mon}-01");

// step 1: insert the shift request

$application = ApplicationController::getInstance();
$user = $application->getUser();
$database = $application->getDatabaseConnection();
$factory = ScaffoldFactory::getInstance($database);
$options = array();

// first, got to get the employee that represents the user
// this is important because a user is not necessarily an employee
$options["conditions"] = array("userId" => $user->id);
$employee = $factory->buildScaffoldObject("Employees");
$employee->find("all", $options);
if("" == $employee->employeeId){
	throw new Exception("According to our records, you are not recorded as an employee. Please speak to your manager to request time off.");
}

// now we get the department assignment, this seems odd but this associates an employee to a department
// and a department has a manager, whic is automatically scaffolded so we get all the info we need here.
$department = $factory->buildScaffoldObject("EmployeeDepartmentAssignments");
$options["conditions"] = array("employeeId" => $employee->employeeId);
$department->find("all", $options);

// pull out the department ...
$obj = $department->getRelatedObjectForKey("departmentId");

// now grab the manager object
$manager = $obj->getRelatedObjectForKey("manager");
if(null == $manager){
	throw new Exception("Could not find a manger for the department.  Cannot complete your request for time off.");
}
$managerUser = $manager->getRelatedObjectForKey("userId");
$recipients[] = $managerUser->userId;

// so far we have the manager, so we can send the appropriate person a message!
// so, now we can get the shifts and create the request

$shift = $factory->buildScaffoldObject("Shifts");
$shift->employeeId = $employee->employeeId;
$shift->shiftTypeId = $dayOffType;
$shift->scheduledBy = $employee->employeeId;
$shift->approvedBy = $manager->employeeId; // BUG: need to create this as null-able
foreach($datesData as $theDate){
	$shift->scheduledDate = $theDate;
	$shift->add();
	// this is stuff to build the message
	$temp = date_create($theDate);
	$info = getdate($temp->format("U"));
	$datesOutput[] = $info["weekday"] . " " . $info["month"] . " " . $info["mday"] . ", " . $info["year"];
}

// step 2: send the message!
$output = "<ul>";
foreach($datesOutput as $do){
	$output .= "<li>{$do}</li>";
}
$output .= "</ul>";

$text .= "<br/>{$output}";
try{
	$message->sendMessage($recipients, $title, $text, $attachments);
	$output = "<success>true</success>";
} catch( Exception $error){
	$output = "<error>" . $error->getMessage() . "</error>";
}
echo $output;
?>