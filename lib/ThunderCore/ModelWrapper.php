<?php
namespace ThunderCore;


/**
* wraps up entities class in object to easy use Models
*/
class ModelWrapper extends Helpers\BasicHelper
{
  private $model_name;
  public $app;
  

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
  * returns reference to newly created entity
  */
  public function new() {
    $new_user = new $this->model_name();
    $new_user->set_do_persist(true);
    return $new_user;
  }


  /**
  * return array of all entities for chosen model
  */
  public function all() {
    $this->app = $GLOBALS['application'];
    return $this->app->entityManager->getRepository($this->model_name)->findAll();
  }

  /**
   * return model with specific id
   */
  public function find($id) {
    $this->app = $GLOBALS['application'];
    return $this->app->entityManager->getRepository($this->model_name)->find($id);
  }

}