<?
require_once( "resources/bin/constants.php");
add_required_class( 'Module.Class.php', MODEL );

$autoSuggest = "AutoSuggestModule";
$regexAutoSuggest = "/AutoSuggestModule$/";
$moduleName = $_REQUEST[ 'module' ];
$table = "";

if(1 == preg_match($regexAutoSuggest, $moduleName)){
	$table = preg_replace($regexAutoSuggest, "", $moduleName);
	$_REQUEST["table"] = $table;
	$moduleName = $autoSuggest;
}
$module = new Module( $moduleName );
$output = $module->getOutput(JSON_FORMAT,$_REQUEST);
header("Content-type: application/json");
echo $output;
?>