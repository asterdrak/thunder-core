<?php

namespace ThunderCore;

/**
* allows for proper class loading (with security check)
*/
class ControllersClassLoader
{
  private $valid = false;

  public function __construct() {
    $this->valid = true;
  }

  public function autoload($className) {
    $groups = App::Instance()->groups;

    $this->_directories = array_map(
      function($group_name) { return "./app/controllers/{$group_name}/"; }, $groups->current
      );

    if(strpos($className, 'Controller') == false) {
       return false;
    }


    foreach ($this->_directories as $directory) {
      $class_file = $directory.$className.'.php';
      if(file_exists($class_file)) {
        require($class_file);
        return true;
      }
    }
    throw new \Exception("Bad class name in ControllerClassLoader (check your url)", 1);

  }


  public function register() {
    if($this->valid) {
      spl_autoload_register(array($this, 'autoload'));
      $this->valid = false;
    }
  }
}
