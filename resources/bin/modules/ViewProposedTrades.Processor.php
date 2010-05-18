<?php
add_required_class("Scaffold.Class.php", SCAFFOLD);
add_required_class("Message.Class.php", MODEL);

$status = array("preview_trade" => 0, "accept_trade" => 1, "reject_trade" => 2, "offer_counter_proposal" => 3 );
$messageStatusOptions = array("preview_trade" => 0, "accept_trade" => "accepted", "reject_trade" => "rejected", "offer_counter_proposal" => 3 );
//shift_trade_action	accept_trade
//shift_trade_id	1

$shiftTradeId = $_REQUEST["shift_trade_id"];
$shiftTradeAction = $_REQUEST["shift_trade_action"];

//$shiftTradeId = "1";
//$shiftTradeAction = "accept_trade";
$statusValue = $status[$shiftTradeAction];
$messageStatus = $messageStatusOptions[$shiftTradeAction];

$application = ApplicationController::getInstance();
$user = $application->getUser();
$database = $application->getDatabaseConnection();
$message = new Message($application);
$factory = ScaffoldFactory::getInstance($database);
$options = array();

$scaffold = $factory->buildScaffoldObject("ShiftTrades");
$objects = $scaffold->find($shiftTradeId, $options);

if(0 < count($objects)){
	// step 1: update status
	$shiftTrade = $objects[0];
	$shiftTrade->status = $statusValue;
	$shiftTrade->update();

	// step 2: send a message to the manager
	$trader = $shiftTrade->getRelatedObjectForKey("trader");
	$tradee = $shiftTrade->getRelatedObjectForKey("tradee");
	$tradingShift = $shiftTrade->getRelatedObjectForKey("tradingShift");
	$tradingForShift = $shiftTrade->getRelatedObjectForKey("tradingForShift");
	
	$objects = null;
	$scaffold = $factory->buildScaffoldObject("EmployeeDepartmentAssignments");
	$options["conditions"] = array("employeeId" => $trader->employeeId);
	$objects = $scaffold->find("all", $options);
	if(0 < count($objects)){
		$assignment = $objects[0];
		$department = $assignment->getRelatedObjectForKey("departmentId");
		$manager = $department->getRelatedObjectForKey("manager");
		$recipients = array($trader->userId, $tradee->userId, $manager->userId);
		$title = "Your updated trade request status.";
		$text = 
		"{$user->username} has {$messageStatus} your trade request.  As a reminder, you asked {$user->username} to work {$tradingShift->scheduledDate} for you
		and that you would work {$tradingForShift->scheduledDate} for {$user->username}.";
		
		$message->sendMessage($recipients, $title, $text, $attachments);
	}
}

echo "<success>true</success>";
?>