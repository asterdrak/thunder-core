<?php
namespace ThunderCore;


/**
* wraps up entities class in object to easy use Models
*/
class ModelWrapper
{
  private $model_name;
  private $app;
  

  /**
   * 
   */
  function __construct($model_name)
  {
    $this->model_name = $model_name;
    $this->app = $GLOBALS['application'];
  }

  public function print() {
    echo $this->model_name;
  }

  /**
  * return array of all entities for chosen model
  */
  public function all() {
    return $this->app->entityManager->getRepository($this->model_name)->findAll();
  }

  /**
   * return model with specific id
   */
  public function find($id) {
    return $this->app->entityManager->getRepository($this->model_name)->find($id);
  }
}