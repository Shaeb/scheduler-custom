<?
require_once( "resources/bin/constants.php");
add_required_class( 'Module.Class.php', MODEL );
$moduleName = $_REQUEST[ 'module' ];
//if($application->isPageGated($pageName)){
//	if(!$application->session->isSessionAuthenticated()){
//		$application->enforceGate();
//	}
//}
$module = new Module( $moduleName );
$output = $module->getOutput(XML_FORMAT,$_REQUEST);
header('Content-Type: text/xml');  
echo htmlspecialchars_decode( $output );
?>