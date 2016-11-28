<?php

namespace ThunderCore;


/**
* Base class for entities (adds my methods)
*/
abstract class ModelBaseClass extends Helpers\BasicHelper
{

  /**
   * variable decide if in save method we should do persist on entity or not
   * by default is set to false (do not persist)
   * if object is newly created it should be set to true explicitly
   */
  private $do_persist = false;

  // ----------------------------------------
  //          PUBLIC METHODS AUTOS
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
  * checks if method get exists end runs it
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


  /**
   * magic setter for models classes
   * checks if method set exists and runs it
   * if opposite throws an exception
   */
  public function __set($name, $value) {
    $method_name = "set" . $this->underscoresToCamelCase($name);

    if (method_exists($this, $method_name)) {
      $this->{$method_name}($value);
    }
    else {
      throw new Exception("Bad method name (parameter)", 1);
    }
  }

  // ----------------------------------------
  //     PUBLIC METHODDS CLASS INTERFACE
  // ----------------------------------------

  /**
  * tries to save new entity by persisting entity and flushing entityManager
  * catches any error, restarts EntitryMenager and returns false (allowing next try)
  * if flush succeds it returns true
  */
  public function save() {
    $app = $GLOBALS['application'];
    $app->entityManager->persist($this);

    try {
      $app->entityManager->flush();
      return true;
    } catch (\Exception $e) {
      $app->restart_entityManager();
      error_log($e->getMessage());
      return false;
      
    }
  }

  /**
  * sets do_persist variable (as param takes only (0 or 1)
  * in other situation throws an exception
  */
  public function set_do_persist($bool) {
    if($bool !== true AND $bool !== false) throw new Exception("Param must be 0 or 1", 1);
    else $this->do_persist = $bool;
  }


  /**
  * reloads database and set up user from scratch
  * returns proper object (new)
  */
  public function reload() {
    $app = $GLOBALS['application'];
    $app->reload_database();
    return $app->entityManager->getRepository(get_class($this))->find($this->id);
  }


  // ----------------------------------------
  //             PRIVATE METHODS
  // ----------------------------------------

  private function underscoresToCamelCase($string, $capitalizeFirstCharacter = false) {
      return str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
  }

}



