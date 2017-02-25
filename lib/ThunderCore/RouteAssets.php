<?php
namespace ThunderCore;

use ThunderCore\RouteAssetsFileTypes\RouteAssetFile;

/**
* class which gets file name as param and returns file by http if exists
*/
class RouteAssets
{


  private $strategy;
  private $filename;
  private $extension;
  
  function __construct($filename)
  {
    if(file_exists($filename)) {
      http_response_code(200);
      $this->extension = pathinfo($filename, PATHINFO_EXTENSION);

      $extension_uppercased = ucfirst($this->extension);
      $strategy_name = "\ThunderCore\RouteAssetsFileTypes\RouteAsset{$extension_uppercased}";

      if(class_exists($strategy_name)) {
        $this->strategy = new $strategy_name;
        $this->strategy->return_file($filename, $this->extension);
      }
      else {
        $this->strategy = new RouteAssetFile;
        $this->strategy->return_file($filename, $this->extension);
      }
    }
  }
}