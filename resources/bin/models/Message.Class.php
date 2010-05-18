<?php
add_required_class("Scaffold.Class.php", SCAFFOLD);

class Message {
	private $application;
	private $user;
	private $database;
	private $scaffold;
	private $factory;
	private $id;
	 
	public function __construct(ApplicationController $application){
		if(!isset($application)){
			throw new Exception( "ApplicationController is not set");
		}
		$this->application = ApplicationController::getInstance();
		$this->user = $this->application->getUser();
		$this->database = $this->application->getDatabaseConnection();
		$this->factory = ScaffoldFactory::getInstance($this->database);
		$this->scaffold = $this->factory->buildScaffoldObject("Messages");
	}
	
	public function findMessages(){
		return $this->scaffold->find("all", array("order_by" => "sent", "conditions" => array("sentBy" => $this->user->id)));
	}
	
	public function sendMessage($recipients, $title, $message, $attachments){
		if(0 == count($recipients)){
			throw new Exception("No recipients added to the message");
		}
		$this->scaffold->sentBy = $this->user->id;
		$this->scaffold->title = $title;
		$this->scaffold->message = $message;
		$this->scaffold->add();
		$this->id = $this->database->getID();
		$this->scaffold->find($this->id);
		
		$recipientScaffold = $this->factory->buildScaffoldObject("MessageRecipients");
		$recipientScaffold->messageId = $this->id;
		foreach($recipients as $recipient){
			$recipientScaffold->recipient = $recipient;
			$recipientScaffold->add();
		}
	}
	
	public function getId(){
		return $this->id;
	}
}
/*******
$message = new Message(ApplicationController::getInstance());
$title = "This is a test message";
$text = "this is a test message.  This is only a test message.  If this were a real message, it would not be one that repeatedly states that it is a test message.";
$recipients = array("2");
$attachments = null;
for($i = 0; $i < 5; ($i++)){
	$message->sendMessage($recipients, $title, $text, $attachments);
}
$newMessages = $message->findMessages();
echo "<hr/>";
echo "<table><tr><td>message id</td><td>sent by</td><td>title></td><td>message</td><td>sent</td></tr>";
foreach($newMessages as $new){
	echo "<tr><td>{$new->messageId}</td><td>{$new->sentBy}</td><td>{$new->title}</td><td>{$new->message}</td><td>{$new->sent}</td></tr>";
}
echo "</table>";
*****/
?>