<?php

namespace ThunderCore;


/**
* looks for views for current user and passed controller_name and method_name
* renders if finds
*/
class View
{

  private $controller_name;
  private $method_name;

  function __construct($controller_name, $method_name, $view_vars = [])
  {



    $app = App::Instance();

    foreach ($app->groups->current as $group) {
      $view_filename = "app/views/{$group}/{$controller_name}/{$method_name}.haml";
      if(file_exists($view_filename))
        $app->render->display($view_filename, $view_vars);
    }

  }
}