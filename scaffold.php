<?php
require_once( "resources/bin/constants.php");
add_required_class( 'Scaffold.Controller.php', SCAFFOLD );
add_required_class( 'Scaffold.Class.php', SCAFFOLD );
add_required_class( 'Scaffolding.Class.php', SCAFFOLD );

$controller = ScaffoldController::getInstance($application);
$action = $_REQUEST["action"];
$table = $_REQUEST["table"];
$id = $_REQUEST["id"];
if(isset($_REQUEST["submit"])){
	echo $controller->doSubmission($action, $table, $id, $_REQUEST);
} else {
	echo $controller->doAction($action, $table, $id);
}

//$action = $_REQUEST["action"];
//$connection = $application->getDatabaseConnection();
//
//switch($action){
//	case "create":
//		showCreateInfo($connection);
//		break;
//	case "tableInfo":
//		showTableInfo($connection);
//		break;
//	case "allTables":
//	default:
//		showAllTables($connection);
//}
//
//function showAllTables($connection) {
//	$connection->connect();
//	$query = "show tables";
//	$connection->query($query);
//	$results = $connection->getResults();
//	echo "<h1>view columns</h1><p>";
//	while($result = mysql_fetch_row($results)){
//		echo '<a href="/medtele/scaffold/tableInfo/' . $result[0] . '">' . $result[0] . "</a><br/>";
//	}
//	mysql_data_seek($results,0);
//	echo "</p><h1>view create table info</h1><p>";
//	while($result = mysql_fetch_row($results)){
//		echo '<a href="/medtele/scaffold/create/' . $result[0] . '">' . $result[0] . "</a><br/>";
//	}
//	echo "</p>";
//}
//
//function showTableInfo($connection){
//	$connection->connect();
//	$table = $_REQUEST["table"];
//	$query = "show columns from {$table}";
//	$connection->query($query);
//	$results = $connection->getResults();
//	
//	while($result = $connection->getObject()){
//		print_r($result);
//		echo "<br/>";
//	}	
//}
//
//function showCreateInfo($connection){
//	$connection->connect();
//	$table = $_REQUEST["table"];
//	$query = "show create table {$table}";
//	$regex1 = "/FOREIGN KEY \(\`[\w]+\`\) REFERENCES \`[\w]+\` \(\`[\w]+\`\)/";
//	$regex2 = "/\`[\w]+\`/";
//	$regex3 = "/\`/";
//	$connection->query($query);
//	$results = $connection->getResults();
//	$map = array();
//	
//	while($result = mysql_fetch_row($results)){
//		$values = null;
//		$num = preg_match_all($regex1, $result[1], $values);
//		foreach( $values[0] as $value){
//			//print_r($value);
//			$num = preg_match_all( $regex2, $value, $matches);
//			//print_r($matches);
//			if(3 == $num){
//				$foreign_key = preg_replace($regex3, "", $matches[0][0]);
//				$referenced_table = preg_replace($regex3, "", $matches[0][1]);
//				$referenced_field = preg_replace($regex3, "", $matches[0][2]);
//				$map[] = array("foreign_key" => $foreign_key, "referenced_table" => $referenced_table, "referenced_field" => $referenced_field);
//			}
//			$matches = null;
//		}
//		//print_r($values);
//		//print_r($result[1]);
//		//FOREIGN KEY (`assignedBy`) REFERENCES `employees` (`employeeId`)
//		print_r($map);
//		//print_r($result[1]);
//	}	
//}
?>