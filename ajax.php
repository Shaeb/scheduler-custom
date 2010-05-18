<?
ob_start();
require_once( "resources/bin/constants.php");
add_required_class( 'Module.Class.php', MODEL );
$moduleName = $_REQUEST[ 'module' ];
$action = $_REQUEST["action"];
$module = new Module( $moduleName );
$map = $module->getXMLAsMap();
$output = "";

$format = XML_FORMAT;

if(isset($_REQUEST['format'])){
	$format = $_REQUEST["format"];
}

if( "process" == $action ){
	$output = $module->getProcessorOutput($format,$_REQUEST);
} else {
	$output = $module->getOutput($format,$_REQUEST);
}
header('Content-Type: text/xml');
echo htmlspecialchars_decode( $output );
echo ob_get_clean();
?>