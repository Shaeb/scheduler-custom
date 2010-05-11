<?php
$title = "View Proposed Trades";
$message = "";
$tradeButtons = <<<END
<ul>
	<li><input type="button" name="accept_trade" value="Accept Trade"/></li>
	<li><input type="button" name="reject_trade" value="Reject Trade"/></li>
	<li><input type="button" name="preview_trade" value="Preview Trade"/></li>
	<li><input type="button" name="offer_counter_proposal" value="Offer Counter Proposal"/></li>
</ul>
END;
echo '<div class="view_proposed_trades_container"><h3>' . $title . '</h3><p class="view_proposed_trades_message">' . $message . '</p>';

for( $i = 0; $i < 5; ($i++)){
	$trade = $i + 7;
	echo "<hr/><p>Person X has asked to trade the following days with you:<br/>";
	echo "If you work August {$i}, 2010 for me I will work August {$trade}, 2010 for you.</p>";
	echo $tradeButtons;
}
echo "</div>";
?>