<?php
namespace ThunderCore;


/**
* Base class for entities (adds my methods)
*/
abstract class ModelBaseClass
{

  // ----------------------------------------
  //           PUBLIC METHODS
  // ----------------------------------------

  /**
   * methods which return null if is not overriden
   */
  public function getName() {}

  /**
   * returns null if getName method is not overriden
   * returns entity name if getName is set
   */
  public function __toString() {
    return $this->getName();
  }

  /**
  * magic getter for models classes
  * checks if method exists end runs it
  * if opposite throws an exception
  */
  public function __get($name) {
    $method_name = "get" . $this->underscoresToCamelCase($name);

    if (method_exists($this, $method_name)) {
      return $this->{$method_name}();
    }
    else {
      throw new Exception("Bad method name (parameter)", 1);
    }
  }


  // ----------------------------------------
  //             PRIVATE METHODS
  // ----------------------------------------

  private function underscoresToCamelCase($string, $capitalizeFirstCharacter = false) {
      return str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
  }

}



