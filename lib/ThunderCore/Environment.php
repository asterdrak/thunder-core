<?php
namespace ThunderCore;

use ArrayAccess;

/**
* 
*/
class Environment implements ArrayAccess// extends AnotherClass
{
  private $variables;

  private function throw_not_implemented() {
    throw new Exception("Operation not implemented for security reasons.");
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

  function __construct($ENVIRONMENT_NAME)
  {
    require_once (App::$root_dir . "/config/environments/" . strtolower($ENVIRONMENT_NAME) . ".php");
    $this->variables = $environment_variables;

  }

}

?>