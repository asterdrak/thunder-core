<?php
namespace ThunderCore\Abstracts;

/**
* interface for strategy pattern in routing assets
*/
interface RouteAssetStrategy
{

  /**
  * returns file with proper set headers for passed extension
  */
  public function return_file(string $filename, string $extension);
}
