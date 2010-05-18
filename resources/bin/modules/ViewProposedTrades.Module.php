<?php
add_required_class("Scaffold.Class.php", SCAFFOLD);

$application = ApplicationController::getInstance();
$user = $application->getUser();
$database = $application->getDatabaseConnection();
$factory = ScaffoldFactory::getInstance($database);
$options = array();

$options["conditions"] = array("userId" => $user->id);
$scaffold = $factory->buildScaffoldObject("Employees");
$scaffold->find("all", $options);
$employee = $scaffold->me();

$scaffold = $factory->buildScaffoldObject("ShiftTrades");
$options["conditions"] = array("tradee" => $employee->employeeId, "status" => 0 /**, "throw" => true **/);
$shiftTrades = $scaffold->find("all", $options);
$title = "View Proposed Trades";
$message = "";

$i = 0;
echo '<div class="view_proposed_trades_container" id="view_proposed_trades_container"><h3>' . $title . '</h3><p class="view_proposed_trades_message">' . $message . '</p>';
foreach($shiftTrades as $shiftTrade){
	// find the user ...
	$employee = $shiftTrade->getRelatedObjectForKey("trader");
	$trader = $employee->getRelatedObjectForKey("userId");
	$tradingShift = $shiftTrade->getRelatedObjectForKey("tradingShift");
	$tradingForShift = $shiftTrade->getRelatedObjectForKey("tradingForShift");
	
	$i = $shiftTrade->shiftTradeId;
	$tradeButtons = <<<END
<form method="POST" action="../ajax/ViewProposedTradesModule/process" name="shift_trade_form" class="shift_trade_form" id="shift_trade_form_{$i}">
	<input type="hidden" name="shift_trade_id" value="{$i}" />
	<input type="hidden" name="shift_trade_action" id="shift_trade_action_{$i}" />
	<ul>
		<li><input type="submit" name="accept_trade" class="accept_trade_button shift_trade_buttons" value="Accept Trade" id="accept_trade_button_{$i}"/></li>
		<li><input type="submit" name="reject_trade" class="reject_trade_button shift_trade_buttons" value="Reject Trade" id="reject_trade_button_{$i}"/></li>
		<li><input type="submit" name="preview_trade" class="preview_trade_button shift_trade_buttons" value="Preview Trade" id="preview_trade_button_{$i}"/></li>
		<li><input type="submit" name="offer_counter_proposal" class="offer_counter_button shift_trade_buttons" value="Offer Counter Proposal" id="offer_counter_button_{$i}"/></li>
	</ul>
</form>
END;
	echo "<hr/><p>{$trader->username} has asked to trade the following days with you:<br/>";
	echo "If you work {$tradingShift->scheduledDate} for me I will work {$tradingForShift->scheduledDate} for you.</p>";
	echo $tradeButtons;
}
echo "</div>";
?>