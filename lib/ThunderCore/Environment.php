<?php
namespace ThunderCore;

use ArrayAccess;

/**
* executes file from external app config/environments/$ENVIRONMENT_NAME.php
* and stores array from variable $environment_variables in private field
* implements ArrayAcces to return variables by Array operator
*/
class Environment implements ArrayAccess
{

  /**
   * stores env variables from external app
   */
  private $variables; 
  /**
   * name of env DEVELOPMENT, TEST, PRODUCTION
   */
  private $name;

  // ----------------------------------------
  //            PUBLIC METHODS
  // ----------------------------------------


  public function __construct($ENVIRONMENT_NAME) {
    require_once(App::$root_dir . "/config/environments/" . strtolower($ENVIRONMENT_NAME) . ".php");
    $this->name = $ENVIRONMENT_NAME;
    $this->variables = $environment_variables;
  }

  public function register_environment_variable($variable_name, $variable) {
    if(is_array($this->variables))
      $this->variables[$variable_name] = $variable;
    else {
      $message = "Use YOUR_APP/config/environments/ENVIRONMENT_NAME.php file to set \$environment_variables";
      throw new Exception($message, 1);
    }
  }

  public function offsetGet($offset) {
    return isset($this->variables[$offset]) ? $this->variables[$offset] : null;
  }

  public function offsetSet($offset, $value) {
    $this->throw_not_implemented();
  }


  public function offsetExists($offset) {
      return isset($this->variables[$offset]);
  }

  public function offsetUnset($offset) {
    $this->throw_not_implemented();
    
  }

  public function __get($property) {
            if (property_exists($this, $property)) {
                return $this->$property;
            }
    }


  // ----------------------------------------
  //            PRIVATE METHODS
  // ----------------------------------------

  private function throw_not_implemented() {
    throw new Exception("Operation not implemented for security reasons.");
  }

}

?>