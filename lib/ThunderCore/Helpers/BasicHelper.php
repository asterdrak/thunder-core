<?php
namespace ThunderCore\Helpers;

/**
* basic methods for extending other objects (general)
*/
abstract class BasicHelper
{
  
  /**
  * method returns current object methods
  */
  public function methods() {
    return get_class_methods($this);
  }
}