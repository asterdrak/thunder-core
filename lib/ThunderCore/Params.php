<?php

namespace ThunderCore;

/**
* 
*/
class Params extends Helpers\BasicHelper implements \ArrayAccess
{
  public $container;

  function __construct($container = NULL)
  {
    if(is_null($container)) $this->container = $_POST;
    else $this->container = $container;
  }

  /**
  * docs
  */
  public function require(string $model) {
    return new Params($this->container[$model]);
  }

  /**
  * docs
  */
  public function permit(Array $allowed) {
    return array_intersect_key($this->container, array_flip($allowed));
  }


  public function offsetSet($offset, $value) {
    return false;
      // if (is_null($offset)) {
      //     $this->container[] = $value;
      // } else {
      //     $this->container[$offset] = $value;
      // }
  }

  public function offsetExists($offset) {
      return isset($this->container[$offset]);
  }

  public function offsetUnset($offset) {
      unset($this->container[$offset]);
  }

  public function offsetGet($offset) {
      return isset($this->container[$offset]) ? $this->container[$offset] : null;
  }
}

?>