<?php

namespace ThunderCore;


/**
* takes controller and method, runs proper scripts and returns proper vars
*/
class Element
{
  private $css_meta;
  private $js_meta;
  public $render_template = true;

  public $vars = [];

  function __construct($controller_name, $method_name, $resource_id = NULL)
  {
    $controller_class_name = ucfirst($controller_name) . 'Controller';

    $controller = new $controller_class_name();

    if(!method_exists($controller, $method_name))
      throw new \Exception("Bad method name in Element (check your url)", 1);

    $controller->$method_name($resource_id);

    $this->vars['yield'] = function() use(&$controller, &$controller_name, &$method_name) {
      $view = new View($controller_name, $method_name, get_object_vars($controller));
    };

  }
}