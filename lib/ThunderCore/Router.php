<?php
namespace ThunderCore;

/**
* in future, perhaps:
* handling routes by using external file and canonical url's
*/
class Router
{
  private $url;
  private $controller;
  private $method;
  private $id;


  function __construct($url)
  {
    $this->url = $url;

    if($this->url != '/')
      new RouteAssets("assets{$this->url}");

    $url_arr = explode("/", $url);

    if($url_arr[1] == null) {
      $this->controller = 'application';
      $this->method = 'index';
    }
    else {
      $this->controller = $url_arr[1];
      if(empty($url_arr[2]))
        $this->method = 'index';
      elseif (is_numeric($url_arr[2])) {
        $this->method = 'show';
        $this->id = intval($url_arr[2]);
      }
      else
        $this->method = $url_arr[2];
    }
  }

  public function __get($property) {
    if (property_exists($this, $property)) {
        return $this->$property;
    }
  }
}