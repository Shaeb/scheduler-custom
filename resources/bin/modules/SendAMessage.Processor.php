<?php
add_required_class("Message.Class.php", MODEL);
//    [module] => SendAMessageModule
//    [action] => process
//    [process] => ajax
//    [send_a_message_to] => 
//    [as_values_062] => blasterman,
//    [send_a_message_title] => title!
//    [send_a_message_message] => I would like to propose a shift trade with you.
//    [submit] => Send Your Message
//    [PHPSESSID] => aa24531aa8debe0b4efabe853b9bd2fe

$message = new Message(ApplicationController::getInstance());
$title = $_REQUEST["send_a_message_title"];
$text = $_REQUEST["send_a_message_message"];
$attachments = null;
$keys = array_keys($_REQUEST);
$regex = "/^as\_values\_/";
foreach($keys as $key){
	if(preg_match($regex,$key)){
		$recipients = $_REQUEST[$key];
		$recipients = preg_replace("/\,$/", "", $recipients);
		$recipients = preg_split("/\,/",$recipients);
	}
}
$output = "";
try{
	$message->sendMessage($recipients, $title, $text, $attachments);
	$output = "<success>true</success>";
} catch( Exception $error){
	$output = "<error>" . $error->getMessage() . "</error>";
}
echo $output;
?>