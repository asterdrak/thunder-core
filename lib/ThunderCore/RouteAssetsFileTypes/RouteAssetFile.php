<?php
namespace ThunderCore\RouteAssetsFileTypes;

use \ThunderCore\Abstracts\RouteAssetStrategy;

/**
* sets up rooting strategy for css files
*/
class RouteAssetFile implements RouteAssetStrategy
{

  function return_file(string $filename, string $extension)
  {
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename="'.basename($filename).'"');
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: ' . filesize($filename));
      readfile($filename);
      exit;
  }
}