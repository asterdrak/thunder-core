<?php
namespace ThunderCore\RouteAssetsFileTypes;

use \ThunderCore\Abstracts\RouteAssetStrategy;

/**
* sets up rooting strategy for css files
*/
class RouteAssetPng implements RouteAssetStrategy
{

  function return_file(string $filename, string $extension)
  {
      header('Content-Type: image/png');
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: ' . filesize($filename));
      readfile($filename);
      exit;
  }
}