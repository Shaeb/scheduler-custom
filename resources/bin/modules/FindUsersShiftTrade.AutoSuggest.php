<?php
add_required_class("Scaffold.Class.php", SCAFFOLD);

$dateTrading = $_REQUEST["shift_trade_date_trading"];
$dateTradingFor = $_REQUEST["shift_trade_date_trading_for"];

//$dateTrading = "2010-05-29";
//$dateTradingFor = "2010-05-28";

$application = ApplicationController::getInstance();
$database = $application->getDatabaseConnection();
$currentUser = $application->getUser();
$factory = ScaffoldFactory::getInstance($database);

$shift = $factory->buildScaffoldObject("Shifts");
$employeeScaffold = $factory->buildScaffoldObject("Employees");
//$employeeScaffold = $factory->buildScaffoldObject("Employees");

//$options["filters"] = array("employeeId", "shiftTypeId", "scheduledBy", "approvedBy");
$options["conditions"] = array( "scheduledDate" => "{$dateTradingFor}' and scheduledDate != '{$dateTrading}" /**, "throw" => true**/);
$traderShift = $shift->find("all", $options);
$output = "";

foreach($traderShift as $trader){
	$objects = $employeeScaffold->find($trader->employeeId);
	$employee = $objects[0];
	$user = $employee->getRelatedObjectForKey("userId");
	
	if($user->username != $currentUser->username){
		$output .= "<input type=" . '"radio" name="possible_match" value="' . $user->userId . '"' . "/><label>{$user->username}</label>";
	}
}
echo $output;
?>