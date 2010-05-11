<?php
//$query = mysql_query("SELECT * FROM my_table WHERE my_field LIKE '%$input%'");

$table = $_REQUEST["table"];
$action = $_REQUEST["action"];
$query = $_REQUEST["query"];
$application = ApplicationController::getInstance();
$database = $application->getDatabaseConnection();
$settings = $application->getSettings();
$fields = $settings["global"]["autosuggest"][$table][$action]["fields"];
$key = $settings["global"]["autosuggest"][$table][$action]["key"];
$query = "SELECT {$fields} FROM {$table} WHERE {$key} LIKE '%{$query}%';";
$output = array();
$application = ApplicationController::getInstance();
$database = $application->getDatabaseConnection();
if($database->connect()){
	if($database->query($query)){
		while( $results = $database->getObject() ) {
			$output[] = array("name" => $results->name, "value" => "{$results->value}");
		}
	}
}
echo json_encode($output);
?>