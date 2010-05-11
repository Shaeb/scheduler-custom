<?php
require_once( "resources/bin/constants.php");

class ApplicationSettingsMap extends Document {
private $environment;
	private $url;
	private $appSettingsFileName;
	private $tags;
	private $settings;
	private $environmentRootNode;
	public  $isLoaded;
	//private static $instance; 

	public function __construct($appSettingsFileName, $environment) {
		if( isset( $appSettingsFileName ) && isset($environment) ) {
			$this->appSettingsFileName = $appSettingsFileName;
			$this->url = APPSETTINGS_PATH . $this->appSettingsFileName . PAGE_EXTENSION;
			if( $this->urlExists( $this->url ) ) {
				$this->preserveWithWhiteSpace = false;
				$this->isLoaded = $this->load( $this->url, LIBXML_NOBLANKS );
				$this->settings = array();
				$this->settings = $this->reload($this->documentElement->childNodes, $this->settings);
				print_r($this->settings);
			} else {
				$this->log( "url not found: $this->url." );
				$this->reload(APPLICATION_DEFAULT_SETTINGS_FILE, $environment);
			}
		}
	}
		
	public function reload(DOMNodeList $nodes, $settings){	
		foreach($nodes as $node){
			if(!$node->hasChildNodes() || "#text" == $node->firstChild->nodeName || "#comment" == $node->firstChild->nodeName){
				$settings[$node->nodeName] = $node->nodeValue;
			} else {
				$settings[$node->nodeName] = $this->reload($node->childNodes, $settings[$node->nodeName]); 
			}
		}
		return $settings;
	}
}
?>