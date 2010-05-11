<?php
//require_once( '../constants.php' );
add_required_class( 'Scaffold.Class.php', SCAFFOLD );
add_required_class( 'Scaffolding.Class.php', SCAFFOLD );

class ScaffoldController{
	private static $instance;
	private $application;
	private $connection;
	private $scaffolding;
	private $scaffoldObject; 
	private $factory;
	private $action;

	private function __construct(ApplicationController $application){
		if(isset($application)){
			$this->application = $application;
			$this->connection = $this->application->getDatabaseConnection();
			$this->scaffolding = null;
			$this->scaffoldObject = null;
			$this->factory = ScaffoldFactory::getInstance($this->connection);
		}
	}	

	public static function getInstance(ApplicationController $application) {
		if( !isset( self::$instance ) ) {
			self::$instance = new ScaffoldController($application);
		}
		return self::$instance;
	}
	
	public function doAction($action, $table){
		if(!isset($table)){
			throw new Exception("Table not set");
		}
		if(!isset($action)){
			throw new Exception("Action not set");
		}
		$this->scaffoldObject = $this->factory->buildScaffoldObject($table); 
		if(3 == func_num_args()){
			$id = func_get_arg((func_num_args() - 1));
			if(isset($id) && is_numeric($id)){
				$this->scaffoldObject->find($id);
			}
		}
		$settings = $this->application->loadGlobalSettings();
		$template = $settings[GLOBAL_ENVIRONMENT][$table][$action];
		if(!isset($template)){
			// check to see if there are global defaults listed, if so use them ...
			//$settings = $this->application->getSettingsFor("default",GLOBAL_ENVIRONMENT);
			$template = $settings[GLOBAL_ENVIRONMENT][DEFAULT_TABLE][$action];
		}
		if(isset($template)){
			$this->scaffolding = new Scaffolding($this->scaffoldObject, $template);
			$this->scaffolding->bind();
		} else {
			$this->scaffolding = new Scaffolding($this->scaffoldObject, null);
			// this is to create default scaffolds ...
			$this->scaffolding->buildDefaultForm($action);
		}
		$data = $this->scaffolding->saveHTML();
		$data = str_replace("[ACTION]", $action, $data);
		return $data;
	}
	
	public function doSubmission($action, $table, $id, $parameters){
		print_r($this->scaffoldObject->values);
		if(!isset($table)){
			throw new Exception("Table not set");
		}
		if(!isset($action)){
			throw new Exception("Action not set");
		}
		// we only care about id if the action is not to add the record...
		if(!isset($id) && $action != ACTION_ADD){
			throw new Exception("Id not set");
		}
		if(!isset($parameters)){
			throw new Exception("Parameters not set");
		}
		$this->scaffoldObject = $this->factory->buildScaffoldObject($table);
		if(isset($id)){
			$this->scaffoldObject->find($id);
		}
		$keys = array_keys($this->scaffoldObject->values);
		foreach($keys as $key){
			$this->scaffoldObject->$key = $parameters[$key];
		}
		// this is to create default scaffolds ...
		switch($action){
			case ACTION_ADD:
				$this->scaffoldObject->add();
				$message = "Successfully created object.";
				break;
			case ACTION_DELETE:
				$this->scaffoldObject->delete();
				$message = "Successfully deleted object.";
				break;
			case ACTION_UPDATE:
				$this->scaffoldObject->update();
				$message = "Successfully updated object.";
				break;
			case ACTION_FIND:
				//$this->scaffoldObject->find();
				break;
			case ACTION_ADDLIST:
			default:
				break;
		}
		$this->application->addMessage($message);
	}
	
	public function doAdd($scaffold, $action, $table){
		if(!isset($scaffold)){
			throw new Exception( "Could not find scaffold object for {$table} => {$action}");
		}
	}	
	public function getOutput($format = XML_FORMAT, $parameters){
		if(!isset($parameters) && !is_array($parameters)){
			throw new Exception( "Cannot build scaffolding output.  Parameters not set or not an array.");
		}
		$action = $parameters["action"];
		$table = $parameters["table"];
		$id = $parameters["id"];
		if(isset($parameters["submit"])){
			$this->doSubmission($action, $table, $id, $parameters);
		}
		$output = '<div class="scaffolding_container scaffolding_action_' . $action . " scaffolding_table_{$table}" . '">';
		$output .= $this->doAction($action, $table, $id);
		$output .= "</div>";
		return $output;
	}
}
?>