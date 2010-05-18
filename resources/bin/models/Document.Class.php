<?
define( XML_FORMAT, "XML");
define( HTML_FORMAT, "HTML");
define( JSON_FORMAT, "JSON");
define( RAW_FORMAT, "RAW");

class Document extends DOMDocument {
	protected $xmlMap;
	protected $url;
	protected $isLoaded;
	
	
	protected function loadDocument($url) {
		if( isset( $url )  ) {
			$this->url = $url;
			if( $this->urlExists( $this->url ) ) {
				$this->preserveWithWhiteSpace = false;
				$this->isLoaded = $this->load( $this->url, LIBXML_NOBLANKS );
				if($this->isLoaded){
					$this->xmlMap = array();
					$this->xmlMap = $this->reload($this->documentElement->childNodes, $this->xmlMap);
				} else {
					throw new Exception("could not load document {$this->url} in Document");
				}
			} else {
				throw new Exception( "url not found: {$this->url}." );
			}
		}
	}
	
	protected function urlExists($url) {
		$hdrs = @get_headers($url);
		return is_array($hdrs) ? preg_match("/^HTTP\\/\\d+\\.\\d+\\s+2\\d\\d\\s+.*$/",$hdrs[0]) : false;
	}

	protected function log( $data ) {
		return; // just temporary because i dont wont to work with output buffering ATM
		if( DEBUGGING ) {
			ob_start();
			echo "$data\n";
			ob_flush();
		}
	}

	protected function query( $tagName, DOMNode $relativeNode = null) {
		//$this->log( "<!-- start: load $tagName -->" );
		$xpath = new DOMXPath( $this );
		//$query = (isset($relativeNode)) ? ".//{$tagName}" : "//{$tagName}";
		if(isset($relativeNode)){
			$nodes = $xpath->query( ".//{$tagName}", $relativeNode );
		} else {
			$nodes = $xpath->query( "//{$tagName}" );
		}
		return $nodes;
	}

	protected function stripHTMLOutput( $data ) {
		$data = preg_replace( '/^<!DOCTYPE.+?>/', '',
							str_replace( array('<html>', '</html>', '<body>', '</body>'),
							array('', '', '', ''), $data ) );
		return $data;
	}
	
	// for this function, a child is unique if at least the nodeValue is different from any other child of the same nodeType 
	public function appendUniqueChildToParent($parent, $child, $compare) {
		$success = false;
		if(isset($parent) && isset($child)){
			$nodes = $this->query($child->nodeName, $parent);
			
			// if there are none of the same tags in the parent, then it is unique
			if(0 == $nodes->length) {
				$parent->appendChild($child);
				$success = true;
			} else {
				$duplicate = false;
				foreach($nodes as $node){
					if(isset($compare)) {
						//will call user-supplied compare function
						$duplicate = call_user_func($compare, $child, $node);
						//$duplicate = $compare($child, $node);
						if($duplicate){
							break;
						}
					} else {
						if($node->nodeValue == $child->nodeValue) {
							$duplicate = true;
							$success = false;
							break;
						}
					}
				}
				if(!$duplicate){
					$parent->appendChild($child);
					$success = true;
				}
			}
		} else {
			echo "parent or child not set";
			exit();
		}
		return success;
	}
	
	public function saveJSON(){}
	
	public function getOutput($format = XML_FORMAT){
		return (XML_FORMAT == $format) ? $this->saveXML() : (HTML_FORMAT == $format) ? $this->saveHTML() : 
			(JSON_FORMAT == $formation ) ? $this->saveJSON() : $this->saveHTML();
	}
	
	public function getProcessorOutput($format = XML_FORMAT, $paramaters){
		
	}
	
	public function getXMLAsMap(){
		return $this->xmlMap;
	}
	
	protected function reload(DOMNodeList $nodes, $settings){	
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
/**
class XMLMap extends Document {
	private $url;
	private $settings;
	public  $isLoaded;
	//private static $instance; 

	public function __construct($url) {
		if( isset( $url )  ) {
			$this->url = $url;
			if( $this->urlExists( $this->url ) ) {
				$this->preserveWithWhiteSpace = false;
				$this->isLoaded = $this->load( $this->url, LIBXML_NOBLANKS );
				$this->settings = array();
				$this->settings = $this->reload($this->documentElement->childNodes, $this->settings);
			} else {
				throw new Exception( "url not found: {$this->url}." );
			}
		}
	}
		
	private function reload(DOMNodeList $nodes, $settings){	
		foreach($nodes as $node){
			if(!$node->hasChildNodes() || "#text" == $node->firstChild->nodeName || "#comment" == $node->firstChild->nodeName){
				$settings[$node->nodeName] = $node->nodeValue;
			} else {
				$settings[$node->nodeName] = $this->reload($node->childNodes, $settings[$node->nodeName]); 
			}
		}
		return $settings;
	}
	
	public function getData(){
		return $this->settings;
	}
}
**/
?>