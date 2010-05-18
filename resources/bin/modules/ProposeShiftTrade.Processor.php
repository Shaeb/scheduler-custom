<?php
add_required_class("Message.Class.php", MODEL);
add_required_class("Scaffold.Class.php", SCAFFOLD);

//message_text	enter you message here
//message_title	title...
//possible_match	MiMi
//shift_trade_date_trading	2010-5-24
//shift_trade_date_trading_for...	2010-5-25
//submit	Send Trade Request

$result = "false";

$title = $_REQUEST["message_title"];
$text = $_REQUEST["message_text"];
$tradee = $_REQUEST["possible_match"];
$dateTrading = $_REQUEST["shift_trade_date_trading"];
$dateTradingFor = $_REQUEST["shift_trade_date_trading_for"];
//$dateTrading = $_REQUEST["shift_trade_date_trading"] . " 00:00:00";
//$dateTradingFor = $_REQUEST["shift_trade_date_trading_for"] . " 00:00:00";

$application = ApplicationController::getInstance();
$user = $application->getUser();
$database = $application->getDatabaseConnection();
$factory = ScaffoldFactory::getInstance($database);
$message = new Message($application);
$options = array();

//$tradee = "3";
//$dateTrading = "2010-05-29";
//$dateTradingFor = "2010-05-28";
$attachments = null;

// gotta get the employee
// step 1: validate that the dates can be traded

// gotta get the employee
$employee = $factory->buildScaffoldObject("Employees");
$options["conditions"] = array("userId" => $user->id);
$employeeTrader = $employee->find("all", $options);
$employeeTrader = $employeeTrader[0];

// gotta get the shift
$shift = $factory->buildScaffoldObject("Shifts");
$options["conditions"] = array("employeeId" => $employeeTrader->employeeId, "scheduledDate" => $dateTrading);
$traderShift = $shift->find("all", $options);
$traderShift = $traderShift[0];

// okay now getting the info on the person we are trading with ...
$options["conditions"] = array("userId" => $tradee);
$employeeTradee = $employee->find("all", $options);
$employeeTradee = $employeeTradee[0];

$recipients = array($employeeTradee->userId);

// gotta get the shift
//$tradeeShift = $factory->buildScaffoldObject("Shifts");
$options["conditions"] = array("employeeId" => $employeeTradee->employeeId, "scheduledDate" => $dateTradingFor);
$tradeeShift = $shift->find("all", $options);
$tradeeShift = $tradeeShift[0];

$traderShiftTrue = preg_match("/^{$dateTrading}/",$traderShift->scheduledDate);
$tradeeShiftTrude = preg_match("/^{$dateTradingFor}/",$tradeeShift->scheduledDate);
$tradeeShiftNotTruee = !preg_match("/^{$dateTrading}/",$tradeeShift->scheduledDate);

if( $traderShiftTrue && $tradeeShiftTrude && $tradeeShiftNotTruee ){
	// step 2:  we now have to insert the shift trade request
	$shiftTrade = $factory->buildScaffoldObject("ShiftTrades");
	$shiftTrade->trader = $employeeTrader->employeeId;
	$shiftTrade->tradee = $employeeTradee->employeeId;
	$shiftTrade->tradingShift = $traderShift->shiftId;
	$shiftTrade->tradingForShift = $tradeeShift->shiftId;
	$shiftTrade->approvedBy = $employeeTrader->employeeId;

	if($shiftTrade->add()){
		// step 3:  we now have to message both users
		$message->sendMessage($recipients, $title, $text, $attachments);
		$result = "true";
	}
} else {
//	echo ($dateTrading == $traderShift->scheduledDate) . "<br/>";
//	echo ($dateTradingFor == $tradeeShift->scheduledDate) . "<br/>";
//	echo ($dateTrading != $tradeeShift->scheduledDate) . "<br/>"; 
}

//	NOTE:  we will _not_ message management regarding the shift trade request at this point.
//		that functionality will occur _after_ the request has been accepted by the tradee
//$formatted_number = "3529788639@txt.att.net";
//mail($formatted_number, "SMS", "Hello, Nate!");

echo "<success>{$result}</success>";
?>