<?php
class FormFactory extends Document{
	public $controlTypes;
	private $controls;
	private static  $instance;
	
	private function __construct(){
		$this->controlTypes = (object)array("INPUT_HIDDEN" => 0, "INPUT_TEXT" => 1, "INPUT_PASSWORD" => 2, "INPUT_MULTILINE_TEXT" => 3,
			"LABEL" => 4, "SELECT" => 5, "RADIO_SINGLE" => 6, "RADIO_GROUP" => 7, "CHECKBOX_SINGLE" => 8, "CHECKBOX_GROUP" => 9,
			"BUTTON" => 10, "BUTTON_SUBMIT" => 11, "INPUT_FILE_UPLOAD" => 12, "INPUT_AUTOSUGGEST" => 13	);
	}
	
	public static function getInstance() {
		if( !isset( self::$instance ) ) {
			self::$instance = new FormFactory();
		}
		return self::$instance;
	}
	
	public function addControl($controlType, $controlOptions){
		if(isset($controlType) && isset($controlOptions)){
			$this->controls[] = new FormControl($controlType, $controlOptions);
		}
	}
	
	public function build(){
		
	}
	
	public function getOutput($format = XML_FORMAT){
		
	}
}

class FormControl {
	
	public function __construct($controlType, $options){
		
	}
}
?>