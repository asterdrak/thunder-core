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

  // ----------------------------------------
  //            PUBLIC METHODS
  // ----------------------------------------


  public function __construct($ENVIRONMENT_NAME) {
    require_once (App::$root_dir . "/config/environments/" . strtolower($ENVIRONMENT_NAME) . ".php");
    $this->variables = $environment_variables;

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


  // ----------------------------------------
  //            PRIVATE METHODS
  // ----------------------------------------

  private function throw_not_implemented() {
    throw new Exception("Operation not implemented for security reasons.");
  }

}

?>